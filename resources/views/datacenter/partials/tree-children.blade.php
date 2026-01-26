@foreach($items as $item)
    @if($item->type === 'category')
        <!-- Category Node -->
        <div class="tree-item mb-2">
            <div class="tree-node d-flex align-items-center p-3 bg-light rounded" data-bs-toggle="collapse" data-bs-target="#item-{{ $item->id }}" aria-expanded="true">
                <i class="fa fa-folder text-warning fa-lg me-3"></i>
                <div class="flex-grow-1">
                    <h5 class="mb-0">{{ $item->name }}</h5>
                    @if($item->description)
                    <small class="text-muted">{{ \Illuminate\Support\Str::limit($item->description, 80) }}</small>
                    @endif
                </div>
                <i class="fa fa-chevron-down ms-2 text-muted"></i>
            </div>
            @if($item->children->count() > 0)
            <div class="collapse show mt-2" id="item-{{ $item->id }}">
                <div class="tree-children ps-4">
                    @include('datacenter.partials.tree-children', ['items' => $item->children, 'level' => $level + 1])
                </div>
            </div>
            @endif
        </div>
    @elseif($item->type === 'file')
        <!-- File Leaf -->
        <div class="tree-leaf p-2 mb-1 border-start border-3 border-primary">
            @if($item->file_path)
            <a href="{{ asset($item->file_path) }}" target="_blank" class="file-download-link" download>
                @if($item->file_type === 'pdf')
                <i class="fa fa-file-pdf text-danger me-2"></i>
                @elseif($item->file_type === 'excel')
                <i class="fa fa-file-excel text-success me-2"></i>
                @elseif($item->file_type === 'word')
                <i class="fa fa-file-word text-primary me-2"></i>
                @else
                <i class="fa fa-file text-secondary me-2"></i>
                @endif
                <div class="flex-grow-1">
                    <strong>{{ $item->name }}</strong>
                    @if($item->description)
                    <br><small class="text-muted">{{ \Illuminate\Support\Str::limit($item->description, 60) }}</small>
                    @endif
                    @if($item->formatted_file_size)
                    <br><small class="text-muted"><i class="fa fa-download me-1"></i>{{ $item->formatted_file_size }}</small>
                    @endif
                </div>
                <i class="fa fa-external-link-alt ms-2 text-muted"></i>
            </a>
            @else
            <div class="d-flex align-items-center">
                @if($item->file_type === 'pdf')
                <i class="fa fa-file-pdf text-danger me-2"></i>
                @elseif($item->file_type === 'excel')
                <i class="fa fa-file-excel text-success me-2"></i>
                @elseif($item->file_type === 'word')
                <i class="fa fa-file-word text-primary me-2"></i>
                @else
                <i class="fa fa-file text-secondary me-2"></i>
                @endif
                <div>
                    <strong>{{ $item->name }}</strong>
                    @if($item->description)
                    <br><small class="text-muted">{{ \Illuminate\Support\Str::limit($item->description, 60) }}</small>
                    @endif
                </div>
            </div>
            @endif
        </div>
    @endif
@endforeach

