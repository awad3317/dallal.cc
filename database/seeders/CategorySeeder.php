<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
        
            [
                'name' => 'الإلكترونيات',
                'icon' => 'FaMicrochip',
                'parent_id' => null,
            ],
            [
                'name' => 'الهواتف الذكية',
                'icon' => 'FaMobileAlt',
                'parent_id' => 1,
            ],
            [
                'name' => 'أجهزة اللابتوب',
                'icon' => 'FaLaptop',
                'parent_id' => 1,
            ],
            [
                'name' => 'الأجهزة اللوحية',
                'icon' => 'FaTabletAlt',
                'parent_id' => 1,
            ],
            [
                'name' => 'الكاميرات',
                'icon' => 'FaCamera',
                'parent_id' => 1,
            ],
            [
                'name' => 'الألعاب الإلكترونية',
                'icon' => 'FaGamepad',
                'parent_id' => 1,
            ],

        
            [
                'name' => 'الملابس',
                'icon' => 'FaTshirt',
                'parent_id' => null,
            ],
            [
                'name' => 'ملابس رجالية',
                'icon' => 'FaMale',
                'parent_id' => 7,
            ],
            [
                'name' => 'ملابس نسائية',
                'icon' => 'FaFemale',
                'parent_id' => 7,
            ],
            [
                'name' => 'أطفال',
                'icon' => 'FaChild',
                'parent_id' => 7,
            ],
            [
                'name' => 'إكسسوارات',
                'icon' => 'FaRing',
                'parent_id' => 7,
            ],
            [
                'name' => 'أحذية',
                'icon' => 'FaShoePrints',
                'parent_id' => 7,
            ],

                    [
                'name' => 'الأثاث',
                'icon' => 'FaCouch',
                'parent_id' => null,
            ],
            [
                'name' => 'غرف النوم',
                'icon' => 'FaBed',
                'parent_id' => 13,
            ],
            [
                'name' => 'الصالونات',
                'icon' => 'FaChair',
                'parent_id' => 13,
            ],
            [
                'name' => 'المطابخ',
                'icon' => 'FaUtensils',
                'parent_id' => 13,
            ],
            [
                'name' => 'ديكورات',
                'icon' => 'FaPalette',
                'parent_id' => 13,
            ],

        
            [
                'name' => 'السيارات',
                'icon' => 'FaCar',
                'parent_id' => null,
            ],
            [
                'name' => 'قطع غيار',
                'icon' => 'FaCarBattery',
                'parent_id' => 18,
            ],
            [
                'name' => 'دراجات نارية',
                'icon' => 'FaMotorcycle',
                'parent_id' => 18,
            ],
            [
                'name' => 'قوارب',
                'icon' => 'FaShip',
                'parent_id' => 18,
            ],

             [
                'name' => 'العقارات',
                'icon' => 'FaBuilding',
                'parent_id' => null,
            ],
            [
                'name' => 'شقق للبيع',
                'icon' => 'FaHome',
                'parent_id' => 22,
            ],
            [
                'name' => 'شقق للإيجار',
                'icon' => 'FaKey',
                'parent_id' => 22,
            ],
            [
                'name' => 'أراضي',
                'icon' => 'FaMapMarkedAlt',
                'parent_id' => 22,
            ],
            [
                'name' => 'فلل',
                'icon' => 'FaHotel',
                'parent_id' => 22,
            ],

        
            [
                'name' => 'الأجهزة المنزلية',
                'icon' => 'FaBlender',
                'parent_id' => null,
            ],
            [
                'name' => 'ثلاجات',
                'icon' => 'FaSnowflake',
                'parent_id' => 27,
            ],
            [
                'name' => 'غسالات',
                'icon' => 'FaTint',
                'parent_id' => 27,
            ],
            [
                'name' => 'أفران',
                'icon' => 'FaFire',
                'parent_id' => 27,
            ],
            [
                'name' => 'مكيفات',
                'icon' => 'FaFan',
                'parent_id' => 27,
            ],

        
            [
                'name' => 'الرياضة',
                'icon' => 'FaRunning',
                'parent_id' => null,
            ],
            [
                'name' => 'معدات رياضية',
                'icon' => 'FaDumbbell',
                'parent_id' => 32,
            ],
            [
                'name' => 'دراجات',
                'icon' => 'FaBicycle',
                'parent_id' => 32,
            ],
            [
                'name' => 'ملابس رياضية',
                'icon' => 'FaTshirt',
                'parent_id' => 32,
            ],

        
            [
                'name' => 'حيوانات أليفة',
                'icon' => 'FaPaw',
                'parent_id' => null,
            ],
            [
                'name' => 'كلاب',
                'icon' => 'FaDog',
                'parent_id' => 36,
            ],
            [
                'name' => 'قطط',
                'icon' => 'FaCat',
                'parent_id' => 36,
            ],
            [
                'name' => 'طيور',
                'icon' => 'FaDove',
                'parent_id' => 36,
            ],
            [
                'name' => 'أسماك',
                'icon' => 'FaFish',
                'parent_id' => 36,
            ],

               [
                'name' => 'كتب وتعليم',
                'icon' => 'FaBook',
                'parent_id' => null,
            ],
            [
                'name' => 'كتب مدرسية',
                'icon' => 'FaBookOpen',
                'parent_id' => 41,
            ],
            [
                'name' => 'روايات',
                'icon' => 'FaBookReader',
                'parent_id' => 41,
            ],
            [
                'name' => 'أدوات مكتبية',
                'icon' => 'FaPen',
                'parent_id' => 41,
            ],

          [
                'name' => 'خدمات',
                'icon' => 'FaHandsHelping',
                'parent_id' => null,
            ],
            [
                'name' => 'تصميم مواقع',
                'icon' => 'FaCode',
                'parent_id' => 45,
            ],
            [
                'name' => 'خدمات كهربائية',
                'icon' => 'FaPlug',
                'parent_id' => 45,
            ],
            [
                'name' => 'نقل عفش',
                'icon' => 'FaTruckMoving',
                'parent_id' => 45,
            ],
            [
                'name' => 'خدمات تنظيف',
                'icon' => 'FaBroom',
                'parent_id' => 45,
            ],

            [
                'name' => 'أطعمة ومشروبات',
                'icon' => 'FaUtensilSpoon',
                'parent_id' => null,
            ],
            [
                'name' => 'مطاعم',
                'icon' => 'FaHamburger',
                'parent_id' => 50,
            ],
            [
                'name' => 'مشروبات',
                'icon' => 'FaCoffee',
                'parent_id' => 50,
            ],
            [
                'name' => 'حلويات',
                'icon' => 'FaBirthdayCake',
                'parent_id' => 50,
            ],
            [
                'name' => 'فواكه وخضروات',
                'icon' => 'FaCarrot',
                'parent_id' => 50,
            ],
            [
                'name' => 'أدوات منزلية',
                'icon' => 'FaHome',
                'parent_id' => null,
            ],
            [
                'name' => 'أدوات المطبخ',
                'icon' => 'FaUtensils',
                'parent_id' => 55, 
            ],
            [
                'name' => 'أدوات الحدائق',
                'icon' => 'FaSeedling',
                'parent_id' => 55,
            ],
            [
                'name' => 'أدوات التنظيف',
                'icon' => 'FaBroom',
                'parent_id' => 55,
            ],

            [
                'name' => 'أجهزة رياضية',
                'icon' => 'FaDumbbell',
                'parent_id' => null,
            ],
            [
                'name' => 'أجهزة المشي',
                'icon' => 'FaWalking',
                'parent_id' => 59,
            ],
            [
                'name' => 'أوزان رياضية',
                'icon' => 'FaWeight',
                'parent_id' => 59,
            ],

            [
                'name' => 'أدوات ومعدات',
                'icon' => 'FaTools',
                'parent_id' => null,
            ],
            [
                'name' => 'أدوات البناء',
                'icon' => 'FaHammer',
                'parent_id' => 62,
            ],
            [
                'name' => 'معدات صناعية',
                'icon' => 'FaIndustry',
                'parent_id' => 62,
            ],
            [
                'name' => 'أدوات النجارة',
                'icon' => 'FaTree',
                'parent_id' => 62,
            ],

            [
                'name' => 'مواد غذائية',
                'icon' => 'FaShoppingBasket',
                'parent_id' => null,
            ],
            [
                'name' => 'تمور',
                'icon' => 'FaAppleAlt',
                'parent_id' => 66,
            ],
            [
                'name' => 'عسل',
                'icon' => 'FaHoneyPot',
                'parent_id' => 66,
            ],
            [
                'name' => 'مكسرات',
                'icon' => 'FaAcorn',
                'parent_id' => 66,
            ],

            [
                'name' => 'أغراض الأطفال',
                'icon' => 'FaBaby',
                'parent_id' => null,
            ],
            [
                'name' => 'عربيات أطفال',
                'icon' => 'FaBabyCarriage',
                'parent_id' => 70,
            ],
            [
                'name' => 'ألعاب أطفال',
                'icon' => 'FaPuzzlePiece',
                'parent_id' => 70,
            ],
            [
                'name' => 'ملابس أطفال',
                'icon' => 'FaChild',
                'parent_id' => 70,
            ],

            [
                'name' => 'أجهزة طبية',
                'icon' => 'FaClinicMedical',
                'parent_id' => null,
            ],
            [
                'name' => 'أجهزة قياس الضغط',
                'icon' => 'FaHeartbeat',
                'parent_id' => 74,
            ],
            [
                'name' => 'كراسي متحركة',
                'icon' => 'FaWheelchair',
                'parent_id' => 74,
            ],
            [
                'name' => 'مستلزمات طبية',
                'icon' => 'FaSyringe',
                'parent_id' => 74,
            ],

            [
                'name' => 'تحف وأعمال فنية',
                'icon' => 'FaPalette',
                'parent_id' => null,
            ],
            [
                'name' => 'لوحات فنية',
                'icon' => 'FaPaintBrush',
                'parent_id' => 78,
            ],
            [
                'name' => 'تحف قديمة',
                'icon' => 'FaMonument',
                'parent_id' => 78,
            ],
            [
                'name' => 'ساعات حائط',
                'icon' => 'FaClock',
                'parent_id' => 78,
            ],

            [
                'name' => 'مستلزمات التجميل',
                'icon' => 'FaSprayCan',
                'parent_id' => null,
            ],
            [
                'name' => 'عطور',
                'icon' => 'FaWind',
                'parent_id' => 82,
            ],
            [
                'name' => 'أدوات تجميل',
                'icon' => 'FaEye',
                'parent_id' => 82,
            ],
            [
                'name' => 'كريمات عناية',
                'icon' => 'FaPumpSoap',
                'parent_id' => 82,
            ],

            [
                'name' => 'مقتنيات متنوعة',
                'icon' => 'FaBoxOpen',
                'parent_id' => null,
            ],
            [
                'name' => 'عملات وطوابع',
                'icon' => 'FaMoneyBillWave',
                'parent_id' => 86,
            ],
            [
                'name' => 'مجوهرات',
                'icon' => 'FaRing',
                'parent_id' => 86,
            ],
            [
                'name' => 'ساعات يد',
                'icon' => 'FaStopwatch',
                'parent_id' => 86,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}