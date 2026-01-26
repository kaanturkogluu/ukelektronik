@extends('layouts.app')

@section('title', 'Ekibimiz - UK Elektronik')

@section('content')
    <!-- Page Header Start -->
    <div class="container-fluid page-header py-5 mb-5">
        <div class="container py-5">
            <h1 class="display-3 text-white mb-3 animated slideInDown">Ekibimiz</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="text-white" href="{{ route('home') }}">Ana Sayfa</a></li>
                    <li class="breadcrumb-item"><a class="text-white" href="#">Sayfalar</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">Ekibimiz</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Team Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <h6 class="text-primary">Ekip Üyeleri</h6>
                <h1 class="mb-4">Deneyimli Ekip Üyelerimiz</h1>
            </div>
            <div class="row g-4">
                @forelse($teams as $index => $team)
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="{{ 0.1 + ($index * 0.2) }}s">
                    <div class="team-item rounded overflow-hidden">
                        <div class="d-flex">
                            @php
                                $imageUrl = $team->image;
                                if ($imageUrl && !str_starts_with($imageUrl, 'http://') && !str_starts_with($imageUrl, 'https://') && !str_starts_with($imageUrl, '/')) {
                                    $imageUrl = asset($imageUrl);
                                } elseif (!$imageUrl) {
                                    $imageUrl = asset('img/team-1.jpg');
                                }
                            @endphp
                            <img class="img-fluid w-75" src="{{ $imageUrl }}" alt="{{ $team->name }}">
                            <div class="team-social w-25">
                                @if($team->facebook)
                                <a class="btn btn-lg-square btn-outline-primary rounded-circle mt-3" href="{{ $team->facebook }}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                @endif
                                @if($team->twitter)
                                <a class="btn btn-lg-square btn-outline-primary rounded-circle mt-3" href="{{ $team->twitter }}" target="_blank"><i class="fab fa-twitter"></i></a>
                                @endif
                                @if($team->instagram)
                                <a class="btn btn-lg-square btn-outline-primary rounded-circle mt-3" href="{{ $team->instagram }}" target="_blank"><i class="fab fa-instagram"></i></a>
                                @endif
                                @if($team->linkedin)
                                <a class="btn btn-lg-square btn-outline-primary rounded-circle mt-3" href="{{ $team->linkedin }}" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                                @endif
                            </div>
                        </div>
                        <div class="p-4">
                            <h5>{{ $team->name }}</h5>
                            <span>{{ $team->position }}</span>
                            @if($team->description)
                            <p class="text-muted mt-2 mb-0" style="font-size: 0.9rem;">{{ \Illuminate\Support\Str::limit($team->description, 100) }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center">
                    <p class="text-muted">Henüz ekip üyesi eklenmemiş.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    <!-- Team End -->
@endsection
