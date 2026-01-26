@extends('admin.layouts.app')

@section('title', 'Yeni SSS')
@section('page-title', 'Yeni Soru Ekle')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Yeni Soru Ekle</h5>
    </div>
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        
        <form action="{{ route('admin.faqs.store') }}" method="POST">
            @csrf
            <!-- Soru - Çoklu Dil -->
            <div class="mb-3">
                <label class="form-label">Soru *</label>
                <ul class="nav nav-tabs mb-2" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#question_tr" type="button" role="tab">Türkçe</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#question_en" type="button" role="tab">English</button>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="question_tr" role="tabpanel">
                        <textarea class="form-control" name="question_tr" rows="2" required>{{ old('question_tr') }}</textarea>
                    </div>
                    <div class="tab-pane fade" id="question_en" role="tabpanel">
                        <textarea class="form-control" name="question_en" rows="2" required>{{ old('question_en') }}</textarea>
                    </div>
                </div>
            </div>
            
            <!-- Cevap - Çoklu Dil -->
            <div class="mb-3">
                <label class="form-label">Cevap *</label>
                <ul class="nav nav-tabs mb-2" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#answer_tr" type="button" role="tab">Türkçe</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#answer_en" type="button" role="tab">English</button>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="answer_tr" role="tabpanel">
                        <textarea class="form-control" name="answer_tr" rows="5" required>{{ old('answer_tr') }}</textarea>
                    </div>
                    <div class="tab-pane fade" id="answer_en" role="tabpanel">
                        <textarea class="form-control" name="answer_en" rows="5" required>{{ old('answer_en') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Sıralama</label>
                    <input type="number" class="form-control" name="sort_order" value="{{ old('sort_order', 0) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-check mt-4">
                        <input type="checkbox" class="form-check-input" name="is_active" id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Aktif</label>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Kaydet</button>
                <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary">İptal</a>
            </div>
        </form>
    </div>
</div>
@endsection

