@extends('layouts.app')

@section('title', __('common.about_us') . ' - UK Elektronik')

@section('content')
    <!-- Page Header Start -->
    <div class="container-fluid page-header py-5 mb-5">
        <div class="container py-5">
            <h1 class="display-3 text-white mb-3 animated slideInDown">{{ __('common.about_us') }}</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="text-white" href="{{ route('home') }}">{{ __('common.home') }}</a></li>
                    <li class="breadcrumb-item"><a class="text-white" href="#">{{ __('common.pages') }}</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">{{ __('common.about_us') }}</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->

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
    <!-- Feature End -->

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
                        <h6 class="text-primary">{{ __('common.about_us') }}</h6>
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
                        <a href="{{ route('service') }}" class="btn btn-primary rounded-pill py-3 px-5 mt-3">{{ __('common.learn_more') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->

    <!-- Team Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <h6 class="text-primary">{{ __('common.team_members') }}</h6>
                <h1 class="mb-4">{{ __('common.experienced_team_members') }}</h1>
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
                    <p class="text-muted">{{ __('common.no_team_members') }}</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    <!-- Team End -->
@endsection

