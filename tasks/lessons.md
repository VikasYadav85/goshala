# Lessons Learned
Rules added here prevent repeated mistakes. Each rule was born from an actual error.

## Code Patterns
(none yet)

## Common Pitfalls
- With `gh pr merge --repo`, always pass the PR number, URL, or branch explicitly; repository selection alone does not identify which PR to merge.

## Testing
(none yet)

## Dependencies / Deploy
- Prod runs PHP 8.2.31; local is PHP 8.3+. `composer require` on local can lock a package
  version needing PHP 8.3 (e.g. spatie/laravel-permission 8.x), which then FAILS `composer install`
  on the server and can take the site down. RULE: keep `config.platform.php` pinned to the prod
  PHP version in composer.json so composer always resolves prod-compatible versions. Verify a new
  package's PHP requirement before adding.
- When a deploy leaves the site broken, roll the SERVER CODE back first (`git reset --hard <last-good>`)
  to restore service, then fix forward locally — don't debug on a live-down site.

## Bulk Operations
- NEVER run sed -i on files without checking the file list first
- NEVER run find -exec on directories without excluding: .claude/skills/, node_modules/, .git/, dist/, build/
- ALWAYS show the exact file list before any operation touching 5+ files
- ALWAYS exclude lock files (package-lock.json, bun.lock, yarn.lock) from bulk modifications
