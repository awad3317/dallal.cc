<?php

namespace Database\Factories;

use App\Models\Ad;
use App\Models\User;
use App\Models\Category;
use App\Models\region;
use App\Models\SaleOption;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ad>
 */
class AdFactory extends Factory
{
    protected $model = Ad::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $arabicData = $this->getArabicData();
        
        $category = Category::inRandomOrder()->first();
        $categoryImage = $this->getCategoryImage($category->id);
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'category_id' => $category->id,
            'region_id' => region::inRandomOrder()->first()->id,
            'title' => $this->faker->randomElement($arabicData['titles']),
            'description' => $this->faker->randomElement($arabicData['descriptions']),
            'price' => $this->faker->numberBetween(100, 100000),
            'primary_image' => $categoryImage,
            'status' => $this->faker->randomElement(['جديد', 'مستعمل']),
            'sale_option_id' => SaleOption::inRandomOrder()->first()->id,
            'views' => 0,
            'likes' => 0,
            'verified' => true,
        ];
    }
    private function getArabicData()
    {
        return [
            'titles' => [
                'سيارة فاخرة للبيع', 'شقة مفروشة للإيجار', 'لابتوب جديد بمواصفات عالية',
                'هاتف ذكي أيفون 13', 'أرض سكنية مميزة', 'أثاث منزلي كامل', 'دراجة نارية جديدة',
                'كاميرا احترافية للتصوير', 'ساعة رولكس أصلية', 'جهاز بلايستيشن 5'
            ],
            'descriptions' => [
                'الحالة ممتازة، استعمال نظيف، ضمان سنتين، السعر قابل للتفاوض',
                'مساحة كبيرة، موقع مميز، جميع الخدمات متوفرة، مناسب للعائلات',
                'جديد بالكرتونة، ضمان الوكالة، مواصفات خيالية، سعر منافس',
                'نظيف جدا، صيانة دورية، استعمال شخصي، لا يوجد أي عيوب',
                'فرصة لا تعوض، سعر مناسب، موقع استراتيجي، وثائق قانونية كاملة'
            ]
        ];
    }

    private function getCategoryImage($categoryId)
    {
        $categoryImages = [
            1 => 'https://source.unsplash.com/random/800x600/?car',
            2 => 'https://source.unsplash.com/random/800x600/?laptop',
            3 => 'https://source.unsplash.com/random/800x600/?apartment',
            4 => 'https://source.unsplash.com/random/800x600/?furniture',
            5 => 'https://source.unsplash.com/random/800x600/?phone',
            6 => 'https://source.unsplash.com/random/800x600/?motorcycle',
            7 => 'https://source.unsplash.com/random/800x600/?camera',
            8 => 'https://source.unsplash.com/random/800x600/?watch',
            9 => 'https://source.unsplash.com/random/800x600/?game',
            10 => 'https://source.unsplash.com/random/800x600/?book'
        ];

        return $categoryImages[$categoryId] ?? 'https://source.unsplash.com/random/800x600/?product';
    }
}
