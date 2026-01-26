@extends('layouts.app')

@section('title', __('common.projects') . ' - UK Elektronik')

@section('content')
    <!-- Page Header Start -->
    <div class="container-fluid page-header py-5 mb-5">
        <div class="container py-5">
            <h1 class="display-3 text-white mb-3 animated slideInDown">{{ __('common.projects') }}</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="text-white" href="{{ route('home') }}">{{ __('common.home') }}</a></li>
                    <li class="breadcrumb-item"><a class="text-white" href="#">{{ __('common.pages') }}</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">{{ __('common.projects') }}</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Projects Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <h6 class="text-primary">{{ __('common.our_projects') }}</h6>
                <h1 class="mb-4">{{ __('common.latest_renewable_energy_projects') }}</h1>
            </div>
            <div class="row mt-n2 wow fadeInUp" data-wow-delay="0.3s">
                <div class="col-12 text-center">
                    <ul class="list-inline mb-5" id="portfolio-flters">
                        <li class="mx-2 {{ !$selectedCategory ? 'active' : '' }}">
                            <a href="{{ route('project') }}" class="text-decoration-none text-dark">{{ __('common.all') }}</a>
                        </li>
                        @foreach($categories as $category)
                        <li class="mx-2 {{ $selectedCategory && $selectedCategory->id == $category->id ? 'active' : '' }}">
                            <a href="{{ route('project', ['category' => $category->slug]) }}" class="text-decoration-none text-dark">{{ $category->name }}</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="row g-4 portfolio-container wow fadeInUp" data-wow-delay="0.5s">
                @forelse($projects as $index => $project)
                <div class="col-lg-4 col-md-6 portfolio-item category-{{ $project->category ? $project->category->id : 'none' }}">
                    <a href="{{ route('project.detail', $project->slug) }}" class="text-decoration-none">
                        <div class="portfolio-img rounded overflow-hidden position-relative" style="cursor: pointer; transition: transform 0.3s; height: 250px; background-color: #f8f9fa;">
                            @if(!empty($project->image))
                                @if(str_starts_with($project->image, 'http://') || str_starts_with($project->image, 'https://') || str_starts_with($project->image, '/'))
                                    <img class="img-fluid w-100 h-100" src="{{ $project->image }}" alt="{{ $project->title }}" style="object-fit: cover; object-position: center;">
                                @else
                                    <img class="img-fluid w-100 h-100" src="{{ asset($project->image) }}" alt="{{ $project->title }}" style="object-fit: cover; object-position: center;">
                                @endif
                            @else
                                <img class="img-fluid w-100 h-100" src="{{ asset('img/img-600x400-' . (($index % 6) + 1) . '.jpg') }}" alt="{{ $project->title }}" style="object-fit: cover; object-position: center;">
                            @endif
                            <div class="portfolio-btn">
                                <span class="btn btn-lg-square btn-outline-light rounded-circle mx-1"><i class="fa fa-eye"></i></span>
                                <span class="btn btn-lg-square btn-outline-light rounded-circle mx-1"><i class="fa fa-link"></i></span>
                            </div>
                        </div>
                        <div class="pt-3">
                            <p class="text-primary mb-0">{{ $project->category ? $project->category->name : '' }}</p>
                            <hr class="text-primary w-25 my-2">
                            <h5 class="lh-base">{{ Str::limit($project->title, 50) }}</h5>
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

    <!-- Quote Start -->
    <div class="container-fluid bg-light overflow-hidden my-5 px-lg-0">
        <div class="container quote px-lg-0">
            <div class="row g-0 mx-lg-0">
                <div class="col-lg-6 ps-lg-0 wow fadeIn" data-wow-delay="0.1s" style="min-height: 400px;">
                    <div class="position-relative h-100">
                        <img class="position-absolute img-fluid w-100 h-100" src="{{ asset('img/quote.jpg') }}" style="object-fit: cover;" alt="">
                    </div>
                </div>
                <div class="col-lg-6 quote-text py-5 wow fadeIn" data-wow-delay="0.5s">
                    <div class="p-lg-5 pe-lg-0">
                        <h6 class="text-primary">{{ __('common.free_quote') }}</h6>
                        <h1 class="mb-4">{{ __('common.get_free_quote') }}</h1>
                        <p class="mb-4 pb-2">{{ __('common.quote_description') }}</p>
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
                        <a href="{{ route('contact') }}" class="btn btn-primary rounded-pill py-3 px-5">{{ __('common.contact_us') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Quote End -->

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

