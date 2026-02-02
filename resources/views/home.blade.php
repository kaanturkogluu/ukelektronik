@extends('layouts.app')

@section('title', 'Ana Sayfa - UK Elektronik')

@section('content')
    <!-- Carousel Start -->
    <div class="container-fluid p-0 wow fadeIn" data-wow-delay="0.1s" style="height: calc(100vh - 105px); overflow: hidden;">
        <div class="owl-carousel header-carousel position-relative" style="height: calc(100vh - 105px);">
            @forelse($sliders as $slider)
            <div class="owl-carousel-item position-relative" data-dot="<img src='{{ str_starts_with($slider->image, 'http://') || str_starts_with($slider->image, 'https://') ? $slider->image : asset($slider->image) }}'>" style="height: calc(100vh - 105px);">
                <img class="img-fluid" src="{{ str_starts_with($slider->image, 'http://') || str_starts_with($slider->image, 'https://') ? $slider->image : asset($slider->image) }}" alt="{{ $slider->title }}" style="width: 100%; height: calc(100vh - 105px); object-fit: cover;">
                <div class="owl-carousel-inner" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center;">
                    <div class="container">
                        <div class="row justify-content-start">
                            <div class="col-10 col-lg-8">
                                <h1 class="display-2 text-white animated slideInDown">{{ $slider->title }}</h1>
                                <p class="fs-5 fw-medium text-white mb-4 pb-3">{{ $slider->description }}</p>
                                @if($slider->button_text && $slider->button_link && !str_contains($slider->button_link, 'quote'))
                                <a href="{{ $slider->button_link }}" class="btn btn-primary rounded-pill py-3 px-5 animated slideInLeft">{{ $slider->button_text }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <!-- Varsayılan slider (hiç slider yoksa) -->
            <div class="owl-carousel-item position-relative" data-dot="<img src='{{ asset('img/carousel-1.jpg') }}'>" style="height: calc(100vh - 105px);">
                <img class="img-fluid" src="{{ asset('img/carousel-1.jpg') }}" alt="" style="width: 100%; height: calc(100vh - 105px); object-fit: cover;">
                <div class="owl-carousel-inner" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center;">
                    <div class="container">
                        <div class="row justify-content-start">
                            <div class="col-10 col-lg-8">
                                <h1 class="display-2 text-white animated slideInDown">{{ __('common.home') }}</h1>
                                <p class="fs-5 fw-medium text-white mb-4 pb-3">{{ __('common.welcome_message') }}</p>
                                <a href="{{ route('contact') }}" class="btn btn-primary rounded-pill py-3 px-5 animated slideInLeft">{{ __('common.contact') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforelse
        </div>
    </div>
    <!-- Carousel End -->

    <!-- Feature Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-md-6 col-lg-3 wow fadeIn text-center" data-wow-delay="0.1s">
                    <div class="d-flex align-items-center justify-content-center mb-4">
                        <div class="btn-lg-square bg-primary rounded-circle">
                            <i class="fa fa-check text-white"></i>
                        </div>
                    </div>
                    <h5 class="mb-3">Kaliteli Hizmetler</h5>
                </div>
                <div class="col-md-6 col-lg-3 wow fadeIn text-center" data-wow-delay="0.3s">
                    <div class="d-flex align-items-center justify-content-center mb-4">
                        <div class="btn-lg-square bg-primary rounded-circle">
                            <i class="fa fa-user-check text-white"></i>
                        </div>
                    </div>
                    <h5 class="mb-3">Uzman Çalışanlar</h5>
                </div>
                <div class="col-md-6 col-lg-3 wow fadeIn text-center" data-wow-delay="0.5s">
                    <div class="d-flex align-items-center justify-content-center mb-4">
                        <div class="btn-lg-square bg-primary rounded-circle">
                            <i class="fa fa-drafting-compass text-white"></i>
                        </div>
                    </div>
                    <h5 class="mb-3">Ücretsiz Danışmanlık</h5>
                </div>
                <div class="col-md-6 col-lg-3 wow fadeIn text-center" data-wow-delay="0.7s">
                    <div class="d-flex align-items-center justify-content-center mb-4">
                        <div class="btn-lg-square bg-primary rounded-circle">
                            <i class="fa fa-headphones text-white"></i>
                        </div>
                    </div>
                    <h5 class="mb-3">Müşteri Desteği</h5>
                </div>
            </div>
        </div>
    </div>
    <!-- Feature Start -->

    <!-- About Start -->
    <div class="container-fluid bg-light overflow-hidden my-5 px-lg-0">
        <div class="container about px-lg-0">
            <div class="row g-0 mx-lg-0">
                <div class="col-lg-6 ps-lg-0 wow fadeIn" data-wow-delay="0.1s" style="min-height: 400px;">
                    <div class="position-relative h-100">
                        <img class="position-absolute img-fluid w-100 h-100" src="{{ asset('img/about.jpg') }}" style="object-fit: cover;" alt="">
                    </div>
                </div>
                <div class="col-lg-6 about-text py-5 wow fadeIn" data-wow-delay="0.5s">
                    <div class="p-lg-5 pe-lg-0">
                        <h6 class="text-primary">Hakkımızda</h6>
                        <h1 class="mb-4">UK Elektronik</h1>
                        <p class="mb-3">UK Elektronik, yenilenebilir enerji teknolojileri alanında faaliyet göstermek üzere kurulmuş, güneş enerjisi sistemleri konusunda anahtar teslim çözümler sunan genç ve dinamik bir mühendislik firmasıdır.</p>
                        <p class="mb-3">Kurulduğumuz günden bu yana hedefimiz; bireysel konutlar, villalar, iş yerleri, fabrikalar ve endüstriyel tesisler için verimli, güvenilir ve uzun ömürlü güneş enerji sistemleri kurarak müşterilerimize sürdürülebilir ve ekonomik enerji çözümleri sunmaktır.</p>
                        <p class="mb-3"><strong>UK Elektronik olarak;</strong></p>
                        <p class="mb-2"><i class="fa fa-check-circle text-primary me-3"></i>Keşif ve projelendirme</p>
                        <p class="mb-2"><i class="fa fa-check-circle text-primary me-3"></i>Mühendislik hesaplamaları</p>
                        <p class="mb-2"><i class="fa fa-check-circle text-primary me-3"></i>Kurulum ve devreye alma</p>
                        <p class="mb-3"><i class="fa fa-check-circle text-primary me-3"></i>Bakım ve teknik destek</p>
                        <p class="mb-3">süreçlerinin tamamını profesyonel ekibimizle uçtan uca yönetiyoruz.</p>
                        <p class="mb-3">Kullandığımız tüm ürünler yüksek kalite standartlarına sahip olup, sistemlerimiz maksimum verim ve uzun ömür esas alınarak tasarlanmaktadır.</p>
                        <p class="mb-3"><em>Bizim için güneş enerjisi sadece bir yatırım değil; daha temiz bir gelecek, daha düşük enerji maliyeti ve daha sürdürülebilir bir dünya demektir.</em></p>
                        <p class="mb-3"><strong>UK Elektronik olarak hedefimiz;</strong> müşteri memnuniyetini her zaman ön planda tutarak, güvenilir, şeffaf ve yenilikçi çözümlerle sektörde kalıcı ve güçlü bir marka olmaktır.</p>
                        <p class="mb-4"><strong>Geleceğin enerjisini bugünden çatınıza taşıyoruz.</strong></p>
                        <a href="{{ route('contact') }}" class="btn btn-primary rounded-pill py-3 px-5 mt-3">{{ __('common.contact') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->

    <!-- Service Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <h6 class="text-primary">{{ __('common.our_services') }}</h6>
                <h1 class="mb-4">Yenilenebilir Enerji Dünyasında Öncüyüz</h1>
            </div>
            <div class="row g-4">
                @forelse($services as $index => $service)
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="{{ ($index % 3) * 0.2 + 0.1 }}s">
                    <a href="{{ route('service.detail', $service->slug) }}" class="text-decoration-none">
                        <div class="service-item rounded overflow-hidden" style="cursor: pointer; transition: transform 0.3s;">
                            <div style="height: 250px; background-color: #f8f9fa; overflow: hidden;">
                                @if(!empty($service->image))
                                    @if(str_starts_with($service->image, 'http://') || str_starts_with($service->image, 'https://'))
                                        <img class="img-fluid w-100 h-100" src="{{ $service->image }}" alt="{{ $service->title }}" style="object-fit: cover; object-position: center;">
                                    @else
                                        <img class="img-fluid w-100 h-100" src="{{ asset($service->image) }}" alt="{{ $service->title }}" style="object-fit: cover; object-position: center;">
                                    @endif
                                @else
                                    <img class="img-fluid w-100 h-100" src="{{ asset('img/img-600x400-' . (($index % 6) + 1) . '.jpg') }}" alt="{{ $service->title }}" style="object-fit: cover; object-position: center;">
                                @endif
                            </div>
                            <div class="position-relative p-4">
                                @if($service->icon)
                                <div class="d-flex align-items-center mb-3">
                                    <div class="btn-lg-square bg-primary rounded-circle me-3 flex-shrink-0">
                                        <i class="fa {{ $service->icon }} text-white"></i>
                                    </div>
                                    <h4 class="mb-0" style="color: #1A2A36;">{{ $service->title }}</h4>
                                </div>
                                @else
                                <h4 class="mb-3" style="color: #1A2A36;">{{ $service->title }}</h4>
                                @endif
                                <p style="color: #6c757d;">{{ $service->short_description }}</p>
                                <span class="small fw-medium text-primary">{{ __('common.our_services') }}<i class="fa fa-arrow-right ms-2"></i></span>
                            </div>
                        </div>
                    </a>
                </div>
                @empty
                <div class="col-12 text-center">
                    <p class="text-muted">Henüz hizmet eklenmemiş.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    <!-- Service End -->

    <!-- Feature Start -->
    <div class="container-fluid bg-light overflow-hidden my-5 px-lg-0">
        <div class="container feature px-lg-0">
            <div class="row g-0 mx-lg-0">
                <div class="col-lg-6 feature-text py-5 wow fadeIn" data-wow-delay="0.1s">
                    <div class="p-lg-5 ps-lg-0">
                        <h6 class="text-primary">Neden Bizi Seçmelisiniz!</h6>
                        <h1 class="mb-4">Tam Ticari ve Konut Güneş Sistemleri</h1>
                        <p class="mb-4 pb-2">UK Elektronik olarak, ticari ve konut projeleriniz için anahtar teslim güneş enerjisi sistemleri sunuyoruz. Profesyonel ekibimiz ve kaliteli ürünlerimizle güvenilir çözümler sağlıyoruz.</p>
                        <div class="row g-4">
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <div class="btn-lg-square bg-primary rounded-circle">
                                        <i class="fa fa-check text-white"></i>
                                    </div>
                                    <div class="ms-4">
                                        <p class="mb-0">Kaliteli</p>
                                        <h5 class="mb-0">Hizmetler</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <div class="btn-lg-square bg-primary rounded-circle">
                                        <i class="fa fa-user-check text-white"></i>
                                    </div>
                                    <div class="ms-4">
                                        <p class="mb-0">Uzman</p>
                                        <h5 class="mb-0">Çalışanlar</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <div class="btn-lg-square bg-primary rounded-circle">
                                        <i class="fa fa-drafting-compass text-white"></i>
                                    </div>
                                    <div class="ms-4">
                                        <p class="mb-0">Ücretsiz</p>
                                        <h5 class="mb-0">Danışmanlık</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <div class="btn-lg-square bg-primary rounded-circle">
                                        <i class="fa fa-headphones text-white"></i>
                                    </div>
                                    <div class="ms-4">
                                        <p class="mb-0">Müşteri</p>
                                        <h5 class="mb-0">Desteği</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 pe-lg-0 wow fadeIn" data-wow-delay="0.5s" style="min-height: 400px;">
                    <div class="position-relative h-100">
                        <img class="position-absolute img-fluid w-100 h-100" src="{{ asset('img/feature.jpg') }}" style="object-fit: cover;" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Feature End -->

    <!-- Projects Start -->
    <div class="container-xxl py-5" style="margin-bottom: 0;">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <h6 class="text-primary">Projelerimiz</h6>
                <h1 class="mb-4">En Son Güneş ve Yenilenebilir Enerji Projelerimizi İnceleyin</h1>
            </div>
            <div class="row mt-n2 wow fadeInUp" data-wow-delay="0.3s">
                <div class="col-12 text-center">
                    <ul class="list-inline mb-5" id="portfolio-flters">
                        <li class="mx-2 active" data-filter="*">Tümü</li>
                        @foreach($categories as $category)
                        <li class="mx-2" data-filter=".category-{{ $category->id }}">{{ $category->name }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="row g-4 portfolio-container wow fadeInUp" data-wow-delay="0.5s">
                @forelse($projects as $index => $project)
                @php
                    $categoryClass = $project->category ? 'category-' . $project->category->id : 'first';
                @endphp
                <div class="col-lg-4 col-md-6 portfolio-item {{ $categoryClass }}">
                    <a href="{{ route('project.detail', $project->slug) }}" class="text-decoration-none">
                        <div class="portfolio-img rounded overflow-hidden" style="cursor: pointer; height: 250px; background-color: #f8f9fa;">
                            @if(!empty($project->image))
                                @if(str_starts_with($project->image, 'http://') || str_starts_with($project->image, 'https://') || str_starts_with($project->image, '/'))
                                    <img class="img-fluid w-100 h-100" src="{{ $project->image }}" alt="{{ $project->title }}" style="object-fit: cover; object-position: center;">
                                @else
                                    <img class="img-fluid w-100 h-100" src="{{ asset($project->image) }}" alt="{{ $project->title }}" style="object-fit: cover; object-position: center;">
                                @endif
                            @else
                                <img class="img-fluid w-100 h-100" src="{{ asset('img/img-600x400-' . (($index % 6) + 1) . '.jpg') }}" alt="{{ $project->title }}" style="object-fit: cover; object-position: center;">
                            @endif
                        </div>
                        <div class="pt-3">
                            <p class="text-primary mb-0">{{ $project->category ? $project->category->name : '' }}</p>
                            <hr class="text-primary w-25 my-2">
                            <h5 class="lh-base">{{ $project->title }}</h5>
                        </div>
                    </a>
                </div>
                @empty
                <div class="col-12 text-center">
                    <p class="text-muted">Henüz proje eklenmemiş.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    <!-- Projects End -->

    <!-- Team Start -->
    <div class="container-xxl py-5" style="margin-top: 0; clear: both;">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <h6 class="text-primary">Ekip Üyeleri</h6>
                <h1 class="mb-4">Deneyimli Ekip Üyelerimiz</h1>
            </div>
            <div class="row g-4">
                @forelse($teams as $index => $team)
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="{{ 0.1 + ($index * 0.2) }}s">
                    <div class="team-item rounded overflow-hidden">
                        <div class="d-flex">
                            @php
                                $imageUrl = $team->image;
                                if ($imageUrl && !str_starts_with($imageUrl, 'http://') && !str_starts_with($imageUrl, 'https://') && !str_starts_with($imageUrl, '/')) {
                                    $imageUrl = asset($imageUrl);
                                } elseif (!$imageUrl) {
                                    $imageUrl = asset('img/team-1.jpg');
                                }
                            @endphp
                            <img class="img-fluid w-75" src="{{ $imageUrl }}" alt="{{ $team->name }}">
                            <div class="team-social w-25">
                                @if($team->facebook)
                                <a class="btn btn-lg-square btn-outline-primary rounded-circle mt-3" href="{{ $team->facebook }}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                @endif
                                @if($team->twitter)
                                <a class="btn btn-lg-square btn-outline-primary rounded-circle mt-3" href="{{ $team->twitter }}" target="_blank"><i class="fab fa-twitter"></i></a>
                                @endif
                                @if($team->instagram)
                                <a class="btn btn-lg-square btn-outline-primary rounded-circle mt-3" href="{{ $team->instagram }}" target="_blank"><i class="fab fa-instagram"></i></a>
                                @endif
                                @if($team->linkedin)
                                <a class="btn btn-lg-square btn-outline-primary rounded-circle mt-3" href="{{ $team->linkedin }}" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                                @endif
                            </div>
                        </div>
                        <div class="p-4">
                            <h5>{{ $team->name }}</h5>
                            <span>{{ $team->position }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center">
                    <p class="text-muted">Henüz ekip üyesi eklenmemiş.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    <!-- Team End -->
@endsection

