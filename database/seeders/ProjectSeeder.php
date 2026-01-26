<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\ProjectCategory;
use Illuminate\Support\Str;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        // Get category IDs
        $gunesEnerjisi = ProjectCategory::where('slug', 'gunes-enerjisi')->first();
        $ruzgarEnerjisi = ProjectCategory::where('slug', 'ruzgar-enerjisi')->first();
        $hidroelektrik = ProjectCategory::where('slug', 'hidroelektrik')->first();
        
        $projects = [
            [
                'title' => 'Endüstriyel Tesis Güneş Enerjisi Projesi',
                'slug' => 'endustriyel-tesis-gunes-enerjisi-projesi',
                'category_id' => $gunesEnerjisi ? $gunesEnerjisi->id : 1,
                'image' => 'img/img-600x400-6.jpg',
                'description' => 'Büyük ölçekli endüstriyel tesis için kurduğumuz güneş enerjisi sistemi. 500 kW kapasiteli bu sistem, tesisin enerji ihtiyacının %70\'ini karşılamaktadır. Yüksek verimli monokristal paneller kullanılarak kurulmuş olan sistem, otomatik izleme ve kontrol sistemi ile 7/24 izlenmektedir. 25 yıl garanti ile uzun ömürlü ve çevre dostu bir çözümdür.',
                'details' => [
                    'Kurulum Kapasitesi' => '500 kW',
                    'Panel Sayısı' => '1,250 adet',
                    'Yıllık Üretim' => '750,000 kWh',
                    'Kurulum Tarihi' => '2023',
                    'Lokasyon' => 'Hatay, Türkiye',
                    'Tasarruf' => 'Yıllık %70 enerji tasarrufu',
                    'CO2 Tasarrufu' => '375 ton/yıl',
                    'Geri Ödeme Süresi' => '6 yıl'
                ],
                'features' => [
                    'Yüksek verimli monokristal paneller',
                    'Otomatik izleme ve kontrol sistemi',
                    'Uzaktan erişim ile 7/24 izleme',
                    '25 yıl garanti',
                    'Çevre dostu ve sürdürülebilir',
                    'Anahtar teslim kurulum'
                ],
                'gallery' => [
                    'img/img-600x400-6.jpg',
                    'img/img-600x400-1.jpg',
                    'img/img-600x400-2.jpg'
                ],
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'title' => 'Kırsal Bölge Rüzgar Türbini Projesi',
                'slug' => 'kirsal-bolge-ruzgar-turbini-projesi',
                'category_id' => $ruzgarEnerjisi ? $ruzgarEnerjisi->id : 2,
                'image' => 'img/img-600x400-5.jpg',
                'description' => 'Kırsal bölgede kurduğumuz rüzgar türbini sistemi. 200 kW kapasiteli bu sistem, bölgenin enerji ihtiyacını karşılamaktadır. Düşük gürültü seviyeli modern türbinler kullanılarak kurulmuş olan sistem, otomatik rüzgar yönü takip sistemi ile maksimum verimlilik sağlamaktadır. Uzaktan izleme ve kontrol imkanı ile 7/24 takip edilmektedir.',
                'details' => [
                    'Kurulum Kapasitesi' => '200 kW',
                    'Türbin Sayısı' => '2 adet',
                    'Yıllık Üretim' => '450,000 kWh',
                    'Kurulum Tarihi' => '2023',
                    'Lokasyon' => 'Hatay, Türkiye',
                    'Tasarruf' => 'Yıllık %60 enerji tasarrufu',
                    'CO2 Tasarrufu' => '225 ton/yıl',
                    'Geri Ödeme Süresi' => '7 yıl'
                ],
                'features' => [
                    'Düşük gürültü seviyeli modern türbinler',
                    'Otomatik rüzgar yönü takip sistemi',
                    'Uzaktan izleme ve kontrol',
                    'Yüksek verimlilik',
                    'Çevre dostu enerji üretimi',
                    'Uzun ömürlü sistem'
                ],
                'gallery' => [
                    'img/img-600x400-5.jpg',
                    'img/img-600x400-2.jpg',
                    'img/img-600x400-3.jpg'
                ],
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'title' => 'Küçük Ölçekli Hidroelektrik Santral',
                'slug' => 'kucuk-olcekli-hidroelektrik-santral',
                'category_id' => $hidroelektrik ? $hidroelektrik->id : 3,
                'image' => 'img/img-600x400-4.jpg',
                'description' => 'Küçük ölçekli hidroelektrik santral projesi. 150 kW kapasiteli bu sistem, su gücünden sürekli enerji üretimi sağlamaktadır. Yüksek verimli Francis türbini kullanılarak kurulmuş olan sistem, otomatik kontrol sistemi ile çalışmaktadır. Düşük bakım maliyeti ve çevre dostu tasarımı ile uzun ömürlü bir çözümdür.',
                'details' => [
                    'Kurulum Kapasitesi' => '150 kW',
                    'Türbin Tipi' => 'Francis Türbini',
                    'Yıllık Üretim' => '1,200,000 kWh',
                    'Kurulum Tarihi' => '2022',
                    'Lokasyon' => 'Hatay, Türkiye',
                    'Tasarruf' => 'Yıllık %80 enerji tasarrufu',
                    'CO2 Tasarrufu' => '600 ton/yıl',
                    'Geri Ödeme Süresi' => '5 yıl'
                ],
                'features' => [
                    'Sürekli ve güvenilir enerji üretimi',
                    'Otomatik kontrol sistemi',
                    'Düşük bakım maliyeti',
                    'Çevre dostu tasarım',
                    'Uzun ömürlü sistem',
                    'Yerel mevzuata uygun'
                ],
                'gallery' => [
                    'img/img-600x400-4.jpg',
                    'img/img-600x400-3.jpg',
                    'img/img-600x400-1.jpg'
                ],
                'is_active' => true,
                'sort_order' => 3
            ]
        ];

        foreach ($projects as $project) {
            Project::updateOrCreate(
                ['slug' => $project['slug']],
                $project
            );
        }
    }
}

