<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Image;
use App\Models\Meal;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->isProduction()) {
            throw new RuntimeException('DemoSeeder cannot be executed in production environment.');
        }

        // Standard categories
        $traditionalCat = Category::firstOrCreate(['name' => 'Traditional']);
        $orientalCat = Category::firstOrCreate(['name' => 'Oriental']);
        $sweetsCat = Category::firstOrCreate(['name' => 'Sweets']);
        $fastFoodCat = Category::firstOrCreate(['name' => 'Fast Food']);

        $demoPassword = env('DEMO_PASSWORD', 'Password123!');

        // Available meal images in public storage
        $sampleImages = [
            'meals/0dd1b168-be58-419b-bfc7-46a71046a6a0.jpg',
            'meals/2n1k1cJMc92hyIDbhqGhYems0pii7Le5z1MNfJSB.jpg',
            'meals/4EXaDrc6N8H31w3PlPQshYcIeWeKQDwZl8LXVWB4.jpg',
            'meals/9Q3TXib9DenK1QlzqjQ27Ny86u4wFbXxreS712fd.jpg',
            'meals/C2J9ap9FCBZLSX3gYmVDy3X49QvpuJH1mvELz9bv.jpg',
            'meals/cjS4VO6LEpvqtoG4v6YgdQfF5jg65recYYrGpneU.jpg',
            'meals/cTFlmDD302PkSoX6VlGPpEN3vs2pd7UWmJBCKZhz.jpg',
            'meals/DqJAthoLlUdRLsmd6ohZPX6l1fANimoHucHWgxy1.jpg',
            'meals/E1TSzP5MAWxBa3EJ2nNc4BJZoL2Nkehn4pI5h8Zd.jpg',
            'meals/e5mP2HMbn17u2hegXrCznmwnCWuEiiBH5mVenpqE.jpg',
            'meals/E8k1G6KJm9q1BB3xd3bVfIeP26WmnMjT2pg6EuQF.jpg',
            'meals/EJ1MZTK1aoLm4ZHgl0xPYmpP8CrnvRM2nji4tsQv.jpg',
            'meals/ep2NOc55DQU4Vdf5gXXycroPhTst7UYaCob3Pcts.jpg',
            'meals/f3LE3pHTcaWtikLu8hSUf2k3gpZq4jEBOCByAwW5.jpg',
            'meals/HRxKBvqPsAA8x5Vqj1sRCod5C4zFY4eLuje8FyMA.jpg',
            'meals/HTLNuKsEwWabiFHijoJXuod2TbUI0YlMtmOVrR2U.jpg',
        ];
        $imgIndex = 0;

        // 6 Active Palestinian Home Chefs + 1 Coming Soon Chef
        $chefsData = [
            [
                'name' => 'Fatima Al-Ghazawi',
                'email' => 'fatima@wajbati.local',
                'kitchen_name' => 'Mama Fatima Kitchen',
                'phone' => '0599111222',
                'address' => 'Gaza, Al-Rimal',
                'bio' => 'Authentic Gazan home cooking, fresh spices, and slow-cooked traditional dishes made with love.',
                'meals' => [
                    [
                        'name' => 'Traditional Maqluba with Chicken',
                        'category_id' => $traditionalCat->id,
                        'description' => 'A famous Palestinian upside-down dish layered with spiced rice, fried cauliflower, eggplant, and tender chicken.',
                        'price' => 45.00,
                        'number_persons' => 4,
                        'prep_time_minutes' => 45,
                        'is_available' => true,
                    ],
                    [
                        'name' => 'Gazan Sumaghiyyeh',
                        'category_id' => $traditionalCat->id,
                        'description' => 'A traditional Gazan celebratory delicacy made with ground sumac, chard, beef cubes, tahini, and chickpeas.',
                        'price' => 38.00,
                        'number_persons' => 3,
                        'prep_time_minutes' => 40,
                        'is_available' => true,
                    ],
                    [
                        'name' => 'Stuffed Vine Leaves & Zucchini (Warak Dawali)',
                        'category_id' => $orientalCat->id,
                        'description' => 'Rolled grape leaves and baby courgettes stuffed with fragrant herb rice and minced lamb, slow simmered.',
                        'price' => 50.00,
                        'number_persons' => 4,
                        'prep_time_minutes' => 60,
                        'is_available' => true,
                    ],
                ],
            ],
            [
                'name' => 'Youssef Al-Qudsi',
                'email' => 'youssef@wajbati.local',
                'kitchen_name' => 'Jerusalem Heritage Kitchen',
                'phone' => '0599222333',
                'address' => 'Jerusalem, Old City',
                'bio' => 'Carrying forward generational recipes from the alleys of old Jerusalem with premium Palestinian olive oil.',
                'meals' => [
                    [
                        'name' => 'Palestinian Musakhan Roll Platter',
                        'category_id' => $traditionalCat->id,
                        'description' => 'Taboon bread rolls filled with shredded sumac chicken, caramelized Jenin red onions, and toasted pine nuts.',
                        'price' => 42.00,
                        'number_persons' => 3,
                        'prep_time_minutes' => 35,
                        'is_available' => true,
                    ],
                    [
                        'name' => 'Shish Barak in Garlicky Yogurt',
                        'category_id' => $orientalCat->id,
                        'description' => 'Handmade beef dumplings simmered in hot velvety yogurt sauce infused with garlic, mint, and toasted pine nuts.',
                        'price' => 48.00,
                        'number_persons' => 3,
                        'prep_time_minutes' => 45,
                        'is_available' => true,
                    ],
                ],
            ],
            [
                'name' => 'Mariam Al-Khalili',
                'email' => 'mariam@wajbati.local',
                'kitchen_name' => 'Hebron Traditional Kitchen',
                'phone' => '0599333444',
                'address' => 'Hebron, Ein Sara',
                'bio' => 'Specializing in hearty family gatherings, slow-simmered stews, and authentic Khalili Qidreh.',
                'meals' => [
                    [
                        'name' => 'Hebronite Qidreh with Lamb',
                        'category_id' => $traditionalCat->id,
                        'description' => 'Spiced spiced rice baked in copper pots with chickpeas, whole garlic cloves, and fall-apart local lamb.',
                        'price' => 65.00,
                        'number_persons' => 4,
                        'prep_time_minutes' => 60,
                        'is_available' => true,
                    ],
                    [
                        'name' => 'Creamy Hummus with Sautéed Lamb',
                        'category_id' => $orientalCat->id,
                        'description' => 'Silk-smooth handmade hummus topped with warm spiced minced lamb, olive oil, and roasted almonds.',
                        'price' => 25.00,
                        'number_persons' => 2,
                        'prep_time_minutes' => 20,
                        'is_available' => true,
                    ],
                ],
            ],
            [
                'name' => 'Nader Al-Nabulsi',
                'email' => 'nader@wajbati.local',
                'kitchen_name' => 'Nablus Sweet & Pastry Heritage',
                'phone' => '0599444555',
                'address' => 'Nablus, Rafidia',
                'bio' => 'Master of authentic Nabulsi cheeses, warm Kunafa, and golden olive oil pastries.',
                'meals' => [
                    [
                        'name' => 'Original Nabulsi Knafeh (Khishneh)',
                        'category_id' => $sweetsCat->id,
                        'description' => 'Crisp shredded kataifi crust over stretchy melted Nabulsi sweet cheese, soaked in orange blossom syrup.',
                        'price' => 32.00,
                        'number_persons' => 4,
                        'prep_time_minutes' => 25,
                        'is_available' => true,
                    ],
                    [
                        'name' => 'Mini Fatayer Trio (Spinach, Cheese, Zaatar)',
                        'category_id' => $fastFoodCat->id,
                        'description' => 'Dozen golden baked pastry triangles filled with tangy wild spinach, akawi cheese, and fresh green thyme.',
                        'price' => 28.00,
                        'number_persons' => 3,
                        'prep_time_minutes' => 30,
                        'is_available' => true,
                    ],
                ],
            ],
            [
                'name' => 'Salma Al-Jalili',
                'email' => 'salma@wajbati.local',
                'kitchen_name' => 'Galilee Home Flavors',
                'phone' => '0599555666',
                'address' => 'Jenin, Al-Basateen',
                'bio' => 'Farm-to-table home specialties using wild mountain herbs, fresh labneh, and seasonal greens.',
                'meals' => [
                    [
                        'name' => 'Palestinian Freekeh with Roasted Chicken',
                        'category_id' => $traditionalCat->id,
                        'description' => 'Smoky green roasted wheat cooked in rich broth, topped with spiced tender chicken and golden nuts.',
                        'price' => 44.00,
                        'number_persons' => 3,
                        'prep_time_minutes' => 40,
                        'is_available' => true,
                    ],
                    [
                        'name' => 'Mutabbal & Fresh Garden Tabbouleh',
                        'category_id' => $orientalCat->id,
                        'description' => 'Smoked charred eggplant dip with tahini alongside finely chopped parsley, mint, and bulgur salad.',
                        'price' => 22.00,
                        'number_persons' => 2,
                        'prep_time_minutes' => 20,
                        'is_available' => true,
                    ],
                ],
            ],
            [
                'name' => 'Ahmad Al-Bahr',
                'email' => 'ahmad@wajbati.local',
                'kitchen_name' => 'Jaffa Sea Kitchen',
                'phone' => '0599666777',
                'address' => 'Gaza, Port Road',
                'bio' => 'Coastal seafood traditions prepared with daily fresh catch, cumin, and caramelized onions.',
                'meals' => [
                    [
                        'name' => 'Coastal Sayadieh with Fish Fillet',
                        'category_id' => $traditionalCat->id,
                        'description' => 'Fragrant cumin-caramelized brown rice topped with pan-seared sea bass fillet and roasted almonds.',
                        'price' => 55.00,
                        'number_persons' => 3,
                        'prep_time_minutes' => 45,
                        'is_available' => true,
                    ],
                    [
                        'name' => 'Spicy Gazan Shrimp Clay Pot (Zibdiyyeh)',
                        'category_id' => $orientalCat->id,
                        'description' => 'Succulent fresh shrimps baked in red clay bowl with tomatoes, hot green peppers, garlic, and dill seeds.',
                        'price' => 58.00,
                        'number_persons' => 2,
                        'prep_time_minutes' => 30,
                        'is_available' => true,
                    ],
                ],
            ],
            // Chef with 0 dishes to test "Coming Soon" state and sorting
            [
                'name' => 'Samer Al-Khatib',
                'email' => 'samer@wajbati.local',
                'kitchen_name' => 'Samer Modern Kitchen',
                'phone' => '0599888999',
                'address' => 'Ramallah, Al-Masyoun',
                'bio' => 'New culinary artist preparing fresh modern Mediterranean fusion meals. Menu launching soon!',
                'meals' => [],
            ],
        ];

        // Seed a customer for ratings
        $customer = User::firstOrCreate(
            ['email' => 'customer@wajbati.local'],
            [
                'name' => 'Omar Customer',
                'password' => Hash::make($demoPassword),
                'phone' => '0599000111',
                'email_verified_at' => now(),
            ]
        );

        foreach ($chefsData as $cData) {
            $chef = User::firstOrNew(['email' => $cData['email']]);
            $chef->name = $cData['name'];
            $chef->password = Hash::make($demoPassword);
            $chef->phone = $cData['phone'];
            $chef->kitchen_name = $cData['kitchen_name'];
            $chef->address = $cData['address'];
            $chef->bio = $cData['bio'];
            $chef->email_verified_at = now();
            $chef->role = 'chef';
            $chef->save();

            foreach ($cData['meals'] as $mData) {
                $meal = Meal::updateOrCreate(
                    [
                        'name' => $mData['name'],
                        'chef_id' => $chef->id,
                    ],
                    [
                        'category_id' => $mData['category_id'],
                        'description' => $mData['description'],
                        'price' => $mData['price'],
                        'number_persons' => $mData['number_persons'],
                        'prep_time_minutes' => $mData['prep_time_minutes'],
                        'is_available' => $mData['is_available'],
                    ]
                );

                // Attach sample image
                if (isset($sampleImages[$imgIndex % count($sampleImages)])) {
                    $imgPath = $sampleImages[$imgIndex % count($sampleImages)];
                    Image::updateOrCreate(
                        [
                            'imageable_id' => $meal->id,
                            'imageable_type' => Meal::class,
                        ],
                        [
                            'image_path' => $imgPath,
                        ]
                    );
                    $imgIndex++;
                }

                // Attach sample rating
                Rating::updateOrCreate(
                    [
                        'user_id' => $customer->id,
                        'meal_id' => $meal->id,
                    ],
                    [
                        'chef_id' => $chef->id,
                        'star' => rand(4, 5),
                        'body' => 'Delicious and truly authentic home-cooked flavor! Will definitely order again.',
                    ]
                );
            }
        }
    }
}
