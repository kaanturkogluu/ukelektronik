@extends('admin.layouts.app')

@section('title', 'Ekip Üyeleri')
@section('page-title', 'Ekip Üyeleri')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Ekip Üyeleri</h5>
        <a href="{{ route('admin.teams.create') }}" class="btn btn-primary">
            <i class="fa fa-plus me-2"></i>Yeni Ekip Üyesi
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Ad Soyad</th>
                        <th>Görev</th>
                        <th>Durum</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teams as $team)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($team->image)
                                <img src="{{ str_starts_with($team->image, 'http://') || str_starts_with($team->image, 'https://') || str_starts_with($team->image, '/') ? $team->image : asset($team->image) }}" alt="{{ $team->name }}" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                @endif
                                {{ $team->name }}
                            </div>
                        </td>
                        <td>{{ $team->position }}</td>
                        <td>
                            @if($team->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Pasif</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.teams.edit', $team) }}" class="btn btn-sm btn-warning">
                                <i class="fa fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.teams.destroy', $team) }}" method="POST" class="d-inline" onsubmit="return confirm('Silmek istediğinize emin misiniz?')">
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
                        <td colspan="4" class="text-center">Henüz ekip üyesi eklenmemiş.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $teams->links() }}
    </div>
</div>
@endsection

