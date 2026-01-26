@extends('layouts.app')

@section('title', 'Data Merkezi - UK Elektronik')

@section('content')
    <!-- Page Header Start -->
    <div class="container-fluid page-header py-5 mb-5">
        <div class="container py-5">
            <h1 class="display-3 text-white mb-3 animated slideInDown">Data Merkezi</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="text-white" href="{{ route('home') }}">Ana Sayfa</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">Data Merkezi</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Data Center Tree Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <h6 class="text-primary">İndirme Merkezi</h6>
                <h1 class="mb-4">Hiyerarşik Yapı ve Bileşenler</h1>
                <p class="mb-0">İndirme merkezimizin organizasyonel yapısını ve bileşenlerini keşfedin</p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <!-- Tree Structure -->
                            <div class="datacenter-tree">
                                @if($brands->count() > 0)
                                    @foreach($brands as $brand)
                                    <div class="tree-item root-item mb-3">
                                        <div class="tree-node d-flex align-items-center p-3 bg-primary text-white rounded" data-bs-toggle="collapse" data-bs-target="#brand-{{ $brand->id }}" aria-expanded="true">
                                            <i class="fa fa-building fa-2x me-3"></i>
                                            <div class="flex-grow-1">
                                                <h4 class="mb-0">{{ $brand->name }}</h4>
                                                @if($brand->description)
                                                <small>{{ $brand->description }}</small>
                                                @endif
                                            </div>
                                            <i class="fa fa-chevron-down ms-2"></i>
                                        </div>
                                        
                                        @if($brand->children->count() > 0)
                                        <div class="collapse show mt-3" id="brand-{{ $brand->id }}">
                                            <div class="tree-children ps-4">
                                                @include('datacenter.partials.tree-children', ['items' => $brand->children, 'level' => 1])
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    @endforeach
                                @else
                                    <div class="alert alert-info text-center">
                                        <i class="fa fa-info-circle me-2"></i>
                                        Henüz içerik eklenmemiş.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Data Center Tree End -->
@endsection

@section('styles')
<style>
    .datacenter-tree {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .tree-node {
        cursor: pointer;
        transition: all 0.3s ease;
        user-select: none;
    }

    .tree-node:hover {
        transform: translateX(5px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .tree-node[aria-expanded="false"] .fa-chevron-down {
        transform: rotate(-90deg);
        transition: transform 0.3s ease;
    }

    .tree-children {
        border-left: 2px dashed #dee2e6;
        margin-left: 20px;
        padding-left: 20px;
    }

    .tree-leaf {
        transition: all 0.2s ease;
        border-radius: 4px;
    }

    .tree-leaf:hover {
        background-color: #f8f9fa;
        transform: translateX(5px);
        padding-left: 15px !important;
    }

    .root-item .tree-node {
        font-weight: 600;
    }

    .tree-item .tree-node {
        font-weight: 500;
    }

    .tree-leaf {
        font-size: 0.95rem;
    }

    .collapse {
        transition: height 0.35s ease;
    }

    .file-download-link {
        text-decoration: none;
        color: inherit;
        display: flex;
        align-items: center;
        transition: all 0.2s ease;
    }

    .file-download-link:hover {
        color: #0d6efd;
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .tree-children {
            margin-left: 10px;
            padding-left: 10px;
        }
        
        .tree-node {
            font-size: 0.9rem;
        }
        
        .tree-leaf {
            font-size: 0.85rem;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle chevron icon on collapse
        const treeNodes = document.querySelectorAll('.tree-node[data-bs-toggle="collapse"]');
        treeNodes.forEach(node => {
            node.addEventListener('click', function() {
                const target = this.getAttribute('data-bs-target');
                const collapseElement = document.querySelector(target);
                const chevron = this.querySelector('.fa-chevron-down');
                
                if (collapseElement) {
                    collapseElement.addEventListener('shown.bs.collapse', function() {
                        if (chevron) chevron.style.transform = 'rotate(0deg)';
                    });
                    
                    collapseElement.addEventListener('hidden.bs.collapse', function() {
                        if (chevron) chevron.style.transform = 'rotate(-90deg)';
                    });
                }
            });
        });
    });
</script>
@endsection
