<?php

namespace App\Providers;

use App\Models\SiteSetting;
use App\Services\RazorpayService;
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
        // Make site-wide values available to every view
        View::share('publicSettings', $this->safeSettings());

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
                'email' => SiteSetting::get('contact_email', env('TRUST_EMAIL')),
                'whatsapp' => SiteSetting::get('contact_whatsapp', env('TRUST_WHATSAPP')),
                'address' => SiteSetting::get('contact_address', env('TRUST_ADDRESS')),
                'tagline' => SiteSetting::get('site_tagline', 'Serving Gau Mata with Devotion, Compassion & Humanity.'),
                'footer_about' => SiteSetting::get('footer_about', 'Gopal Seva Samarpan Trust is a sanctuary for rescued and abandoned cows, rooted in spiritual values and driven by transparency.'),
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
                'email' => env('TRUST_EMAIL'),
                'whatsapp' => env('TRUST_WHATSAPP'),
                'address' => env('TRUST_ADDRESS'),
                'tagline' => 'Serving Gau Mata with Devotion, Compassion & Humanity.',
                'footer_about' => '',
                'social' => ['instagram' => '#', 'facebook' => '#', 'youtube' => '#', 'twitter' => '#'],
            ];
        }
    }
}
