@extends('admin.layouts.app')

@section('title', 'İndirme Öğesi Düzenle')
@section('page-title', 'İndirme Öğesi Düzenle')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">İndirme Öğesi Düzenle</h5>
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

        <form action="{{ route('admin.downloads.update', $item) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="form-label">Ad * <small class="text-muted">(Marka, kategori veya dosya adı)</small></label>
                <input type="text" class="form-control" name="name" value="{{ old('name', $item->name) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Tip *</label>
                <select class="form-control" name="type" id="type_select" required>
                    <option value="brand" {{ old('type', $item->type) === 'brand' ? 'selected' : '' }}>Marka</option>
                    <option value="category" {{ old('type', $item->type) === 'category' ? 'selected' : '' }}>Kategori</option>
                    <option value="file" {{ old('type', $item->type) === 'file' ? 'selected' : '' }}>Dosya</option>
                </select>
            </div>

            <div class="mb-3" id="parent_id_container">
                <label class="form-label">Üst Öğe <small class="text-muted">(Bu öğenin ekleneceği üst öğe - Marka seçildiğinde boş olmalı)</small></label>
                <select class="form-control" name="parent_id" id="parent_id">
                    <option value="">Seçiniz</option>
                    @foreach($items as $itm)
                    @if($itm->id != $item->id)
                    <option value="{{ $itm->id }}" {{ old('parent_id', $item->parent_id) == $itm->id ? 'selected' : '' }}>
                        {{ $itm->name }} 
                        @if($itm->isBrand())
                        (Marka)
                        @elseif($itm->isCategory())
                        (Kategori)
                        @endif
                    </option>
                    @endif
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Açıklama <small class="text-muted">(Opsiyonel açıklama)</small></label>
                <textarea class="form-control" name="description" rows="3">{{ old('description', $item->description) }}</textarea>
            </div>

            <div class="mb-3" id="file_container">
                <label class="form-label">
                    @if($item->file_path)
                    Mevcut Dosya: <a href="{{ asset($item->file_path) }}" target="_blank">{{ $item->original_filename ?? basename($item->file_path) }}</a>
                    @else
                    Dosya
                    @endif
                    <small class="text-muted">(Excel, Word veya PDF dosyası - Max: 10MB)</small>
                </label>
                <input type="file" class="form-control" name="file" accept=".xlsx,.xls,.doc,.docx,.pdf">
                <small class="text-muted">Yeni dosya yüklerseniz mevcut dosya değiştirilir. Desteklenen formatlar: .xlsx, .xls, .doc, .docx, .pdf</small>
            </div>

            <div class="mb-3">
                <label class="form-label">Sıralama <small class="text-muted">(Düşük sayı önce görünür)</small></label>
                <input type="number" class="form-control" name="sort_order" value="{{ old('sort_order', $item->sort_order) }}" min="0">
            </div>

            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $item->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">
                        Aktif
                    </label>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Güncelle</button>
                <a href="{{ route('admin.downloads.index') }}" class="btn btn-secondary">İptal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type_select');
    const parentIdContainer = document.getElementById('parent_id_container');
    const parentIdSelect = document.getElementById('parent_id');
    const fileContainer = document.getElementById('file_container');

    function toggleFields() {
        const type = typeSelect.value;
        
        if (type === 'brand') {
            parentIdContainer.style.display = 'none';
            parentIdSelect.value = '';
            fileContainer.style.display = 'none';
        } else if (type === 'category') {
            parentIdContainer.style.display = 'block';
            fileContainer.style.display = 'none';
        } else if (type === 'file') {
            parentIdContainer.style.display = 'block';
            fileContainer.style.display = 'block';
        }
    }

    typeSelect.addEventListener('change', toggleFields);
    toggleFields(); // Initial call
});
</script>
@endsection

