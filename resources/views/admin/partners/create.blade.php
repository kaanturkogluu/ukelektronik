@extends('admin.layouts.app')

@section('title', 'Yeni Çözüm Ortağı Ekle')
@section('page-title', 'Yeni Çözüm Ortağı Ekle')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Çözüm Ortağı Bilgileri</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.partners.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">İsim</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="link" class="form-label">Link (Opsiyonel)</label>
                        <input type="url" class="form-control @error('link') is-invalid @enderror" id="link" name="link" value="{{ old('link') }}" placeholder="https://example.com">
                        @error('link')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="logo_file" class="form-label">Logo</label>
                        <input type="file" class="form-control @error('logo_file') is-invalid @enderror" id="logo_file" name="logo_file" accept="image/*">
                        <div class="form-text text-info">
                            <i class="fa fa-info-circle me-1"></i> Önerilen Boyut: <b>250x150 px</b> veya benzer orantı. Arka planı şeffaf (PNG/WebP) logolar daha iyi görünür.
                        </div>
                        @error('logo_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="logo" class="form-label">Veya Logo URL</label>
                        <input type="text" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo" value="{{ old('logo') }}" placeholder="/img/example-logo.png">
                    </div>

                    <div class="mb-3">
                        <label for="sort_order" class="form-label">Sıralama</label>
                        <input type="number" class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}">
                        @error('sort_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" checked>
                        <label class="form-check-label" for="is_active">Aktif mi?</label>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.partners.index') }}" class="btn btn-secondary">İptal</a>
                        <button type="submit" class="btn btn-primary">Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
