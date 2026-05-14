@extends('layouts.app')

@section('title', '404 - Sayfa Bulunamadı')

@section('content')
<div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <i class="bi bi-exclamation-triangle display-1 text-primary"></i>
                <h1 class="display-1">404</h1>
                <h1 class="mb-4">Sayfa Bulunamadı</h1>
                <p class="mb-4">Üzgünüz, aradığınız sayfa sitemizde mevcut değil! Belki ana sayfamıza gitmek veya bir arama yapmayı denemek istersiniz?</p>
                <a class="btn btn-primary rounded-pill py-3 px-5" href="{{ route('home') }}">Ana Sayfaya Dön</a>
            </div>
        </div>
    </div>
</div>
@endsection
