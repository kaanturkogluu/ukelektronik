@extends('layouts.app')

@section('title', '404 - Sayfa Bulunamadı - UK Elektronik')

@section('content')
    <!-- Page Header Start -->
    <div class="container-fluid page-header py-5 mb-5">
        <div class="container py-5">
            <h1 class="display-3 text-white mb-3 animated slideInDown">404 Hatası</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="text-white" href="{{ route('home') }}">Ana Sayfa</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">404 Hatası</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- 404 Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-8 text-center wow fadeInUp" data-wow-delay="0.1s">
                    <div class="error-content mb-5">
                        <div class="error-number mb-4">
                            <h1 class="display-1 fw-bold text-primary" style="font-size: 150px; line-height: 1; text-shadow: 2px 2px 4px rgba(0,0,0,0.1);">404</h1>
                        </div>
                        <div class="error-icon mb-4">
                            <i class="fas fa-exclamation-triangle fa-5x text-warning animated pulse"></i>
                        </div>
                        <h2 class="mb-4">Sayfa Bulunamadı</h2>
                        <p class="lead mb-4 text-muted">Üzgünüz, aradığınız sayfa mevcut değil veya taşınmış olabilir. Lütfen URL'yi kontrol edin veya aşağıdaki bağlantıları kullanın.</p>
                    </div>
                    
                    <div class="error-actions mb-5">
                        <div class="row g-3 justify-content-center">
                            <div class="col-md-4">
                                <a href="{{ route('home') }}" class="btn btn-primary rounded-pill py-3 px-5 w-100">
                                    <i class="fa fa-home me-2"></i>Ana Sayfaya Dön
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('contact') }}" class="btn btn-outline-primary rounded-pill py-3 px-5 w-100">
                                    <i class="fa fa-envelope me-2"></i>İletişim
                                </a>
                            </div>
                            <div class="col-md-4">
                                <button onclick="history.back()" class="btn btn-outline-secondary rounded-pill py-3 px-5 w-100">
                                    <i class="fa fa-arrow-left me-2"></i>Geri Dön
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="quick-links">
                        <h5 class="mb-3">Hızlı Bağlantılar</h5>
                        <div class="row g-3 justify-content-center">
                            <div class="col-auto">
                                <a href="{{ route('about') }}" class="btn btn-link text-decoration-none">Hakkımızda</a>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('service') }}" class="btn btn-link text-decoration-none">Hizmetler</a>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('project') }}" class="btn btn-link text-decoration-none">Projeler</a>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('products') }}" class="btn btn-link text-decoration-none">Ürünler</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- 404 End -->
@endsection

@section('styles')
<style>
    .error-content {
        padding: 2rem 0;
    }
    
    .error-number h1 {
        animation: fadeInDown 1s ease-out;
        font-weight: 900;
        letter-spacing: -5px;
    }
    
    .error-icon i {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
            opacity: 1;
        }
        50% {
            transform: scale(1.1);
            opacity: 0.8;
        }
    }
    
    .error-actions .btn {
        transition: all 0.3s ease;
    }
    
    .error-actions .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .quick-links .btn-link {
        color: #6c757d;
        transition: all 0.3s ease;
        padding: 0.5rem 1rem;
    }
    
    .quick-links .btn-link:hover {
        color: #0d6efd;
        text-decoration: underline !important;
    }
    
    @media (max-width: 768px) {
        .error-number h1 {
            font-size: 100px !important;
        }
        
        .error-icon i {
            font-size: 3rem !important;
        }
    }
</style>
@endsection
