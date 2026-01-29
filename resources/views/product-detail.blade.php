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
                                <img class="img-fluid rounded w-100 product-detail-image" src="{{ $product['image'] }}" alt="{{ $product['name'] }}">
                            @else
                                <img class="img-fluid rounded w-100 product-detail-image" src="{{ asset($product['image']) }}" alt="{{ $product['name'] }}">
                            @endif
                        @else
                            <img class="img-fluid rounded w-100 product-detail-image" src="{{ asset('img/img-600x400-1.jpg') }}" alt="{{ $product['name'] }}">
                        @endif
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="mb-4">
                        <span class="badge bg-primary mb-2">{{ $product['category'] }}</span>
                        <h2 class="mb-3">{{ $product['name'] }}</h2>
                    </div>

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

                    <div class="mb-4 d-flex align-items-center">
                        @if(!empty($product['whatsapp_url']))
                            <a href="{{ $product['whatsapp_url'] }}" target="_blank" class="whatsapp-product-link" title="WhatsApp ile ürün hakkında bilgi al">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        @else
                            <a href="{{ route('contact') }}" class="whatsapp-product-link text-secondary" title="İletişim">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        @endif
                        <span class="ms-2 text-muted small align-middle">Bilgi için iletişime geçin</span>
                    </div>
                </div>
            </div>

            @if(!empty($product['description_items']) && is_array($product['description_items']) && count($product['description_items']) > 0)
            <div class="row mt-5">
                <div class="col-12">
                    <div class="product-description">
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
                </div>
            </div>
            @elseif(!empty($product['description']))
            <div class="row mt-5">
                <div class="col-12">
                    <div class="product-description table-responsive">
                        {!! $product['description'] !!}
                    </div>
                </div>
            </div>
            @endif

            @if(isset($product['specs']) && is_array($product['specs']) && count($product['specs']) > 0)
            <div class="row mt-5">
                <div class="col-12">
                    <div class="product-detail-specs-wrap bg-light rounded p-4">
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

            @if(!empty($similarProducts) && count($similarProducts) > 0)
            <div class="row mt-5">
                <div class="col-12">
                    <h4 class="mb-4">Benzer Ürünler</h4>
                    <div class="row g-4">
                        @foreach($similarProducts as $sp)
                        <div class="col-6 col-md-4 col-lg-2">
                            <a href="{{ route('product.detail', $sp['slug']) }}" class="text-decoration-none">
                                <div class="bg-white rounded overflow-hidden shadow-sm h-100" style="transition: transform 0.3s, box-shadow 0.3s;">
                                    <div class="position-relative" style="height: 160px; background-color: #f8f9fa; overflow: hidden;">
                                        <img class="img-fluid w-100 h-100" src="{{ str_starts_with($sp['image'], 'http') ? $sp['image'] : asset($sp['image']) }}" alt="{{ $sp['name'] }}" style="object-fit: cover; object-position: center;">
                                    </div>
                                    <div class="p-3">
                                        <h6 class="mb-0 text-dark small text-truncate" title="{{ $sp['name'] }}">{{ $sp['name'] }}</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endforeach
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
    .product-detail-image {
        max-height: 320px;
        object-fit: contain;
    }
    .contact-link {
        transition: color 0.3s ease;
    }
    .contact-link:hover {
        color: var(--primary) !important;
    }
    .product-description {
        line-height: 1.6;
        margin-left: auto;
        margin-right: auto;
        max-width: 100%;
    }
    .product-description table {
        margin-left: auto;
        margin-right: auto;
    }
    .product-description img {
        max-width: 100%;
        height: auto;
        display: block;
        margin: 0.75rem auto;
    }
    .product-detail-specs-wrap {
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
    }
    .whatsapp-product-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: #25D366;
        color: #fff !important;
        font-size: 28px;
        text-decoration: none;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .whatsapp-product-link:hover {
        color: #fff !important;
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(37, 211, 102, 0.5);
    }
    .whatsapp-product-link.text-secondary {
        background: #6c757d;
    }
    .whatsapp-product-link.text-secondary:hover {
        color: #fff !important;
    }
</style>
@endsection

