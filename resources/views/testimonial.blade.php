@extends('layouts.app')

@section('title', 'Müşteri Yorumları - UK Elektronik')

@section('content')
    <!-- Page Header Start -->
    <div class="container-fluid page-header py-5 mb-5">
        <div class="container py-5">
            <h1 class="display-3 text-white mb-3 animated slideInDown">Müşteri Yorumları</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="text-white" href="{{ route('home') }}">Ana Sayfa</a></li>
                    <li class="breadcrumb-item"><a class="text-white" href="#">Sayfalar</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">Müşteri Yorumları</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Testimonial Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <h6 class="text-primary">Müşteri Yorumları</h6>
                <h1 class="mb-4">Müşterilerimiz Ne Diyor!</h1>
            </div>
            <div class="owl-carousel testimonial-carousel wow fadeInUp" data-wow-delay="0.1s">
                <div class="testimonial-item text-center">
                    <div class="testimonial-img position-relative">
                        <img class="img-fluid rounded-circle mx-auto mb-5" src="{{ asset('img/testimonial-1.jpg') }}">
                        <div class="btn-square bg-primary rounded-circle">
                            <i class="fa fa-quote-left text-white"></i>
                        </div>
                    </div>
                    <div class="testimonial-text text-center rounded p-4">
                        <p>Kurulum süreci hızlı ve sorunsuzdu. Ekibin ilgisi ve teknik desteği beklentimizin üzerindeydi.</p>
                        <h5 class="mb-1">Müşteri Adı</h5>
                        <span class="fst-italic">Meslek</span>
                    </div>
                </div>
                <div class="testimonial-item text-center">
                    <div class="testimonial-img position-relative">
                        <img class="img-fluid rounded-circle mx-auto mb-5" src="{{ asset('img/testimonial-2.jpg') }}">
                        <div class="btn-square bg-primary rounded-circle">
                            <i class="fa fa-quote-left text-white"></i>
                        </div>
                    </div>
                    <div class="testimonial-text text-center rounded p-4">
                        <p>Enerji maliyetlerimiz ciddi şekilde düştü. Profesyonel yaklaşım ve kaliteli ekipman için teşekkürler.</p>
                        <h5 class="mb-1">Müşteri Adı</h5>
                        <span class="fst-italic">Meslek</span>
                    </div>
                </div>
                <div class="testimonial-item text-center">
                    <div class="testimonial-img position-relative">
                        <img class="img-fluid rounded-circle mx-auto mb-5" src="{{ asset('img/testimonial-3.jpg') }}">
                        <div class="btn-square bg-primary rounded-circle">
                            <i class="fa fa-quote-left text-white"></i>
                        </div>
                    </div>
                    <div class="testimonial-text text-center rounded p-4">
                        <p>Projeden teslim sonrası desteğe kadar her aşamada yanımızdaydılar. Gönül rahatlığıyla tavsiye ederim.</p>
                        <h5 class="mb-1">Müşteri Adı</h5>
                        <span class="fst-italic">Meslek</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Testimonial End -->
@endsection

