<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Theme;
use App\Models\Tenant;
use App\Models\TenantSetting;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Review;
use App\Models\ReviewVote;
use App\Models\Media;
use App\Enums\TenantStatus;
use App\Enums\UserRole;
use Illuminate\Support\Facades\File;

class DemoSeeder extends Seeder
{
    // -------------------------------------------------------------------------
    // Load theme definitions from JSON
    // -------------------------------------------------------------------------

    /**
     * Reads themes.json from database/seeders/data/ and returns the parsed array.
     */
    private function themeData(): array
    {
        $path = database_path('seeders/data/themes.json');

        if (! File::exists($path)) {
            throw new \RuntimeException(
                "Theme JSON file not found at [{$path}]. "
                . "Please place themes.json in database/seeders/data/."
            );
        }

        $raw = File::get($path);
        $decoded = json_decode($raw, associative: true, flags: JSON_THROW_ON_ERROR);

        $nullableKeys = ['palette', 'font', 'buttons', 'inputs', 'glows', 'corners'];

        return array_map(function (array $theme) use ($nullableKeys): array {
            foreach ($nullableKeys as $key) {
                if (isset($theme[$key]) && $theme[$key] === []) {
                    $theme[$key] = null;
                }
            }
            return $theme;
        }, $decoded);
    }

    // -------------------------------------------------------------------------
    // Tenant + product definitions
    // -------------------------------------------------------------------------

    private function tenantData(): array
    {
        return [
            [
                'tenant' => [
                    'name'     => 'Maison Élite',
                    'status'   => TenantStatus::ACTIVE,
                    'logo_url' => null,
                ],
                'domain'   => 'maison.localhost',
                'theme'    => 'Luxe Noir',
                'settings' => [
                    'language'      => 'en',
                    'currency'      => 'USD',
                    'slogan'        => 'Timeless Luxury, Refined.',
                    'contact_email' => 'hello@maison.localhost',
                    'contact_phone' => '+1-800-000-0001',
                ],
                'owner' => [
                    'name'     => 'Sophie Laurent',
                    'username' => 'sophie',
                    'email'    => 'sophie@maison.localhost',
                    'password' => 'password',
                ],
                'categories' => ['Necklaces', 'Rings', 'Bracelets', 'Watches'],
                'products'   => [
                    ['name' => 'Obsidian Pearl Necklace', 'price' => 1240.00, 'stock' => 12, 'description' => 'Handcrafted obsidian beads with a freshwater pearl centrepiece on an 18k gold chain.'],
                    ['name' => 'Onyx Signet Ring',         'price' =>  890.00, 'stock' => 20, 'description' => 'Sterling silver signet ring set with a polished black onyx stone.'],
                    ['name' => 'Gold Leaf Bangle',         'price' =>  560.00, 'stock' => 35, 'description' => 'Delicate 14k gold bangle with an embossed leaf motif.'],
                    ['name' => 'Ivory Dial Watch',         'price' => 2400.00, 'stock' =>  8, 'description' => 'Swiss quartz movement with an ivory dial and a satin leather strap.'],
                    ['name' => 'Diamond Stud Earrings',    'price' => 3200.00, 'stock' =>  6, 'description' => '0.5ct total diamond weight set in 18k white gold four-prong settings.'],
                    ['name' => 'Velvet Choker',            'price' =>  180.00, 'stock' => 50, 'description' => 'Midnight black velvet choker with an antique gold clasp.'],
                ],
            ],

            [
                'tenant' => [
                    'name'     => 'Souq Taze',
                    'status'   => TenantStatus::ACTIVE,
                    'logo_url' => null,
                ],
                'domain'   => 'souq.localhost',
                'theme'    => 'Fresh Market',
                'settings' => [
                    'language'      => 'ar',
                    'currency'      => 'SAR',
                    'slogan'        => 'طازج دائماً',
                    'contact_email' => 'info@souq.localhost',
                    'contact_phone' => '+966-50-000-0002',
                ],
                'owner' => [
                    'name'     => 'Ahmad Al-Rashid',
                    'username' => 'ahmad',
                    'email'    => 'ahmad@souq.localhost',
                    'password' => 'password',
                ],
                'categories' => ['Fruits', 'Vegetables', 'Dairy', 'Bakery'],
                'products'   => [
                    ['name' => 'Organic Avocados × 6',   'price' =>  24.99, 'stock' => 200, 'description' => 'Ripe Hass avocados, certified organic, sourced from local farms.'],
                    ['name' => 'Medjool Dates 1kg',       'price' =>  59.00, 'stock' => 150, 'description' => 'Premium Medjool dates — soft, sweet, and naturally dried.'],
                    ['name' => 'Fresh Camel Milk 1L',     'price' =>  38.00, 'stock' =>  80, 'description' => 'Farm-fresh camel milk, pasteurised and chilled for delivery.'],
                    ['name' => 'Whole Wheat Khubz ×5',   'price' =>  12.50, 'stock' => 300, 'description' => 'Freshly baked whole-wheat Arabic flatbread, soft and warm.'],
                    ['name' => 'Mixed Herb Bundle',       'price' =>  15.00, 'stock' => 120, 'description' => 'Parsley, mint, and coriander harvested daily from our greenhouse.'],
                    ['name' => 'Pomegranate Juice 750ml','price' =>  27.00, 'stock' =>  90, 'description' => 'Cold-pressed, no added sugar. Pure pomegranate from Al-Jawf region.'],
                ],
            ],

            [
                'tenant' => [
                    'name'     => 'TechDrop Store',
                    'status'   => TenantStatus::ACTIVE,
                    'logo_url' => null,
                ],
                'domain'   => 'techdrop.localhost',
                'theme'    => 'TechDrop',
                'settings' => [
                    'language'      => 'en',
                    'currency'      => 'USD',
                    'slogan'        => 'Drop-worthy tech, every week.',
                    'contact_email' => 'support@techdrop.localhost',
                    'contact_phone' => '+1-800-000-0003',
                ],
                'owner' => [
                    'name'     => 'Marcus Webb',
                    'username' => 'marcus',
                    'email'    => 'marcus@techdrop.localhost',
                    'password' => 'password',
                ],
                'categories' => ['Audio', 'Wearables', 'Accessories', 'Gaming'],
                'products'   => [
                    ['name' => 'ProMax X15 Earbuds',         'price' => 199.00, 'stock' =>  60, 'description' => 'Active noise cancellation, 36h battery, IPX5 water resistance.'],
                    ['name' => 'NovaBand 4 Smartwatch',      'price' => 349.00, 'stock' =>  40, 'description' => 'AMOLED always-on display, SpO2, GPS, and 7-day battery life.'],
                    ['name' => 'MagCharge Pad Pro',          'price' =>  59.00, 'stock' => 150, 'description' => '25W MagSafe-compatible wireless charging pad with adaptive cooling.'],
                    ['name' => 'Stealth 60 Gaming Headset',  'price' => 129.00, 'stock' =>  35, 'description' => '7.1 surround, detachable boom mic, memory foam earcups.'],
                    ['name' => 'UltraLink USB-C Hub 9-in-1', 'price' =>  89.00, 'stock' =>  80, 'description' => '4K HDMI, 100W PD, SD card, 3× USB-A 3.0, Ethernet, and audio jack.'],
                    ['name' => 'PixelCam Webcam 4K',         'price' => 169.00, 'stock' =>  25, 'description' => 'Sony sensor, autofocus, dual noise-cancelling mics, plug-and-play.'],
                ],
            ],

            [
                'tenant' => [
                    'name'     => 'Petit Petal',
                    'status'   => TenantStatus::ACTIVE,
                    'logo_url' => null,
                ],
                'domain'   => 'petitpetal.localhost',
                'theme'    => 'Pastel Boutique',
                'settings' => [
                    'language'      => 'en',
                    'currency'      => 'GBP',
                    'slogan'        => 'Little things, big smiles.',
                    'contact_email' => 'hello@petitpetal.localhost',
                    'contact_phone' => '+44-800-000-0004',
                ],
                'owner' => [
                    'name'     => 'Emma Clarke',
                    'username' => 'emma',
                    'email'    => 'emma@petitpetal.localhost',
                    'password' => 'password',
                ],
                'categories' => ['Plush Toys', 'Clothing', 'Nursery', 'Books'],
                'products'   => [
                    ['name' => 'Bunny Plush Toy',          'price' =>  18.50, 'stock' => 120, 'description' => 'Super-soft velvet bunny with embroidered details, suitable from birth.'],
                    ['name' => 'Rainbow Knit Cardigan',    'price' =>  32.00, 'stock' =>  80, 'description' => '100% organic cotton rainbow stripe cardigan, sizes 0–24 months.'],
                    ['name' => 'Cloud Mobile Crib Toy',   'price' =>  24.99, 'stock' =>  60, 'description' => 'Pastel cloud mobile with soft chimes, easy ceiling or crib mount.'],
                    ['name' => 'My First Words Book',     'price' =>  12.00, 'stock' => 200, 'description' => 'Padded board book with bright illustrations for toddlers 1–3 years.'],
                    ['name' => 'Star Night Light',        'price' =>  22.00, 'stock' =>  90, 'description' => 'USB rechargeable silicone star night light with warm amber glow.'],
                    ['name' => 'Personalised Name Puzzle','price' =>  28.00, 'stock' =>  70, 'description' => 'Wooden name puzzle with hand-painted pastel letters, made to order.'],
                ],
            ],

            [
                'tenant' => [
                    'name'     => 'Hepco Supply',
                    'status'   => TenantStatus::ACTIVE,
                    'logo_url' => null,
                ],
                'domain'   => 'hepco.localhost',
                'theme'    => 'Industrial Supply',
                'settings' => [
                    'language'      => 'en',
                    'currency'      => 'EUR',
                    'slogan'        => 'Built for industry.',
                    'contact_email' => 'orders@hepco.localhost',
                    'contact_phone' => '+49-800-000-0005',
                ],
                'owner' => [
                    'name'     => 'Klaus Braun',
                    'username' => 'klaus',
                    'email'    => 'klaus@hepco.localhost',
                    'password' => 'password',
                ],
                'categories' => ['Fasteners', 'Power Tools', 'Safety', 'Hydraulics'],
                'products'   => [
                    ['name' => 'M12 Hex Bolt Set (100pcs)',     'price' =>  34.90, 'stock' => 500, 'description' => 'Grade 8.8 zinc-plated M12×50 hex bolts, full thread, DIN 931.'],
                    ['name' => 'Angle Grinder 850W',            'price' => 119.00, 'stock' =>  40, 'description' => '115mm disc, 11,000 RPM, spindle lock, side handle included.'],
                    ['name' => 'Safety Helmet EN 397',          'price' =>  18.50, 'stock' => 200, 'description' => 'HDPE shell, adjustable ratchet harness, meets EN 397 standard.'],
                    ['name' => 'Hydraulic Floor Jack 3T',       'price' => 189.00, 'stock' =>  25, 'description' => '3-tonne capacity, 145–480mm lift range, overload safety valve.'],
                    ['name' => 'Cable Tie Pack (500pcs)',       'price' =>  12.90, 'stock' => 800, 'description' => '200×3.6mm nylon 66 cable ties, natural colour, UL94-V2 rated.'],
                    ['name' => 'Digital Vernier Caliper 150mm', 'price' =>  27.50, 'stock' =>  60, 'description' => '0.01mm resolution, stainless steel, IP54, auto power-off.'],
                ],
            ],

            [
                'tenant' => [
                    'name'     => 'Velour Fashion',
                    'status'   => TenantStatus::ACTIVE,
                    'logo_url' => null,
                ],
                'domain'   => 'velour.localhost',
                'theme'    => 'Velour',
                'settings' => [
                    'language'      => 'en',
                    'currency'      => 'EUR',
                    'slogan'        => 'Dress with intention.',
                    'contact_email' => 'style@velour.localhost',
                    'contact_phone' => '+33-800-000-0006',
                ],
                'owner' => [
                    'name'     => 'Isabelle Moreau',
                    'username' => 'isabelle',
                    'email'    => 'isabelle@velour.localhost',
                    'password' => 'password',
                ],
                'categories' => ['Dresses', 'Outerwear', 'Knitwear', 'Accessories'],
                'products'   => [
                    ['name' => 'Silk Bias-Cut Midi Dress',  'price' => 340.00, 'stock' => 30, 'description' => '100% silk charmeuse in deep plum, fluid drape, invisible side zip.'],
                    ['name' => 'Cashmere Turtleneck',       'price' => 210.00, 'stock' => 45, 'description' => 'Grade-A two-ply cashmere, relaxed fit, available in six muted tones.'],
                    ['name' => 'Tailored Wool Blazer',      'price' => 480.00, 'stock' => 20, 'description' => 'Italian wool-blend, single-breasted, structured shoulders, fully lined.'],
                    ['name' => 'Wide-Leg Crepe Trousers',   'price' => 195.00, 'stock' => 50, 'description' => 'High-waisted, soft crepe fabric with a subtle sheen. Side pockets.'],
                    ['name' => 'Suede Crossbody Bag',       'price' => 265.00, 'stock' => 22, 'description' => 'Italian suede in cognac, adjustable chain strap, magnetic snap closure.'],
                    ['name' => 'Merino Oversized Coat',     'price' => 620.00, 'stock' => 15, 'description' => 'Double-faced merino wool, dropped shoulders, single statement button.'],
                ],
            ],

            [
                'tenant' => [
                    'name'     => 'PixelQuest Games',
                    'status'   => TenantStatus::ACTIVE,
                    'logo_url' => null,
                ],
                'domain'   => 'pixelquest.localhost',
                'theme'    => 'PixelQuest',
                'settings' => [
                    'language'      => 'en',
                    'currency'      => 'USD',
                    'slogan'        => 'Level up your collection.',
                    'contact_email' => 'play@pixelquest.localhost',
                    'contact_phone' => '+1-800-000-0007',
                ],
                'owner' => [
                    'name'     => 'Ryan Nakamura',
                    'username' => 'ryan',
                    'email'    => 'ryan@pixelquest.localhost',
                    'password' => 'password',
                ],
                'categories' => ['Retro Games', 'Controllers', 'Merch', 'Collectibles'],
                'products'   => [
                    ['name' => 'RetroBox 512 Console',         'price' =>  89.00, 'stock' =>  75, 'description' => 'Pre-loaded with 512 classic titles, HDMI out, 2 wireless pads included.'],
                    ['name' => 'Mechanical Arcade Stick',      'price' => 149.00, 'stock' =>  40, 'description' => 'Sanwa buttons and joystick, USB-C, compatible with PC and most consoles.'],
                    ['name' => 'Pixel Hero Enamel Pin Set',    'price' =>  18.00, 'stock' => 200, 'description' => 'Set of 6 hard enamel pins — classic 8-bit characters, gold metal base.'],
                    ['name' => 'Limited Edition Cartridge Art','price' =>  45.00, 'stock' =>  50, 'description' => 'Framed pixel-art print inspired by golden-era cartridge label art. 30×40cm.'],
                    ['name' => '8-Bit Soundtrack Vinyl LP',    'price' =>  36.00, 'stock' =>  60, 'description' => 'Official chiptune OST pressed on coloured vinyl, includes download code.'],
                    ['name' => 'Glow Keycap Set (RGB)',        'price' =>  29.00, 'stock' => 100, 'description' => 'Pixel-art legends, PBT double-shot, compatible with MX-style switches.'],
                ],
            ],
        ];
    }

    // -------------------------------------------------------------------------
    // Sample reviews per product
    // -------------------------------------------------------------------------

    private function sampleReviews(): array
    {
        return [
            ['rating' => 5, 'comment' => 'Absolutely love it — exactly as described and arrived quickly.'],
            ['rating' => 5, 'comment' => 'Outstanding quality. Will definitely buy again.'],
            ['rating' => 4, 'comment' => 'Great product, minor packaging issue but the item itself is perfect.'],
            ['rating' => 4, 'comment' => 'Very happy with this purchase. Good value for money.'],
            ['rating' => 3, 'comment' => 'Decent quality but took a while to arrive.'],
            ['rating' => 5, 'comment' => 'Exceeded my expectations — highly recommend to everyone.'],
            ['rating' => 2, 'comment' => 'Not quite what I expected from the description.'],
            ['rating' => 5, 'comment' => 'Perfect gift. Beautifully presented and great quality.'],
            ['rating' => 4, 'comment' => 'Solid build and looks exactly like the photos.'],
            ['rating' => 3, 'comment' => 'Does what it says, nothing more. Average experience.'],
        ];
    }

    public function run(): void
    {
        // ------------------------------------------------------------------
        // Seed global themes
        // ------------------------------------------------------------------
        $this->command->info('Seeding global themes...');

        $themes = [];
        foreach ($this->themeData() as $data) {
            $theme = Theme::updateOrCreate(
                ['name' => $data['name']],
                [
                    'is_default' => $data['is_default'],
                    'icon_pack'  => $data['icon_pack'],
                    'currency'   => $data['currency'],
                    'palette'    => $data['palette']  ?? null,
                    'font'       => $data['font']     ?? null,
                    'buttons'    => $data['buttons']  ?? null,
                    'inputs'     => $data['inputs']   ?? null,
                    'header'     => $data['header']   ?? null,
                    'm_header'   => $data['m_header']   ?? null,
                    'glows'      => $data['glows']    ?? null,
                    'corners'    => $data['corners']  ?? null,
                ]
            );
            $themes[$theme->name] = $theme;
        }

        $this->command->info('Themes seeded: ' . count($themes));

        // ------------------------------------------------------------------
        // Seed tenants
        // ------------------------------------------------------------------
        foreach ($this->tenantData() as $entry) {

            $this->command->info("Seeding tenant: {$entry['tenant']['name']}...");

            // --- Tenant ---
            $tenant = Tenant::updateOrCreate(
                ['name' => $entry['tenant']['name']],
                [
                    'status'   => $entry['tenant']['status'],
                    'logo_url' => $entry['tenant']['logo_url'],
                ]
            );

            // --- Domain ---
            $tenant->domains()->updateOrCreate(
                ['domain' => $entry['domain']],
                ['domain' => $entry['domain']]
            );

            // --- Owner user ---
            $owner = User::updateOrCreate(
                ['email' => $entry['owner']['email']],
                [
                    'name'     => $entry['owner']['name'],
                    'username' => $entry['owner']['username'],
                    'password' => bcrypt($entry['owner']['password']),
                    'gender'   => 'male',
                    'phone'    => '970591234567',
                    'role'     => UserRole::TENANT_OWNER,
                ]
            );

            // Attach owner to tenant if not already attached
            if (! $tenant->users()->where('user_id', $owner->id)->exists()) {
                $tenant->users()->attach($owner->id, ['role' => UserRole::TENANT_OWNER]);
            }

            // --- Tenant Settings + Theme assignment ---
            $assignedTheme = $themes[$entry['theme']] ?? $themes['System Default'];

            TenantSetting::updateOrCreate(
                ['tenant_id' => $tenant->id],
                [
                    'theme_id'      => $assignedTheme->id,
                    'language'      => $entry['settings']['language'],
                    'currency'      => $entry['settings']['currency'],
                    'slogan'        => $entry['settings']['slogan'],
                    'contact_email' => $entry['settings']['contact_email'],
                    'contact_phone' => $entry['settings']['contact_phone'],
                ]
            );

            // --- Switch into tenant context for tenant-DB data ---
            try {
                tenancy()->initialize($tenant);

                // --- Categories ---
                $categoryModels = [];
                foreach ($entry['categories'] as $catName) {
                    $categoryModels[$catName] = Category::updateOrCreate(['name' => $catName]);
                }

                // --- Products + Media + Reviews ---
                $reviewPool = $this->sampleReviews();

                $reviewUsers = User::inRandomOrder()->limit(5)->get();

                // Fallback: create a generic reviewer if no users exist yet
                if ($reviewUsers->isEmpty()) {
                    $reviewUsers = collect([
                        User::updateOrCreate(
                            ['email' => 'reviewer@seed.localhost'],
                            [
                                'name'     => 'Seed Reviewer',
                                'username' => 'seed_reviewer',
                                'password' => bcrypt('password'),
                                'gender'   => 'male',
                                'phone'    => '970591234567',
                            ]
                        ),
                    ]);
                }

                foreach ($entry['products'] as $index => $productData) {

                    $product = Product::updateOrCreate(
                        ['name' => $productData['name']],
                        [
                            'price'       => $productData['price'],
                            'stock'       => $productData['stock'],
                            'description' => $productData['description'],
                        ]
                    );

                    // Assign to a rotating category
                    $catName  = $entry['categories'][$index % count($entry['categories'])];
                    $category = $categoryModels[$catName];
                    if (! $product->categories()->where('category_id', $category->id)->exists()) {
                        $product->categories()->attach($category->id);
                    }

                    // Placeholder media record
                    Media::updateOrCreate(
                        [
                            'mediable_id'    => $product->id,
                            'mediable_type'  => Product::class,
                            'collection_name'=> 'product_images',
                        ],
                        [
                            'file_path' => "products/placeholder-{$product->id}.jpg",
                            'file_type' => 'image',
                            'file_size' => 0,
                        ]
                    );

                    // Seed 3 reviews per product (skip if already seeded)
                    if ($product->reviews()->count() === 0) {
                        $ratingSum    = 0;
                        $reviewsCount = 0;

                        $selectedReviews = collect($reviewPool)->shuffle()->take(3);

                        $usedReviewerIds = [];

                        foreach ($selectedReviews as $reviewData) {
                            $availableReviewers = $reviewUsers->whereNotIn('id', $usedReviewerIds);

                            if ($availableReviewers->isEmpty()) {
                                break;
                            }

                            $reviewer = $availableReviewers->random();
                            $usedReviewerIds[] = $reviewer->id;

                            $review = Review::create([
                                'product_id' => $product->id,
                                'user_id'    => $reviewer->id,
                                'rating'     => $reviewData['rating'],
                                'comment'    => $reviewData['comment'],
                            ]);

                            $ratingSum    += $reviewData['rating'];
                            $reviewsCount += 1;

                            $voters = $reviewUsers
                                ->whereNotIn('id', [$reviewer->id])
                                ->take(2);

                            foreach ($voters as $voter) {
                                ReviewVote::updateOrCreate(
                                    ['review_id' => $review->id, 'user_id' => $voter->id],
                                    ['is_helpful' => true]
                                );
                            }
                        }

                        if ($reviewsCount > 0) {
                            $product->update([
                                'reviews_count' => $reviewsCount,
                                'rating_sum'    => $ratingSum,
                                'avg_rating'    => round($ratingSum / $reviewsCount, 1),
                            ]);
                        }
                    }
                }
            } finally {
                tenancy()->end();
            }

            $this->command->info("  ✓ {$entry['tenant']['name']} — domain: {$entry['domain']} — theme: {$entry['theme']}");
        }

        $this->command->info('');
        $this->command->info('All tenants seeded successfully.');
    }
}