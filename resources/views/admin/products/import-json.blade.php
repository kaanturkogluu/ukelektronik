@extends('admin.layouts.app')

@section('title', 'JSON Ürün İçe Aktar')
@section('page-title', 'JSON Ürün İçe Aktar')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">urunler.json ile İçe Aktar</h5>
    </div>
    <div class="card-body">
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <p class="text-muted">
            Her öğe: <code>stock_code</code>, <code>title</code>, <code>brand</code>, <code>mainCategory</code>, <code>category</code>, <code>subCategory</code>, <code>stockAmount</code>, <code>details</code>, <code>picture1Path</code>…<code>picture4Path</code> formatında olmalıdır.
        </p>

        <form action="{{ route('admin.products.import-json.run') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="use_default" id="use_default" value="1">
                    <label class="form-check-label" for="use_default">Proje kökündeki <strong>urunler.json</strong> dosyasını kullan</label>
                </div>
            </div>
            <div class="mb-3" id="file_group">
                <label for="json_file" class="form-label">Veya JSON dosyası yükle</label>
                <input type="file" class="form-control" name="json_file" id="json_file" accept=".json,application/json">
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-upload me-2"></i>İçe Aktarmayı Kuyruğa Al
            </button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">İptal</a>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('use_default').addEventListener('change', function() {
        document.getElementById('file_group').style.display = this.checked ? 'none' : 'block';
    });
</script>
@endsection
