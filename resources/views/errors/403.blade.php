@extends('layouts.app')

@section('title', '403 - Yetkisiz Erişim')

@section('content')
<div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <i class="bi bi-shield-lock display-1 text-primary"></i>
                <h1 class="display-1">403</h1>
                <h1 class="mb-4">Yetkisiz Erişim</h1>
                <p class="mb-4">Bu sayfaya erişim yetkiniz bulunmamaktadır. Eğer bir hata olduğunu düşünüyorsanız lütfen sistem yöneticisi ile iletişime geçin.</p>
                <a class="btn btn-primary rounded-pill py-3 px-5" href="{{ route('home') }}">Ana Sayfaya Dön</a>
            </div>
        </div>
    </div>
</div>
@endsection
