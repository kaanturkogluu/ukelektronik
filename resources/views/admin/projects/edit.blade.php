@extends('admin.layouts.app')

@section('title', 'Proje Düzenle')
@section('page-title', 'Proje Düzenle')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Proje Düzenle</h5>
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
        
        <form action="{{ route('admin.projects.update', $project) }}" method="POST" enctype="multipart/form-data" id="projectForm">
            @csrf
            @method('PUT')
            @php
                $titleTranslations = $project->getTitleTranslations();
                $descTranslations = $project->getDescriptionTranslations();
            @endphp
            
            <!-- Proje Başlığı - Çoklu Dil -->
            <div class="mb-3">
                <label class="form-label">Proje Başlığı * <small class="text-muted">(Ana sayfada ve detay sayfasında görünecek başlık)</small></label>
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
                        <input type="text" class="form-control" name="title_tr" value="{{ old('title_tr', $titleTranslations['tr'] ?? '') }}" required>
                    </div>
                    <div class="tab-pane fade" id="title_en" role="tabpanel">
                        <input type="text" class="form-control" name="title_en" value="{{ old('title_en', $titleTranslations['en'] ?? '') }}" required>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Kategori * <small class="text-muted">(Projenin kategorisi)</small></label>
                <select class="form-control" name="category_id" required>
                    <option value="">Kategori Seçin</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $project->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Ana Görsel -->
            <div class="mb-3">
                <label class="form-label">Ana Görsel * <small class="text-muted">(Proje listesinde ve detay sayfasında görünecek ana resim)</small></label>
                @if($project->image)
                <div class="mb-2">
                    <p class="text-muted small">Mevcut Görsel:</p>
                    <img src="{{ str_starts_with($project->image, 'http://') || str_starts_with($project->image, 'https://') || str_starts_with($project->image, '/') ? $project->image : asset($project->image) }}" 
                         alt="Mevcut görsel" 
                         style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; border-radius: 4px; margin-bottom: 10px;">
                </div>
                @endif
                @php
                    $isImageUrl = $project->image && (str_starts_with($project->image, 'http://') || str_starts_with($project->image, 'https://') || str_starts_with($project->image, '/'));
                    $imageType = old('image_type', $isImageUrl ? 'url' : 'file');
                @endphp
                <div class="mb-2">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="image_type" id="image_type_url" value="url" {{ $imageType == 'url' ? 'checked' : '' }} onchange="toggleImageInput()">
                        <label class="form-check-label" for="image_type_url">URL ile Yükle</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="image_type" id="image_type_file" value="file" {{ $imageType == 'file' ? 'checked' : '' }} onchange="toggleImageInput()">
                        <label class="form-check-label" for="image_type_file">Bilgisayardan Yükle</label>
                    </div>
                </div>
                <div id="image_url_container" style="display: {{ $imageType == 'url' ? 'block' : 'none' }};">
                    <input type="text" class="form-control" name="image" id="image_url" value="{{ old('image', $project->image) }}" placeholder="https://example.com/image.jpg veya /path/to/image.jpg">
                </div>
                <div id="image_file_container" style="display: {{ $imageType == 'file' ? 'block' : 'none' }};">
                    <input type="file" class="form-control" name="image_file" id="image_file" accept="image/*">
                    <small class="text-muted">Yeni dosya seçmezseniz mevcut görsel korunur.</small>
                </div>
            </div>
            
            <!-- Proje Açıklaması - Çoklu Dil -->
            <div class="mb-3">
                <label class="form-label">Proje Açıklaması * <small class="text-muted">(Projenin detaylı açıklaması)</small></label>
                <ul class="nav nav-tabs mb-2" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#desc_tr" type="button" role="tab">Türkçe</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#desc_en" type="button" role="tab">English</button>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="desc_tr" role="tabpanel">
                        <textarea class="form-control" name="description_tr" rows="5" required>{{ old('description_tr', $descTranslations['tr'] ?? '') }}</textarea>
                    </div>
                    <div class="tab-pane fade" id="desc_en" role="tabpanel">
                        <textarea class="form-control" name="description_en" rows="5" required>{{ old('description_en', $descTranslations['en'] ?? '') }}</textarea>
                    </div>
                </div>
            </div>
            
            <!-- Proje Detayları -->
            <div class="mb-3">
                <label class="form-label">Proje Detayları <small class="text-muted">(Örn: Kapasite, Tarih, Lokasyon gibi bilgiler)</small></label>
                <div id="details_container">
                    @php
                        $details = is_array($project->details) ? $project->details : (is_string($project->details) ? json_decode($project->details, true) : []);
                        if (!is_array($details)) $details = [];
                    @endphp
                    @if(count($details) > 0)
                        @foreach($details as $key => $value)
                        <div class="row mb-2 detail-item">
                            <div class="col-md-5">
                                <input type="text" class="form-control detail-key" value="{{ $key }}" placeholder="Özellik Adı (örn: Kapasite)">
                            </div>
                            <div class="col-md-5">
                                <input type="text" class="form-control detail-value" value="{{ $value }}" placeholder="Değer (örn: 500 kW)">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger btn-sm remove-detail">Sil</button>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="row mb-2 detail-item">
                            <div class="col-md-5">
                                <input type="text" class="form-control detail-key" placeholder="Özellik Adı (örn: Kapasite)">
                            </div>
                            <div class="col-md-5">
                                <input type="text" class="form-control detail-value" placeholder="Değer (örn: 500 kW)">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger btn-sm remove-detail" style="display: none;">Sil</button>
                            </div>
                        </div>
                    @endif
                </div>
                <button type="button" class="btn btn-success btn-sm mt-2" onclick="addDetailRow()">+ Detay Ekle</button>
            </div>
            
            <!-- Özellikler -->
            <div class="mb-3">
                <label class="form-label">Proje Özellikleri <small class="text-muted">(Projenin öne çıkan özellikleri, her satıra bir özellik)</small></label>
                <div id="features_container">
                    @php
                        $features = is_array($project->features) ? $project->features : (is_string($project->features) ? json_decode($project->features, true) : []);
                        if (!is_array($features)) $features = [];
                    @endphp
                    @if(count($features) > 0)
                        @foreach($features as $feature)
                        <div class="mb-2 feature-item">
                            <div class="input-group">
                                <input type="text" class="form-control feature-input" value="{{ $feature }}" placeholder="Özellik yazın (örn: Yüksek verimlilik)">
                                <button type="button" class="btn btn-danger remove-feature">Sil</button>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="mb-2 feature-item">
                            <div class="input-group">
                                <input type="text" class="form-control feature-input" placeholder="Özellik yazın (örn: Yüksek verimlilik)">
                                <button type="button" class="btn btn-danger remove-feature" style="display: none;">Sil</button>
                            </div>
                        </div>
                    @endif
                </div>
                <button type="button" class="btn btn-success btn-sm mt-2" onclick="addFeatureRow()">+ Özellik Ekle</button>
            </div>
            
            <!-- Galeri -->
            <div class="mb-3">
                <label class="form-label">Galeri Görselleri <small class="text-muted">(Proje detay sayfasında gösterilecek ek görseller)</small></label>
                <div id="gallery_container">
                    @php
                        $gallery = is_array($project->gallery) ? $project->gallery : (is_string($project->gallery) ? json_decode($project->gallery, true) : []);
                        if (!is_array($gallery)) $gallery = [];
                    @endphp
                    @if(count($gallery) > 0)
                        @foreach($gallery as $index => $galleryImage)
                        @php
                            $isGalleryUrl = $galleryImage && (str_starts_with($galleryImage, 'http://') || str_starts_with($galleryImage, 'https://') || str_starts_with($galleryImage, '/'));
                        @endphp
                        <div class="mb-3 gallery-item border p-3 rounded" data-index="{{ $index }}">
                            @if($galleryImage)
                            <div class="mb-2">
                                <p class="text-muted small">Mevcut Görsel:</p>
                                <img src="{{ $isGalleryUrl ? $galleryImage : asset($galleryImage) }}" 
                                     alt="Galeri görseli" 
                                     style="max-width: 150px; max-height: 150px; border: 1px solid #ddd; border-radius: 4px; margin-bottom: 10px;">
                            </div>
                            @endif
                            <div class="mb-2">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input gallery-type" type="radio" name="gallery_type_{{ $index }}" value="url" {{ $isGalleryUrl ? 'checked' : '' }}>
                                    <label class="form-check-label">URL</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input gallery-type" type="radio" name="gallery_type_{{ $index }}" value="file" {{ !$isGalleryUrl ? 'checked' : '' }}>
                                    <label class="form-check-label">Dosya</label>
                                </div>
                            </div>
                            <div class="gallery-url-container" style="display: {{ $isGalleryUrl ? 'block' : 'none' }};">
                                <input type="text" class="form-control gallery-url" name="gallery_urls[]" value="{{ $galleryImage }}" placeholder="Görsel URL'si">
                            </div>
                            <div class="gallery-file-container" style="display: {{ !$isGalleryUrl ? 'block' : 'none' }};">
                                <input type="file" class="form-control gallery-file" name="gallery_files[]" accept="image/*">
                                <input type="hidden" class="gallery-existing-url" name="gallery_urls[]" value="{{ $galleryImage }}">
                                <small class="text-muted">Yeni dosya seçmezseniz mevcut görsel korunur.</small>
                            </div>
                            <button type="button" class="btn btn-danger btn-sm mt-2 remove-gallery">Görseli Sil</button>
                        </div>
                        @endforeach
                    @else
                        <div class="mb-3 gallery-item border p-3 rounded" data-index="0">
                            <div class="mb-2">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input gallery-type" type="radio" name="gallery_type_0" value="url" checked>
                                    <label class="form-check-label">URL</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input gallery-type" type="radio" name="gallery_type_0" value="file">
                                    <label class="form-check-label">Dosya</label>
                                </div>
                            </div>
                            <div class="gallery-url-container">
                                <input type="text" class="form-control gallery-url" name="gallery_urls[]" placeholder="Görsel URL'si">
                            </div>
                            <div class="gallery-file-container" style="display: none;">
                                <input type="file" class="form-control gallery-file" name="gallery_files[]" accept="image/*">
                            </div>
                            <button type="button" class="btn btn-danger btn-sm mt-2 remove-gallery" style="display: none;">Görseli Sil</button>
                        </div>
                    @endif
                </div>
                <button type="button" class="btn btn-success btn-sm mt-2" onclick="addGalleryRow()">+ Galeri Görseli Ekle</button>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Sıralama <small class="text-muted">(Listede görünme sırası, küçük sayı önce görünür)</small></label>
                    <input type="number" class="form-control" name="sort_order" value="{{ old('sort_order', $project->sort_order) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-check mt-4">
                        <input type="checkbox" class="form-check-input" name="is_active" id="is_active" {{ old('is_active', $project->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Aktif <small class="text-muted">(Aktif olmayan projeler sitede görünmez)</small></label>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Güncelle</button>
                <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">İptal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
@php
    $detailsCount = 0;
    if (is_array($project->details)) {
        $detailsCount = count($project->details);
    } elseif (is_string($project->details)) {
        $decoded = json_decode($project->details, true);
        $detailsCount = is_array($decoded) ? count($decoded) : 0;
    }
    
    $featuresCount = 0;
    if (is_array($project->features)) {
        $featuresCount = count($project->features);
    } elseif (is_string($project->features)) {
        $decoded = json_decode($project->features, true);
        $featuresCount = is_array($decoded) ? count($decoded) : 0;
    }
    
    $galleryCount = 0;
    if (is_array($project->gallery)) {
        $galleryCount = count($project->gallery);
    } elseif (is_string($project->gallery)) {
        $decoded = json_decode($project->gallery, true);
        $galleryCount = is_array($decoded) ? count($decoded) : 0;
    }
@endphp
let detailCounter = {{ $detailsCount > 0 ? $detailsCount : 1 }};
let featureCounter = {{ $featuresCount > 0 ? $featuresCount : 1 }};
let galleryCounter = {{ $galleryCount > 0 ? $galleryCount : 1 }};

function toggleImageInput() {
    const urlRadio = document.getElementById('image_type_url');
    const fileRadio = document.getElementById('image_type_file');
    const urlContainer = document.getElementById('image_url_container');
    const fileContainer = document.getElementById('image_file_container');
    const urlInput = document.getElementById('image_url');
    const fileInput = document.getElementById('image_file');
    
    if (urlRadio.checked) {
        urlContainer.style.display = 'block';
        fileContainer.style.display = 'none';
        fileInput.value = '';
        urlInput.required = true;
        fileInput.required = false;
    } else {
        urlContainer.style.display = 'none';
        fileContainer.style.display = 'block';
        urlInput.value = '';
        urlInput.required = false;
        fileInput.required = false; // Dosya zorunlu değil, mevcut görsel korunur
    }
}

// Initialize image input toggle on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleImageInput();
});

function updateDetailRemoveButtons() {
    const items = document.querySelectorAll('.detail-item');
    items.forEach(item => {
        const removeBtn = item.querySelector('.remove-detail');
        if (removeBtn) {
            removeBtn.style.display = items.length > 1 ? 'block' : 'none';
        }
    });
}

function addDetailRow() {
    const container = document.getElementById('details_container');
    const newRow = document.createElement('div');
    newRow.className = 'row mb-2 detail-item';
    newRow.innerHTML = `
        <div class="col-md-5">
            <input type="text" class="form-control detail-key" placeholder="Özellik Adı (örn: Kapasite)">
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control detail-value" placeholder="Değer (örn: 500 kW)">
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger btn-sm remove-detail">Sil</button>
        </div>
    `;
    container.appendChild(newRow);
    
    newRow.querySelector('.remove-detail').addEventListener('click', function() {
        newRow.remove();
        updateDetailRemoveButtons();
    });
    
    updateDetailRemoveButtons();
}

function updateFeatureRemoveButtons() {
    const items = document.querySelectorAll('.feature-item');
    items.forEach(item => {
        const removeBtn = item.querySelector('.remove-feature');
        if (removeBtn) {
            removeBtn.style.display = items.length > 1 ? 'block' : 'none';
        }
    });
}

function addFeatureRow() {
    const container = document.getElementById('features_container');
    const newRow = document.createElement('div');
    newRow.className = 'mb-2 feature-item';
    newRow.innerHTML = `
        <div class="input-group">
            <input type="text" class="form-control feature-input" placeholder="Özellik yazın (örn: Yüksek verimlilik)">
            <button type="button" class="btn btn-danger remove-feature">Sil</button>
        </div>
    `;
    container.appendChild(newRow);
    
    newRow.querySelector('.remove-feature').addEventListener('click', function() {
        newRow.remove();
        updateFeatureRemoveButtons();
    });
    
    updateFeatureRemoveButtons();
}

function addGalleryRow() {
    const container = document.getElementById('gallery_container');
    const newRow = document.createElement('div');
    newRow.className = 'mb-3 gallery-item border p-3 rounded';
    newRow.setAttribute('data-index', galleryCounter);
    newRow.innerHTML = `
        <div class="mb-2">
            <div class="form-check form-check-inline">
                <input class="form-check-input gallery-type" type="radio" name="gallery_type_${galleryCounter}" value="url" checked>
                <label class="form-check-label">URL</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input gallery-type" type="radio" name="gallery_type_${galleryCounter}" value="file">
                <label class="form-check-label">Dosya</label>
            </div>
        </div>
        <div class="gallery-url-container">
            <input type="text" class="form-control gallery-url" name="gallery_urls[]" placeholder="Görsel URL'si">
        </div>
        <div class="gallery-file-container" style="display: none;">
            <input type="file" class="form-control gallery-file" name="gallery_files[]" accept="image/*">
        </div>
        <button type="button" class="btn btn-danger btn-sm mt-2 remove-gallery">Görseli Sil</button>
    `;
    container.appendChild(newRow);
    galleryCounter++;
    
    // Toggle functionality
    newRow.querySelectorAll('.gallery-type').forEach(radio => {
        radio.addEventListener('change', function() {
            const item = this.closest('.gallery-item');
            const urlContainer = item.querySelector('.gallery-url-container');
            const fileContainer = item.querySelector('.gallery-file-container');
            const urlInput = item.querySelector('.gallery-url');
            const fileInput = item.querySelector('.gallery-file');
            const hiddenInput = item.querySelector('.gallery-existing-url');
            
            if (this.value === 'url') {
                urlContainer.style.display = 'block';
                fileContainer.style.display = 'none';
                fileInput.value = '';
                fileInput.removeAttribute('name');
                if (hiddenInput) hiddenInput.removeAttribute('name');
                urlInput.setAttribute('name', 'gallery_urls[]');
            } else {
                urlContainer.style.display = 'none';
                fileContainer.style.display = 'block';
                urlInput.value = '';
                urlInput.removeAttribute('name');
                fileInput.setAttribute('name', 'gallery_files[]');
                if (hiddenInput) hiddenInput.setAttribute('name', 'gallery_urls[]');
            }
        });
        
        // Handle file input change
        const fileInput = newRow.querySelector('.gallery-file');
        const hiddenInput = newRow.querySelector('.gallery-existing-url');
        if (fileInput && hiddenInput) {
            fileInput.addEventListener('change', function() {
                if (this.files && this.files.length > 0) {
                    hiddenInput.removeAttribute('name');
                } else {
                    hiddenInput.setAttribute('name', 'gallery_urls[]');
                }
            });
        }
    });
    
    newRow.querySelector('.remove-gallery').addEventListener('click', function() {
        newRow.remove();
        updateGalleryRemoveButtons();
    });
    
    updateGalleryRemoveButtons();
}

function updateGalleryRemoveButtons() {
    const items = document.querySelectorAll('.gallery-item');
    items.forEach(item => {
        const removeBtn = item.querySelector('.remove-gallery');
        if (removeBtn) {
            removeBtn.style.display = items.length > 1 ? 'block' : 'none';
        }
    });
}

// Remove buttons for initial items
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.remove-detail').forEach(btn => {
        btn.addEventListener('click', function() {
            this.closest('.detail-item').remove();
            updateDetailRemoveButtons();
        });
    });
    
    document.querySelectorAll('.remove-feature').forEach(btn => {
        btn.addEventListener('click', function() {
            this.closest('.feature-item').remove();
            updateFeatureRemoveButtons();
        });
    });
    
    document.querySelectorAll('.remove-gallery').forEach(btn => {
        btn.addEventListener('click', function() {
            this.closest('.gallery-item').remove();
            updateGalleryRemoveButtons();
        });
    });
    
    // Initial update to hide buttons if only one item exists
    updateDetailRemoveButtons();
    updateFeatureRemoveButtons();
    updateGalleryRemoveButtons();
    
    // Gallery type toggle for initial items
    document.querySelectorAll('.gallery-type').forEach(radio => {
        radio.addEventListener('change', function() {
            const item = this.closest('.gallery-item');
            const urlContainer = item.querySelector('.gallery-url-container');
            const fileContainer = item.querySelector('.gallery-file-container');
            const urlInput = item.querySelector('.gallery-url');
            const fileInput = item.querySelector('.gallery-file');
            const hiddenInput = item.querySelector('.gallery-existing-url');
            
            if (this.value === 'url') {
                urlContainer.style.display = 'block';
                fileContainer.style.display = 'none';
                fileInput.value = '';
                fileInput.removeAttribute('name');
                if (hiddenInput) hiddenInput.removeAttribute('name');
                urlInput.setAttribute('name', 'gallery_urls[]');
            } else {
                urlContainer.style.display = 'none';
                fileContainer.style.display = 'block';
                urlInput.value = '';
                urlInput.removeAttribute('name');
                fileInput.setAttribute('name', 'gallery_files[]');
                if (hiddenInput) hiddenInput.setAttribute('name', 'gallery_urls[]');
            }
        });
        
        // Also handle file input change to disable hidden input when file is selected
        const item = radio.closest('.gallery-item');
        const fileInput = item ? item.querySelector('.gallery-file') : null;
        const hiddenInput = item ? item.querySelector('.gallery-existing-url') : null;
        if (fileInput && hiddenInput) {
            fileInput.addEventListener('change', function() {
                if (this.files && this.files.length > 0) {
                    // File selected, remove hidden input name so it won't be sent
                    hiddenInput.removeAttribute('name');
                } else {
                    // No file, restore hidden input name to keep existing image
                    hiddenInput.setAttribute('name', 'gallery_urls[]');
                }
            });
        }
    });
    
    // Form submit handler
    const projectForm = document.getElementById('projectForm');
    if (projectForm) {
        projectForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default form submission
            
            const form = this;
            
            // Handle image input based on selected type
            const imageType = document.querySelector('input[name="image_type"]:checked');
            if (imageType) {
                if (imageType.value === 'url') {
                    const fileInput = document.getElementById('image_file');
                    if (fileInput) {
                        fileInput.removeAttribute('name');
                    }
                } else {
                    const urlInput = document.getElementById('image_url');
                    if (urlInput) {
                        urlInput.removeAttribute('name');
                    }
                }
            }
            
            // Collect details
            const details = {};
            document.querySelectorAll('.detail-item').forEach(item => {
                const keyInput = item.querySelector('.detail-key');
                const valueInput = item.querySelector('.detail-value');
                if (keyInput && valueInput) {
                    const key = keyInput.value.trim();
                    const value = valueInput.value.trim();
                    if (key && value) {
                        details[key] = value;
                    }
                }
            });
            
            // Collect features
            const features = [];
            document.querySelectorAll('.feature-input').forEach(input => {
                const value = input.value.trim();
                if (value) {
                    features.push(value);
                }
            });
            
            // Create FormData from form
            const formData = new FormData(form);
            
            // Add details and features to FormData
            if (Object.keys(details).length > 0) {
                formData.set('details', JSON.stringify(details));
            } else {
                formData.set('details', '');
            }
            
            if (features.length > 0) {
                formData.set('features', JSON.stringify(features));
            } else {
                formData.set('features', '');
            }
            
            // Submit using fetch
            fetch(form.action, {
                method: form.method,
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                } else {
                    return response.text().then(html => {
                        document.open();
                        document.write(html);
                        document.close();
                    });
                }
            }).catch(error => {
                console.error('Error:', error);
                // Fallback to normal form submission
                form.submit();
            });
        });
    }
});
</script>
@endsection
