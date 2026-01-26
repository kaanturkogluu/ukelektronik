<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Slider;

class SliderSeeder extends Seeder
{
    public function run(): void
    {
        $sliders = [
            [
                'title' => [
                    'tr' => 'Güneş ve Yenilenebilir Enerjide Öncüler',
                    'en' => 'Leaders in Solar and Renewable Energy'
                ],
                'description' => [
                    'tr' => 'UK Elektronik olarak, sürdürülebilir enerji çözümleri ile geleceği bugünden inşa ediyoruz. Güneş enerjisi sistemlerinde uzman ekibimizle yanınızdayız.',
                    'en' => 'As UK Elektronik, we are building the future today with sustainable energy solutions. We are with you with our expert team in solar energy systems.'
                ],
                'image' => 'https://images.unsplash.com/photo-1509391366360-2e959784a276?w=1920&h=1080&fit=crop',
                'button_text' => [
                    'tr' => 'Enerji Hesaplama',
                    'en' => 'Energy Calculator'
                ],
                'button_link' => '/quote',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => [
                    'tr' => 'Sürdürülebilir Enerji Çözümleri',
                    'en' => 'Sustainable Energy Solutions'
                ],
                'description' => [
                    'tr' => 'Yenilenebilir enerji kaynaklarını kullanarak çevre dostu ve ekonomik çözümler sunuyoruz. Gelecek nesillere daha temiz bir dünya bırakmak için çalışıyoruz.',
                    'en' => 'We offer environmentally friendly and economical solutions by using renewable energy sources. We work to leave a cleaner world for future generations.'
                ],
                'image' => 'https://images.unsplash.com/photo-1466611653911-95081537e5b7?w=1920&h=1080&fit=crop',
                'button_text' => [
                    'tr' => 'Hizmetlerimiz',
                    'en' => 'Our Services'
                ],
                'button_link' => '/service',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => [
                    'tr' => 'Profesyonel Enerji Danışmanlığı',
                    'en' => 'Professional Energy Consulting'
                ],
                'description' => [
                    'tr' => 'Enerji ihtiyaçlarınızı analiz ediyor, en uygun çözümleri sunuyoruz. Güneş paneli kurulumundan bakım hizmetlerine kadar yanınızdayız.',
                    'en' => 'We analyze your energy needs and offer the most suitable solutions. We are with you from solar panel installation to maintenance services.'
                ],
                'image' => 'https://images.unsplash.com/photo-1497435334941-8c899ee9e8e9?w=1920&h=1080&fit=crop',
                'button_text' => [
                    'tr' => 'Projelerimiz',
                    'en' => 'Our Projects'
                ],
                'button_link' => '/project',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($sliders as $slider) {
            Slider::updateOrCreate(
                ['sort_order' => $slider['sort_order']],
                [
                    'title' => json_encode($slider['title']),
                    'description' => json_encode($slider['description']),
                    'image' => $slider['image'],
                    'button_text' => json_encode($slider['button_text']),
                    'button_link' => $slider['button_link'],
                    'sort_order' => $slider['sort_order'],
                    'is_active' => $slider['is_active'],
                ]
            );
        }
    }
}
