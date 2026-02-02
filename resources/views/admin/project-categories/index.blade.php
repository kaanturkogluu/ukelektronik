@extends('admin.layouts.app')

@section('title', 'Proje Kategorileri')
@section('page-title', 'Proje Kategorileri')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Proje Kategorileri</h5>
        <a href="{{ route('admin.project-categories.create') }}" class="btn btn-primary">
            <i class="fa fa-plus me-2"></i>Yeni Kategori
        </a>
    </div>
    <div class="card-body">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Kategori Adı</th>
                        <th>Açıklama</th>
                        <th>Proje Sayısı</th>
                        <th>Sıralama</th>
                        <th>Durum</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td>{{ Str::limit($category->description, 50) }}</td>
                        <td>
                            <span class="badge bg-info">{{ $category->projects_count ?? 0 }}</span>
                        </td>
                        <td>{{ $category->sort_order }}</td>
                        <td>
                            @if($category->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Pasif</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.project-categories.edit', $category) }}" class="btn btn-sm btn-warning">
                                <i class="fa fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.project-categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu kategoriyi silmek istediğinize emin misiniz? Bu kategoriye ait projelerin kategorisi kaldırılacaktır.')">
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
                        <td colspan="6" class="text-center">Henüz proje kategorisi eklenmemiş.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
