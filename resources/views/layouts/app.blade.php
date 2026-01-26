<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="utf-8">
    <title>@yield('title', 'UK Elektronik - Güneş ve Yenilenebilir Enerji')</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="{{ asset('favicon.ico') }}" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500&family=Roboto:wght@500;700;900&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/lightbox/css/lightbox.min.css') }}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    
    @yield('styles')
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Yükleniyor...</span>
        </div>
    </div>
    <!-- Spinner End -->

    <!-- Topbar Start -->
    <div class="container-fluid bg-dark p-0 fixed-top d-none d-lg-block" style="z-index: 10000; background: rgba(0, 0, 0, 0.7) !important; backdrop-filter: blur(10px); height: 45px; position: fixed !important; top: 0 !important; left: 0 !important; right: 0 !important;">
        <div class="row gx-0 h-100">
            <div class="col-lg-8 px-5 text-start">
                @php
                    $displayPhone = \App\Models\Setting::get('display_phone');
                    $displayPhoneType = 'phone';
                    if (empty($displayPhone)) {
                        $phonesJson = \App\Models\Setting::get('phones', '[]');
                        $phones = is_string($phonesJson) ? json_decode($phonesJson, true) : [];
                        if (!empty($phones)) {
                            $firstPhone = is_array($phones[0]) ? $phones[0] : ['number' => $phones[0], 'type' => 'phone'];
                            $displayPhone = $firstPhone['number'] ?? $firstPhone;
                            $displayPhoneType = $firstPhone['type'] ?? 'phone';
                        } else {
                            $displayPhone = '+90 (212) 123 45 67';
                        }
                    } else {
                        // Find phone type from phones array
                        $phonesJson = \App\Models\Setting::get('phones', '[]');
                        $phones = is_string($phonesJson) ? json_decode($phonesJson, true) : [];
                        foreach($phones as $phone) {
                            $phoneNumber = is_array($phone) ? ($phone['number'] ?? '') : $phone;
                            if ($phoneNumber == $displayPhone) {
                                $displayPhoneType = is_array($phone) ? ($phone['type'] ?? 'phone') : 'phone';
                                break;
                            }
                        }
                    }
                @endphp
                <div class="h-100 d-inline-flex align-items-center me-4">
                    @if($displayPhoneType === 'whatsapp')
                    <small class="fab fa-whatsapp text-primary me-2"></small>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $displayPhone) }}" target="_blank" class="text-white text-decoration-none contact-link-topbar" title="WhatsApp ile iletişime geçin">
                        <small>{{ $displayPhone }}</small>
                    </a>
                    @else
                    <small class="fa fa-phone-alt text-primary me-2"></small>
                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $displayPhone) }}" class="text-white text-decoration-none contact-link-topbar" title="Aramak için tıklayın">
                        <small>{{ $displayPhone }}</small>
                    </a>
                    @endif
                </div>
                @php
                    $displayEmail = \App\Models\Setting::get('display_email');
                    if (empty($displayEmail)) {
                        $emailsJson = \App\Models\Setting::get('emails', '[]');
                        $emails = is_string($emailsJson) ? json_decode($emailsJson, true) : [];
                        $displayEmail = !empty($emails) ? $emails[0] : 'info@ukelektronik.com';
                    }
                @endphp
                <div class="h-100 d-inline-flex align-items-center me-4">
                    <small class="fa fa-envelope text-primary me-2"></small>
                    <a href="mailto:{{ $displayEmail }}" class="text-white text-decoration-none contact-link-topbar" title="E-posta göndermek için tıklayın">
                        <small>{{ $displayEmail }}</small>
                    </a>
                </div>
                <div class="h-100 d-inline-flex align-items-center">
                    <a href="{{ route('datacenter') }}" class="text-white text-decoration-none me-3" style="font-size: 0.875rem;">{{ __('common.datacenter') }}</a>
                </div>
            </div>
            <div class="col-lg-4 px-5 text-end">
                <div class="h-100 d-inline-flex align-items-center me-4">
                    <form action="{{ route('lang.switch') }}" method="GET" id="languageForm" style="display: inline;">
                        <input type="hidden" name="redirect" value="{{ url()->current() }}">
                        <select class="form-select form-select-sm border-0 bg-transparent text-white topbar-language-select" 
                                name="locale" 
                                id="languageSelect"
                                onchange="document.getElementById('languageForm').submit();"
                                style="width: auto; padding: 0.25rem 2rem 0.25rem 0.5rem; font-size: 0.875rem; color: white !important;">
                            <option value="tr" {{ app()->getLocale() == 'tr' ? 'selected' : '' }} style="background-color: #333; color: white;">{{ __('common.turkish') }}</option>
                            <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }} style="background-color: #333; color: white;">{{ __('common.english') }}</option>
                        </select>
                    </form>
                </div>
                <div class="h-100 d-inline-flex align-items-center mx-n2">
                    @php
                        $facebook = \App\Models\Setting::get('facebook');
                        $twitter = \App\Models\Setting::get('twitter');
                        $linkedin = \App\Models\Setting::get('linkedin');
                        $instagram = \App\Models\Setting::get('instagram');
                    @endphp
                    @if($facebook)
                    <a class="btn btn-square btn-link rounded-0 border-0 border-end border-secondary text-white" href="{{ $facebook }}" target="_blank" style="color: white !important; padding: 0.5rem;"><i class="fab fa-facebook-f"></i></a>
                    @endif
                    @if($twitter)
                    <a class="btn btn-square btn-link rounded-0 border-0 border-end border-secondary text-white" href="{{ $twitter }}" target="_blank" style="color: white !important; padding: 0.5rem;"><i class="fab fa-twitter"></i></a>
                    @endif
                    @if($linkedin)
                    <a class="btn btn-square btn-link rounded-0 border-0 border-end border-secondary text-white" href="{{ $linkedin }}" target="_blank" style="color: white !important; padding: 0.5rem;"><i class="fab fa-linkedin-in"></i></a>
                    @endif
                    @if($instagram)
                    <a class="btn btn-square btn-link rounded-0 text-white" href="{{ $instagram }}" target="_blank" style="color: white !important; padding: 0.5rem;"><i class="fab fa-instagram"></i></a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->

    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top p-0 navbar-mobile" style="background: rgba(0, 0, 0, 0.3); backdrop-filter: blur(10px); z-index: 9999; transition: all 0.3s; position: fixed !important; left: 0 !important; right: 0 !important;">
        <div class="container-fluid">
            <a href="{{ route('home') }}" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
                <img src="{{ asset('uklogo.png') }}" alt="UK Elektronik Solar Enerji" class="navbar-logo" style="max-height: 60px; width: auto;">
            </a>
            <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" style="border-color: rgba(255, 255, 255, 0.5);">
                <span class="navbar-toggler-icon" style="filter: brightness(0) invert(1);"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav mx-auto p-2 p-lg-0">
                    <a href="{{ route('home') }}" class="nav-item nav-link text-white {{ request()->routeIs('home') ? 'active' : '' }}" style="color: white !important; font-weight: 500;">{{ __('common.home') }}</a>
                    <a href="{{ route('about') }}" class="nav-item nav-link text-white {{ request()->routeIs('about') ? 'active' : '' }}" style="color: white !important; font-weight: 500;">{{ __('common.about') }}</a>
                    <a href="{{ route('service') }}" class="nav-item nav-link text-white {{ request()->routeIs('service') ? 'active' : '' }}" style="color: white !important; font-weight: 500;">{{ __('common.services') }}</a>
                    <a href="{{ route('project') }}" class="nav-item nav-link text-white {{ request()->routeIs('project') ? 'active' : '' }}" style="color: white !important; font-weight: 500;">{{ __('common.projects') }}</a>
                    <a href="{{ route('products') }}" class="nav-item nav-link text-white {{ request()->routeIs('products') ? 'active' : '' }}" style="color: white !important; font-weight: 500;">{{ __('common.products') }}</a>
                    <a href="{{ route('faq') }}" class="nav-item nav-link text-white {{ request()->routeIs('faq') ? 'active' : '' }}" style="color: white !important; font-weight: 500;">SSS</a>
                    <a href="{{ route('contact') }}" class="nav-item nav-link text-white {{ request()->routeIs('contact') ? 'active' : '' }}" style="color: white !important; font-weight: 500;">{{ __('common.contact') }}</a>
                </div>
                <a href="{{ route('quote') }}" class="btn btn-primary rounded-0 py-4 px-lg-5 d-none d-lg-block">Enerji Hesaplama<i class="fa fa-arrow-right ms-3"></i></a>
                
                <!-- Mobile Topbar Content -->
                <div class="d-lg-none border-top border-secondary mt-2 pt-2 px-3 pb-2">
                    @php
                        $displayPhone = \App\Models\Setting::get('display_phone');
                        $displayPhoneType = 'phone';
                        if (empty($displayPhone)) {
                            $phonesJson = \App\Models\Setting::get('phones', '[]');
                            $phones = is_string($phonesJson) ? json_decode($phonesJson, true) : [];
                            if (!empty($phones)) {
                                $firstPhone = is_array($phones[0]) ? $phones[0] : ['number' => $phones[0], 'type' => 'phone'];
                                $displayPhone = $firstPhone['number'] ?? $firstPhone;
                                $displayPhoneType = $firstPhone['type'] ?? 'phone';
                            } else {
                                $displayPhone = '+90 (212) 123 45 67';
                            }
                        } else {
                            $phonesJson = \App\Models\Setting::get('phones', '[]');
                            $phones = is_string($phonesJson) ? json_decode($phonesJson, true) : [];
                            foreach($phones as $phone) {
                                $phoneNumber = is_array($phone) ? ($phone['number'] ?? '') : $phone;
                                if ($phoneNumber == $displayPhone) {
                                    $displayPhoneType = is_array($phone) ? ($phone['type'] ?? 'phone') : 'phone';
                                    break;
                                }
                            }
                        }
                    @endphp
                    <div class="mb-2">
                        @if($displayPhoneType === 'whatsapp')
                        <small class="fab fa-whatsapp text-primary me-2"></small>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $displayPhone) }}" target="_blank" class="text-white text-decoration-none" style="font-size: 0.875rem;">
                            <small>{{ $displayPhone }}</small>
                        </a>
                        @else
                        <small class="fa fa-phone-alt text-primary me-2"></small>
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $displayPhone) }}" class="text-white text-decoration-none" style="font-size: 0.875rem;">
                            <small>{{ $displayPhone }}</small>
                        </a>
                        @endif
                    </div>
                    @php
                        $displayEmail = \App\Models\Setting::get('display_email');
                        if (empty($displayEmail)) {
                            $emailsJson = \App\Models\Setting::get('emails', '[]');
                            $emails = is_string($emailsJson) ? json_decode($emailsJson, true) : [];
                            $displayEmail = !empty($emails) ? $emails[0] : 'info@ukelektronik.com';
                        }
                    @endphp
                    <div class="mb-2">
                        <small class="fa fa-envelope text-primary me-2"></small>
                        <a href="mailto:{{ $displayEmail }}" class="text-white text-decoration-none" style="font-size: 0.875rem;">
                            <small>{{ $displayEmail }}</small>
                        </a>
                    </div>
                    <div class="mb-2">
                        <a href="{{ route('datacenter') }}" class="text-white text-decoration-none" style="font-size: 0.875rem;">{{ __('common.datacenter') }}</a>
                    </div>
                    <div class="mb-2">
                        <form action="{{ route('lang.switch') }}" method="GET" id="languageFormMobile" style="display: inline;">
                            <input type="hidden" name="redirect" value="{{ url()->current() }}">
                            <select class="form-select form-select-sm border-0 bg-transparent text-white" 
                                    name="locale" 
                                    id="languageSelectMobile"
                                    onchange="document.getElementById('languageFormMobile').submit();"
                                    style="width: auto; padding: 0.25rem 2rem 0.25rem 0.5rem; font-size: 0.875rem; color: white !important;">
                                <option value="tr" {{ app()->getLocale() == 'tr' ? 'selected' : '' }} style="background-color: #333; color: white;">{{ __('common.turkish') }}</option>
                                <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }} style="background-color: #333; color: white;">{{ __('common.english') }}</option>
                            </select>
                        </form>
                    </div>
                    <div class="d-flex gap-2">
                        @php
                            $facebook = \App\Models\Setting::get('facebook');
                            $twitter = \App\Models\Setting::get('twitter');
                            $linkedin = \App\Models\Setting::get('linkedin');
                            $instagram = \App\Models\Setting::get('instagram');
                        @endphp
                        @if($facebook)
                        <a class="btn btn-square btn-link rounded-0 border-0 text-white" href="{{ $facebook }}" target="_blank" style="color: white !important; padding: 0.5rem;"><i class="fab fa-facebook-f"></i></a>
                        @endif
                        @if($twitter)
                        <a class="btn btn-square btn-link rounded-0 border-0 text-white" href="{{ $twitter }}" target="_blank" style="color: white !important; padding: 0.5rem;"><i class="fab fa-twitter"></i></a>
                        @endif
                        @if($linkedin)
                        <a class="btn btn-square btn-link rounded-0 border-0 text-white" href="{{ $linkedin }}" target="_blank" style="color: white !important; padding: 0.5rem;"><i class="fab fa-linkedin-in"></i></a>
                        @endif
                        @if($instagram)
                        <a class="btn btn-square btn-link rounded-0 border-0 text-white" href="{{ $instagram }}" target="_blank" style="color: white !important; padding: 0.5rem;"><i class="fab fa-instagram"></i></a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->

    <!-- Content wrapper with padding for fixed navbar -->
    <div class="content-wrapper" style="padding-top: 105px;">
        @yield('content')
    </div>

    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-body footer mt-5 pt-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-4 col-md-6">
                    <h5 class="text-white mb-4">{{ __('common.contact_info') }}</h5>
                    @php
                        $footerAddress = \App\Models\Setting::get('address', 'Dörtyol, Hatay, Türkiye');
                    @endphp
                    <p class="mb-2">
                        <i class="fa fa-map-marker-alt me-3"></i>
                        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($footerAddress) }}" target="_blank" class="text-white-50 text-decoration-none contact-link-footer" title="Haritada görmek için tıklayın">
                            {{ $footerAddress }}
                        </a>
                    </p>
                    @php
                        $phonesJson = \App\Models\Setting::get('phones', '[]');
                        $phones = is_string($phonesJson) ? json_decode($phonesJson, true) : [];
                        if (!is_array($phones)) $phones = [];
                        // Convert old format to new format if needed
                        if (!empty($phones) && is_string($phones[0] ?? null)) {
                            $phones = array_map(function($phone) {
                                return ['number' => $phone, 'type' => 'phone'];
                            }, $phones);
                        }
                        if (empty($phones)) $phones = [['number' => '+90 (212) 123 45 67', 'type' => 'phone']];
                    @endphp
                    @foreach($phones as $phone)
                    @php
                        $phoneNumber = is_array($phone) ? ($phone['number'] ?? '') : $phone;
                        $phoneType = is_array($phone) ? ($phone['type'] ?? 'phone') : 'phone';
                    @endphp
                    <p class="mb-2">
                        @if($phoneType === 'whatsapp')
                        <i class="fab fa-whatsapp me-3"></i>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $phoneNumber) }}" target="_blank" class="text-white-50 text-decoration-none contact-link-footer" title="WhatsApp ile iletişime geçin">
                            {{ $phoneNumber }}
                        </a>
                        @else
                        <i class="fa fa-phone-alt me-3"></i>
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $phoneNumber) }}" class="text-white-50 text-decoration-none contact-link-footer" title="Aramak için tıklayın">
                            {{ $phoneNumber }}
                        </a>
                        @endif
                    </p>
                    @endforeach
                    @php
                        $emailsJson = \App\Models\Setting::get('emails', '[]');
                        $emails = is_string($emailsJson) ? json_decode($emailsJson, true) : [];
                        if (empty($emails)) $emails = ['info@ukelektronik.com'];
                    @endphp
                    @foreach($emails as $email)
                    <p class="mb-2">
                        <i class="fa fa-envelope me-3"></i>
                        <a href="mailto:{{ $email }}" class="text-white-50 text-decoration-none contact-link-footer" title="E-posta göndermek için tıklayın">
                            {{ $email }}
                        </a>
                    </p>
                    @endforeach
                    <div class="d-flex pt-2">
                        @php
                            $facebook = \App\Models\Setting::get('facebook');
                            $twitter = \App\Models\Setting::get('twitter');
                            $linkedin = \App\Models\Setting::get('linkedin');
                            $instagram = \App\Models\Setting::get('instagram');
                        @endphp
                        @if($facebook)
                        <a class="btn btn-square btn-outline-light btn-social" href="{{ $facebook }}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        @endif
                        @if($twitter)
                        <a class="btn btn-square btn-outline-light btn-social" href="{{ $twitter }}" target="_blank"><i class="fab fa-twitter"></i></a>
                        @endif
                        @if($linkedin)
                        <a class="btn btn-square btn-outline-light btn-social" href="{{ $linkedin }}" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                        @endif
                        @if($instagram)
                        <a class="btn btn-square btn-outline-light btn-social" href="{{ $instagram }}" target="_blank"><i class="fab fa-instagram"></i></a>
                        @endif
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h5 class="text-white mb-4">{{ __('common.quick_links') }}</h5>
                    <a class="btn btn-link" href="{{ route('home') }}">{{ __('common.home') }}</a>
                    <a class="btn btn-link" href="{{ route('about') }}">{{ __('common.about') }}</a>
                    <a class="btn btn-link" href="{{ route('service') }}">{{ __('common.services') }}</a>
                    <a class="btn btn-link" href="{{ route('project') }}">{{ __('common.projects') }}</a>
                    <a class="btn btn-link" href="{{ route('products') }}">{{ __('common.products') }}</a>
                    <a class="btn btn-link" href="{{ route('contact') }}">{{ __('common.contact') }}</a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h5 class="text-white mb-4">{{ __('common.projects') }}</h5>
                    @php
                        $footerProjects = \App\Models\Project::where('is_active', true)
                            ->orderBy('created_at', 'desc')
                            ->limit(6)
                            ->get();
                    @endphp
                    @if($footerProjects->count() > 0)
                        @foreach($footerProjects as $project)
                            <a class="btn btn-link" href="{{ route('project.detail', $project->slug) }}">{{ $project->title }}</a>
                        @endforeach
                    @else
                        <p class="text-white-50 mb-0">Henüz proje eklenmemiş.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-12 text-center">
                        &copy; <a href="{{ route('home') }}">UK Elektronik</a>, {{ __('common.all_rights_reserved') }}.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square rounded-circle back-to-top"><i class="bi bi-arrow-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('lib/counterup/counterup.min.js') }}"></script>
    <script src="{{ asset('lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('lib/isotope/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('lib/lightbox/js/lightbox.min.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('js/main.js') }}"></script>

    @yield('scripts')
    
    <style>
        .contact-link-topbar {
            transition: all 0.3s ease;
        }
        
        .contact-link-topbar:hover {
            color: #0d6efd !important;
            text-decoration: underline !important;
        }
        
        .contact-link-footer {
            transition: all 0.3s ease;
        }
        
        .contact-link-footer:hover {
            color: #ffffff !important;
            text-decoration: underline !important;
        }
        
        /* Topbar Language Select Styles */
        .topbar-language-select {
            color: white !important;
            background-color: transparent !important;
        }
        
        .topbar-language-select,
        .topbar-language-select:focus,
        .topbar-language-select:active {
            color: white !important;
            background-color: transparent !important;
        }
        
        .topbar-language-select option {
            background-color: #1a1a1a !important;
            color: white !important;
            padding: 8px !important;
        }
        
        .topbar-language-select option:hover,
        .topbar-language-select option:focus,
        .topbar-language-select option:active {
            background-color: #0d6efd !important;
            color: white !important;
        }
        
        .topbar-language-select option:checked {
            background-color: #0d6efd !important;
            color: white !important;
        }
        
        /* Select açıldığında dropdown menüsü */
        select.topbar-language-select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
        }
        
        /* Select açıldığında arka plan */
        .topbar-language-select:focus {
            background-color: rgba(0, 0, 0, 0.5) !important;
            color: white !important;
            outline: none !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
        }
        
        /* Navbar pozisyon ayarları */
        @media (min-width: 992px) {
            .navbar-mobile {
                top: 45px !important;
            }
        }
        
        @media (max-width: 991.98px) {
            .navbar-mobile {
                top: 0 !important;
            }
            
            /* Content padding mobilde azalt */
            .content-wrapper {
                padding-top: 70px !important;
            }
        }
        
        /* Mobil görünümde logo ve hamburger menü hizalama */
        @media (max-width: 991.98px) {
            .navbar .container-fluid {
                display: flex !important;
                align-items: center !important;
                justify-content: space-between !important;
            }
            
            .navbar-logo {
                max-height: 40px !important;
                height: auto;
                width: auto;
            }
            
            .navbar-brand {
                padding: 0.5rem 0.75rem !important;
                margin: 0 !important;
                flex: 0 0 auto !important;
            }
            
            .navbar-toggler {
                padding: 0.25rem 0.5rem !important;
                margin-left: auto !important;
                flex: 0 0 auto !important;
            }
        }
    </style>
</body>

</html>

