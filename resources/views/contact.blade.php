@extends('layouts.app')

@section('title', __('common.contact') . ' - UK Elektronik')

@section('content')
    <!-- Page Header Start -->
    <div class="container-fluid page-header py-5 mb-5">
        <div class="container py-5">
            <h1 class="display-3 text-white mb-3 animated slideInDown">{{ __('common.contact') }}</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="text-white" href="{{ route('home') }}">{{ __('common.home') }}</a></li>
                    <li class="breadcrumb-item"><a class="text-white" href="#">{{ __('common.pages') }}</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">{{ __('common.contact') }}</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Contact Start -->
    <div class="container-fluid bg-light overflow-hidden px-lg-0" style="margin: 6rem 0;">
        <div class="container contact px-lg-0">
            <div class="row g-0 mx-lg-0">
                <div class="col-lg-6 contact-text py-5 wow fadeIn" data-wow-delay="0.5s">
                    <div class="p-lg-5 ps-lg-0">
                        <h6 class="text-primary">{{ __('common.contact') }}</h6>
                        <h1 class="mb-4">{{ __('common.contact_us_title') }}</h1>
                        <p class="mb-4">{{ __('common.contact_us_description') }}</p>
                        <div class="mb-4">
                            <p class="mb-3">
                                <i class="fa fa-map-marker-alt text-primary me-3"></i>
                                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($address) }}" target="_blank" class="text-decoration-none text-dark contact-link" title="Haritada görmek için tıklayın">
                                    {{ $address }}
                                </a>
                            </p>
                            
                            @if(count($phones) > 0)
                            <div class="mb-3">
                                @foreach($phones as $phone)
                                @php
                                    $phoneNumber = is_array($phone) ? ($phone['number'] ?? '') : $phone;
                                    $phoneType = is_array($phone) ? ($phone['type'] ?? 'phone') : 'phone';
                                @endphp
                                <p class="mb-2">
                                    @if($phoneType === 'whatsapp')
                                    <i class="fab fa-whatsapp text-primary me-3"></i>
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $phoneNumber) }}" target="_blank" class="text-decoration-none text-dark contact-link" title="WhatsApp ile iletişime geçin">
                                        {{ $phoneNumber }}
                                    </a>
                                    @else
                                    <i class="fa fa-phone-alt text-primary me-3"></i>
                                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $phoneNumber) }}" class="text-decoration-none text-dark contact-link" title="Aramak için tıklayın">
                                        {{ $phoneNumber }}
                                    </a>
                                    @endif
                                </p>
                                @endforeach
                            </div>
                            @endif
                            
                            @if(count($emails) > 0)
                            <div class="mb-3">
                                @foreach($emails as $email)
                                <p class="mb-2">
                                    <i class="fa fa-envelope text-primary me-3"></i>
                                    <a href="mailto:{{ $email }}" class="text-decoration-none text-dark contact-link" title="E-posta göndermek için tıklayın">
                                        {{ $email }}
                                    </a>
                                </p>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 pe-lg-0" style="min-height: 400px;">
                    <div class="position-relative h-100">
                        @if($mapIframe)
                            {!! $mapIframe !!}
                        @else
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d25546.910812412498!2d36.202517995053796!3d36.833761122002755!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x152f3f078f32e5fd%3A0xf6413fee371d9a26!2zRMO2cnR5b2wsIEhhdGF5!5e0!3m2!1str!2str!4v1769292355929!5m2!1str!2str" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact End -->
@endsection

@section('styles')
<style>
    .contact-link {
        transition: all 0.3s ease;
        border-bottom: 1px solid transparent;
    }
    
    .contact-link:hover {
        color: #0d6efd !important;
        border-bottom-color: #0d6efd;
        text-decoration: none !important;
    }
</style>
@endsection

