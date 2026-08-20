<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class FormAccessibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_every_visible_text_control_has_an_associated_label(): void
    {
        $failures = [];
        $views = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(resource_path('views')));

        foreach ($views as $view) {
            if (! $view->isFile() || $view->getExtension() !== 'php' || ! str_ends_with($view->getFilename(), '.blade.php')) {
                continue;
            }

            $contents = file_get_contents($view->getPathname());
            preg_match_all('/<(input|select|textarea)\b([^>]*)>/is', $contents, $controls, PREG_SET_ORDER);

            foreach ($controls as $control) {
                $attributes = $control[2];
                preg_match('/\btype\s*=\s*["\']([^"\']+)["\']/i', $attributes, $typeMatch);
                $type = strtolower($typeMatch[1] ?? 'text');

                if (in_array($type, ['hidden', 'submit', 'button', 'reset', 'checkbox', 'radio'], true)) {
                    continue;
                }

                preg_match('/\bid\s*=\s*["\']([^"\']+)["\']/i', $attributes, $idMatch);
                $id = $idMatch[1] ?? null;
                $relativePath = str_replace(base_path().DIRECTORY_SEPARATOR, '', $view->getPathname());

                if (! $id) {
                    $failures[] = $relativePath.' has a visible '.$control[1].' without an id';

                    continue;
                }

                $labelPattern = '/<label\b[^>]*\bfor\s*=\s*["\']'.preg_quote($id, '/').'["\'][^>]*>/i';
                if (! preg_match($labelPattern, $contents)) {
                    $failures[] = $relativePath.' has #'.$id.' without a matching label[for]';
                }
            }
        }

        $this->assertSame([], $failures, implode("\n", $failures));
    }

    public function test_permission_create_and_edit_forms_render_without_blade_errors(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $user = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN, 'is_active' => true]);
        $user->syncRoles([User::ROLE_SUPER_ADMIN]);
        $permission = Permission::firstWhere('name', 'manage-permissions');

        $this->actingAs($user)->get(route('admin.permissions.create'))->assertOk();
        $this->actingAs($user)->get(route('admin.permissions.edit', $permission))->assertOk();
    }
}
