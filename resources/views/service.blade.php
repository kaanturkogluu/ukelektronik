@extends('layouts.app')

@section('title', __('common.services') . ' - UK Elektronik')

@section('content')
    <!-- Page Header Start -->
    <div class="container-fluid page-header py-5 mb-5">
        <div class="container py-5">
            <h1 class="display-3 text-white mb-3 animated slideInDown">{{ __('common.services') }}</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="text-white" href="{{ route('home') }}">{{ __('common.home') }}</a></li>
                    <li class="breadcrumb-item"><a class="text-white" href="#">{{ __('common.pages') }}</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">{{ __('common.services') }}</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Service Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <h6 class="text-primary">{{ __('common.our_services') }}</h6>
                <h1 class="mb-4">{{ __('common.renewable_energy_leader') }}</h1>
            </div>
            <div class="row g-4">
                @forelse($services as $index => $service)
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="{{ ($index % 3) * 0.2 }}s">
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
                                <span class="small fw-medium text-primary">Detayları Gör<i class="fa fa-arrow-right ms-2"></i></span>
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

@endsection

@push('styles')
<style>
    .service-item {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .service-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush

