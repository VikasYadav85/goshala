<?php

namespace App\Providers;

use App\Models\SiteSetting;
use App\Models\User;
use App\Services\RazorpayService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Mailer\Bridge\Brevo\Transport\BrevoTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(RazorpayService::class, function () {
            return new RazorpayService(
                (string) config('services.razorpay.key', ''),
                (string) config('services.razorpay.secret', ''),
                (string) config('services.razorpay.currency', 'INR'),
            );
        });
    }

    public function boot(): void
    {
        // super_admin bypasses every gate/permission check (present and future).
        Gate::before(fn (User $user, string $ability) => $user->hasRole(User::ROLE_SUPER_ADMIN) ? true : null);

        // Make site-wide values available to every view. Resolved lazily per render
        // (not once at boot) so it reflects settings written during the request —
        // and reads from the cached settings collection, so it stays cheap.
        View::composer('*', fn ($view) => $view->with('publicSettings', $this->safeSettings()));

        // Brevo HTTP-API mail transport (this host blocks outbound SMTP).
        Mail::extend('brevo', function () {
            return (new BrevoTransportFactory)->create(
                new Dsn('brevo+api', 'default', (string) config('services.brevo.key')),
            );
        });
    }

    private function safeSettings(): array
    {
        try {
            return [
                'phone' => SiteSetting::get('contact_phone', env('TRUST_PHONE')),
                'phone2' => SiteSetting::get('contact_phone_2', env('TRUST_PHONE_2')),
                'email' => SiteSetting::get('contact_email', env('TRUST_EMAIL')),
                'email2' => SiteSetting::get('contact_email_2', env('TRUST_EMAIL_2')),
                'whatsapp' => SiteSetting::get('contact_whatsapp', env('TRUST_WHATSAPP')),
                'address' => SiteSetting::get('contact_address', env('TRUST_ADDRESS')),
                'registered_office' => SiteSetting::get('registered_office', env('TRUST_REGISTERED_OFFICE')),
                'tagline' => SiteSetting::get('site_tagline', 'Serving Gau Mata with Devotion, Compassion & Humanity.'),
                'footer_about' => SiteSetting::get('footer_about', 'Gopal Samarpan Sewa Charitable Trust is a sanctuary for rescued and abandoned cows, rooted in spiritual values and driven by transparency.'),
                'social' => [
                    'instagram' => SiteSetting::get('social_instagram', '#'),
                    'facebook'  => SiteSetting::get('social_facebook', '#'),
                    'youtube'   => SiteSetting::get('social_youtube', '#'),
                    'twitter'   => SiteSetting::get('social_twitter', '#'),
                ],
            ];
        } catch (\Throwable $e) {
            return [
                'phone' => env('TRUST_PHONE'),
                'phone2' => env('TRUST_PHONE_2'),
                'email' => env('TRUST_EMAIL'),
                'email2' => env('TRUST_EMAIL_2'),
                'whatsapp' => env('TRUST_WHATSAPP'),
                'address' => env('TRUST_ADDRESS'),
                'registered_office' => env('TRUST_REGISTERED_OFFICE'),
                'tagline' => 'Serving Gau Mata with Devotion, Compassion & Humanity.',
                'footer_about' => '',
                'social' => ['instagram' => '#', 'facebook' => '#', 'youtube' => '#', 'twitter' => '#'],
            ];
        }
    }
}
