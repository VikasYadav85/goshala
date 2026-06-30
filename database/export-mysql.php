<?php
/**
 * Standalone SQLite → MySQL dump generator for Gopal Seva Samarpan Trust platform.
 * Reads platform/database/database.sqlite and writes a MySQL-compatible .sql file
 * with CREATE TABLE + INSERT statements ready to import via phpMyAdmin / mysql CLI.
 *
 * Run from project root:
 *   php platform/database/export-mysql.php
 */

$sqlitePath = __DIR__ . '/database.sqlite';
$outPath    = __DIR__ . '/gopal_seva_mysql.sql';
$dbName     = 'gopal_seva';

if (! file_exists($sqlitePath)) {
    fwrite(STDERR, "ERROR: SQLite file not found at $sqlitePath\n");
    exit(1);
}

$pdo = new PDO("sqlite:$sqlitePath");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$out = fopen($outPath, 'w');

// Header
fwrite($out, "-- =====================================================\n");
fwrite($out, "-- Gopal Seva Samarpan Trust — MySQL dump\n");
fwrite($out, "-- Generated: " . date('Y-m-d H:i:s') . "\n");
fwrite($out, "-- Source: SQLite ($sqlitePath)\n");
fwrite($out, "-- Target: MySQL 5.7+ / MariaDB 10.3+ (utf8mb4)\n");
fwrite($out, "-- =====================================================\n\n");

fwrite($out, "SET NAMES utf8mb4;\n");
fwrite($out, "SET FOREIGN_KEY_CHECKS=0;\n");
fwrite($out, "SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';\n");
fwrite($out, "SET time_zone='+00:00';\n\n");

fwrite($out, "CREATE DATABASE IF NOT EXISTS `$dbName` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;\n");
fwrite($out, "USE `$dbName`;\n\n");

// Get all user tables
$tables = $pdo->query(
    "SELECT name, sql FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%' ORDER BY name"
)->fetchAll(PDO::FETCH_ASSOC);

/**
 * Map a SQLite declared column type to a MySQL one.
 */
function mapType(string $sqliteType, string $colName, bool $isPk = false): string
{
    $t = strtolower(trim($sqliteType));

    if ($isPk && (str_starts_with($t, 'integer') || $t === '')) {
        return 'BIGINT UNSIGNED NOT NULL AUTO_INCREMENT';
    }

    // Foreign key columns commonly named *_id
    if (preg_match('/(_id)$|^id$/', $colName) && (str_starts_with($t, 'integer') || $t === 'int')) {
        return 'BIGINT UNSIGNED';
    }

    // length-bearing types like varchar(255)
    if (preg_match('/^varchar\((\d+)\)/', $t, $m)) {
        return 'VARCHAR(' . $m[1] . ')';
    }

    if (preg_match('/^char\((\d+)\)/', $t, $m)) {
        return 'CHAR(' . $m[1] . ')';
    }

    if (str_starts_with($t, 'tinyint(1)')) {
        return 'TINYINT(1)';
    }

    return match (true) {
        str_starts_with($t, 'integer'),
        str_starts_with($t, 'bigint') => 'BIGINT',
        str_starts_with($t, 'int')    => 'INT',
        str_starts_with($t, 'smallint') => 'SMALLINT',
        str_starts_with($t, 'tinyint') => 'TINYINT',
        str_starts_with($t, 'unsigned big int') => 'BIGINT UNSIGNED',

        str_starts_with($t, 'datetime') => 'DATETIME',
        str_starts_with($t, 'timestamp') => 'TIMESTAMP NULL DEFAULT NULL',
        str_starts_with($t, 'date')     => 'DATE',
        str_starts_with($t, 'time')     => 'TIME',

        str_starts_with($t, 'longtext') => 'LONGTEXT',
        str_starts_with($t, 'mediumtext') => 'MEDIUMTEXT',
        str_starts_with($t, 'text')     => 'TEXT',

        str_starts_with($t, 'real'),
        str_starts_with($t, 'double')   => 'DOUBLE',
        str_starts_with($t, 'float')    => 'FLOAT',
        str_starts_with($t, 'decimal'),
        str_starts_with($t, 'numeric')  => 'DECIMAL(15,2)',

        str_starts_with($t, 'blob')     => 'LONGBLOB',
        str_starts_with($t, 'json')     => 'JSON',
        str_starts_with($t, 'varchar')  => 'VARCHAR(255)',

        default => 'TEXT',
    };
}

/**
 * Get foreign-key info via PRAGMA foreign_key_list.
 */
function pragmaFks(PDO $pdo, string $table): array
{
    $rows = $pdo->query("PRAGMA foreign_key_list(" . $pdo->quote($table) . ")")->fetchAll(PDO::FETCH_ASSOC);
    $rows = $pdo->query("PRAGMA foreign_key_list(`$table`)")->fetchAll(PDO::FETCH_ASSOC);
    return $rows;
}

/**
 * Get index info via PRAGMA index_list / index_info.
 */
function pragmaIndexes(PDO $pdo, string $table): array
{
    $list = $pdo->query("PRAGMA index_list(`$table`)")->fetchAll(PDO::FETCH_ASSOC);
    $out = [];
    foreach ($list as $idx) {
        $info = $pdo->query("PRAGMA index_info(`{$idx['name']}`)")->fetchAll(PDO::FETCH_ASSOC);
        $cols = array_column($info, 'name');
        $out[] = [
            'name'    => $idx['name'],
            'unique'  => (int) $idx['unique'] === 1,
            'columns' => $cols,
            'origin'  => $idx['origin'] ?? '', // 'pk', 'u' (unique), 'c' (created)
        ];
    }
    return $out;
}

foreach ($tables as $tbl) {
    $tableName = $tbl['name'];
    fwrite($out, "-- ----------------------------\n-- Table: $tableName\n-- ----------------------------\n");
    fwrite($out, "DROP TABLE IF EXISTS `$tableName`;\n");

    // Columns
    $cols = $pdo->query("PRAGMA table_info(`$tableName`)")->fetchAll(PDO::FETCH_ASSOC);
    $colDefs = [];
    $pkCols  = [];
    foreach ($cols as $c) {
        $name    = $c['name'];
        $isPk    = ((int) $c['pk']) === 1;
        $notnull = ((int) $c['notnull']) === 1;
        $default = $c['dflt_value'];
        $type    = mapType($c['type'] ?? '', $name, $isPk);

        $line = "  `$name` $type";

        if ($isPk) {
            $pkCols[] = $name;
            // primary-key column already gets NOT NULL via AUTO_INCREMENT
            if (! str_contains($type, 'AUTO_INCREMENT')) {
                $line .= ' NOT NULL';
            }
        } else {
            $line .= $notnull ? ' NOT NULL' : ' NULL';
        }

        if ($default !== null && ! $isPk) {
            // sqlite stores defaults as raw SQL literals
            $d = trim((string) $default);
            if (strcasecmp($d, 'NULL') === 0) {
                $line .= ' DEFAULT NULL';
            } elseif (preg_match('/^-?\d+(\.\d+)?$/', $d)) {
                $line .= " DEFAULT $d";
            } elseif (preg_match("/^'.*'$/s", $d)) {
                $line .= " DEFAULT $d";
            } elseif (strcasecmp($d, 'CURRENT_TIMESTAMP') === 0) {
                $line .= ' DEFAULT CURRENT_TIMESTAMP';
            } else {
                $line .= ' DEFAULT ' . $pdo->quote($d);
            }
        }

        $colDefs[] = $line;
    }

    if ($pkCols) {
        $colDefs[] = '  PRIMARY KEY (`' . implode('`,`', $pkCols) . '`)';
    }

    // Indexes (unique + non-unique, skipping the auto-created PK index)
    $idxs = pragmaIndexes($pdo, $tableName);
    foreach ($idxs as $idx) {
        if ($idx['origin'] === 'pk') {
            continue;
        }
        $colsList = '`' . implode('`,`', $idx['columns']) . '`';
        $idxName = preg_replace('/[^A-Za-z0-9_]/', '_', $idx['name']);
        $colDefs[] = ($idx['unique']
            ? "  UNIQUE KEY `$idxName` ($colsList)"
            : "  KEY `$idxName` ($colsList)");
    }

    fwrite($out, "CREATE TABLE `$tableName` (\n" . implode(",\n", $colDefs) . "\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n\n");

    // Data
    $count = (int) $pdo->query("SELECT COUNT(*) FROM `$tableName`")->fetchColumn();
    if ($count === 0) {
        fwrite($out, "-- (empty)\n\n");
        continue;
    }

    $colNames = array_column($cols, 'name');
    $colList  = '`' . implode('`,`', $colNames) . '`';

    $rows = $pdo->query("SELECT * FROM `$tableName`")->fetchAll(PDO::FETCH_ASSOC);

    // Chunk INSERTs at 100 rows for readability
    $chunks = array_chunk($rows, 100);
    foreach ($chunks as $chunk) {
        $values = [];
        foreach ($chunk as $row) {
            $vals = [];
            foreach ($colNames as $col) {
                $v = $row[$col] ?? null;
                if ($v === null) {
                    $vals[] = 'NULL';
                } elseif (is_int($v) || is_float($v)) {
                    $vals[] = $v;
                } elseif (is_string($v)) {
                    // Escape backslashes & single quotes for MySQL string literal
                    $escaped = str_replace(
                        ["\\",   "'",   "\"",   "\0",  "\n", "\r", "\x1a"],
                        ["\\\\", "\\'", "\\\"", "\\0", "\\n", "\\r", "\\Z"],
                        $v,
                    );
                    $vals[] = "'$escaped'";
                } else {
                    $vals[] = "'" . $v . "'";
                }
            }
            $values[] = '(' . implode(',', $vals) . ')';
        }
        fwrite($out, "INSERT INTO `$tableName` ($colList) VALUES\n" . implode(",\n", $values) . ";\n\n");
    }
}

fwrite($out, "SET FOREIGN_KEY_CHECKS=1;\n");
fclose($out);

echo "OK — wrote " . filesize($outPath) . " bytes to $outPath\n";
echo "Tables exported: " . count($tables) . "\n";
foreach ($tables as $t) {
    $cnt = (int) $pdo->query("SELECT COUNT(*) FROM `{$t['name']}`")->fetchColumn();
    printf("  %-30s %d rows\n", $t['name'], $cnt);
}
