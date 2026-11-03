<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Favourite;
use App\Models\Image;
use App\Models\Meal;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
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

        // Clean old dummy data safely via Eloquent forceDelete
        OrderItem::query()->delete();
        OrderStatusHistory::query()->delete();
        Order::query()->delete();
        Rating::query()->delete();
        Favourite::query()->delete();
        Image::where('imageable_type', Meal::class)->delete();
        Meal::withTrashed()->forceDelete();
        User::withTrashed()->where('role', 'chef')->whereNotIn('email', [
            'khalil@wajbati.com',
            'ahmed@wajbati.com',
            'sarah@wajbati.com',
            'mohammed@wajbati.com',
        ])->forceDelete();

        // Standard categories
        $traditionalCat = Category::firstOrCreate(['name' => 'Traditional']);
        $orientalCat = Category::firstOrCreate(['name' => 'Oriental']);
        $sweetsCat = Category::firstOrCreate(['name' => 'Sweets']);
        $fastFoodCat = Category::firstOrCreate(['name' => 'Fast Food']);
        $westernCat = Category::firstOrCreate(['name' => 'Western']);

        $demoPassword = env('DEMO_PASSWORD', 'Password123!');

        // 4 Authentic Palestinian Home Chefs & 7 Handcrafted Meals
        $chefsData = [
            [
                'name' => 'خليل',
                'email' => 'khalil@wajbati.com',
                'kitchen_name' => 'مطبخ خليل التراثي',
                'phone' => '0599111222',
                'address' => 'القدس - البلدة القديمة',
                'bio' => 'شيف متخصص في تحضير الأكلات التراثية والمخبوزات البلدية على خبز الطابون بزيت الزيتون الفلسطيني الأصيل والبهارات المورثة.',
                'meals' => [
                    [
                        'name' => 'مسخن فلسطيني بلدي على خبز الطابون',
                        'category_id' => $traditionalCat->id,
                        'description' => 'دجاج محمر متبل بالسماق البلدي الصافي وزيت الزيتون، يقدم فوق خبز الطابون الطازج مع بصل مكرمل بالكامل ومزين باللوز المحمص والصنوبر مع اللبن الرائب.',
                        'price' => 45.00,
                        'number_persons' => 4,
                        'prep_time_minutes' => 60,
                        'is_available' => true,
                        'image_path' => 'meals/musakhan.jpg',
                        'review' => 'مسخن على أصوله، نكهة السماق وزيت الزيتون والخبز الطابون تحفة فنية!',
                    ],
                    [
                        'name' => 'محاشي مشكلة على الطريقة البيتية',
                        'category_id' => $traditionalCat->id,
                        'description' => 'تشكيلة بيتية فاخرة تضم الكوسا والباذنجان والبصل والفليفلة المحشوة بأرز متبل ولحم مفروم طازج ومطهوة بصلصة طماطم شهية وغنية بالنعناع والثوم.',
                        'price' => 38.00,
                        'number_persons' => 3,
                        'prep_time_minutes' => 75,
                        'is_available' => true,
                        'image_path' => 'meals/mahshi.jpg',
                        'review' => 'محاشي دايبة دوب وتسبيكة الصلصة ممتازة جداً، شغل بيتي نظيف.',
                    ],
                ],
            ],
            [
                'name' => 'أحمد',
                'email' => 'ahmed@wajbati.com',
                'kitchen_name' => 'مشاوي ومطبخ الشيف أحمد',
                'phone' => '0599222333',
                'address' => 'نابلس - شارع رفيديا',
                'bio' => 'خبير المشاوي والأطباق الشرقية السريعة المحضرة على الفحم الطبيعي بتتبيلات سرية ولحوم بلدية طازجة يومياً.',
                'meals' => [
                    [
                        'name' => 'شيش كباب مشوي مع خضار مشوية',
                        'category_id' => $orientalCat->id,
                        'description' => 'أسياخ لحم بلدي مفروم ومتبل بالخلطة الشرقية، مشوية على الفحم مع تشكيلة من الخضار المشوية كالكوسا والفليفلة والبصل وشرائح الليمون.',
                        'price' => 50.00,
                        'number_persons' => 2,
                        'prep_time_minutes' => 35,
                        'is_available' => true,
                        'image_path' => 'meals/shish-kebab.png',
                        'review' => 'الكباب طري ومتبل بامتياز وريحة الشواء على الفحم واضحة ولذيذة.',
                    ],
                    [
                        'name' => 'أجنحة دجاج حارة ومقرمشة مشوية',
                        'category_id' => $fastFoodCat->id,
                        'description' => 'أجنحة دجاج مشوية على اللهب بصلصة حارة وتوابل مدخنة، تقدم مع صلصة الشطة الحرفية وشرائح الليمون وديب مقرمش.',
                        'price' => 28.00,
                        'number_persons' => 2,
                        'prep_time_minutes' => 30,
                        'is_available' => true,
                        'image_path' => 'meals/spicy-wings.jpg',
                        'review' => 'الأجنحة حارة ومقرمشة، الصوص الحار رهيب ومضبوط 100%.',
                    ],
                ],
            ],
            [
                'name' => 'سارة',
                'email' => 'sarah@wajbati.com',
                'kitchen_name' => 'مطبخ وبيك هاوس سارة',
                'phone' => '0599333444',
                'address' => 'رام الله - حي الطيرة',
                'bio' => 'مطبخ عصري يقدم المخبوزات والبيتزا الإيطالية المخبوزة في الفرن الحجري، بالإضافة للسلطات الصحية والوجبات الخفيفة الطازجة.',
                'meals' => [
                    [
                        'name' => 'بيتزا مارغريتا إيطالية حجرية',
                        'category_id' => $fastFoodCat->id,
                        'description' => 'عجينة نابوليتانية إيطالية مخمرة طبيعياً ومخبوزة على الحجر، مغطاة بصلصة الطماطم الطازجة وجبنة الموزاريلا وأوراق الريحان مع زيت زيتون بكر.',
                        'price' => 32.00,
                        'number_persons' => 2,
                        'prep_time_minutes' => 25,
                        'is_available' => true,
                        'image_path' => 'meals/margherita-pizza.jpg',
                        'review' => 'بيتزا نابولية حقيقية! أطراف العجينة هشة وخفيفة والجبنة ممتازة.',
                    ],
                    [
                        'name' => 'سلطة سيزر بصدور الدجاج المشوية',
                        'category_id' => $westernCat->id,
                        'description' => 'شرائح صدور دجاج متبلة ومشوية تقدم فوق طبقة غنية من الخس المقرمش، جبنة البارميزان الإيطالية، طماطم كرزية وخبز محمص مع صلصة سيزر خاصة.',
                        'price' => 26.00,
                        'number_persons' => 1,
                        'prep_time_minutes' => 20,
                        'is_available' => true,
                        'image_path' => 'meals/caesar-salad.jpg',
                        'review' => 'سلطة فريش والدجاج مشوي وطري جداً، الدريسنج خفيف ولذيذ.',
                    ],
                ],
            ],
            [
                'name' => 'محمد',
                'email' => 'mohammed@wajbati.com',
                'kitchen_name' => 'حلويات الشيف محمد',
                'phone' => '0599444555',
                'address' => 'نابلس - الدوار الرئيسي',
                'bio' => 'صانع حلويات شرقية متخصص في الكنافة النابلسية بالسمن البلدي والجبنة البلدية الأصلية والمخبوزات الحلوة المحلاة بالقطر الفاخر.',
                'meals' => [
                    [
                        'name' => 'كنافة نابلسية خشنة بالجبنة البلدية',
                        'category_id' => $sweetsCat->id,
                        'description' => 'كنافة نابلسية خشنة ذهبية ومقرمشة، محشوة بالجبنة البلدية العكاوية المذابة والمحلاة، تسقى بالقطر المعطر بماء الورد وتزين بالفستق الحلبي الفاخر.',
                        'price' => 22.00,
                        'number_persons' => 2,
                        'prep_time_minutes' => 25,
                        'is_available' => true,
                        'image_path' => 'meals/knafeh.jpg',
                        'review' => 'كنافة نابلسية أصلية ما بتتقاوم! الجبنة بتمط ومقرمشة وسكرها معتدل.',
                    ],
                ],
            ],
        ];

        // Seed a verified customer for initial authentic reviews
        $customer = User::firstOrCreate(
            ['email' => 'customer@wajbati.local'],
            [
                'name' => 'عمر الزبون',
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

                // Attach exact meal image
                Image::updateOrCreate(
                    [
                        'imageable_id' => $meal->id,
                        'imageable_type' => Meal::class,
                    ],
                    [
                        'image_path' => $mData['image_path'],
                    ]
                );

                // Attach realistic rating & review
                Rating::updateOrCreate(
                    [
                        'user_id' => $customer->id,
                        'meal_id' => $meal->id,
                    ],
                    [
                        'chef_id' => $chef->id,
                        'star' => 5,
                        'body' => $mData['review'],
                    ]
                );
            }
        }
    }
}
