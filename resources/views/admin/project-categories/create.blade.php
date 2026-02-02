@extends('admin.layouts.app')

@section('title', 'Yeni Proje Kategorisi')
@section('page-title', 'Yeni Proje Kategorisi')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Yeni Proje Kategorisi Ekle</h5>
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

        <form action="{{ route('admin.project-categories.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Kategori Adı <span class="text-danger">*</span> <small class="text-muted">(Proje listesinde filtre ve gruplamada kullanılır)</small></label>
                <input type="text" class="form-control" name="name" value="{{ old('name') }}" required placeholder="Örn: Ticari Kurulumlar">
            </div>

            <div class="mb-3">
                <label class="form-label">Açıklama <small class="text-muted">(İsteğe bağlı)</small></label>
                <textarea class="form-control" name="description" rows="3" placeholder="Kategori hakkında kısa açıklama...">{{ old('description') }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Sıralama <small class="text-muted">(Listede görünme sırası, küçük sayı önce görünür)</small></label>
                    <input type="number" class="form-control" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-check mt-4">
                        <input type="checkbox" class="form-check-input" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Aktif <small class="text-muted">(Aktif olmayan kategoriler sitede görünmez)</small></label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save me-2"></i>Kaydet
                </button>
                <a href="{{ route('admin.project-categories.index') }}" class="btn btn-secondary">İptal</a>
            </div>
        </form>
    </div>
</div>
@endsection
