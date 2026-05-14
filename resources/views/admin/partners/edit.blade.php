@extends('admin.layouts.app')

@section('title', 'Çözüm Ortağını Düzenle')
@section('page-title', 'Çözüm Ortağını Düzenle')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Çözüm Ortağı Bilgileri: {{ $partner->name }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.partners.update', $partner) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">İsim</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $partner->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="link" class="form-label">Link (Opsiyonel)</label>
                        <input type="url" class="form-control @error('link') is-invalid @enderror" id="link" name="link" value="{{ old('link', $partner->link) }}" placeholder="https://example.com">
                        @error('link')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="logo_file" class="form-label">Logo</label>
                        @if($partner->logo)
                            <div class="mb-2">
                                <img src="{{ asset($partner->logo) }}" alt="{{ $partner->name }}" style="max-width: 200px; height: auto; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px; padding: 10px;">
                            </div>
                        @endif
                        <input type="file" class="form-control @error('logo_file') is-invalid @enderror" id="logo_file" name="logo_file" accept="image/*">
                        <div class="form-text text-info">
                            <i class="fa fa-info-circle me-1"></i> Önerilen Boyut: <b>250x150 px</b> veya benzer orantı.
                        </div>
                        @error('logo_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="logo" class="form-label">Veya Logo URL</label>
                        <input type="text" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo" value="{{ old('logo', $partner->logo) }}" placeholder="/img/example-logo.png">
                    </div>

                    <div class="mb-3">
                        <label for="sort_order" class="form-label">Sıralama</label>
                        <input type="number" class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" value="{{ old('sort_order', $partner->sort_order) }}">
                        @error('sort_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" {{ $partner->is_active ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Aktif mi?</label>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.partners.index') }}" class="btn btn-secondary">İptal</a>
                        <button type="submit" class="btn btn-primary">Güncelle</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
