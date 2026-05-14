@extends('layouts.app')

@section('title', '500 - Sunucu Hatası')

@section('content')
<div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <i class="bi bi-gear-wide-connected display-1 text-primary"></i>
                <h1 class="display-1">500</h1>
                <h1 class="mb-4">Sunucu Hatası</h1>
                <p class="mb-4">Üzgünüz, sunucumuzda beklenmedik bir hata oluştu. Teknik ekibimiz durumdan haberdar edildi. Lütfen daha sonra tekrar deneyin.</p>
                <a class="btn btn-primary rounded-pill py-3 px-5" href="{{ route('home') }}">Ana Sayfaya Dön</a>
            </div>
        </div>
    </div>
</div>
@endsection
