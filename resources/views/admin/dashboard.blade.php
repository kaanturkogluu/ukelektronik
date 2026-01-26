@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="fa fa-box fa-3x text-success mb-3"></i>
                <h3>{{ $stats['products'] }}</h3>
                <p class="text-muted mb-0">Ürün</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="fa fa-cogs fa-3x text-info mb-3"></i>
                <h3>{{ $stats['services'] }}</h3>
                <p class="text-muted mb-0">Hizmet</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="fa fa-project-diagram fa-3x text-warning mb-3"></i>
                <h3>{{ $stats['projects'] }}</h3>
                <p class="text-muted mb-0">Proje</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="fa fa-images fa-3x text-primary mb-3"></i>
                <h3>{{ $stats['sliders'] }}</h3>
                <p class="text-muted mb-0">Slider</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Hızlı Erişim</h5>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.products.create') }}" class="btn btn-success w-100">
                            <i class="fa fa-plus me-2"></i>Yeni Ürün
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.services.create') }}" class="btn btn-info w-100">
                            <i class="fa fa-plus me-2"></i>Yeni Hizmet
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.projects.create') }}" class="btn btn-warning w-100">
                            <i class="fa fa-plus me-2"></i>Yeni Proje
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary w-100">
                            <i class="fa fa-plus me-2"></i>Yeni Slider
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

