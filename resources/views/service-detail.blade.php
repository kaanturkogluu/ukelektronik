@extends('layouts.app')

@section('title', $service['title'] . ' - UK Elektronik')

@section('content')
    <!-- Page Header Start -->
    <div class="container-fluid page-header py-5 mb-5">
        <div class="container py-5">
            <h1 class="display-3 text-white mb-3 animated slideInDown">{{ $service['title'] }}</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="text-white" href="{{ route('home') }}">Ana Sayfa</a></li>
                    <li class="breadcrumb-item"><a class="text-white" href="{{ route('service') }}">Hizmetler</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">{{ $service['title'] }}</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Service Detail Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8">
                    <div class="mb-5">
                        @if(!empty($service['image']))
                            @if(str_starts_with($service['image'], 'http://') || str_starts_with($service['image'], 'https://'))
                                <img class="img-fluid rounded w-100" src="{{ $service['image'] }}" alt="{{ $service['title'] }}">
                            @else
                                <img class="img-fluid rounded w-100" src="{{ asset($service['image']) }}" alt="{{ $service['title'] }}">
                            @endif
                        @else
                            <img class="img-fluid rounded w-100" src="{{ asset('img/img-600x400-1.jpg') }}" alt="{{ $service['title'] }}">
                        @endif
                    </div>
                    <div class="mb-5">
                        <h2 class="mb-4">{{ $service['title'] }}</h2>
                        <p class="mb-4">{{ $service['description'] }}</p>
                    </div>

                    <div class="mb-5">
                        <h3 class="mb-4">Özellikler</h3>
                        <div class="row g-4">
                            @foreach($service['features'] as $feature)
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <div class="btn-lg-square bg-primary rounded-circle me-3 flex-shrink-0">
                                        <i class="fa fa-check text-white"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-2">{{ $feature }}</h5>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-5">
                        <h3 class="mb-4">Avantajlar</h3>
                        <div class="row g-4">
                            @foreach($service['benefits'] as $benefit)
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <div class="btn-lg-square bg-primary rounded-circle me-3 flex-shrink-0">
                                        <i class="fa fa-star text-white"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-2">{{ $benefit }}</h5>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="bg-light rounded p-4 mb-5">
                        <h4 class="mb-4">Hizmet Bilgileri</h4>
                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-3">
                                @if(!empty($service['icon']))
                                <div class="btn-lg-square bg-primary rounded-circle me-3">
                                    <i class="fa {{ $service['icon'] }} text-white fa-2x"></i>
                                </div>
                                @endif
                                <h5 class="mb-0">{{ $service['title'] }}</h5>
                            </div>
                            <p>{{ $service['short_description'] }}</p>
                        </div>
                        <div class="mb-4">
                            <h5 class="mb-3">İletişim</h5>
                            <p class="mb-2"><i class="fa fa-phone-alt text-primary me-2"></i>+90 (212) 123 45 67</p>
                            <p class="mb-2"><i class="fa fa-envelope text-primary me-2"></i>info@ukelektronik.com</p>
                            <p class="mb-0"><i class="fa fa-map-marker-alt text-primary me-2"></i>Dörtyol, Hatay, Türkiye</p>
                        </div>
                        <a href="{{ route('contact') }}" class="btn btn-primary w-100 py-3">İletişime Geç</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Service Detail End -->
@endsection

