@extends('admin.layouts.app')

@section('title', 'Slider Yönetimi')
@section('page-title', 'Slider Yönetimi')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Slider Yönetimi</h5>
        <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary">
            <i class="fa fa-plus me-2"></i>Yeni Slider
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Görsel</th>
                        <th>Başlık</th>
                        <th>Sıralama</th>
                        <th>Durum</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sliders as $slider)
                    <tr>
                        <td>
                            @if($slider->image)
                                @if(str_starts_with($slider->image, 'http://') || str_starts_with($slider->image, 'https://'))
                                    <img src="{{ $slider->image }}" alt="Slider" style="width: 80px; height: 50px; object-fit: cover; border-radius: 4px;">
                                @else
                                    <img src="{{ asset($slider->image) }}" alt="Slider" style="width: 80px; height: 50px; object-fit: cover; border-radius: 4px;">
                                @endif
                            @else
                                <span class="text-muted">Görsel Yok</span>
                            @endif
                        </td>
                        <td>{{ $slider->title }}</td>
                        <td>{{ $slider->sort_order }}</td>
                        <td>
                            @if($slider->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Pasif</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.sliders.edit', $slider) }}" class="btn btn-sm btn-warning">
                                <i class="fa fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.sliders.destroy', $slider) }}" method="POST" class="d-inline" onsubmit="return confirm('Silmek istediğinize emin misiniz?')">
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
                        <td colspan="5" class="text-center">Henüz slider eklenmemiş.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

