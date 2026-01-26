@extends('layouts.app')

@section('title', __('common.faq') . ' - UK Elektronik')

@section('content')
    <!-- Page Header Start -->
    <div class="container-fluid page-header py-5 mb-5">
        <div class="container py-5">
            <h1 class="display-3 text-white mb-3 animated slideInDown">{{ __('common.faq') }}</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="text-white" href="{{ route('home') }}">{{ __('common.home') }}</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">{{ __('common.faq') }}</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- FAQ Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <h6 class="text-primary">{{ __('common.faq') }}</h6>
                <h1 class="mb-4">{{ __('common.faq_title') }}</h1>
                <p class="mb-4">{{ __('common.faq_description') }}</p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="accordion" id="faqAccordion">
                        @foreach($faqs as $index => $faq)
                        <div class="accordion-item wow fadeInUp" data-wow-delay="{{ 0.1 * ($index + 1) }}s">
                            <h2 class="accordion-header" id="heading{{ $index }}">
                                <button class="accordion-button {{ $index != 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="{{ $index == 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $index }}">
                                    <i class="fa fa-question-circle text-primary me-3"></i>
                                    {{ $faq['question'] }}
                                </button>
                            </h2>
                            <div id="collapse{{ $index }}" class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}" aria-labelledby="heading{{ $index }}" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p class="mb-0">{{ $faq['answer'] }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-12 text-center">
                    <div class="bg-light rounded p-5">
                        <h4 class="mb-3">Sorunuz mu var?</h4>
                        <p class="mb-4">Aradığınız cevabı bulamadınız mı? Bizimle iletişime geçin, size yardımcı olalım.</p>
                        <a href="{{ route('contact') }}" class="btn btn-primary py-3 px-5">İletişime Geç</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- FAQ End -->
@endsection

@section('styles')
<style>
    .accordion-button {
        font-weight: 500;
        color: var(--dark);
    }
    
    .accordion-button:not(.collapsed) {
        background-color: var(--light);
        color: var(--primary);
    }
    
    .accordion-button:focus {
        box-shadow: none;
        border-color: var(--primary);
    }
    
    .accordion-item {
        border: 1px solid #e9ecef;
        margin-bottom: 1rem;
        border-radius: 0.5rem;
        overflow: hidden;
    }
</style>
@endsection

