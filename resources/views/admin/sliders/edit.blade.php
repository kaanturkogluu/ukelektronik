@extends('admin.layouts.app')

@section('title', 'Slider Düzenle')
@section('page-title', 'Slider Düzenle')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Slider Düzenle</h5>
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
        
        <form action="{{ route('admin.sliders.update', $slider) }}" method="POST" enctype="multipart/form-data" id="sliderForm">
            @csrf
            @method('PUT')
            
            <!-- Başlık - Çoklu Dil -->
            <div class="mb-4">
                <label class="form-label fw-bold">
                    Başlık <span class="text-danger">*</span>
                    <small class="text-muted d-block fw-normal">→ Slider'da görünecek ana başlık</small>
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
                        @php
                            $titleTranslations = $slider->getTitleTranslations();
                        @endphp
                        <input type="text" class="form-control" name="title_tr" value="{{ old('title_tr', $titleTranslations['tr'] ?? '') }}" placeholder="Örn: Güneş ve Yenilenebilir Enerjide Öncüler" data-required="true">
                    </div>
                    <div class="tab-pane fade" id="title_en" role="tabpanel">
                        <input type="text" class="form-control" name="title_en" value="{{ old('title_en', $titleTranslations['en'] ?? '') }}" placeholder="Ex: Leaders in Solar and Renewable Energy" data-required="true">
                    </div>
                </div>
            </div>

            <!-- Açıklama - Çoklu Dil -->
            <div class="mb-4">
                <label class="form-label fw-bold">
                    Açıklama <span class="text-danger">*</span>
                    <small class="text-muted d-block fw-normal">→ Slider'da başlığın altında görünecek açıklama metni</small>
                </label>
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
                        @php
                            $descTranslations = $slider->getDescriptionTranslations();
                        @endphp
                        <textarea class="form-control" name="description_tr" rows="3" placeholder="Örn: UK Elektronik olarak, sürdürülebilir enerji çözümleri ile geleceği bugünden inşa ediyoruz..." data-required="true">{{ old('description_tr', $descTranslations['tr'] ?? '') }}</textarea>
                    </div>
                    <div class="tab-pane fade" id="desc_en" role="tabpanel">
                        <textarea class="form-control" name="description_en" rows="3" placeholder="Ex: As UK Elektronik, we are building the future today with sustainable energy solutions..." data-required="true">{{ old('description_en', $descTranslations['en'] ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Görsel Seçimi -->
            <div class="mb-4">
                <label class="form-label fw-bold">
                    Slider Görseli <span class="text-danger">*</span>
                    <small class="text-muted d-block fw-normal">→ Slider'da arka planda görünecek görsel</small>
                </label>
                <div class="card border-primary">
                    <div class="card-body">
                        @if($slider->image)
                            <div class="mb-3">
                                <label class="form-label">Mevcut Görsel:</label>
                                <div>
                                    @if(str_starts_with($slider->image, 'http://') || str_starts_with($slider->image, 'https://'))
                                        <img src="{{ $slider->image }}" alt="Mevcut Görsel" style="max-width: 300px; max-height: 200px; border: 1px solid #ddd; border-radius: 4px;">
                                    @else
                                        <img src="{{ asset($slider->image) }}" alt="Mevcut Görsel" style="max-width: 300px; max-height: 200px; border: 1px solid #ddd; border-radius: 4px;">
                                    @endif
                                </div>
                            </div>
                        @endif
                        
                        <div class="mb-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="image_source" id="imageSourceUrl" value="url" {{ old('image_source', $slider->image && !str_starts_with($slider->image, '/img/sliders/') ? 'url' : 'url') == 'url' ? 'checked' : '' }} onchange="toggleImageSource()">
                                <label class="form-check-label" for="imageSourceUrl">URL ile Yükle</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="image_source" id="imageSourceFile" value="file" {{ old('image_source') == 'file' ? 'checked' : '' }} onchange="toggleImageSource()">
                                <label class="form-check-label" for="imageSourceFile">Bilgisayardan Yükle</label>
                            </div>
                        </div>
                        
                        <!-- URL Input -->
                        <div id="urlInputContainer">
                            <label class="form-label">Görsel URL'si</label>
                            @php
                                $currentImage = $slider->image;
                                $isLocalFile = $currentImage && str_starts_with($currentImage, '/img/sliders/');
                                $imageValue = old('image', $isLocalFile ? '' : $currentImage);
                            @endphp
                            <input type="text" class="form-control" id="imageUrl" name="image" value="{{ $imageValue }}" placeholder="https://example.com/image.jpg veya /img/slider.jpg">
                            @if($currentImage && !$isLocalFile)
                                <small class="text-muted">Mevcut görsel korunacak. Değiştirmek için yeni URL girin.</small>
                            @endif
                            <div id="imagePreview" class="mt-2" style="display: none;">
                                <img id="previewImg" src="" alt="Önizleme" style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; border-radius: 4px;">
                            </div>
                        </div>
                        
                        <!-- File Input -->
                        <div id="fileInputContainer" style="display: none;">
                            <label class="form-label">Görsel Dosyası</label>
                            <input type="file" class="form-control" id="imageFile" accept="image/*" onchange="previewImageFile(this)">
                            <small class="text-muted">Yeni dosya seçmezseniz mevcut görsel korunur.</small>
                            <div id="filePreview" class="mt-2" style="display: none;">
                                <img id="previewFileImg" src="" alt="Önizleme" style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; border-radius: 4px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buton Metni - Çoklu Dil -->
            <div class="mb-4">
                <label class="form-label fw-bold">
                    Buton Metni
                    <small class="text-muted d-block fw-normal">→ Slider'da görünecek butonun üzerindeki yazı (boş bırakılırsa buton gösterilmez)</small>
                </label>
                <ul class="nav nav-tabs mb-2" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#btn_text_tr" type="button" role="tab">Türkçe</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#btn_text_en" type="button" role="tab">English</button>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="btn_text_tr" role="tabpanel">
                        @php
                            $buttonTextTranslations = $slider->getButtonTextTranslations();
                        @endphp
                        <input type="text" class="form-control" name="button_text_tr" value="{{ old('button_text_tr', $buttonTextTranslations['tr'] ?? '') }}" placeholder="Örn: Enerji Hesaplama">
                    </div>
                    <div class="tab-pane fade" id="btn_text_en" role="tabpanel">
                        <input type="text" class="form-control" name="button_text_en" value="{{ old('button_text_en', $buttonTextTranslations['en'] ?? '') }}" placeholder="Ex: Energy Calculator">
                    </div>
                </div>
            </div>

            <!-- Buton Linki -->
            <div class="mb-4">
                <label class="form-label fw-bold">
                    Buton Linki
                    <small class="text-muted d-block fw-normal">→ Butona tıklandığında gidilecek sayfa (route veya URL)</small>
                </label>
                <input type="text" class="form-control" name="button_link" value="{{ old('button_link', $slider->button_link) }}" placeholder="Örn: /quote veya {{ route('quote') }}">
            </div>

            <!-- Sıralama ve Durum -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Sıralama</label>
                    <input type="number" class="form-control" name="sort_order" value="{{ old('sort_order', $slider->sort_order) }}" min="0">
                    <small class="text-muted">Küçük sayı önce görünür</small>
                </div>
                <div class="col-md-6">
                    <div class="form-check mt-4">
                        <input type="checkbox" class="form-check-input" name="is_active" id="is_active" {{ old('is_active', $slider->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Aktif</label>
                        <small class="text-muted d-block">Aktif olmayan slider'lar gösterilmez</small>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Güncelle</button>
                <a href="{{ route('admin.sliders.index') }}" class="btn btn-secondary">İptal</a>
            </div>
        </form>
    </div>
</div>

<script>
function toggleImageSource() {
    const urlRadio = document.getElementById('imageSourceUrl');
    const fileRadio = document.getElementById('imageSourceFile');
    const urlContainer = document.getElementById('urlInputContainer');
    const fileContainer = document.getElementById('fileInputContainer');
    
    if (urlRadio.checked) {
        urlContainer.style.display = 'block';
        fileContainer.style.display = 'none';
        document.getElementById('imageFile').removeAttribute('name');
        document.getElementById('imageUrl').setAttribute('name', 'image');
    } else {
        urlContainer.style.display = 'none';
        fileContainer.style.display = 'block';
        document.getElementById('imageUrl').removeAttribute('name');
        document.getElementById('imageFile').setAttribute('name', 'image_file');
    }
}

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

// URL preview
document.getElementById('imageUrl')?.addEventListener('input', function() {
    const url = this.value.trim();
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    
    if (url) {
        previewImg.src = url.startsWith('http') ? url : (url.startsWith('/') ? url : '/' + url);
        preview.style.display = 'block';
    } else {
        preview.style.display = 'none';
    }
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleImageSource();
    
    // Form submit handler - ensure correct image input has name attribute and validate required fields
    document.getElementById('sliderForm').addEventListener('submit', function(e) {
        const urlRadio = document.getElementById('imageSourceUrl');
        const fileRadio = document.getElementById('imageSourceFile');
        const imageUrl = document.getElementById('imageUrl');
        const imageFile = document.getElementById('imageFile');
        
        // Handle image input name attribute
        if (urlRadio.checked) {
            // URL seçiliyse, file input'un name'ini kaldır ve URL input'una name ekle
            imageFile.removeAttribute('name');
            imageUrl.setAttribute('name', 'image');
        } else {
            // File seçiliyse, URL input'unun name'ini kaldır ve file input'una name ekle
            imageUrl.removeAttribute('name');
            imageFile.setAttribute('name', 'image_file');
        }
        
        // Validate required fields - check all fields regardless of tab visibility
        const requiredFields = document.querySelectorAll('[data-required="true"]');
        let isValid = true;
        let firstInvalidField = null;
        
        requiredFields.forEach(function(field) {
            if (!field.value || field.value.trim() === '') {
                isValid = false;
                if (!firstInvalidField) {
                    firstInvalidField = field;
                }
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Lütfen tüm zorunlu alanları doldurun.');
            
            // Focus on first invalid field and switch to its tab
            if (firstInvalidField) {
                const tabPane = firstInvalidField.closest('.tab-pane');
                if (tabPane) {
                    const tabId = tabPane.id;
                    const tabButton = document.querySelector(`button[data-bs-target="#${tabId}"]`);
                    if (tabButton) {
                        // Use Bootstrap tab API to show the tab
                        const tab = new bootstrap.Tab(tabButton);
                        tab.show();
                        // Wait for tab to be shown, then focus
                        tabButton.addEventListener('shown.bs.tab', function() {
                            firstInvalidField.focus();
                        }, { once: true });
                    } else {
                        firstInvalidField.focus();
                    }
                } else {
                    firstInvalidField.focus();
                }
            }
            return false;
        }
    });
});
</script>
@endsection
