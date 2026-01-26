@extends('layouts.app')

@section('title', $product['name'] . ' - UK Elektronik')

@section('content')
    <!-- Page Header Start -->
    <div class="container-fluid page-header py-5 mb-5">
        <div class="container py-5">
            <h1 class="display-3 text-white mb-3 animated slideInDown">{{ $product['name'] }}</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="text-white" href="{{ route('home') }}">Ana Sayfa</a></li>
                    <li class="breadcrumb-item"><a class="text-white" href="{{ route('products') }}">Ürünler</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">{{ $product['name'] }}</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Product Detail Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6">
                    <div class="mb-4">
                        @if(!empty($product['image']))
                            @if(str_starts_with($product['image'], 'http://') || str_starts_with($product['image'], 'https://') || str_starts_with($product['image'], '/'))
                                <img class="img-fluid rounded w-100" src="{{ $product['image'] }}" alt="{{ $product['name'] }}" style="max-height: 500px; object-fit: cover;">
                            @else
                                <img class="img-fluid rounded w-100" src="{{ asset($product['image']) }}" alt="{{ $product['name'] }}" style="max-height: 500px; object-fit: cover;">
                            @endif
                        @else
                            <img class="img-fluid rounded w-100" src="{{ asset('img/img-600x400-1.jpg') }}" alt="{{ $product['name'] }}" style="max-height: 500px; object-fit: cover;">
                        @endif
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="mb-4">
                        <span class="badge bg-primary mb-2">{{ $product['category'] }}</span>
                        <h2 class="mb-3">{{ $product['name'] }}</h2>
                        @if(!empty($product['short_description']))
                        <p class="mb-4">{{ $product['short_description'] }}</p>
                        @endif
                    </div>
                    
                    @if(!empty($product['description_items']) && is_array($product['description_items']) && count($product['description_items']) > 0)
                    <div class="mb-4">
                        <h4 class="mb-3">Detaylı Açıklama</h4>
                        <ul class="list-unstyled">
                            @foreach($product['description_items'] as $item)
                            <li class="mb-2">
                                <div class="d-flex align-items-start">
                                    <div class="btn-lg-square bg-primary rounded-circle me-3 flex-shrink-0" style="width: 30px; height: 30px; min-width: 30px;">
                                        <i class="fa fa-check text-white" style="font-size: 12px;"></i>
                                    </div>
                                    <div>
                                        <p class="mb-0">{{ $item }}</p>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @elseif(!empty($product['description']))
                    <div class="mb-4">
                        <h4 class="mb-3">Detaylı Açıklama</h4>
                        <p class="mb-0">{{ $product['description'] }}</p>
                    </div>
                    @endif

                    @if(isset($product['features']) && is_array($product['features']) && count($product['features']) > 0)
                    <div class="mb-4">
                        <h4 class="mb-3">Ürün Özellikleri</h4>
                        <div class="row g-3">
                            @foreach($product['features'] as $feature)
                            <div class="col-12">
                                <div class="d-flex align-items-start">
                                    <div class="btn-lg-square bg-primary rounded-circle me-3 flex-shrink-0">
                                        <i class="fa fa-check text-white"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $feature }}</h6>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="mb-4">
                        @if(!empty($product['whatsapp_url']))
                            <a href="{{ $product['whatsapp_url'] }}" target="_blank" class="btn btn-primary btn-lg w-100 py-3">
                                <i class="fab fa-whatsapp me-2"></i>Teklif Al (WhatsApp)
                            </a>
                        @else
                            <a href="{{ route('contact') }}" class="btn btn-primary btn-lg w-100 py-3">
                                <i class="fa fa-phone me-2"></i>Teklif Al
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            @if(isset($product['specs']) && is_array($product['specs']) && count($product['specs']) > 0)
            <div class="row mt-5">
                <div class="col-12">
                    <div class="bg-light rounded p-4">
                        <h4 class="mb-4">Teknik Özellikler</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    @foreach($product['specs'] as $key => $value)
                                    <tr>
                                        <th class="w-50 bg-white">{{ $key }}</th>
                                        <td>{{ $value }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="row mt-5">
                <div class="col-12">
                    <div class="bg-light rounded p-4">
                        <h4 class="mb-4">{{ __('common.contact') }}</h4>
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
                        <a href="{{ route('contact') }}" class="btn btn-primary">{{ __('common.contact_us') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Product Detail End -->
@endsection

@section('styles')
<style>
    .contact-link {
        transition: color 0.3s ease;
    }
    
    .contact-link:hover {
        color: var(--primary) !important;
    }
</style>
@endsection

