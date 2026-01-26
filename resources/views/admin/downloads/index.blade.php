@extends('admin.layouts.app')

@section('title', 'İndirme Merkezi')
@section('page-title', 'İndirme Merkezi Yönetimi')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">İndirme Merkezi</h5>
        <div>
            <a href="{{ route('admin.downloads.create', ['type' => 'brand']) }}" class="btn btn-primary">
                <i class="fa fa-plus me-2"></i>Yeni Marka
            </a>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if($brands->count() > 0)
        <div class="tree-view">
            @foreach($brands as $brand)
            <div class="tree-item mb-3">
                <div class="tree-node card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-building me-2 text-primary"></i>
                            <strong>{{ $brand->name }}</strong>
                            @if(!$brand->is_active)
                            <span class="badge bg-secondary ms-2">Pasif</span>
                            @endif
                        </div>
                        <div>
                            <a href="{{ route('admin.downloads.create', ['parent_id' => $brand->id, 'type' => 'category']) }}" class="btn btn-sm btn-success">
                                <i class="fa fa-plus"></i> Kategori Ekle
                            </a>
                            <a href="{{ route('admin.downloads.edit', $brand) }}" class="btn btn-sm btn-warning">
                                <i class="fa fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.downloads.destroy', $brand) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu markayı ve tüm alt öğelerini silmek istediğinize emin misiniz?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @if($brand->children->count() > 0)
                    <div class="card-body">
                        @include('admin.downloads.partials.tree-children', ['items' => $brand->children, 'level' => 1])
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="alert alert-info">
            <i class="fa fa-info-circle me-2"></i>Henüz marka eklenmemiş. İlk markayı eklemek için "Yeni Marka" butonuna tıklayın.
        </div>
        @endif
    </div>
</div>
@endsection

@section('styles')
<style>
.tree-view {
    padding: 10px;
}

.tree-item {
    margin-bottom: 15px;
}

.tree-node {
    border: 1px solid #dee2e6;
    border-radius: 5px;
}

.tree-children {
    margin-left: 30px;
    margin-top: 10px;
    padding-left: 20px;
    border-left: 2px solid #e9ecef;
}

.tree-children .tree-node {
    margin-bottom: 10px;
}

.level-1 { margin-left: 0; }
.level-2 { margin-left: 30px; }
.level-3 { margin-left: 60px; }
.level-4 { margin-left: 90px; }
</style>
@endsection

