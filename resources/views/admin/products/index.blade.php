@extends('admin.layouts.app')

@section('title', 'Ürünler')
@section('page-title', 'Ürünler')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Ürünler</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.products.import-json') }}" class="btn btn-outline-primary">
                <i class="fa fa-file-import me-2"></i>JSON İçe Aktar
            </a>
            <a href="{{ route('admin.product-categories.index') }}" class="btn btn-info">
                <i class="fa fa-tags me-2"></i>Kategorileri Yönet
            </a>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <i class="fa fa-plus me-2"></i>Yeni Ürün
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Ürün Adı</th>
                        <th>Kategori</th>
                        <th>Durum</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>
                            @if($product->category && is_object($product->category))
                                {{ $product->category->name }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($product->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Pasif</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning">
                                <i class="fa fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Silmek istediğinize emin misiniz?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Henüz ürün eklenmemiş.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $products->links('vendor.pagination.bootstrap-5') }}
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Pagination genel stil */
    .pagination {
        margin-top: 1rem;
    }
    
    .pagination .page-link {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }
</style>
@endsection

