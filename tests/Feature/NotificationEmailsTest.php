<?php

namespace Tests\Feature;

use App\Mail\ContactAcknowledgementMail;
use App\Mail\ContactAdminNotification;
use App\Mail\SubscriberWelcomeMail;
use App\Mail\VolunteerAdminNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class NotificationEmailsTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_emails_admin_and_sender(): void
    {
        Mail::fake();
        config(['services.admin.email' => 'admin@example.com']);

        $this->post('/contact', [
            'name' => 'Ram Kumar',
            'email' => 'ram@example.com',
            'message_type' => 'general',
            'message' => 'I would like to visit the goshala this weekend.',
        ])->assertRedirect();

        Mail::assertSent(ContactAdminNotification::class, fn ($m) => $m->hasTo('admin@example.com'));
        Mail::assertSent(ContactAcknowledgementMail::class, fn ($m) => $m->hasTo('ram@example.com'));
    }

    public function test_new_subscriber_gets_welcome_email(): void
    {
        Mail::fake();

        $this->post('/subscribe', ['email' => 'newsub@example.com', 'name' => 'Sita'])
            ->assertRedirect();

        Mail::assertSent(SubscriberWelcomeMail::class, fn ($m) => $m->hasTo('newsub@example.com'));
    }

    public function test_resubscribe_does_not_resend_welcome(): void
    {
        \App\Models\Subscriber::create(['email' => 'existing@example.com', 'is_subscribed' => true]);
        Mail::fake();

        $this->post('/subscribe', ['email' => 'existing@example.com'])->assertRedirect();

        Mail::assertNotSent(SubscriberWelcomeMail::class);
    }

    public function test_volunteer_form_emails_admin(): void
    {
        Mail::fake();
        config(['services.admin.email' => 'admin@example.com']);

        $this->post('/volunteer', [
            'full_name' => 'Gopal Das',
            'email' => 'gopal@example.com',
            'phone' => '9876543210',
        ])->assertRedirect(route('volunteer.thanks'));

        Mail::assertSent(VolunteerAdminNotification::class, fn ($m) => $m->hasTo('admin@example.com'));
    }
}
