@extends('admin.layouts.app')

@section('title', 'Yeni Ürün')
@section('page-title', 'Yeni Ürün')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Yeni Ürün Ekle</h5>
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
        
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
            @csrf
            <div class="mb-3">
                <label class="form-label">Ürün Adı * <small class="text-muted">(Ürün listesinde ve detay sayfasında görünecek ad)</small></label>
                <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Kategori * <small class="text-muted">(Ürünün kategorisi)</small></label>
                <select class="form-control" name="category_id" required>
                    <option value="">Kategori Seçin</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Ana Görsel -->
            <div class="mb-3">
                <label class="form-label">Ürün Görseli * <small class="text-muted">(Ürün listesinde ve detay sayfasında görünecek ana resim)</small></label>
                <div class="mb-2">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="image_type" id="image_type_url" value="url" checked onchange="toggleImageInput()">
                        <label class="form-check-label" for="image_type_url">URL ile Yükle</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="image_type" id="image_type_file" value="file" onchange="toggleImageInput()">
                        <label class="form-check-label" for="image_type_file">Bilgisayardan Yükle</label>
                    </div>
                </div>
                <div id="image_url_container">
                    <input type="text" class="form-control" name="image" id="image_url" value="{{ old('image') }}" placeholder="https://example.com/image.jpg veya /path/to/image.jpg">
                </div>
                <div id="image_file_container" style="display: none;">
                    <input type="file" class="form-control" name="image_file" id="image_file" accept="image/*">
                </div>
                <div id="image_preview" class="mt-2" style="display: none;">
                    <img id="preview_img" src="" alt="Önizleme" style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Detaylı Açıklama <small class="text-muted">(Ürün detay sayfasında görünecek uzun açıklama)</small></label>
                <textarea class="form-control" name="description" rows="5">{{ old('description') }}</textarea>
            </div>
            
            <!-- Teknik Özellikler (Specs) -->
            <div class="mb-3">
                <label class="form-label">Teknik Özellikler <small class="text-muted">(Örn: Güç, Garanti, Boyut gibi bilgiler)</small></label>
                <div id="specs_container">
                    <div class="row mb-2 spec-item">
                        <div class="col-md-5">
                            <input type="text" class="form-control spec-key" placeholder="Özellik Adı (örn: Güç)">
                        </div>
                        <div class="col-md-5">
                            <input type="text" class="form-control spec-value" placeholder="Değer (örn: 400W)">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-sm remove-spec" style="display: none;">Sil</button>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-success btn-sm mt-2" onclick="addSpecRow()">+ Özellik Ekle</button>
            </div>
            
            <!-- Ürün Özellikleri (Features) -->
            <div class="mb-3">
                <label class="form-label">Ürün Özellikleri <small class="text-muted">(Ürünün öne çıkan özellikleri, her satıra bir özellik)</small></label>
                <div id="features_container">
                    <div class="mb-2 feature-item">
                        <div class="input-group">
                            <input type="text" class="form-control feature-input" placeholder="Özellik yazın (örn: Yüksek verimlilik)">
                            <button type="button" class="btn btn-danger remove-feature" style="display: none;">Sil</button>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-success btn-sm mt-2" onclick="addFeatureRow()">+ Özellik Ekle</button>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Sıralama <small class="text-muted">(Listede görünme sırası, küçük sayı önce görünür)</small></label>
                    <input type="number" class="form-control" name="sort_order" value="{{ old('sort_order', 0) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-check mt-4">
                        <input type="checkbox" class="form-check-input" name="is_active" id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Aktif <small class="text-muted">(Aktif olmayan ürünler sitede görünmez)</small></label>
                    </div>
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Kaydet</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">İptal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
let specCounter = 1;
let featureCounter = 1;

function toggleImageInput() {
    const urlRadio = document.getElementById('image_type_url');
    const fileRadio = document.getElementById('image_type_file');
    const urlContainer = document.getElementById('image_url_container');
    const fileContainer = document.getElementById('image_file_container');
    const urlInput = document.getElementById('image_url');
    const fileInput = document.getElementById('image_file');
    const preview = document.getElementById('image_preview');
    
    if (urlRadio.checked) {
        urlContainer.style.display = 'block';
        fileContainer.style.display = 'none';
        fileInput.value = '';
        urlInput.required = true;
        fileInput.required = false;
        preview.style.display = 'none';
    } else {
        urlContainer.style.display = 'none';
        fileContainer.style.display = 'block';
        urlInput.value = '';
        urlInput.required = false;
        fileInput.required = true;
        preview.style.display = 'none';
    }
}

// Image preview for URL
document.getElementById('image_url')?.addEventListener('input', function() {
    const preview = document.getElementById('image_preview');
    const previewImg = document.getElementById('preview_img');
    if (this.value.trim()) {
        previewImg.src = this.value;
        preview.style.display = 'block';
    } else {
        preview.style.display = 'none';
    }
});

// Image preview for file
document.getElementById('image_file')?.addEventListener('change', function(e) {
    const preview = document.getElementById('image_preview');
    const previewImg = document.getElementById('preview_img');
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(this.files[0]);
    } else {
        preview.style.display = 'none';
    }
});

function updateSpecRemoveButtons() {
    const items = document.querySelectorAll('.spec-item');
    items.forEach(item => {
        const removeBtn = item.querySelector('.remove-spec');
        if (removeBtn) {
            removeBtn.style.display = items.length > 1 ? 'block' : 'none';
        }
    });
}

function addSpecRow() {
    const container = document.getElementById('specs_container');
    const newRow = document.createElement('div');
    newRow.className = 'row mb-2 spec-item';
    newRow.innerHTML = `
        <div class="col-md-5">
            <input type="text" class="form-control spec-key" placeholder="Özellik Adı (örn: Güç)">
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control spec-value" placeholder="Değer (örn: 400W)">
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger btn-sm remove-spec">Sil</button>
        </div>
    `;
    container.appendChild(newRow);
    
    newRow.querySelector('.remove-spec').addEventListener('click', function() {
        newRow.remove();
        updateSpecRemoveButtons();
    });
    
    updateSpecRemoveButtons();
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

// Remove buttons for initial items
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.remove-spec').forEach(btn => {
        btn.addEventListener('click', function() {
            this.closest('.spec-item').remove();
            updateSpecRemoveButtons();
        });
    });
    
    document.querySelectorAll('.remove-feature').forEach(btn => {
        btn.addEventListener('click', function() {
            this.closest('.feature-item').remove();
            updateFeatureRemoveButtons();
        });
    });
    
    // Initial update to hide buttons if only one item exists
    updateSpecRemoveButtons();
    updateFeatureRemoveButtons();
    
    // Initialize image input toggle
    toggleImageInput();
    
    // Form submit handler
    const productForm = document.getElementById('productForm');
    if (productForm) {
        productForm.addEventListener('submit', function(e) {
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
            
            // Collect specs
            const specs = {};
            document.querySelectorAll('.spec-item').forEach(item => {
                const keyInput = item.querySelector('.spec-key');
                const valueInput = item.querySelector('.spec-value');
                if (keyInput && valueInput) {
                    const key = keyInput.value.trim();
                    const value = valueInput.value.trim();
                    if (key && value) {
                        specs[key] = value;
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
            
            // Add specs and features to FormData
            if (Object.keys(specs).length > 0) {
                formData.set('specs', JSON.stringify(specs));
            } else {
                formData.set('specs', '');
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
