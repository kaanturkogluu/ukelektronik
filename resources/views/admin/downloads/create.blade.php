@extends('admin.layouts.app')

@section('title', 'Yeni İndirme Öğesi')
@section('page-title', 'Yeni İndirme Öğesi Ekle')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            @if($type === 'brand')
            Yeni Marka Ekle
            @elseif($type === 'category')
            Yeni Kategori Ekle
            @else
            Yeni Dosya Ekle
            @endif
        </h5>
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

        <form action="{{ route('admin.downloads.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">Ad * <small class="text-muted">(Marka, kategori veya dosya adı)</small></label>
                <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
            </div>

            <input type="hidden" name="type" value="{{ $type }}">

            @if($type !== 'brand')
            <div class="mb-3">
                <label class="form-label">Üst Öğe * <small class="text-muted">(Bu öğenin ekleneceği üst öğe)</small></label>
                <select class="form-control" name="parent_id" required>
                    <option value="">Seçiniz</option>
                    @if($parent)
                    <option value="{{ $parent->id }}" selected>{{ $parent->name }} ({{ $parent->type === 'brand' ? 'Marka' : 'Kategori' }})</option>
                    @endif
                    @foreach($items as $item)
                    @if($item->id != ($parent->id ?? null))
                    <option value="{{ $item->id }}" {{ old('parent_id') == $item->id ? 'selected' : '' }}>
                        {{ $item->name }} 
                        @if($item->isBrand())
                        (Marka)
                        @elseif($item->isCategory())
                        (Kategori)
                        @endif
                    </option>
                    @endif
                    @endforeach
                </select>
            </div>
            @endif

            <div class="mb-3">
                <label class="form-label">Açıklama <small class="text-muted">(Opsiyonel açıklama)</small></label>
                <textarea class="form-control" name="description" rows="3">{{ old('description') }}</textarea>
            </div>

            @if($type === 'file')
            <div class="mb-3">
                <label class="form-label">Dosya * <small class="text-muted">(Excel, Word veya PDF dosyası - Max: 10MB)</small></label>
                <input type="file" class="form-control" name="file" accept=".xlsx,.xls,.doc,.docx,.pdf" required>
                <small class="text-muted">Desteklenen formatlar: .xlsx, .xls, .doc, .docx, .pdf</small>
            </div>
            @endif

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
                <a href="{{ route('admin.downloads.index') }}" class="btn btn-secondary">İptal</a>
            </div>
        </form>
    </div>
</div>
@endsection

