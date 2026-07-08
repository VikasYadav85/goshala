<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_returns_valid_xml_with_home_url(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml');
        $response->assertSee('<urlset', false);
        $response->assertSee(route('home'), false);
        $response->assertSee(route('donations.index'), false);

        // Must be well-formed XML.
        $this->assertNotFalse(simplexml_load_string($response->getContent()));
    }

    public function test_robots_txt_exposes_sitemap_and_blocks_admin(): void
    {
        $response = $this->get('/robots.txt');

        $response->assertStatus(200);
        $response->assertSee('Disallow: /admin', false);
        $response->assertSee('Sitemap: '.route('sitemap'), false);
    }

    public function test_home_page_has_canonical_and_structured_data(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('rel="canonical"', false);
        $response->assertSee('application/ld+json', false);
        $response->assertSee('"@type":"NGO"', false);
    }
}
