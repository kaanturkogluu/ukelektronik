@extends('admin.layouts.app')

@section('title', 'Yeni Ekip Üyesi')
@section('page-title', 'Yeni Ekip Üyesi Ekle')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Yeni Ekip Üyesi Ekle</h5>
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
        
        <form action="{{ route('admin.teams.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">Ad Soyad * <small class="text-muted">(Ekip üyesinin tam adı)</small></label>
                <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Görev/Pozisyon * <small class="text-muted">(Ekip üyesinin görevi veya pozisyonu)</small></label>
                <input type="text" class="form-control" name="position" value="{{ old('position') }}" required>
            </div>

            <!-- Resim -->
            <div class="mb-3">
                <label class="form-label">Resim <small class="text-muted">(Ekip üyesinin fotoğrafı)</small></label>
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
            </div>

            <div class="mb-3">
                <label class="form-label">Açıklama <small class="text-muted">(Ekip üyesi hakkında kısa açıklama)</small></label>
                <textarea class="form-control" name="description" rows="3">{{ old('description') }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">E-posta <small class="text-muted">(İletişim e-posta adresi)</small></label>
                    <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Telefon <small class="text-muted">(İletişim telefon numarası)</small></label>
                    <input type="text" class="form-control" name="phone" value="{{ old('phone') }}">
                </div>
            </div>

            <h6 class="mb-3">Sosyal Medya</h6>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Facebook <small class="text-muted">(Facebook profil linki)</small></label>
                    <input type="url" class="form-control" name="facebook" value="{{ old('facebook') }}" placeholder="https://facebook.com/username">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Twitter <small class="text-muted">(Twitter profil linki)</small></label>
                    <input type="url" class="form-control" name="twitter" value="{{ old('twitter') }}" placeholder="https://twitter.com/username">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Instagram <small class="text-muted">(Instagram profil linki)</small></label>
                    <input type="url" class="form-control" name="instagram" value="{{ old('instagram') }}" placeholder="https://instagram.com/username">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">LinkedIn <small class="text-muted">(LinkedIn profil linki)</small></label>
                    <input type="url" class="form-control" name="linkedin" value="{{ old('linkedin') }}" placeholder="https://linkedin.com/in/username">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Sıralama <small class="text-muted">(Düşük sayı önce görünür)</small></label>
                <input type="number" class="form-control" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
            </div>

            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">
                        Aktif
                    </label>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Kaydet</button>
                <a href="{{ route('admin.teams.index') }}" class="btn btn-secondary">İptal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function toggleImageInput() {
    const imageType = document.querySelector('input[name="image_type"]:checked').value;
    const urlContainer = document.getElementById('image_url_container');
    const fileContainer = document.getElementById('image_file_container');
    const urlInput = document.getElementById('image_url');
    const fileInput = document.getElementById('image_file');

    if (imageType === 'url') {
        urlContainer.style.display = 'block';
        fileContainer.style.display = 'none';
        fileInput.removeAttribute('required');
        urlInput.setAttribute('required', 'required');
    } else {
        urlContainer.style.display = 'none';
        fileContainer.style.display = 'block';
        urlInput.removeAttribute('required');
        fileInput.setAttribute('required', 'required');
    }
}
</script>
@endsection

