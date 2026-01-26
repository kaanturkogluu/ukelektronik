<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'title' => 'Güneş Paneli Kurulumu',
                'slug' => 'gunes-paneli-kurulumu',
                'icon' => 'fa-solar-panel',
                'image' => 'img/img-600x400-1.jpg',
                'short_description' => 'Yüksek verimli güneş panelleri ile enerji ihtiyacınızı karşılayın. Modern teknoloji ve kaliteli ürünlerle güvenilir çözümler sunuyoruz.',
                'description' => 'UK Elektronik olarak, güneş enerjisi sistemlerinde uzman ekibimizle yanınızdayız. Yüksek verimli güneş panelleri ile enerji ihtiyacınızı karşılayın. Modern teknoloji ve kaliteli ürünlerle güvenilir çözümler sunuyoruz. Monokristal ve polikristal panel seçenekleri ile ihtiyacınıza uygun çözümler sunuyoruz. Profesyonel keşif, projelendirme, kurulum ve bakım hizmetleri ile anahtar teslim çözümler sağlıyoruz.',
                'features' => [
                    'Yüksek verimli monokristal ve polikristal panel seçenekleri',
                    '25 yıl garanti ile uzun ömürlü sistemler',
                    'Profesyonel keşif ve projelendirme hizmeti',
                    'Anahtar teslim kurulum ve devreye alma',
                    '7/24 teknik destek ve bakım hizmetleri',
                    'Çevre dostu ve sürdürülebilir enerji çözümleri'
                ],
                'benefits' => [
                    'Enerji maliyetlerinde %80\'e varan tasarruf',
                    'Karbon ayak izinizi azaltın',
                    'Uzun vadeli yatırım getirisi',
                    'Devlet teşvikleri ve desteklerden yararlanın'
                ],
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'title' => 'Rüzgar Türbini Sistemleri',
                'slug' => 'ruzgar-turbini-sistemleri',
                'icon' => 'fa-wind',
                'image' => 'img/img-600x400-2.jpg',
                'short_description' => 'Rüzgar enerjisi sistemleri ile temiz ve sürdürülebilir enerji üretimi. Profesyonel kurulum ve bakım hizmetleri.',
                'description' => 'Rüzgar enerjisi sistemleri ile temiz ve sürdürülebilir enerji üretimi sağlıyoruz. Profesyonel kurulum ve bakım hizmetleri sunuyoruz. Rüzgar türbinleri, özellikle rüzgarlı bölgelerde yüksek verimlilik sağlar. Yatay ve dikey eksenli türbin seçenekleri ile farklı ihtiyaçlara uygun çözümler sunuyoruz. Düşük gürültü seviyeli modern tasarımlar ve otomatik rüzgar yönü takip sistemleri ile maksimum verimlilik elde ediyoruz.',
                'features' => [
                    'Yatay ve dikey eksenli türbin seçenekleri',
                    'Düşük gürültü seviyeli modern tasarım',
                    'Otomatik rüzgar yönü takip sistemi',
                    'Uzaktan izleme ve kontrol imkanı',
                    'Profesyonel kurulum ve bakım',
                    'Yüksek verimlilik ve güvenilirlik'
                ],
                'benefits' => [
                    'Sürekli ve güvenilir enerji üretimi',
                    'Düşük işletme maliyetleri',
                    'Çevreye zarar vermeyen temiz enerji',
                    'Uzun ömürlü ve dayanıklı sistemler'
                ],
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'title' => 'Hidroelektrik Santral Projeleri',
                'slug' => 'hidroelektrik-santral-projeleri',
                'icon' => 'fa-water',
                'image' => 'img/img-600x400-3.jpg',
                'short_description' => 'Su gücünden enerji üretimi için profesyonel çözümler. Hidroelektrik sistemlerde deneyimli ekibimizle hizmetinizdeyiz.',
                'description' => 'Su gücünden enerji üretimi için profesyonel çözümler sunuyoruz. Hidroelektrik sistemlerde deneyimli ekibimizle hizmetinizdeyiz. Küçük ve orta ölçekli hidroelektrik santraller için anahtar teslim projeler gerçekleştiriyoruz. Yüksek verimli türbin sistemleri, otomatik kontrol ve izleme sistemleri ile sürekli ve güvenilir enerji üretimi sağlıyoruz. Çevre dostu ve sürdürülebilir tasarımlar ile uzun ömürlü sistemler kuruyoruz.',
                'features' => [
                    'Küçük ve orta ölçekli santral tasarımı',
                    'Yüksek verimli türbin sistemleri',
                    'Otomatik kontrol ve izleme sistemleri',
                    'Çevre dostu ve sürdürülebilir tasarım',
                    'Uzun ömürlü ve bakım gerektirmeyen sistemler',
                    'Yerel ve ulusal mevzuata uygun projeler'
                ],
                'benefits' => [
                    'Sürekli ve güvenilir enerji üretimi',
                    'Düşük işletme maliyetleri',
                    'Yerel ekonomiye katkı',
                    'Çevre dostu enerji üretimi'
                ],
                'is_active' => true,
                'sort_order' => 3
            ]
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['slug' => $service['slug']],
                $service
            );
        }
    }
}

