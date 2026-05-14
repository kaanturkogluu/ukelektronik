@extends('admin.layouts.app')

@section('title', 'Çözüm Ortakları Yönetimi')
@section('page-title', 'Çözüm Ortakları Yönetimi')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Çözüm Ortakları Listesi</h5>
        <a href="{{ route('admin.partners.create') }}" class="btn btn-primary">
            <i class="fa fa-plus me-2"></i>Yeni Çözüm Ortağı
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Logo</th>
                        <th>İsim</th>
                        <th>Link</th>
                        <th>Sıralama</th>
                        <th>Durum</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($partners as $partner)
                    <tr>
                        <td>
                            @if($partner->logo)
                                @if(str_starts_with($partner->logo, 'http://') || str_starts_with($partner->logo, 'https://'))
                                    <img src="{{ $partner->logo }}" alt="{{ $partner->name }}" style="width: 100px; height: 50px; object-fit: contain; background: #f8f9fa; border-radius: 4px; padding: 5px;">
                                @else
                                    <img src="{{ asset($partner->logo) }}" alt="{{ $partner->name }}" style="width: 100px; height: 50px; object-fit: contain; background: #f8f9fa; border-radius: 4px; padding: 5px;">
                                @endif
                            @else
                                <span class="text-muted">Logo Yok</span>
                            @endif
                        </td>
                        <td>{{ $partner->name }}</td>
                        <td>
                            @if($partner->link)
                                <a href="{{ $partner->link }}" target="_blank">{{ Str::limit($partner->link, 30) }}</a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $partner->sort_order }}</td>
                        <td>
                            @if($partner->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Pasif</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.partners.edit', $partner) }}" class="btn btn-sm btn-warning">
                                <i class="fa fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.partners.destroy', $partner) }}" method="POST" class="d-inline" onsubmit="return confirm('Silmek istediğinize emin misiniz?')">
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
                        <td colspan="6" class="text-center">Henüz çözüm ortağı eklenmemiş.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
