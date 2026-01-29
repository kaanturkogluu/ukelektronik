<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Monokristal Güneş Paneli 400W',
                'slug' => 'monokristal-gunes-paneli-400w',
                'category' => 'gunes-panelleri',
                'price' => 8500.00,
                'image' => 'img/img-600x400-1.jpg',
                'description' => 'Yüksek verimli monokristal güneş paneli. %22 verimlilik oranı ile enerji üretiminde maksimum performans sağlar. 25 yıl garanti ve uzun ömürlü yapısı ile güvenilir bir yatırımdır. Hava koşullarına dayanıklı yapısı ve kolay kurulum özelliği ile tercih edilen bir üründür. Çevre dostu teknoloji ile üretilmiştir.',
                'specs' => [
                    'Güç' => '400W',
                    'Verimlilik' => '%22',
                    'Garanti' => '25 Yıl',
                    'Boyut' => '2000x1000x40 mm',
                    'Ağırlık' => '22 kg',
                    'Hücre Tipi' => 'Monokristal',
                    'Maksimum Voltaj' => '41.5V',
                    'Maksimum Akım' => '9.64A'
                ],
                'features' => [
                    'Yüksek verimlilik (%22)',
                    '25 yıl garanti',
                    'Hava koşullarına dayanıklı',
                    'Kolay kurulum',
                    'Çevre dostu',
                    'Düşük sıcaklık katsayısı'
                ],
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'Lityum İyon Batarya 5kWh',
                'slug' => 'lityum-iyon-batarya-5kwh',
                'category' => 'bataryalar',
                'price' => 25000.00,
                'image' => 'img/img-600x400-2.jpg',
                'description' => 'Uzun ömürlü lityum iyon batarya sistemi. 5000 döngü kapasitesi ile yıllarca güvenilir enerji depolama sağlar. Yüksek kapasite ve hızlı şarj özelliği ile off-grid ve hibrit sistemler için ideal çözümdür. Güvenli kullanım ve uzaktan izleme özelliği ile kullanıcı dostu bir üründür.',
                'specs' => [
                    'Kapasite' => '5 kWh',
                    'Voltaj' => '48V',
                    'Döngü' => '5000+',
                    'Garanti' => '10 Yıl',
                    'Boyut' => '600x500x200 mm',
                    'Ağırlık' => '45 kg',
                    'Şarj Süresi' => '4-6 saat',
                    'Deşarj Derinliği' => '%90'
                ],
                'features' => [
                    'Uzun ömürlü (5000+ döngü)',
                    'Yüksek kapasite',
                    'Hızlı şarj',
                    'Güvenli kullanım',
                    'Uzaktan izleme',
                    'BMS (Battery Management System)'
                ],
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'On-Grid İnverter 5kW',
                'slug' => 'on-grid-inverter-5kw',
                'category' => 'inverterler',
                'price' => 12000.00,
                'image' => 'img/img-600x400-3.jpg',
                'description' => 'Şebeke bağlantılı yüksek verimli inverter. %98 verimlilik ile maksimum enerji dönüşümü sağlar. Uzaktan izleme özelliği ile sisteminizi her yerden takip edebilirsiniz. Kolay kurulum ve güvenilir çalışma özellikleri ile tercih edilen bir üründür.',
                'specs' => [
                    'Güç' => '5 kW',
                    'Verimlilik' => '%98',
                    'Giriş Voltajı' => '150-500V',
                    'Garanti' => '5 Yıl',
                    'Boyut' => '500x400x200 mm',
                    'Ağırlık' => '18 kg',
                    'MPPT Sayısı' => '2',
                    'Maksimum DC Güç' => '5500W'
                ],
                'features' => [
                    'Yüksek verimlilik (%98)',
                    'Uzaktan izleme',
                    'Kolay kurulum',
                    'Güvenilir çalışma',
                    'Uzun garanti',
                    'MPPT teknolojisi'
                ],
                'is_active' => true,
                'sort_order' => 3
            ]
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['slug' => $product['slug']],
                $product
            );
        }
    }
}

