@extends('layouts.app')

@section('title', $project['title'] . ' - UK Elektronik')

@section('content')
    <!-- Page Header Start -->
    <div class="container-fluid page-header py-5 mb-5">
        <div class="container py-5">
            <h1 class="display-3 text-white mb-3 animated slideInDown">{{ $project['title'] }}</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="text-white" href="{{ route('home') }}">Ana Sayfa</a></li>
                    <li class="breadcrumb-item"><a class="text-white" href="{{ route('project') }}">Projeler</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">{{ $project['title'] }}</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Project Detail Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8">
                    <div class="mb-5">
                        @if(!empty($project['image']))
                            @if(str_starts_with($project['image'], 'http://') || str_starts_with($project['image'], 'https://') || str_starts_with($project['image'], '/'))
                                <img class="img-fluid rounded w-100" src="{{ $project['image'] }}" alt="{{ $project['title'] }}">
                            @else
                                <img class="img-fluid rounded w-100" src="{{ asset($project['image']) }}" alt="{{ $project['title'] }}">
                            @endif
                        @else
                            <img class="img-fluid rounded w-100" src="{{ asset('img/img-600x400-1.jpg') }}" alt="{{ $project['title'] }}">
                        @endif
                    </div>
                    <div class="mb-5">
                        <h2 class="mb-4">{{ $project['title'] }}</h2>
                        <p class="mb-4">{{ $project['description'] }}</p>
                    </div>

                    @if(isset($project['details']) && is_array($project['details']) && count($project['details']) > 0)
                    <div class="mb-5">
                        <h3 class="mb-4">Proje Detayları</h3>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    @foreach($project['details'] as $key => $value)
                                    <tr>
                                        <th class="w-50 bg-light">{{ $key }}</th>
                                        <td>{{ $value }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    @if(isset($project['features']) && is_array($project['features']) && count($project['features']) > 0)
                    <div class="mb-5">
                        <h3 class="mb-4">Proje Özellikleri</h3>
                        <div class="row g-4">
                            @foreach($project['features'] as $feature)
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <div class="btn-lg-square bg-primary rounded-circle me-3 flex-shrink-0">
                                        <i class="fa fa-check text-white"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-2">{{ $feature }}</h5>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if(isset($project['gallery']) && count($project['gallery']) > 0)
                    <div class="mb-5">
                        <h3 class="mb-4">Proje Galerisi</h3>
                        <div class="row g-3">
                            @foreach($project['gallery'] as $index => $image)
                            <div class="col-md-6">
                                <div class="position-relative gallery-item" style="cursor: pointer;" data-image-index="{{ $index }}">
                                    @if(str_starts_with($image, 'http://') || str_starts_with($image, 'https://') || str_starts_with($image, '/'))
                                        <img class="img-fluid rounded w-100 gallery-image" 
                                             src="{{ $image }}" 
                                             alt="{{ $project['title'] }}">
                                    @else
                                        <img class="img-fluid rounded w-100 gallery-image" 
                                             src="{{ asset($image) }}" 
                                             alt="{{ $project['title'] }}">
                                    @endif
                                    <div class="gallery-overlay">
                                        <i class="fa fa-search-plus fa-3x text-white"></i>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <div class="col-lg-4">
                    <div class="bg-light rounded p-4 mb-5">
                        <h4 class="mb-4">Proje Bilgileri</h4>
                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-3">
                                <div class="btn-lg-square bg-primary rounded-circle me-3">
                                    <i class="fa fa-check text-white fa-2x"></i>
                                </div>
                                <h5 class="mb-0">{{ $project['category'] }}</h5>
                            </div>
                            <p class="text-muted">{{ $project['description'] }}</p>
                        </div>
                        <div class="mb-4">
                            <h5 class="mb-3">İletişim</h5>
                            <p class="mb-2"><i class="fa fa-phone-alt text-primary me-2"></i>+90 (212) 123 45 67</p>
                            <p class="mb-2"><i class="fa fa-envelope text-primary me-2"></i>info@ukelektronik.com</p>
                            <p class="mb-0"><i class="fa fa-map-marker-alt text-primary me-2"></i>Dörtyol, Hatay, Türkiye</p>
                        </div>
                        <a href="{{ route('contact') }}" class="btn btn-primary w-100 py-3">İletişime Geç</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Project Detail End -->

    <!-- Gallery Modal -->
    @if(isset($project['gallery']) && count($project['gallery']) > 0)
    <div class="modal fade" id="galleryModal" tabindex="-1" aria-labelledby="galleryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content bg-dark border-0">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <img id="galleryModalImage" src="" alt="{{ $project['title'] }}" class="img-fluid rounded mb-3" style="max-height: 70vh; object-fit: contain;">
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="button" class="btn btn-outline-light" id="prevImage">
                            <i class="fa fa-chevron-left"></i> Önceki
                        </button>
                        <span id="galleryImageCounter" class="text-white px-3"></span>
                        <button type="button" class="btn btn-outline-light" id="nextImage">
                            Sonraki <i class="fa fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection

@section('styles')
<style>
    .gallery-item {
        position: relative;
        overflow: hidden;
        border-radius: 8px;
    }
    
    .gallery-image {
        transition: transform 0.3s ease;
    }
    
    .gallery-item:hover .gallery-image {
        transform: scale(1.05);
    }
    
    .gallery-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        border-radius: 8px;
    }
    
    .gallery-item:hover .gallery-overlay {
        opacity: 1;
    }
    
    #galleryModal .modal-content {
        background: rgba(0, 0, 0, 0.9) !important;
    }
</style>
@endsection

@section('scripts')
<script>
    (function() {
        // Gallery images with full URLs
        @php
            $galleryUrls = [];
            if (isset($project['gallery']) && is_array($project['gallery'])) {
                foreach ($project['gallery'] as $img) {
                    if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://') || str_starts_with($img, '/')) {
                        $galleryUrls[] = $img;
                    } else {
                        $galleryUrls[] = asset($img);
                    }
                }
            }
        @endphp
        const galleryImages = @json($galleryUrls);
        let currentImageIndex = 0;
        
        console.log('Gallery Images loaded:', galleryImages);

        function openGalleryModal(index) {
            console.log('openGalleryModal called with index:', index);
            if (!galleryImages || galleryImages.length === 0) {
                console.error('Gallery images not loaded');
                return;
            }
            currentImageIndex = parseInt(index);
            if (isNaN(currentImageIndex) || currentImageIndex < 0 || currentImageIndex >= galleryImages.length) {
                console.error('Invalid image index:', currentImageIndex);
                return;
            }
            const modalElement = document.getElementById('galleryModal');
            if (!modalElement) {
                console.error('Gallery modal not found');
                return;
            }
            
            // Resmi önce güncelle
            updateGalleryModal();
            
            // Sonra modal'ı aç
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        }

        function updateGalleryModal() {
            if (!galleryImages || galleryImages.length === 0) {
                return;
            }
            if (currentImageIndex >= 0 && currentImageIndex < galleryImages.length) {
                const imageUrl = galleryImages[currentImageIndex];
                console.log('Setting image URL:', imageUrl);
                
                const modalImage = document.getElementById('galleryModalImage');
                if (modalImage) {
                    modalImage.src = imageUrl;
                    modalImage.onerror = function() {
                        console.error('Failed to load image:', imageUrl);
                    };
                }
                
                const counter = document.getElementById('galleryImageCounter');
                if (counter) {
                    counter.textContent = (currentImageIndex + 1) + ' / ' + galleryImages.length;
                }
                
                // Prev/Next butonlarını kontrol et
                const prevBtn = document.getElementById('prevImage');
                const nextBtn = document.getElementById('nextImage');
                if (prevBtn) prevBtn.style.display = galleryImages.length > 1 ? 'block' : 'none';
                if (nextBtn) nextBtn.style.display = galleryImages.length > 1 ? 'block' : 'none';
            }
        }

        function changeGalleryImage(direction) {
            if (!galleryImages || galleryImages.length === 0) {
                return;
            }
            currentImageIndex += direction;
            
            if (currentImageIndex < 0) {
                currentImageIndex = galleryImages.length - 1;
            } else if (currentImageIndex >= galleryImages.length) {
                currentImageIndex = 0;
            }
            
            updateGalleryModal();
        }

        // Event listener'ları ekle
        document.addEventListener('DOMContentLoaded', function() {
            // Gallery item'lara click event ekle
            const galleryItems = document.querySelectorAll('.gallery-item');
            galleryItems.forEach(function(item) {
                item.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-image-index'));
                    if (!isNaN(index)) {
                        openGalleryModal(index);
                    }
                });
            });

            // Klavye ile navigasyon
            document.addEventListener('keydown', function(e) {
                const modal = document.getElementById('galleryModal');
                if (modal && modal.classList.contains('show')) {
                    if (e.key === 'ArrowLeft') {
                        e.preventDefault();
                        changeGalleryImage(-1);
                    } else if (e.key === 'ArrowRight') {
                        e.preventDefault();
                        changeGalleryImage(1);
                    } else if (e.key === 'Escape') {
                        const bsModal = bootstrap.Modal.getInstance(modal);
                        if (bsModal) {
                            bsModal.hide();
                        }
                    }
                }
            });

            // Modal açıldığında resmi güncelle
            const galleryModal = document.getElementById('galleryModal');
            if (galleryModal) {
                galleryModal.addEventListener('shown.bs.modal', function() {
                    updateGalleryModal();
                });
            }

            // Prev/Next butonları
            const prevBtn = document.getElementById('prevImage');
            const nextBtn = document.getElementById('nextImage');
            if (prevBtn) {
                prevBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    changeGalleryImage(-1);
                });
            }
            if (nextBtn) {
                nextBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    changeGalleryImage(1);
                });
            }
        });

        // Global fonksiyonlar (onclick için)
        window.openGalleryModal = openGalleryModal;
        window.changeGalleryImage = changeGalleryImage;
    })();
</script>
@endsection

