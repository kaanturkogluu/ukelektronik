<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - UK Elektronik</title>
    
    <!-- Favicon -->
    <link href="{{ asset('favicon.ico') }}" rel="icon">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    @yield('styles')
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #1A2A36 0%, #2d3e4f 100%);
            color: white;
            position: fixed;
            width: 250px;
            left: 0;
            top: 0;
            padding-top: 20px;
            z-index: 1000;
        }
        .offcanvas {
            background: linear-gradient(135deg, #1A2A36 0%, #2d3e4f 100%);
            color: white;
        }
        .offcanvas .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            margin: 5px 10px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .offcanvas .nav-link:hover,
        .offcanvas .nav-link.active {
            background-color: rgba(50, 195, 108, 0.2);
            color: white;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            margin: 5px 10px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgba(50, 195, 108, 0.2);
            color: white;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        @media (max-width: 991.98px) {
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
        }
        .navbar-admin {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 15px 20px;
            margin-bottom: 20px;
        }
        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .table {
            background: white;
        }
        .success-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 40px;
            animation: scaleIn 0.3s ease-out;
        }
        @keyframes scaleIn {
            from {
                transform: scale(0);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }
        .modal-content {
            border-radius: 15px;
        }
    </style>
</head>
<body>
    <!-- Mobile Sidebar Toggle Button -->
    <button class="btn btn-primary d-lg-none position-fixed top-0 start-0 m-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas" aria-controls="sidebarOffcanvas" style="z-index: 1001;">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar d-none d-lg-block">
        <div class="text-center mb-4">
            <h4 class="mb-0">UK Elektronik</h4>
            <small class="text-muted">Admin Panel</small>
        </div>
        
        <nav class="nav flex-column">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="fa fa-dashboard me-2"></i>Dashboard
            </a>
            <a class="nav-link {{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}" href="{{ route('admin.sliders.index') }}">
                <i class="fa fa-images me-2"></i>Slider'lar
            </a>
            <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                <i class="fa fa-box me-2"></i>Ürünler
            </a>
            <a class="nav-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}" href="{{ route('admin.services.index') }}">
                <i class="fa fa-cogs me-2"></i>Hizmetler
            </a>
            <a class="nav-link {{ request()->routeIs('admin.projects.*') && !request()->routeIs('admin.project-categories.*') ? 'active' : '' }}" href="{{ route('admin.projects.index') }}">
                <i class="fa fa-project-diagram me-2"></i>Projeler
            </a>
            <a class="nav-link {{ request()->routeIs('admin.project-categories.*') ? 'active' : '' }}" href="{{ route('admin.project-categories.index') }}">
                <i class="fa fa-folder me-2"></i>Proje Kategorileri
            </a>
            <a class="nav-link {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}" href="{{ route('admin.faqs.index') }}">
                <i class="fa fa-question-circle me-2"></i>SSS
            </a>
            <a class="nav-link {{ request()->routeIs('admin.downloads.*') ? 'active' : '' }}" href="{{ route('admin.downloads.index') }}">
                <i class="fa fa-download me-2"></i>İndirme Merkezi
            </a>
            <a class="nav-link {{ request()->routeIs('admin.teams.*') ? 'active' : '' }}" href="{{ route('admin.teams.index') }}">
                <i class="fa fa-users me-2"></i>Ekip Üyeleri
            </a>
            <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                <i class="fa fa-cog me-2"></i>Ayarlar
            </a>
            <hr class="text-white-50 my-3">
            <a class="nav-link" href="{{ route('home') }}" target="_blank">
                <i class="fa fa-external-link me-2"></i>Siteyi Görüntüle
            </a>
            <form method="POST" action="{{ route('admin.logout') }}" class="mt-3">
                @csrf
                <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent">
                    <i class="fa fa-sign-out-alt me-2"></i>Çıkış Yap
                </button>
            </form>
        </nav>
    </div>

    <!-- Mobile Sidebar Offcanvas -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel">
        <div class="offcanvas-header">
            <div class="offcanvas-title" id="sidebarOffcanvasLabel">
                <h4 class="mb-0 text-white">UK Elektronik</h4>
                <small class="text-white-50">Admin Panel</small>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0">
            <nav class="nav flex-column">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}" data-bs-dismiss="offcanvas">
                    <i class="fa fa-dashboard me-2"></i>Dashboard
                </a>
                <a class="nav-link {{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}" href="{{ route('admin.sliders.index') }}" data-bs-dismiss="offcanvas">
                    <i class="fa fa-images me-2"></i>Slider'lar
                </a>
                <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}" data-bs-dismiss="offcanvas">
                    <i class="fa fa-box me-2"></i>Ürünler
                </a>
                <a class="nav-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}" href="{{ route('admin.services.index') }}" data-bs-dismiss="offcanvas">
                    <i class="fa fa-cogs me-2"></i>Hizmetler
                </a>
                <a class="nav-link {{ request()->routeIs('admin.projects.*') && !request()->routeIs('admin.project-categories.*') ? 'active' : '' }}" href="{{ route('admin.projects.index') }}" data-bs-dismiss="offcanvas">
                    <i class="fa fa-project-diagram me-2"></i>Projeler
                </a>
                <a class="nav-link {{ request()->routeIs('admin.project-categories.*') ? 'active' : '' }}" href="{{ route('admin.project-categories.index') }}" data-bs-dismiss="offcanvas">
                    <i class="fa fa-folder me-2"></i>Proje Kategorileri
                </a>
                <a class="nav-link {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}" href="{{ route('admin.faqs.index') }}" data-bs-dismiss="offcanvas">
                    <i class="fa fa-question-circle me-2"></i>SSS
                </a>
                <a class="nav-link {{ request()->routeIs('admin.downloads.*') ? 'active' : '' }}" href="{{ route('admin.downloads.index') }}" data-bs-dismiss="offcanvas">
                    <i class="fa fa-download me-2"></i>İndirme Merkezi
                </a>
                <a class="nav-link {{ request()->routeIs('admin.teams.*') ? 'active' : '' }}" href="{{ route('admin.teams.index') }}" data-bs-dismiss="offcanvas">
                    <i class="fa fa-users me-2"></i>Ekip Üyeleri
                </a>
                <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}" data-bs-dismiss="offcanvas">
                    <i class="fa fa-cog me-2"></i>Ayarlar
                </a>
                <hr class="text-white-50 my-3">
                <a class="nav-link" href="{{ route('home') }}" target="_blank" data-bs-dismiss="offcanvas">
                    <i class="fa fa-external-link me-2"></i>Siteyi Görüntüle
                </a>
                <form method="POST" action="{{ route('admin.logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent">
                        <i class="fa fa-sign-out-alt me-2"></i>Çıkış Yap
                    </button>
                </form>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <div class="navbar-admin">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">@yield('page-title', 'Dashboard')</h5>
                <div>
                    <span class="text-muted">Hoş geldiniz, {{ Auth::user()->name }}</span>
                </div>
            </div>
        </div>

        <!-- Success Popup Modal -->
        @if(session('success'))
        <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-body text-center p-5">
                        <div class="mb-4">
                            <div class="success-icon mx-auto mb-3">
                                <i class="fa fa-check-circle"></i>
                            </div>
                            <h4 class="text-success mb-2">Başarılı!</h4>
                            <p class="text-muted mb-0">{{ session('success') }}</p>
                        </div>
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                            <i class="fa fa-check me-2"></i>Tamam
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Alerts -->
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- Content -->
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Show success modal if exists -->
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
        });
    </script>
    @endif
    
    @yield('scripts')
</body>
</html>

