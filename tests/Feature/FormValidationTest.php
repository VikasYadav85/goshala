<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class FormValidationTest extends TestCase
{
    use RefreshDatabase;

    #[DataProvider('publicForms')]
    public function test_public_forms_reject_missing_required_fields(string $route, array $errors): void
    {
        $this->from(route('home'))
            ->post(route($route), [])
            ->assertRedirect(route('home'))
            ->assertSessionHasErrors($errors);
    }

    public static function publicForms(): array
    {
        return [
            'donation checkout' => ['donations.store', ['donor_name', 'donor_email', 'donor_phone', 'amount', 'frequency']],
            'volunteer registration' => ['volunteer.store', ['full_name', 'email', 'phone']],
            'contact message' => ['contact.store', ['name', 'email', 'message_type', 'message']],
            'newsletter subscription' => ['subscribe', ['email']],
        ];
    }

    #[DataProvider('adminForms')]
    public function test_admin_create_forms_reject_missing_required_fields(string $route, array $errors): void
    {
        $this->seed(RolePermissionSeeder::class);
        $admin = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN, 'is_active' => true]);
        $admin->syncRoles([User::ROLE_SUPER_ADMIN]);

        $this->actingAs($admin)
            ->from(route('admin.dashboard'))
            ->post(route($route), [])
            ->assertRedirect(route('admin.dashboard'))
            ->assertSessionHasErrors($errors);
    }

    public static function adminForms(): array
    {
        return [
            'cow' => ['admin.cows.store', ['name', 'gender', 'monthly_sponsorship_amount', 'status']],
            'campaign' => ['admin.campaigns.store', ['title', 'goal_amount', 'status']],
            'event' => ['admin.events.store', ['title', 'type', 'starts_at', 'status']],
            'blog' => ['admin.blog.store', ['title', 'body', 'status']],
            'testimonial' => ['admin.testimonials.store', ['name', 'rating', 'quote']],
            'team member' => ['admin.team.store', ['name', 'role', 'group']],
            'donation category' => ['admin.donation-categories.store', ['name', 'default_amount']],
            'gallery album' => ['admin.gallery.store', ['name', 'category']],
            'faq' => ['admin.faqs.store', ['group', 'question', 'answer']],
            'user' => ['admin.users.store', ['name', 'email', 'role', 'password']],
            'role' => ['admin.roles.store', ['name']],
            'permission' => ['admin.permissions.store', ['name']],
        ];
    }
}
