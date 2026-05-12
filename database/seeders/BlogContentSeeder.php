<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogContentSeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Gau Seva', 'Spirituality', 'Krishna Bhakti', 'Cow Protection', 'Organic Living', 'Ayurveda', 'Festivals'];
        $catModels = [];
        foreach ($categories as $i => $name) {
            $catModels[$name] = BlogCategory::updateOrCreate(['slug' => Str::slug($name)], ['name' => $name, 'sort_order' => $i]);
        }

        $author = User::where('email', 'editor@gopalsevatrust.org')->first()
            ?? User::where('role', User::ROLE_SUPER_ADMIN)->first();

        $posts = [
            [
                'title' => 'The Spiritual Significance of Gau Seva in the Vedas',
                'category' => 'Spirituality',
                'excerpt' => 'In every Veda, the cow is celebrated as a vessel of divine energy. Discover why our ancestors called her the mother of the universe.',
                'body' => "Gau Mata is celebrated across all four Vedas as the embodiment of all 33 koti devatas. From the Rig Veda's invocations to the Atharva Veda's purification rituals, the cow has been treated as a sacred presence.\n\nIn modern times, the practice of Gau Seva has evolved beyond ritual — it is now an ecological, social, and spiritual practice that knits together communities, restores soil health, and offers a daily reminder of compassion.\n\nWhen you sponsor a cow at our Goshala, you are participating in an ancient tradition of caregiving — one that benefits the giver as much as the receiver. The merit (punya) of feeding a cow is said to equal that of donating an entire elephant in scripture.\n\nHow to begin your Gau Seva journey:\n1. Visit a goshala — see, smell, touch the cows.\n2. Sponsor monthly fodder for one cow.\n3. Volunteer for feeding or aarti.\n4. Share goshala stories with your family.\n5. Choose dairy from sustainable, ethical goshalas.",
                'tags' => ['vedas', 'spirituality', 'gau-mata'],
                'reading_minutes' => 6,
                'is_featured' => true,
            ],
            [
                'title' => '5 Scientific Benefits of Panchgavya',
                'category' => 'Ayurveda',
                'excerpt' => 'Panchgavya — the five products of the cow — are now studied for their antimicrobial, soil-restorative and immunity-boosting properties.',
                'body' => "Panchgavya is a preparation made from five cow products: milk, curd, ghee, gobar (dung), and gomutra (urine). For centuries it has been used in Ayurveda and agriculture. Modern research is rediscovering its benefits:\n\n1. **Antimicrobial activity** — studies show panchgavya inhibits common pathogens.\n2. **Soil microbiome restoration** — used in organic farming, panchgavya enriches soil bacteria diversity.\n3. **Immunity support** — traditional formulations are being studied as adjuvants.\n4. **Cosmetic uses** — gomutra-based herbal preparations are gentle on skin.\n5. **Plant growth stimulation** — proven effective as foliar spray on a wide variety of crops.\n\nAt our Goshala, we produce panchgavya from indigenous Indian cows only, using traditional methods supervised by Ayurvedic practitioners.",
                'tags' => ['panchgavya', 'ayurveda', 'science'],
                'reading_minutes' => 5,
                'is_featured' => true,
            ],
            [
                'title' => 'How Organic Farming Starts with the Indian Cow',
                'category' => 'Organic Living',
                'excerpt' => 'A single Desi cow can support 30 acres of organic farmland. Here\'s the science of why the Indian cow is irreplaceable for sustainable agriculture.',
                'body' => "The Indian Desi cow — Gir, Sahiwal, Tharparkar — produces gobar (dung) and gomutra (urine) that, when combined with herbs, create the most effective natural fertiliser known to traditional farmers.\n\nA single Desi cow's gobar can support around 30 acres of farmland through preparations like Jeevamrita and Beejamrita. Compare this to chemical fertilisers, which deplete soil over time.\n\nAt our Goshala, we operate a 12-acre organic farm fully fertilised by our cows. We grow chemical-free vegetables, grains, and herbs that go to the Goshala kitchen and surrounding villages.",
                'tags' => ['organic', 'farming', 'desi-cow'],
                'reading_minutes' => 4,
            ],
            [
                'title' => 'Why Krishna Loved Cows — Lessons from Vrindavan',
                'category' => 'Krishna Bhakti',
                'excerpt' => 'Krishna spent his childhood as a cowherd in Vrindavan. The bond between Krishna and the cows holds eternal lessons.',
                'body' => "Lord Krishna's life in Vrindavan with the cows was not coincidence — it was teaching. He showed that the divine and the cow are inseparable, that simplicity and seva together are the highest yoga.\n\nThe cow embodies surrender. She gives without expectation. She nurtures everyone. To love the cow is to walk in Krishna's footsteps.\n\nWhen we run our Goshala in Vrindavan, we are not just running a shelter — we are continuing the tradition of Krishna's playground.",
                'tags' => ['krishna', 'vrindavan', 'bhakti'],
                'reading_minutes' => 5,
            ],
        ];

        foreach ($posts as $data) {
            BlogPost::updateOrCreate(
                ['slug' => Str::slug($data['title'])],
                [
                    'category_id' => $catModels[$data['category']]->id,
                    'author_id' => $author?->id,
                    'title' => $data['title'],
                    'excerpt' => $data['excerpt'],
                    'body' => $data['body'],
                    'tags' => $data['tags'],
                    'reading_minutes' => $data['reading_minutes'],
                    'is_featured' => $data['is_featured'] ?? false,
                    'status' => BlogPost::STATUS_PUBLISHED,
                    'published_at' => now()->subDays(rand(1, 60)),
                ],
            );
        }
    }
}
