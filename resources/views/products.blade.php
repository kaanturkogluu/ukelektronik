@extends('layouts.app')

@section('title', __('common.products') . ' - UK Elektronik')

@section('content')
    <!-- Page Header Start -->
    <div class="container-fluid page-header py-5 mb-5">
        <div class="container py-5">
            <h1 class="display-3 text-white mb-3 animated slideInDown">{{ __('common.products') }}</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="text-white" href="{{ route('home') }}">{{ __('common.home') }}</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">{{ __('common.products') }}</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Products Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row">
                <!-- Sidebar - Categories -->
                <div class="col-lg-3 mb-4">
                    <!-- Mobile Toggle Button -->
                    <button class="btn btn-primary w-100 d-lg-none mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#categoriesCollapse" aria-expanded="false" aria-controls="categoriesCollapse">
                        <i class="fa fa-filter me-2"></i>{{ __('common.categories') }}
                        <i class="fa fa-chevron-down ms-2"></i>
                    </button>
                    
                    <!-- Categories Panel -->
                    <div class="collapse d-lg-block" id="categoriesCollapse">
                        <div class="bg-light rounded p-4">
                            <h4 class="mb-4 d-none d-lg-block">{{ __('common.categories') }}</h4>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <a href="{{ route('products') }}" class="text-decoration-none d-flex align-items-center category-link {{ !$selectedCategory ? 'text-primary fw-bold' : 'text-dark' }}">
                                        <i class="fa fa-th me-2"></i>
                                        <span>{{ __('common.all') }}</span>
                                    </a>
                                </li>
                                @foreach($categories as $category)
                                <li class="mb-2">
                                    <a href="{{ route('products', ['category' => $category->slug]) }}" class="text-decoration-none d-flex align-items-center justify-content-between category-link {{ ($selectedCategory && $selectedCategory->id == $category->id) ? 'text-primary fw-bold' : 'text-dark' }}">
                                        <span class="d-flex align-items-center">
                                            <i class="fa fa-chevron-right me-2"></i>
                                            <span>{{ $category->name }}</span>
                                        </span>
                                        <span class="badge bg-secondary ms-2">{{ $category->products_count ?? 0 }}</span>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="col-lg-9">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="mb-0">{{ __('common.products') }}</h2>
                        <span class="text-muted">{{ $products->total() }} ürün bulundu</span>
                    </div>

                    <div class="row g-4 mb-4">
                        @forelse($products as $product)
                        <div class="col-md-6 col-lg-3 wow fadeInUp" data-wow-delay="0.1s">
                            <div class="product-card bg-white rounded overflow-hidden shadow-sm h-100" style="transition: transform 0.3s, box-shadow 0.3s;">
                                <a href="{{ route('product.detail', $product->slug) }}" class="text-decoration-none">
                                    <div class="position-relative" style="height: 250px; background-color: #f8f9fa; overflow: hidden;">
                                        @if($product->image)
                                            @if(str_starts_with($product->image, 'http://') || str_starts_with($product->image, 'https://') || str_starts_with($product->image, '/'))
                                                <img class="img-fluid w-100 h-100" src="{{ $product->image }}" alt="{{ $product->name }}" style="object-fit: cover; object-position: center;">
                                            @else
                                                <img class="img-fluid w-100 h-100" src="{{ asset($product->image) }}" alt="{{ $product->name }}" style="object-fit: cover; object-position: center;">
                                            @endif
                                        @else
                                            <img class="img-fluid w-100 h-100" src="{{ asset('img/img-600x400-1.jpg') }}" alt="{{ $product->name }}" style="object-fit: cover; object-position: center;">
                                        @endif
                                        @if($product->category && is_object($product->category))
                                        <div class="position-absolute top-0 end-0 m-2">
                                            <span class="badge bg-primary">{{ $product->category->name }}</span>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="p-4">
                                        <h5 class="mb-2 text-dark">{{ $product->name }}</h5>
                                        <!-- <p class="text-muted small mb-3" style="min-height: 40px;">{{ $product->short_description }}</p> -->
                                    </div>
                                </a>
                                <div class="px-4 pb-4">
                                    <a href="{{ route('product.detail', $product->slug) }}" class="btn btn-primary btn-sm w-100">
                                        <i class="fa fa-eye me-1"></i>Ürün Detay
                                    </a>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-center">
                            <p class="text-muted">Henüz ürün eklenmemiş.</p>
                        </div>
                        @endforelse
                    </div>
                    
                    <!-- Pagination -->
                    @if($products->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $products->links('vendor.pagination.bootstrap-5') }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Products End -->
@endsection

@section('styles')
<style>
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    
    .category-link:hover {
        color: var(--primary) !important;
        padding-left: 5px;
        transition: all 0.3s;
    }
    
    .category-link i {
        font-size: 0.8rem;
    }
    
    /* Mobil görünümde kategoriler butonu */
    #categoriesCollapse {
        transition: all 0.3s ease;
    }
    
    /* Desktop'ta her zaman göster */
    @media (min-width: 992px) {
        #categoriesCollapse {
            display: block !important;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mobil görünümde collapse butonundaki ikonu güncelle
        const collapseButton = document.querySelector('[data-bs-target="#categoriesCollapse"]');
        const collapseElement = document.getElementById('categoriesCollapse');
        
        if (collapseButton && collapseElement) {
            const icon = collapseButton.querySelector('.fa-chevron-down');
            
            collapseElement.addEventListener('show.bs.collapse', function() {
                if (icon) {
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                }
            });
            
            collapseElement.addEventListener('hide.bs.collapse', function() {
                if (icon) {
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                }
            });
        }
    });
</script>
@endsection
