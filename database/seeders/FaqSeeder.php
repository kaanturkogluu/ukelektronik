<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faq;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'Güneş enerjisi sistemi kurulumu ne kadar sürer?',
                'answer' => 'Güneş enerjisi sistemi kurulumu, sistemin büyüklüğüne ve karmaşıklığına bağlı olarak 1-3 hafta arasında sürebilir. Küçük ev sistemleri (3-5 kW) genellikle 1 hafta içinde tamamlanırken, büyük ticari sistemler (50 kW ve üzeri) daha uzun sürebilir. Kurulum sürecinde keşif, projelendirme, malzeme temini, montaj ve devreye alma aşamaları bulunmaktadır.',
                'sort_order' => 1,
                'is_active' => true
            ],
            [
                'question' => 'Güneş panelleri ne kadar süre dayanır?',
                'answer' => 'Kaliteli güneş panelleri genellikle 25-30 yıl ömre sahiptir. Çoğu üretici 25 yıl garanti vermektedir. Paneller, bu süre boyunca nominal güçlerinin %80\'ini üretmeye devam eder. İlk 10 yıl içinde performans garantisi genellikle %90, sonraki 15 yıl için %80 seviyesindedir. Düzenli bakım ve temizlik ile panellerin ömrü daha da uzatılabilir.',
                'sort_order' => 2,
                'is_active' => true
            ],
            [
                'question' => 'Güneş enerjisi sistemi maliyeti nedir?',
                'answer' => 'Güneş enerjisi sistemi maliyeti, sistem kapasitesine, kullanılan ekipmanlara ve kurulum zorluğuna bağlıdır. Genellikle 1 kW kapasiteli bir sistem için 15.000-25.000 TL arasında bir maliyet beklenebilir. Daha büyük sistemlerde kW başına maliyet düşer. Örneğin, 10 kW\'lık bir sistem için kW başına maliyet 12.000-18.000 TL arasında olabilir. Maliyete panel, inverter, montaj malzemeleri, kurulum ve devreye alma dahildir.',
                'sort_order' => 3,
                'is_active' => true
            ],
            [
                'question' => 'Güneş panelleri kışın çalışır mı?',
                'answer' => 'Evet, güneş panelleri kışın da çalışır. Soğuk hava aslında panellerin verimliliğini artırır çünkü yüksek sıcaklıklar panel verimliliğini düşürür. Ancak kar örtüsü ve daha kısa günler nedeniyle üretim miktarı yaz aylarına göre daha düşük olabilir. Kar örtüsü panellerin üzerini kaplarsa üretim durur, ancak kar genellikle kendiliğinden kayar veya temizlenebilir. Modern paneller kar örtüsüne karşı dayanıklıdır.',
                'sort_order' => 4,
                'is_active' => true
            ],
            [
                'question' => 'Şebeke bağlantısı zorunlu mu?',
                'answer' => 'Hayır, şebeke bağlantısı zorunlu değildir. Off-grid (şebekeden bağımsız) sistemler, batarya ile birlikte çalışır ve tamamen bağımsızdır. Bu sistemler özellikle şebeke erişimi olmayan kırsal bölgeler için idealdir. Ancak on-grid sistemler, fazla üretilen enerjiyi şebekeye satmanıza olanak tanır ve gece veya bulutlu günlerde şebekeden enerji alabilirsiniz. Hibrit sistemler ise her iki özelliği de bir arada sunar.',
                'sort_order' => 5,
                'is_active' => true
            ],
            [
                'question' => 'Güneş panelleri bakım gerektirir mi?',
                'answer' => 'Güneş panelleri minimum bakım gerektirir. Düzenli temizlik (özellikle tozlu bölgelerde) ve yıllık kontrol yeterlidir. Çoğu sistem 20-25 yıl boyunca sorunsuz çalışır. Yağmurlu bölgelerde doğal temizlik gerçekleşir, ancak kurak bölgelerde ayda bir kez temizlik önerilir. Ayrıca, bağlantı noktaları, kablolar ve inverter\'ın düzenli kontrolü önerilir. Profesyonel bakım hizmeti almak uzun vadede daha ekonomik olabilir.',
                'sort_order' => 6,
                'is_active' => true
            ],
            [
                'question' => 'Hangi güneş paneli tipi daha iyidir?',
                'answer' => 'Monokristal paneller daha yüksek verimlilik sunar (%20-22) ancak daha pahalıdır. Polikristal paneller daha ekonomiktir (%16-18 verimlilik) ve iyi bir verimlilik sağlar. Seçim, bütçenize ve ihtiyaçlarınıza bağlıdır. Monokristal paneller, sınırlı alan durumlarında daha fazla güç üretmek için tercih edilir. Polikristal paneller ise geniş alanlarda ve bütçe dostu çözümler için idealdir. Her iki tip de uzun ömürlü ve güvenilirdir.',
                'sort_order' => 7,
                'is_active' => true
            ],
            [
                'question' => 'Güneş enerjisi sistemi için izin gerekir mi?',
                'answer' => '10 kW\'a kadar sistemler için genellikle sadece belediye izni yeterlidir. Daha büyük sistemler için EPDK\'dan lisans alınması gerekebilir. On-grid sistemler için elektrik dağıtım şirketine başvuru yapılması gerekir. Detaylı bilgi için yerel yetkililere danışmanız önerilir. UK Elektronik olarak, tüm izin süreçlerinde size yardımcı oluyoruz ve gerekli belgeleri hazırlıyoruz.',
                'sort_order' => 8,
                'is_active' => true
            ],
            [
                'question' => 'Batarya olmadan güneş enerjisi kullanılabilir mi?',
                'answer' => 'Evet, on-grid sistemlerde batarya olmadan da güneş enerjisi kullanılabilir. Üretilen enerji anında kullanılır, fazlası şebekeye satılır. Gece veya bulutlu günlerde şebekeden enerji alınır. Bu sistemler daha ekonomiktir çünkü batarya maliyeti yoktur. Ancak elektrik kesintilerinde çalışmazlar. Off-grid veya hibrit sistemler için batarya gereklidir.',
                'sort_order' => 9,
                'is_active' => true
            ],
            [
                'question' => 'Güneş enerjisi sistemi geri ödeme süresi nedir?',
                'answer' => 'Güneş enerjisi sistemlerinin geri ödeme süresi genellikle 5-8 yıl arasındadır. Bu süre, sistem maliyeti, enerji tüketimi, elektrik fiyatları ve devlet teşviklerine bağlıdır. Sistem ömrü boyunca önemli tasarruf sağlar. Örneğin, 10 kW\'lık bir sistem yılda yaklaşık 15.000 kWh üretir ve bu da yıllık 30.000-45.000 TL tasarruf sağlayabilir. Devlet teşvikleri ve krediler geri ödeme süresini kısaltabilir.',
                'sort_order' => 10,
                'is_active' => true
            ],
            [
                'question' => 'Güneş panelleri çatıya zarar verir mi?',
                'answer' => 'Hayır, profesyonel kurulum ile güneş panelleri çatıya zarar vermez. Kurulum sırasında çatı yapısı kontrol edilir ve uygun montaj sistemleri kullanılır. Hatta paneller çatıyı güneş ışınlarından koruyarak ömrünü uzatabilir. Montaj sistemleri çatı tipine göre özel olarak seçilir ve su geçirmez bağlantılar kullanılır. Kurulum öncesi çatı muayenesi yapılır ve gerekirse çatı onarımı önerilir.',
                'sort_order' => 11,
                'is_active' => true
            ],
            [
                'question' => 'Güneş enerjisi sistemi için garanti var mı?',
                'answer' => 'Evet, kaliteli güneş panelleri genellikle 25 yıl garanti ile gelir. İnverterler için 5-10 yıl, bataryalar için 5-10 yıl garanti verilir. Ayrıca kurulum firması da işçilik garantisi sağlar. UK Elektronik olarak, tüm sistemlerimiz için kapsamlı garanti paketleri sunuyoruz. Garanti kapsamında panel performansı, inverter çalışması ve kurulum kalitesi yer almaktadır. Ayrıca, 7/24 teknik destek hizmeti sunuyoruz.',
                'sort_order' => 12,
                'is_active' => true
            ],
            [
                'question' => 'Rüzgar türbini sistemleri için uygun alan nasıl olmalıdır?',
                'answer' => 'Rüzgar türbini sistemleri için açık, rüzgarlı alanlar tercih edilmelidir. Binalar, ağaçlar ve diğer engeller rüzgar akışını etkileyebilir. Minimum 10-15 metre yükseklikte bir direk veya kule gereklidir. Yıllık ortalama rüzgar hızı en az 4-5 m/s olmalıdır. Kurulum öncesi rüzgar ölçümü yapılması önerilir. UK Elektronik olarak, rüzgar potansiyeli analizi ve uygun lokasyon seçimi konusunda profesyonel hizmet sunuyoruz.',
                'sort_order' => 13,
                'is_active' => true
            ],
            [
                'question' => 'Hidroelektrik santral kurulumu için ne gereklidir?',
                'answer' => 'Hidroelektrik santral kurulumu için su kaynağı, yeterli debi ve düşü yüksekliği gereklidir. Küçük ölçekli sistemler için minimum 50-100 litre/saniye debi ve 5-10 metre düşü yeterlidir. Kurulum için çevresel etki değerlendirmesi ve gerekli izinler alınmalıdır. UK Elektronik olarak, keşif, projelendirme, izin süreçleri ve kurulum hizmetleri sunuyoruz. Sistemin çevreye etkisi minimum seviyede tutulur.',
                'sort_order' => 14,
                'is_active' => true
            ],
            [
                'question' => 'Enerji depolama sistemleri hangi durumlarda gereklidir?',
                'answer' => 'Enerji depolama sistemleri, off-grid sistemlerde, elektrik kesintilerinde kesintisiz güç sağlamak için ve şebeke bağlantısı olmayan yerlerde gereklidir. Ayrıca, güneş enerjisinin üretildiği saatlerde kullanılamayan fazla enerjiyi depolamak için de kullanılır. Batarya sistemleri, gece veya bulutlu günlerde enerji ihtiyacını karşılar. Hibrit sistemlerde, şebeke kesintilerinde otomatik olarak devreye girer.',
                'sort_order' => 15,
                'is_active' => true
            ]
        ];

        foreach ($faqs as $index => $faq) {
            Faq::updateOrCreate(
                ['question' => $faq['question']],
                $faq
            );
        }
    }
}

