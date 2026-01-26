@foreach($items as $item)
<div class="tree-children level-{{ $level }}">
    <div class="tree-node card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                @if($item->type === 'category')
                <i class="fa fa-folder me-2 text-warning"></i>
                @elseif($item->type === 'file')
                <i class="fa fa-file me-2 text-success"></i>
                @endif
                <strong>{{ $item->name }}</strong>
                @if($item->type === 'file')
                <span class="badge bg-info ms-2">{{ strtoupper($item->file_type ?? 'N/A') }}</span>
                @if($item->formatted_file_size)
                <span class="badge bg-secondary ms-1">{{ $item->formatted_file_size }}</span>
                @endif
                @endif
                @if(!$item->is_active)
                <span class="badge bg-secondary ms-2">Pasif</span>
                @endif
            </div>
            <div>
                @if($item->type === 'category')
                <a href="{{ route('admin.downloads.create', ['parent_id' => $item->id, 'type' => 'category']) }}" class="btn btn-sm btn-success">
                    <i class="fa fa-plus"></i> Alt Kategori
                </a>
                <a href="{{ route('admin.downloads.create', ['parent_id' => $item->id, 'type' => 'file']) }}" class="btn btn-sm btn-primary">
                    <i class="fa fa-upload"></i> Dosya Ekle
                </a>
                @endif
                <a href="{{ route('admin.downloads.edit', $item) }}" class="btn btn-sm btn-warning">
                    <i class="fa fa-edit"></i>
                </a>
                <form action="{{ route('admin.downloads.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu öğeyi silmek istediğinize emin misiniz?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fa fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
        @if($item->description)
        <div class="card-body">
            <p class="mb-0 text-muted">{{ \Illuminate\Support\Str::limit($item->description, 100) }}</p>
        </div>
        @endif
        @if($item->children->count() > 0)
        <div class="card-body">
            @include('admin.downloads.partials.tree-children', ['items' => $item->children, 'level' => $level + 1])
        </div>
        @endif
    </div>
</div>
@endforeach

