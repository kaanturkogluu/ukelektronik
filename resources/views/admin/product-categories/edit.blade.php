@extends('admin.layouts.app')

@section('title', 'Ürün Kategorisi Düzenle')
@section('page-title', 'Ürün Kategorisi Düzenle')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Ürün Kategorisi Düzenle</h5>
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
        
        <form action="{{ route('admin.product-categories.update', $productCategory) }}" method="POST">
            @csrf
            @method('PUT')
            @php
                $nameTranslations = $productCategory->getNameTranslations();
                $descTranslations = $productCategory->getDescriptionTranslations();
            @endphp
            
            <!-- Kategori Adı - Çoklu Dil -->
            <div class="mb-3">
                <label class="form-label">Kategori Adı * <small class="text-muted">(Ürün kategorisinin adı)</small></label>
                <ul class="nav nav-tabs mb-2" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#name_tr" type="button" role="tab">Türkçe</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#name_en" type="button" role="tab">English</button>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="name_tr" role="tabpanel">
                        <input type="text" class="form-control" name="name_tr" value="{{ old('name_tr', $nameTranslations['tr'] ?? '') }}" required>
                    </div>
                    <div class="tab-pane fade" id="name_en" role="tabpanel">
                        <input type="text" class="form-control" name="name_en" value="{{ old('name_en', $nameTranslations['en'] ?? '') }}" required>
                    </div>
                </div>
            </div>
            
            <!-- Açıklama - Çoklu Dil -->
            <div class="mb-3">
                <label class="form-label">Açıklama <small class="text-muted">(Kategori hakkında kısa açıklama)</small></label>
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
                        <textarea class="form-control" name="description_tr" rows="3">{{ old('description_tr', $descTranslations['tr'] ?? '') }}</textarea>
                    </div>
                    <div class="tab-pane fade" id="desc_en" role="tabpanel">
                        <textarea class="form-control" name="description_en" rows="3">{{ old('description_en', $descTranslations['en'] ?? '') }}</textarea>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Sıralama <small class="text-muted">(Listede görünme sırası, küçük sayı önce görünür)</small></label>
                    <input type="number" class="form-control" name="sort_order" value="{{ old('sort_order', $productCategory->sort_order) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-check mt-4">
                        <input type="checkbox" class="form-check-input" name="is_active" id="is_active" {{ old('is_active', $productCategory->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Aktif <small class="text-muted">(Aktif olmayan kategoriler listede görünmez)</small></label>
                    </div>
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Güncelle</button>
                <a href="{{ route('admin.product-categories.index') }}" class="btn btn-secondary">İptal</a>
            </div>
        </form>
    </div>
</div>
@endsection

