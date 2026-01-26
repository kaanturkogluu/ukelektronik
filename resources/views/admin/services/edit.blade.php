@extends('admin.layouts.app')

@section('title', 'Hizmet Düzenle')
@section('page-title', 'Hizmet Düzenle')

@php
    $isStorageImage = !empty($service->image) && (str_starts_with($service->image, 'storage/') || str_starts_with($service->image, '/storage/'));
    $isUrlImage = !empty($service->image) && !$isStorageImage;
    $featureCounter = !empty($service->features) && is_array($service->features) ? count($service->features) : 0;
    $benefitCounter = !empty($service->benefits) && is_array($service->benefits) ? count($service->benefits) : 0;
@endphp

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Hizmet Düzenle</h5>
    </div>
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        
        <form action="{{ route('admin.services.update', $service) }}" method="POST" enctype="multipart/form-data" id="serviceForm">
            @csrf
            @method('PUT')
            
            @php
                $titleTranslations = $service->getTitleTranslations();
                $shortDescTranslations = $service->getShortDescriptionTranslations();
                $descTranslations = $service->getDescriptionTranslations();
            @endphp
            
            <!-- Başlık - Çoklu Dil -->
            <div class="mb-4">
                <label class="form-label fw-bold">
                    Başlık <span class="text-danger">*</span>
                    <small class="text-muted d-block fw-normal">→ Bu veri hizmet kartlarında ve detay sayfasında başlık olarak gösterilir</small>
                </label>
                <ul class="nav nav-tabs mb-2" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#title_tr" type="button" role="tab">Türkçe</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#title_en" type="button" role="tab">English</button>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="title_tr" role="tabpanel">
                        <input type="text" class="form-control" name="title_tr" value="{{ old('title_tr', $titleTranslations['tr'] ?? '') }}" required placeholder="Örn: Güneş Paneli Kurulumu">
                    </div>
                    <div class="tab-pane fade" id="title_en" role="tabpanel">
                        <input type="text" class="form-control" name="title_en" value="{{ old('title_en', $titleTranslations['en'] ?? '') }}" required placeholder="Ex: Solar Panel Installation">
                    </div>
                </div>
            </div>

            <!-- İkon -->
            <div class="mb-4">
                <label class="form-label fw-bold">
                    İkon (Font Awesome)
                    <small class="text-muted d-block fw-normal">→ Bu veri hizmet kartlarında ve detay sayfasında ikon olarak gösterilir</small>
                </label>
                <div class="input-group">
                    <input type="text" class="form-control" id="iconInput" name="icon" value="{{ old('icon', $service->icon) }}" placeholder="fa-solar-panel" readonly>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#iconModal">
                        <i class="fa fa-search"></i> İkon Seç
                    </button>
                </div>
                <small class="text-muted">Seçilen ikon: <span id="selectedIconPreview"></span></small>
            </div>

            <!-- Görsel Seçimi -->
            <div class="mb-4">
                <label class="form-label fw-bold">
                    Hizmet Görseli
                    <small class="text-muted d-block fw-normal">→ Bu veri hizmet kartlarında ve detay sayfasında görsel olarak gösterilir</small>
                </label>
                <div class="card border-primary">
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="image_source" id="imageSourceUrl" value="url" {{ $isUrlImage || empty($service->image) ? 'checked' : '' }} onchange="toggleImageSource()">
                                <label class="form-check-label" for="imageSourceUrl">URL ile Yükle</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="image_source" id="imageSourceFile" value="file" {{ $isStorageImage ? 'checked' : '' }} onchange="toggleImageSource()">
                                <label class="form-check-label" for="imageSourceFile">Bilgisayardan Yükle</label>
                            </div>
                        </div>
                        
                        <!-- URL Input -->
                        <div id="urlInputContainer" style="display: {{ $isUrlImage || empty($service->image) ? 'block' : 'none' }};">
                            <label class="form-label">Görsel URL'si</label>
                            <input type="text" class="form-control" id="imageUrl" name="image" value="{{ old('image', $isUrlImage ? $service->image : '') }}" placeholder="https://example.com/image.jpg veya /img/service.jpg">
                            <div id="imagePreview" class="mt-2" style="display: {{ $isUrlImage ? 'block' : 'none' }};">
                                @if($isUrlImage)
                                    <img id="previewImg" src="{{ str_starts_with($service->image, 'http://') || str_starts_with($service->image, 'https://') ? $service->image : asset($service->image) }}" alt="Önizleme" style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; border-radius: 4px;">
                                @else
                                    <img id="previewImg" src="" alt="Önizleme" style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; border-radius: 4px;">
                                @endif
                            </div>
                        </div>
                        
                        <!-- File Input -->
                        <div id="fileInputContainer" style="display: {{ $isStorageImage ? 'block' : 'none' }};">
                            <label class="form-label">Görsel Dosyası</label>
                            <input type="file" class="form-control" id="imageFile" name="image_file" accept="image/*" onchange="previewImageFile(this)">
                            @if($isStorageImage)
                            <div class="mt-2">
                                <small class="text-muted">Mevcut görsel:</small><br>
                                <img src="{{ asset($service->image) }}" alt="Mevcut görsel" style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; border-radius: 4px;">
                                <p class="text-muted small mt-1">Yeni dosya seçerseniz mevcut görsel değiştirilecektir.</p>
                            </div>
                            @endif
                            <div id="filePreview" class="mt-2" style="display: none;">
                                <img id="previewFileImg" src="" alt="Önizleme" style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; border-radius: 4px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kısa Açıklama -->
            <div class="mb-4">
                <label class="form-label fw-bold">
                    Kısa Açıklama <span class="text-danger">*</span>
                    <small class="text-muted d-block fw-normal">→ Bu veri hizmet kartlarında kısa açıklama olarak gösterilir</small>
                </label>
                <textarea class="form-control" name="short_description" rows="3" required placeholder="Hizmetin kısa tanımı...">{{ old('short_description', $service->short_description) }}</textarea>
            </div>

            <!-- Detaylı Açıklama -->
            <div class="mb-4">
                <label class="form-label fw-bold">
                    Detaylı Açıklama <span class="text-danger">*</span>
                    <small class="text-muted d-block fw-normal">→ Bu veri hizmet detay sayfasında ana açıklama olarak gösterilir</small>
                </label>
                <textarea class="form-control" name="description" rows="6" required placeholder="Hizmetin detaylı açıklaması...">{{ old('description', $service->description) }}</textarea>
            </div>
            
            <!-- Özellikler -->
            <div class="mb-4">
                <label class="form-label fw-bold">
                    <span class="badge bg-primary me-2">ÖZELLİKLER</span>
                    Özellikler
                    <small class="text-muted d-block fw-normal">→ Bu veriler hizmet detay sayfasında özellikler listesi olarak gösterilir (JSON formatında saklanır)</small>
                </label>
                <div class="card border-primary">
                    <div class="card-body">
                        <div id="featuresContainer">
                            @if(!empty($service->features) && is_array($service->features))
                                @foreach($service->features as $index => $feature)
                                <div class="input-group mb-2" id="feature-{{ $index + 1 }}">
                                    <input type="text" class="form-control" name="features[]" value="{{ $feature }}" placeholder="Özellik {{ $index + 1 }}">
                                    <button type="button" class="btn btn-danger" onclick="removeFeatureField('feature-{{ $index + 1 }}')">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                                @endforeach
                                @php
                                    $featureCounter = count($service->features);
                                @endphp
                            @else
                                @php
                                    $featureCounter = 0;
                                @endphp
                            @endif
                        </div>
                        <button type="button" class="btn btn-primary btn-sm mt-3" onclick="addFeatureField()">
                            <i class="fa fa-plus"></i> Yeni Özellik Ekle
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Avantajlar -->
            <div class="mb-4">
                <label class="form-label fw-bold">
                    <span class="badge bg-success me-2">AVANTAJLAR</span>
                    Avantajlar
                    <small class="text-muted d-block fw-normal">→ Bu veriler hizmet detay sayfasında avantajlar listesi olarak gösterilir (JSON formatında saklanır)</small>
                </label>
                <div class="card border-success">
                    <div class="card-body">
                        <div id="benefitsContainer">
                            @if(!empty($service->benefits) && is_array($service->benefits))
                                @foreach($service->benefits as $index => $benefit)
                                <div class="input-group mb-2" id="benefit-{{ $index + 1 }}">
                                    <input type="text" class="form-control" name="benefits[]" value="{{ $benefit }}" placeholder="Avantaj {{ $index + 1 }}">
                                    <button type="button" class="btn btn-danger" onclick="removeBenefitField('benefit-{{ $index + 1 }}')">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                                @endforeach
                                @php
                                    $benefitCounter = count($service->benefits);
                                @endphp
                            @else
                                @php
                                    $benefitCounter = 0;
                                @endphp
                            @endif
                        </div>
                        <button type="button" class="btn btn-success btn-sm mt-3" onclick="addBenefitField()">
                            <i class="fa fa-plus"></i> Yeni Avantaj Ekle
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Sıralama ve Durum -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold">
                        Sıralama
                        <small class="text-muted d-block fw-normal">→ Bu veri hizmetlerin listelenme sırasını belirler (küçük sayılar önce gösterilir)</small>
                    </label>
                    <input type="number" class="form-control" name="sort_order" value="{{ old('sort_order', $service->sort_order) }}" min="0">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">
                        Durum
                        <small class="text-muted d-block fw-normal">→ Bu veri hizmetin aktif/pasif durumunu belirler</small>
                    </label>
                    <div class="form-check mt-2">
                        <input type="checkbox" class="form-check-input" name="is_active" id="is_active" {{ old('is_active', $service->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Aktif (Hizmet görünür olsun)</label>
                    </div>
                </div>
            </div>

            <!-- Butonlar -->
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save me-2"></i>Güncelle
                </button>
                <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">
                    <i class="fa fa-times me-2"></i>İptal
                </a>
            </div>
        </form>
    </div>
</div>

<!-- İkon Seçici Modal -->
<div class="modal fade" id="iconModal" tabindex="-1" aria-labelledby="iconModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="iconModalLabel">Font Awesome İkon Seç</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" class="form-control" id="iconSearch" placeholder="İkon ara...">
                </div>
                <div class="row g-2" id="iconGrid" style="max-height: 400px; overflow-y: auto;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Font Awesome ikonları listesi
const fontAwesomeIcons = [
    'fa-solar-panel', 'fa-bolt', 'fa-plug', 'fa-lightbulb', 'fa-battery-full',
    'fa-wind', 'fa-water', 'fa-fire', 'fa-cog', 'fa-tools', 'fa-wrench',
    'fa-screwdriver', 'fa-hammer', 'fa-home', 'fa-building', 'fa-industry',
    'fa-warehouse', 'fa-store', 'fa-shop', 'fa-chart-line', 'fa-chart-bar',
    'fa-chart-pie', 'fa-calculator', 'fa-microchip', 'fa-server', 'fa-network-wired',
    'fa-satellite', 'fa-satellite-dish', 'fa-signal', 'fa-wifi', 'fa-broadcast-tower',
    'fa-tower-broadcast', 'fa-power-off', 'fa-toggle-on', 'fa-toggle-off',
    'fa-sliders-h', 'fa-sliders', 'fa-adjust', 'fa-sun', 'fa-moon',
    'fa-star', 'fa-check', 'fa-check-circle', 'fa-times', 'fa-times-circle',
    'fa-info-circle', 'fa-question-circle', 'fa-shield-alt', 'fa-shield',
    'fa-lock', 'fa-unlock', 'fa-key', 'fa-user', 'fa-users', 'fa-user-tie',
    'fa-handshake', 'fa-certificate', 'fa-award', 'fa-medal', 'fa-trophy',
    'fa-thumbs-up', 'fa-heart', 'fa-comments', 'fa-envelope', 'fa-phone',
    'fa-mobile-alt', 'fa-laptop', 'fa-desktop', 'fa-camera', 'fa-image',
    'fa-file', 'fa-folder', 'fa-box', 'fa-truck', 'fa-globe', 'fa-map-marker-alt',
    'fa-calendar', 'fa-clock', 'fa-history', 'fa-sync', 'fa-refresh'
];

let featureCounter = {{ $featureCounter }};
let benefitCounter = {{ $benefitCounter }};

// Görsel kaynağı değiştirme
function toggleImageSource() {
    const urlSource = document.getElementById('imageSourceUrl').checked;
    const urlContainer = document.getElementById('urlInputContainer');
    const fileContainer = document.getElementById('fileInputContainer');
    
    if (urlSource) {
        urlContainer.style.display = 'block';
        fileContainer.style.display = 'none';
        document.getElementById('imageFile').value = '';
        document.getElementById('filePreview').style.display = 'none';
    } else {
        urlContainer.style.display = 'none';
        fileContainer.style.display = 'block';
        document.getElementById('imageUrl').value = '';
        document.getElementById('imagePreview').style.display = 'none';
    }
}

// Görsel dosya önizleme
function previewImageFile(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewFileImg').src = e.target.result;
            document.getElementById('filePreview').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Görsel URL önizleme
document.getElementById('imageUrl')?.addEventListener('input', function(e) {
    const url = e.target.value;
    if (url) {
        let imageSrc = url;
        if (!url.startsWith('http://') && !url.startsWith('https://')) {
            if (url.startsWith('/')) {
                imageSrc = url;
            } else {
                imageSrc = '/' + url;
            }
        }
        document.getElementById('previewImg').src = imageSrc;
        document.getElementById('imagePreview').style.display = 'block';
    } else {
        document.getElementById('imagePreview').style.display = 'none';
    }
});

// Özellik alanı ekle
function addFeatureField() {
    featureCounter++;
    const container = document.getElementById('featuresContainer');
    const div = document.createElement('div');
    div.className = 'input-group mb-2';
    div.id = 'feature-' + featureCounter;
    div.innerHTML = `
        <input type="text" class="form-control" name="features[]" placeholder="Özellik ${featureCounter}">
        <button type="button" class="btn btn-danger" onclick="removeFeatureField('feature-${featureCounter}')">
            <i class="fa fa-times"></i>
        </button>
    `;
    container.appendChild(div);
}

// Özellik alanı sil
function removeFeatureField(id) {
    document.getElementById(id).remove();
}

// Avantaj alanı ekle
function addBenefitField() {
    benefitCounter++;
    const container = document.getElementById('benefitsContainer');
    const div = document.createElement('div');
    div.className = 'input-group mb-2';
    div.id = 'benefit-' + benefitCounter;
    div.innerHTML = `
        <input type="text" class="form-control" name="benefits[]" placeholder="Avantaj ${benefitCounter}">
        <button type="button" class="btn btn-danger" onclick="removeBenefitField('benefit-${benefitCounter}')">
            <i class="fa fa-times"></i>
        </button>
    `;
    container.appendChild(div);
}

// Avantaj alanı sil
function removeBenefitField(id) {
    document.getElementById(id).remove();
}

// İkon grid'ini oluştur
function populateIconGrid() {
    const iconGrid = document.getElementById('iconGrid');
    if (!iconGrid) return;
    
    iconGrid.innerHTML = '';
    
    fontAwesomeIcons.forEach(icon => {
        const col = document.createElement('div');
        col.className = 'col-2 col-md-1 text-center p-2';
        col.style.cursor = 'pointer';
        col.style.border = '1px solid #ddd';
        col.style.borderRadius = '4px';
        col.style.margin = '2px';
        col.style.transition = 'all 0.2s';
        col.innerHTML = `<i class="fa ${icon} fa-2x mb-1"></i><br><small style="font-size: 0.7rem;">${icon}</small>`;
        
        col.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f0f0f0';
            this.style.transform = 'scale(1.1)';
        });
        
        col.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
            this.style.transform = 'scale(1)';
        });
        
        col.addEventListener('click', function() {
            selectIcon(icon);
        });
        
        iconGrid.appendChild(col);
    });
}

// İkon arama
document.getElementById('iconSearch')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const iconGrid = document.getElementById('iconGrid');
    const cols = iconGrid.querySelectorAll('.col-2');
    
    cols.forEach(col => {
        const iconName = col.textContent.toLowerCase();
        if (iconName.includes(searchTerm)) {
            col.style.display = '';
        } else {
            col.style.display = 'none';
        }
    });
});

// İkon seç
function selectIcon(icon) {
    document.getElementById('iconInput').value = icon;
    document.getElementById('selectedIconPreview').innerHTML = `<i class="fa ${icon}"></i> ${icon}`;
    const modalElement = document.getElementById('iconModal');
    const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
    modal.hide();
}

// Form submit
document.getElementById('serviceForm').addEventListener('submit', function(e) {
    // Kullanılmayan resim kaynağını kaldır
    const imageSource = document.querySelector('input[name="image_source"]:checked').value;
    if (imageSource === 'file') {
        // URL input'unu kaldır
        const urlInput = document.getElementById('imageUrl');
        if (urlInput) {
            urlInput.removeAttribute('name');
        }
    } else {
        // File input'unu kaldır
        const fileInput = document.getElementById('imageFile');
        if (fileInput) {
            fileInput.removeAttribute('name');
        }
    }
    
    // Boş özellik ve avantaj alanlarını temizle
    const features = Array.from(document.querySelectorAll('input[name="features[]"]'))
        .map(input => input.value.trim())
        .filter(value => value !== '');
    
    const benefits = Array.from(document.querySelectorAll('input[name="benefits[]"]'))
        .map(input => input.value.trim())
        .filter(value => value !== '');
    
    // Hidden input olarak ekle
    const featuresInput = document.createElement('input');
    featuresInput.type = 'hidden';
    featuresInput.name = 'features';
    featuresInput.value = JSON.stringify(features);
    this.appendChild(featuresInput);
    
    const benefitsInput = document.createElement('input');
    benefitsInput.type = 'hidden';
    benefitsInput.name = 'benefits';
    benefitsInput.value = JSON.stringify(benefits);
    this.appendChild(benefitsInput);
});

// Modal açıldığında ikonları yükle
document.getElementById('iconModal')?.addEventListener('shown.bs.modal', function() {
    populateIconGrid();
});

// Sayfa yüklendiğinde
document.addEventListener('DOMContentLoaded', function() {
    const iconValue = document.getElementById('iconInput')?.value;
    if (iconValue) {
        document.getElementById('selectedIconPreview').innerHTML = `<i class="fa ${iconValue}"></i> ${iconValue}`;
    }
    
    const imageUrl = document.getElementById('imageUrl')?.value;
    if (imageUrl) {
        let imageSrc = imageUrl;
        if (!imageUrl.startsWith('http://') && !imageUrl.startsWith('https://')) {
            if (imageUrl.startsWith('/')) {
                imageSrc = imageUrl;
            } else {
                imageSrc = '/' + imageUrl;
            }
        }
        document.getElementById('previewImg').src = imageSrc;
        document.getElementById('imagePreview').style.display = 'block';
    }
});
</script>
@endsection
