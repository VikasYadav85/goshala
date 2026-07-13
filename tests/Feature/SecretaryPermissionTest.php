<?php

namespace Tests\Feature;

use App\Models\Faq;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Add/edit access is gated by Spatie roles/permissions. A "Secretary" is just a
 * team-member title, so what they can do depends entirely on the role assigned.
 * These tests create a Secretary user and prove the behaviour per role.
 */
class SecretaryPermissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    private function secretary(string $role): User
    {
        $user = User::factory()->create([
            'name' => 'Smt. Radha Devi (Secretary)',
            'role' => $role,
            'is_active' => true,
        ]);
        $user->syncRoles([$role]);

        return $user;
    }

    private array $faqPayload = [
        'group' => 'general',
        'question' => 'What are the goshala visiting hours?',
        'answer' => 'Daily from 8am to 6pm.',
        'is_published' => true,
        'sort_order' => 1,
    ];

    /** A Secretary given the "editor" role CAN add and edit content (FAQs). */
    public function test_secretary_as_editor_can_add_and_edit(): void
    {
        $secretary = $this->secretary(User::ROLE_EDITOR);

        $this->actingAs($secretary)->get(route('admin.faqs.create'))->assertOk();

        $this->actingAs($secretary)
            ->post(route('admin.faqs.store'), $this->faqPayload)
            ->assertRedirect(route('admin.faqs.index'));

        $faq = Faq::firstWhere('question', 'What are the goshala visiting hours?');
        $this->assertNotNull($faq);

        $this->actingAs($secretary)
            ->put(route('admin.faqs.update', $faq), [...$this->faqPayload, 'answer' => 'Daily from 7am to 7pm.'])
            ->assertRedirect(route('admin.faqs.index'));

        $this->assertDatabaseHas('faqs', ['id' => $faq->id, 'answer' => 'Daily from 7am to 7pm.']);
    }

    /** The editor role does NOT grant financial (donations) access. */
    public function test_secretary_as_editor_cannot_touch_donations(): void
    {
        $secretary = $this->secretary(User::ROLE_EDITOR);

        $this->actingAs($secretary)->get(route('admin.donations.index'))->assertForbidden();
    }

    /** A Secretary given the "staff" role CANNOT even reach the admin panel. */
    public function test_secretary_as_staff_cannot_add_or_edit(): void
    {
        $secretary = $this->secretary(User::ROLE_STAFF);

        $this->actingAs($secretary)->get(route('admin.faqs.create'))->assertForbidden();

        $this->actingAs($secretary)
            ->post(route('admin.faqs.store'), $this->faqPayload)
            ->assertForbidden();

        $this->assertDatabaseMissing('faqs', ['question' => 'What are the goshala visiting hours?']);
    }

    /** An inactive Secretary is locked out regardless of role. */
    public function test_inactive_secretary_cannot_add_or_edit(): void
    {
        $secretary = $this->secretary(User::ROLE_EDITOR);
        $secretary->update(['is_active' => false]);

        $this->actingAs($secretary)->get(route('admin.faqs.create'))->assertForbidden();
    }
}
