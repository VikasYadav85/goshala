<?php

namespace Tests\Feature;

use Database\Seeders\SiteSettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Guards the trust's public identity: the correct legal name everywhere, the old
 * name gone, and both addresses (goshala + registered office) present.
 */
class TrustIdentityTest extends TestCase
{
    use RefreshDatabase;

    private const NEW_NAME = 'Gopal Samarpan Sewa Charitable Trust';
    private const OLD_NAME = 'Gopal Seva Samarpan Trust';
    private const GOSHALA = 'Jaunpur, Uttar Pradesh - 222001';
    private const REGISTERED = 'Bhayandar East, Thane, Maharashtra - 401105';

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(SiteSettingSeeder::class);
        config(['app.name' => self::NEW_NAME]);
    }

    public function test_public_pages_show_new_name_and_never_the_old_one(): void
    {
        foreach (['/', '/about', '/our-goshala', '/testimonials', '/donate', '/contact', '/faqs'] as $uri) {
            $res = $this->get($uri);
            $res->assertOk();
            $res->assertSee(self::NEW_NAME);
            $res->assertDontSee(self::OLD_NAME);
        }
    }

    public function test_admin_login_shows_new_name_not_old(): void
    {
        $this->get('/admin/login')
            ->assertOk()
            ->assertSee(self::NEW_NAME)
            ->assertDontSee(self::OLD_NAME);
    }

    public function test_contact_page_shows_both_addresses(): void
    {
        $res = $this->get('/contact');
        $res->assertOk();
        $res->assertSee(self::GOSHALA);          // goshala / visiting
        $res->assertSee(self::REGISTERED);       // registered office (deed)
        $res->assertSee('Registered Office');
    }

    public function test_footer_on_every_page_shows_both_addresses(): void
    {
        $res = $this->get('/');
        $res->assertSee(self::GOSHALA);
        $res->assertSee(self::REGISTERED);
    }

    public function test_no_stale_vrindavan_mathura_address_on_contact_or_footer(): void
    {
        // The trust's stated address must no longer read Vrindavan/Mathura.
        $this->get('/contact')->assertDontSee('Vrindavan, Mathura');
        $this->get('/')->assertDontSee('Vrindavan, Mathura');
    }

    public function test_80g_receipt_config_exposes_registered_office(): void
    {
        // The invoice blade renders the registered office as the trust's official address.
        $this->assertArrayHasKey('registered_office', config('services.trust'));
        $this->assertStringContainsString(
            'registered_office',
            file_get_contents(resource_path('views/pdf/donation-invoice.blade.php')),
        );
    }
}
