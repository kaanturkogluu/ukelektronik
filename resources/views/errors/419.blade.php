@extends('layouts.app')

@section('title', '419 - Oturum Süresi Doldu')

@section('content')
<div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <i class="bi bi-clock-history display-1 text-primary"></i>
                <h1 class="display-1">419</h1>
                <h1 class="mb-4">Oturum Süresi Doldu</h1>
                <p class="mb-4">Sayfada çok uzun süre işlem yapmadığınız için oturumunuzun süresi dolmuş olabilir. Lütfen sayfayı yenileyip tekrar deneyin.</p>
                <a class="btn btn-primary rounded-pill py-3 px-5" href="{{ url()->previous() }}">Geri Dön ve Yenile</a>
            </div>
        </div>
    </div>
</div>
@endsection
