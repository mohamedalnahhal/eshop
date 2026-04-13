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
                    'currency'      => 'USD',
                    'slogan'        => 'Timeless Luxury, Refined.',
                    'contact_email' => 'hello@maison.localhost',
                    'contact_phone' => '+1-800-000-0001',
                    'supported_languages' => ['en', 'fr'],
                    'default_language'    => 'en',
                ],
                'owner' => [
                    'name'     => 'Sophie Laurent',
                    'username' => 'sophie',
                    'email'    => 'sophie@maison.localhost',
                    'password' => 'password',
                ],
                'categories' => [
                    ['en' => ['name' => 'Necklaces'], 'fr' => ['name' => 'Colliers']],
                    ['en' => ['name' => 'Rings'],     'fr' => ['name' => 'Bagues']],
                    ['en' => ['name' => 'Bracelets'], 'fr' => ['name' => 'Bracelets']],
                    ['en' => ['name' => 'Watches'],   'fr' => ['name' => 'Montres']],
                ],
                'products'   => [
                    [
                        'price' => 1240.00, 'stock' => 12,
                        'en' => ['name' => 'Obsidian Pearl Necklace', 'description' => 'Handcrafted obsidian beads with a freshwater pearl centrepiece on an 18k gold chain.'],
                        'fr' => ['name' => 'Collier de Perles d\'Obsidienne', 'description' => 'Perles d\'obsidienne fabriquées à la main avec une perle d\'eau douce sur une chaîne en or 18 carats.'],
                    ],
                    [
                        'price' => 890.00, 'stock' => 20,
                        'en' => ['name' => 'Onyx Signet Ring', 'description' => 'Sterling silver signet ring set with a polished black onyx stone.'],
                        'fr' => ['name' => 'Chevalière en Onyx', 'description' => 'Chevalière en argent sterling sertie d\'une pierre d\'onyx noir poli.'],
                    ],
                    [
                        'price' => 560.00, 'stock' => 35,
                        'en' => ['name' => 'Gold Leaf Bangle', 'description' => 'Delicate 14k gold bangle with an embossed leaf motif.'],
                        'fr' => ['name' => 'Bracelet Feuille d\'Or', 'description' => 'Bracelet délicat en or 14 carats avec un motif de feuille en relief.'],
                    ],
                    [
                        'price' => 2400.00, 'stock' => 8,
                        'en' => ['name' => 'Ivory Dial Watch', 'description' => 'Swiss quartz movement with an ivory dial and a satin leather strap.'],
                        'fr' => ['name' => 'Montre à Cadran Ivoire', 'description' => 'Mouvement à quartz suisse avec cadran ivoire et bracelet en cuir satiné.'],
                    ],
                    [
                        'price' => 3200.00, 'stock' => 6,
                        'en' => ['name' => 'Diamond Stud Earrings', 'description' => '0.5ct total diamond weight set in 18k white gold four-prong settings.'],
                        'fr' => ['name' => 'Boucles d\'Oreilles en Diamant', 'description' => 'Diamants de 0,5 ct sertis sur or blanc 18 carats à quatre griffes.'],
                    ],
                    [
                        'price' => 180.00, 'stock' => 50,
                        'en' => ['name' => 'Velvet Choker', 'description' => 'Midnight black velvet choker with an antique gold clasp.'],
                        'fr' => ['name' => 'Ras de Cou en Velours', 'description' => 'Ras de cou en velours noir minuit avec fermoir en or antique.'],
                    ],
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
                    'currency'      => 'SAR',
                    'slogan'        => 'طازج دائماً',
                    'contact_email' => 'info@souq.localhost',
                    'contact_phone' => '+966-50-000-0002',
                    'supported_languages' => ['ar', 'en'],
                    'default_language'    => 'ar',
                ],
                'owner' => [
                    'name'     => 'Ahmad Al-Rashid',
                    'username' => 'ahmad',
                    'email'    => 'ahmad@souq.localhost',
                    'password' => 'password',
                ],
                'categories' => [
                    ['ar' => ['name' => 'فواكه'], 'en' => ['name' => 'Fruits']],
                    ['ar' => ['name' => 'خضروات'], 'en' => ['name' => 'Vegetables']],
                    ['ar' => ['name' => 'ألبان'], 'en' => ['name' => 'Dairy']],
                    ['ar' => ['name' => 'مخبوزات'], 'en' => ['name' => 'Bakery']],
                ],
                'products'   => [
                    [
                        'price' => 24.99, 'stock' => 200,
                        'ar' => ['name' => 'أفوكادو عضوي × 6', 'description' => 'أفوكادو هاس ناضج، عضوي معتمد، من مزارع محلية.'],
                        'en' => ['name' => 'Organic Avocados × 6', 'description' => 'Ripe Hass avocados, certified organic, sourced from local farms.'],
                    ],
                    [
                        'price' => 59.00, 'stock' => 150,
                        'ar' => ['name' => 'تمر مجدول 1 كجم', 'description' => 'تمور مجدول فاخرة — طرية وحلوة ومجففة طبيعياً.'],
                        'en' => ['name' => 'Medjool Dates 1kg', 'description' => 'Premium Medjool dates — soft, sweet, and naturally dried.'],
                    ],
                    [
                        'price' => 38.00, 'stock' => 80,
                        'ar' => ['name' => 'حليب إبل طازج 1 لتر', 'description' => 'حليب إبل طازج من المزرعة، مبستر ومبرد للتوصيل.'],
                        'en' => ['name' => 'Fresh Camel Milk 1L', 'description' => 'Farm-fresh camel milk, pasteurised and chilled for delivery.'],
                    ],
                    [
                        'price' => 12.50, 'stock' => 300,
                        'ar' => ['name' => 'خبز بر ×5', 'description' => 'خبز عربي أسمر طازج، طري ودافئ.'],
                        'en' => ['name' => 'Whole Wheat Khubz ×5', 'description' => 'Freshly baked whole-wheat Arabic flatbread, soft and warm.'],
                    ],
                    [
                        'price' => 15.00, 'stock' => 120,
                        'ar' => ['name' => 'حزمة أعشاب مشكلة', 'description' => 'بقدونس، نعناع، وكزبرة تحصد يومياً من مزارعنا.'],
                        'en' => ['name' => 'Mixed Herb Bundle', 'description' => 'Parsley, mint, and coriander harvested daily from our greenhouse.'],
                    ],
                    [
                        'price' => 27.00, 'stock' => 90,
                        'ar' => ['name' => 'عصير رمان 750 مل', 'description' => 'معصور على البارد، بدون سكر مضاف. رمان طبيعي من منطقة الجوف.'],
                        'en' => ['name' => 'Pomegranate Juice 750ml', 'description' => 'Cold-pressed, no added sugar. Pure pomegranate from Al-Jawf region.'],
                    ],
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
                    'currency'      => 'USD',
                    'slogan'        => 'Drop-worthy tech, every week.',
                    'contact_email' => 'support@techdrop.localhost',
                    'contact_phone' => '+1-800-000-0003',
                    'supported_languages' => ['en', 'es'],
                    'default_language'    => 'en',
                ],
                'owner' => [
                    'name'     => 'Marcus Webb',
                    'username' => 'marcus',
                    'email'    => 'marcus@techdrop.localhost',
                    'password' => 'password',
                ],
                'categories' => [
                    ['en' => ['name' => 'Audio'],       'es' => ['name' => 'Audio']],
                    ['en' => ['name' => 'Wearables'],   'es' => ['name' => 'Vestibles']],
                    ['en' => ['name' => 'Accessories'], 'es' => ['name' => 'Accesorios']],
                    ['en' => ['name' => 'Gaming'],      'es' => ['name' => 'Videojuegos']],
                ],
                'products'   => [
                    [
                        'price' => 199.00, 'stock' => 60,
                        'en' => ['name' => 'ProMax X15 Earbuds', 'description' => 'Active noise cancellation, 36h battery, IPX5 water resistance.'],
                        'es' => ['name' => 'Auriculares ProMax X15', 'description' => 'Cancelación activa de ruido, 36h de batería, resistencia al agua IPX5.'],
                    ],
                    [
                        'price' => 349.00, 'stock' => 40,
                        'en' => ['name' => 'NovaBand 4 Smartwatch', 'description' => 'AMOLED always-on display, SpO2, GPS, and 7-day battery life.'],
                        'es' => ['name' => 'Smartwatch NovaBand 4', 'description' => 'Pantalla AMOLED siempre encendida, SpO2, GPS y 7 días de batería.'],
                    ],
                    [
                        'price' => 59.00, 'stock' => 150,
                        'en' => ['name' => 'MagCharge Pad Pro', 'description' => '25W MagSafe-compatible wireless charging pad with adaptive cooling.'],
                        'es' => ['name' => 'Base MagCharge Pro', 'description' => 'Base de carga inalámbrica de 25W compatible con MagSafe con refrigeración adaptativa.'],
                    ],
                    [
                        'price' => 129.00, 'stock' => 35,
                        'en' => ['name' => 'Stealth 60 Gaming Headset', 'description' => '7.1 surround, detachable boom mic, memory foam earcups.'],
                        'es' => ['name' => 'Auriculares Gaming Stealth 60', 'description' => 'Sonido envolvente 7.1, micrófono extraíble, almohadillas de espuma viscoelástica.'],
                    ],
                    [
                        'price' => 89.00, 'stock' => 80,
                        'en' => ['name' => 'UltraLink USB-C Hub 9-in-1', 'description' => '4K HDMI, 100W PD, SD card, 3× USB-A 3.0, Ethernet, and audio jack.'],
                        'es' => ['name' => 'Hub UltraLink USB-C 9-en-1', 'description' => 'HDMI 4K, PD de 100W, tarjeta SD, 3× USB-A 3.0, Ethernet y conector de audio.'],
                    ],
                    [
                        'price' => 169.00, 'stock' => 25,
                        'en' => ['name' => 'PixelCam Webcam 4K', 'description' => 'Sony sensor, autofocus, dual noise-cancelling mics, plug-and-play.'],
                        'es' => ['name' => 'Cámara Web PixelCam 4K', 'description' => 'Sensor Sony, enfoque automático, micrófonos duales con cancelación de ruido, plug-and-play.'],
                    ],
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
                    'currency'      => 'GBP',
                    'slogan'        => 'Little things, big smiles.',
                    'contact_email' => 'hello@petitpetal.localhost',
                    'contact_phone' => '+44-800-000-0004',
                    'supported_languages' => ['en', 'fr'],
                    'default_language'    => 'en',
                ],
                'owner' => [
                    'name'     => 'Emma Clarke',
                    'username' => 'emma',
                    'email'    => 'emma@petitpetal.localhost',
                    'password' => 'password',
                ],
                'categories' => [
                    ['en' => ['name' => 'Plush Toys'], 'fr' => ['name' => 'Peluches']],
                    ['en' => ['name' => 'Clothing'],   'fr' => ['name' => 'Vêtements']],
                    ['en' => ['name' => 'Nursery'],    'fr' => ['name' => 'Chambre d\'enfant']],
                    ['en' => ['name' => 'Books'],      'fr' => ['name' => 'Livres']],
                ],
                'products'   => [
                    [
                        'price' => 18.50, 'stock' => 120,
                        'en' => ['name' => 'Bunny Plush Toy', 'description' => 'Super-soft velvet bunny with embroidered details, suitable from birth.'],
                        'fr' => ['name' => 'Peluche Lapin', 'description' => 'Lapin en velours ultra-doux avec détails brodés, adapté dès la naissance.'],
                    ],
                    [
                        'price' => 32.00, 'stock' => 80,
                        'en' => ['name' => 'Rainbow Knit Cardigan', 'description' => '100% organic cotton rainbow stripe cardigan, sizes 0–24 months.'],
                        'fr' => ['name' => 'Gilet Tricot Arc-en-ciel', 'description' => 'Gilet rayé arc-en-ciel 100% coton biologique, tailles 0–24 mois.'],
                    ],
                    [
                        'price' => 24.99, 'stock' => 60,
                        'en' => ['name' => 'Cloud Mobile Crib Toy', 'description' => 'Pastel cloud mobile with soft chimes, easy ceiling or crib mount.'],
                        'fr' => ['name' => 'Mobile Nuage pour Bébé', 'description' => 'Mobile nuage pastel avec doux carillons, facile à monter sur le plafond ou le lit.'],
                    ],
                    [
                        'price' => 12.00, 'stock' => 200,
                        'en' => ['name' => 'My First Words Book', 'description' => 'Padded board book with bright illustrations for toddlers 1–3 years.'],
                        'fr' => ['name' => 'Livre Mes Premiers Mots', 'description' => 'Livre cartonné rembourré avec des illustrations vives pour les tout-petits de 1 à 3 ans.'],
                    ],
                    [
                        'price' => 22.00, 'stock' => 90,
                        'en' => ['name' => 'Star Night Light', 'description' => 'USB rechargeable silicone star night light with warm amber glow.'],
                        'fr' => ['name' => 'Veilleuse Étoile', 'description' => 'Veilleuse étoile en silicone rechargeable par USB avec une douce lueur ambrée.'],
                    ],
                    [
                        'price' => 28.00, 'stock' => 70,
                        'en' => ['name' => 'Personalised Name Puzzle', 'description' => 'Wooden name puzzle with hand-painted pastel letters, made to order.'],
                        'fr' => ['name' => 'Puzzle Prénom Personnalisé', 'description' => 'Puzzle prénom en bois avec lettres pastel peintes à la main, fait sur commande.'],
                    ],
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
                    'currency'      => 'EUR',
                    'slogan'        => 'Built for industry.',
                    'contact_email' => 'orders@hepco.localhost',
                    'contact_phone' => '+49-800-000-0005',
                    'supported_languages' => ['en', 'de'],
                    'default_language'    => 'en',
                ],
                'owner' => [
                    'name'     => 'Klaus Braun',
                    'username' => 'klaus',
                    'email'    => 'klaus@hepco.localhost',
                    'password' => 'password',
                ],
                'categories' => [
                    ['en' => ['name' => 'Fasteners'],   'de' => ['name' => 'Befestigungselemente']],
                    ['en' => ['name' => 'Power Tools'], 'de' => ['name' => 'Elektrowerkzeuge']],
                    ['en' => ['name' => 'Safety'],      'de' => ['name' => 'Arbeitsschutz']],
                    ['en' => ['name' => 'Hydraulics'],  'de' => ['name' => 'Hydraulik']],
                ],
                'products'   => [
                    [
                        'price' => 34.90, 'stock' => 500,
                        'en' => ['name' => 'M12 Hex Bolt Set (100pcs)', 'description' => 'Grade 8.8 zinc-plated M12×50 hex bolts, full thread, DIN 931.'],
                        'de' => ['name' => 'M12 Sechskantschrauben-Set (100 Stk)', 'description' => 'Verzinkte M12×50 Sechskantschrauben Güte 8.8, Vollgewinde, DIN 931.'],
                    ],
                    [
                        'price' => 119.00, 'stock' => 40,
                        'en' => ['name' => 'Angle Grinder 850W', 'description' => '115mm disc, 11,000 RPM, spindle lock, side handle included.'],
                        'de' => ['name' => 'Winkelschleifer 850W', 'description' => '115mm Scheibe, 11.000 U/min, Spindelarretierung, inklusive Seitengriff.'],
                    ],
                    [
                        'price' => 18.50, 'stock' => 200,
                        'en' => ['name' => 'Safety Helmet EN 397', 'description' => 'HDPE shell, adjustable ratchet harness, meets EN 397 standard.'],
                        'de' => ['name' => 'Schutzhelm EN 397', 'description' => 'HDPE-Schale, verstellbare Ratschenaufhängung, entspricht der Norm EN 397.'],
                    ],
                    [
                        'price' => 189.00, 'stock' => 25,
                        'en' => ['name' => 'Hydraulic Floor Jack 3T', 'description' => '3-tonne capacity, 145–480mm lift range, overload safety valve.'],
                        'de' => ['name' => 'Hydraulischer Wagenheber 3T', 'description' => '3 Tonnen Tragkraft, 145–480mm Hubbereich, Überlastsicherheitsventil.'],
                    ],
                    [
                        'price' => 12.90, 'stock' => 800,
                        'en' => ['name' => 'Cable Tie Pack (500pcs)', 'description' => '200×3.6mm nylon 66 cable ties, natural colour, UL94-V2 rated.'],
                        'de' => ['name' => 'Kabelbinder-Pack (500 Stk)', 'description' => '200×3,6mm Nylon 66 Kabelbinder, naturfarben, UL94-V2 zertifiziert.'],
                    ],
                    [
                        'price' => 27.50, 'stock' => 60,
                        'en' => ['name' => 'Digital Vernier Caliper 150mm', 'description' => '0.01mm resolution, stainless steel, IP54, auto power-off.'],
                        'de' => ['name' => 'Digitaler Messschieber 150mm', 'description' => '0,01mm Auflösung, Edelstahl, IP54, automatische Abschaltung.'],
                    ],
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
                    'currency'      => 'EUR',
                    'slogan'        => 'Dress with intention.',
                    'contact_email' => 'style@velour.localhost',
                    'contact_phone' => '+33-800-000-0006',
                    'supported_languages' => ['en', 'fr'],
                    'default_language'    => 'en',
                ],
                'owner' => [
                    'name'     => 'Isabelle Moreau',
                    'username' => 'isabelle',
                    'email'    => 'isabelle@velour.localhost',
                    'password' => 'password',
                ],
                'categories' => [
                    ['en' => ['name' => 'Dresses'],     'fr' => ['name' => 'Robes']],
                    ['en' => ['name' => 'Outerwear'],   'fr' => ['name' => 'Manteaux']],
                    ['en' => ['name' => 'Knitwear'],    'fr' => ['name' => 'Mailles']],
                    ['en' => ['name' => 'Accessories'], 'fr' => ['name' => 'Accessoires']],
                ],
                'products'   => [
                    [
                        'price' => 340.00, 'stock' => 30,
                        'en' => ['name' => 'Silk Bias-Cut Midi Dress', 'description' => '100% silk charmeuse in deep plum, fluid drape, invisible side zip.'],
                        'fr' => ['name' => 'Robe Midi en Soie', 'description' => 'Charmeuse 100% soie coupe en biais, couleur prune foncée, tombé fluide, zip latéral invisible.'],
                    ],
                    [
                        'price' => 210.00, 'stock' => 45,
                        'en' => ['name' => 'Cashmere Turtleneck', 'description' => 'Grade-A two-ply cashmere, relaxed fit, available in six muted tones.'],
                        'fr' => ['name' => 'Col Roulé en Cachemire', 'description' => 'Cachemire double fil de grade A, coupe décontractée, disponible en six tons discrets.'],
                    ],
                    [
                        'price' => 480.00, 'stock' => 20,
                        'en' => ['name' => 'Tailored Wool Blazer', 'description' => 'Italian wool-blend, single-breasted, structured shoulders, fully lined.'],
                        'fr' => ['name' => 'Blazer en Laine Ajusté', 'description' => 'Mélange de laine italienne, simple boutonnage, épaules structurées, entièrement doublé.'],
                    ],
                    [
                        'price' => 195.00, 'stock' => 50,
                        'en' => ['name' => 'Wide-Leg Crepe Trousers', 'description' => 'High-waisted, soft crepe fabric with a subtle sheen. Side pockets.'],
                        'fr' => ['name' => 'Pantalon Large en Crêpe', 'description' => 'Taille haute, tissu en crêpe doux avec un éclat subtil. Poches latérales.'],
                    ],
                    [
                        'price' => 265.00, 'stock' => 22,
                        'en' => ['name' => 'Suede Crossbody Bag', 'description' => 'Italian suede in cognac, adjustable chain strap, magnetic snap closure.'],
                        'fr' => ['name' => 'Sac Bandoulière en Daim', 'description' => 'Daim italien cognac, sangle en chaîne réglable, fermeture magnétique.'],
                    ],
                    [
                        'price' => 620.00, 'stock' => 15,
                        'en' => ['name' => 'Merino Oversized Coat', 'description' => 'Double-faced merino wool, dropped shoulders, single statement button.'],
                        'fr' => ['name' => 'Manteau Oversize en Mérinos', 'description' => 'Laine mérinos double face, épaules tombantes, bouton unique.'],
                    ],
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
                    'currency'      => 'USD',
                    'slogan'        => 'Level up your collection.',
                    'contact_email' => 'play@pixelquest.localhost',
                    'contact_phone' => '+1-800-000-0007',
                    'supported_languages' => ['en', 'es'],
                    'default_language'    => 'en',
                ],
                'owner' => [
                    'name'     => 'Ryan Nakamura',
                    'username' => 'ryan',
                    'email'    => 'ryan@pixelquest.localhost',
                    'password' => 'password',
                ],
                'categories' => [
                    [
                        'en' => ['name' => 'Retro Games'],
                        'es' => ['name' => 'Juegos Retro'],
                    ],
                    [
                        'en' => ['name' => 'Controllers'],
                        'es' => ['name' => 'Controles'],
                    ],
                    [
                        'en' => ['name' => 'Merch'],
                        'es' => ['name' => 'Mercancía'],
                    ],
                    [
                        'en' => ['name' => 'Collectibles'],
                        'es' => ['name' => 'Coleccionables'],
                    ],
                ],
                'products'   => [
                    [
                        'price' => 89.00, 
                        'stock' => 75, 
                        'en' => ['name' => 'RetroBox 512 Console', 'description' => 'Pre-loaded with 512 classic titles, HDMI out, 2 wireless pads included.'],
                        'es' => ['name' => 'Consola RetroBox 512', 'description' => 'Precargada con 512 títulos clásicos, salida HDMI, incluye 2 controles inalámbricos.'],
                    ],
                    [
                        'price' => 149.00, 
                        'stock' => 40, 
                        'en' => ['name' => 'Mechanical Arcade Stick', 'description' => 'Sanwa buttons and joystick, USB-C, compatible with PC and most consoles.'],
                        'es' => ['name' => 'Stick Arcade Mecánico', 'description' => 'Botones y joystick Sanwa, USB-C, compatible con PC y la mayoría de las consolas.'],
                    ],
                    [
                        'price' => 18.00, 
                        'stock' => 200, 
                        'en' => ['name' => 'Pixel Hero Enamel Pin Set', 'description' => 'Set of 6 hard enamel pins — classic 8-bit characters, gold metal base.'],
                        'es' => ['name' => 'Set de Pines Pixel Hero', 'description' => 'Juego de 6 pines de esmalte duro — personajes clásicos de 8 bits, base de metal dorado.'],
                    ],
                    [
                        'price' => 45.00, 
                        'stock' => 50, 
                        'en' => ['name' => 'Limited Edition Cartridge Art', 'description' => 'Framed pixel-art print inspired by golden-era cartridge label art. 30×40cm.'],
                        'es' => ['name' => 'Arte de Cartucho Edición Limitada', 'description' => 'Impresión de pixel-art enmarcada inspirada en el arte de los cartuchos de la era dorada. 30x40cm.'],
                    ],
                    [
                        'price' => 36.00, 
                        'stock' => 60, 
                        'en' => ['name' => '8-Bit Soundtrack Vinyl LP', 'description' => 'Official chiptune OST pressed on coloured vinyl, includes download code.'],
                        'es' => ['name' => 'Vinilo LP Banda Sonora 8-Bit', 'description' => 'Banda sonora oficial chiptune en vinilo de color, incluye código de descarga.'],
                    ],
                    [
                        'price' => 29.00, 
                        'stock' => 100, 
                        'en' => ['name' => 'Glow Keycap Set (RGB)', 'description' => 'Pixel-art legends, PBT double-shot, compatible with MX-style switches.'],
                        'es' => ['name' => 'Set de Teclas Glow (RGB)', 'description' => 'Leyendas pixel-art, PBT doble inyección, compatible con interruptores estilo MX.'],
                    ],
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

        $this->command->info('Seeding Super Admin User...');
        User::updateOrCreate(
            ['email' => 'admin@eshop.com'],
            [
                'name'     => 'admin',
                'username' => 'admin',
                'password' => bcrypt('password'),
                'gender'   => 'male',
                'phone'    => '970591234567',
                'role'     => UserRole::ADMIN,
            ]
        );

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
                    'role'     => UserRole::TENANT,
                ]
            );

            // Attach owner to tenant if not already attached
            if (! $tenant->users()->where('user_id', $owner->id)->exists()) {
                $tenant->users()->attach($owner->id, ['role' => UserRole::TENANT]);
            }

            // --- Tenant Settings + Theme assignment ---
            $assignedTheme = $themes[$entry['theme']] ?? $themes['System Default'];

            TenantSetting::updateOrCreate(
                ['tenant_id' => $tenant->id],
                [
                    'theme_id'      => $assignedTheme->id,
                    'currency'      => $entry['settings']['currency'],
                    'slogan'        => $entry['settings']['slogan'],
                    'contact_email' => $entry['settings']['contact_email'],
                    'contact_phone' => $entry['settings']['contact_phone'],
                    'supported_languages' => $entry['settings']['supported_languages'],
                    'default_language'    => $entry['settings']['default_language'],
                ]
            );

            $defaultLanguage = $entry['settings']['default_language'];

            // --- Switch into tenant context for tenant-DB data ---
            try {
                tenancy()->initialize($tenant);

                // --- Categories ---
                $categoryModels = [];
                foreach ($entry['categories'] as $catData) {
                    $defaultName = $catData[$defaultLanguage]['name'];
                    $category = Category::whereTranslation('name', $defaultName)->first();
                    if (!$category) {
                        $category = Category::create($catData);
                    }
                    $categoryModels[$defaultName] = $category;
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
        
                    $defaultName = $productData[$defaultLanguage]['name'];
            
                    $product = Product::whereTranslation('name', $defaultName)->first();
            
                    if (!$product) {
                        $product = Product::create($productData);
                    } else {
                        $product->update($productData);
                    }
            
                    $catData = $entry['categories'][$index % count($entry['categories'])];
                    $catDefaultName = $catData[$defaultLanguage]['name'];
                    $category = $categoryModels[$catDefaultName];
                    
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