<?php

namespace Tests\Feature;

use App\Mail\InvitationMail;
use App\Models\Invitation;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class InvitationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    private function userWithRole(string $role): User
    {
        $user = User::factory()->create(['role' => $role, 'is_active' => true]);
        $user->syncRoles([$role]);

        return $user;
    }

    private array $payload = [
        'invitee_name' => 'Shri Ram Prasad',
        'invitee_email' => 'guest@example.com',
        'occasion' => 'Gau Puja & Annadaan',
        'event_date' => '2026-09-15',
        'event_time' => '10:00 AM onwards',
        'venue' => 'Goshala grounds',
        'message' => 'Your presence will bless the occasion.',
    ];

    public function test_admin_can_send_an_invitation_and_it_is_logged(): void
    {
        Mail::fake();
        $admin = $this->userWithRole(User::ROLE_ADMIN);

        $this->actingAs($admin)->get(route('admin.invitations.create'))->assertOk();

        $this->actingAs($admin)
            ->post(route('admin.invitations.store'), $this->payload)
            ->assertRedirect(route('admin.invitations.index'));

        $invitation = Invitation::firstWhere('invitee_email', 'guest@example.com');
        $this->assertNotNull($invitation);
        $this->assertSame(Invitation::STATUS_SENT, $invitation->status);
        $this->assertSame($admin->id, $invitation->created_by);

        Mail::assertSent(InvitationMail::class, fn ($m) => $m->hasTo('guest@example.com'));
    }

    public function test_editor_without_permission_cannot_access_invitations(): void
    {
        // editor role does not include manage-invitations
        $editor = $this->userWithRole(User::ROLE_EDITOR);

        $this->actingAs($editor)->get(route('admin.invitations.index'))->assertForbidden();
        $this->actingAs($editor)->post(route('admin.invitations.store'), $this->payload)->assertForbidden();
    }

    public function test_invitation_requires_name_email_and_occasion(): void
    {
        $admin = $this->userWithRole(User::ROLE_ADMIN);

        $this->actingAs($admin)
            ->post(route('admin.invitations.store'), ['invitee_name' => ''])
            ->assertSessionHasErrors(['invitee_name', 'invitee_email', 'occasion']);
    }

    public function test_admin_can_resend_an_invitation(): void
    {
        Mail::fake();
        $admin = $this->userWithRole(User::ROLE_ADMIN);
        $invitation = Invitation::create($this->payload + ['status' => Invitation::STATUS_FAILED]);

        $this->actingAs($admin)
            ->post(route('admin.invitations.resend', $invitation))
            ->assertRedirect();

        Mail::assertSent(InvitationMail::class, 1);
        $this->assertSame(Invitation::STATUS_SENT, $invitation->fresh()->status);
    }
}
