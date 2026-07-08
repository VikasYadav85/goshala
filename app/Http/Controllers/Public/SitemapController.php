<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Campaign;
use App\Models\Event;
use App\Models\GalleryAlbum;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Public XML sitemap consumed by search engines.
     * All URLs derive from APP_URL (route helpers), so switching the domain
     * needs no code change — only the APP_URL env value.
     */
    public function index(): Response
    {
        $urls = [];

        // Static public pages: [route name, changefreq, priority]
        $staticPages = [
            ['home', 'weekly', '1.0'],
            ['about', 'monthly', '0.7'],
            ['goshala', 'monthly', '0.7'],
            ['transparency', 'monthly', '0.6'],
            ['testimonials', 'monthly', '0.5'],
            ['faqs', 'monthly', '0.5'],
            ['donations.index', 'weekly', '0.9'],
            ['campaigns.index', 'weekly', '0.8'],
            ['events.index', 'weekly', '0.7'],
            ['gallery.index', 'monthly', '0.6'],
            ['blog.index', 'weekly', '0.7'],
            ['volunteer.index', 'monthly', '0.6'],
            ['contact.index', 'monthly', '0.6'],
        ];

        foreach ($staticPages as [$name, $freq, $priority]) {
            $urls[] = [
                'loc' => route($name),
                'changefreq' => $freq,
                'priority' => $priority,
            ];
        }

        // Published blog posts
        foreach (BlogPost::published()->latest('published_at')->get() as $post) {
            $urls[] = [
                'loc' => route('blog.show', $post->slug),
                'lastmod' => optional($post->updated_at)->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.6',
            ];
        }

        // Public campaigns (exclude drafts)
        foreach (Campaign::whereIn('status', ['active', 'upcoming', 'completed'])->get() as $campaign) {
            $urls[] = [
                'loc' => route('campaigns.show', $campaign->slug),
                'lastmod' => optional($campaign->updated_at)->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ];
        }

        // Events (upcoming + past = all dated public events)
        foreach (Event::whereIn('status', ['upcoming', 'past', 'completed'])->orWhereNotNull('starts_at')->get() as $event) {
            $urls[] = [
                'loc' => route('events.show', $event->slug),
                'lastmod' => optional($event->updated_at)->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.5',
            ];
        }

        // Published gallery albums
        foreach (GalleryAlbum::published()->get() as $album) {
            $urls[] = [
                'loc' => route('gallery.show', $album->slug),
                'lastmod' => optional($album->updated_at)->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.5',
            ];
        }

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }
}
