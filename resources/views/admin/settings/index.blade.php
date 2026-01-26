@extends('admin.layouts.app')

@section('title', 'Ayarlar')
@section('page-title', 'Site Ayarları')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Site Ayarları</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            @method('POST')
            
            <h6 class="mb-3">İletişim Bilgileri</h6>
            <div class="row mb-4">
                <!-- Telefon Numaraları -->
                <div class="col-12 mb-3">
                    <label class="form-label">Telefon Numaraları <small class="text-muted">(Birden fazla telefon numarası ekleyebilirsiniz)</small></label>
                    <div id="phones_container">
                        @php
                            $phonesJson = App\Models\Setting::get('phones', '[]');
                            $phones = is_string($phonesJson) ? json_decode($phonesJson, true) : [];
                            if (!is_array($phones)) $phones = [];
                            // Eski formatı yeni formata çevir (string array ise)
                            if (!empty($phones) && is_string($phones[0] ?? null)) {
                                $phones = array_map(function($phone) {
                                    return ['number' => $phone, 'type' => 'phone'];
                                }, $phones);
                            }
                            if (empty($phones)) $phones = [['number' => '+90 (212) 123 45 67', 'type' => 'phone']];
                        @endphp
                        @foreach($phones as $index => $phone)
                        <div class="mb-2 phone-item">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <input type="text" class="form-control phone-input" name="phone_numbers[]" value="{{ is_array($phone) ? ($phone['number'] ?? $phone) : $phone }}" placeholder="Telefon numarası (örn: +90 (212) 123 45 67)">
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control phone-type" name="phone_types[]">
                                        <option value="phone" {{ (is_array($phone) ? ($phone['type'] ?? 'phone') : 'phone') === 'phone' ? 'selected' : '' }}>Telefon Araması</option>
                                        <option value="whatsapp" {{ (is_array($phone) ? ($phone['type'] ?? 'phone') : 'phone') === 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger w-100 remove-phone">Sil</button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-success btn-sm mt-2" onclick="addPhoneRow()">+ Telefon Ekle</button>
                </div>
                
                <!-- Toplu Görüntülenecek Telefon -->
                <div class="col-12 mb-3">
                    <label class="form-label">Topbar'da Görüntülenecek Telefon <small class="text-muted">(Sitenin üst kısmındaki topbar'da görüntülenecek telefon numarası - sadece bir numara gösterilir)</small></label>
                    <select class="form-control" name="display_phone" id="display_phone">
                        <option value="">Seçiniz (İlk numara otomatik seçilir)</option>
                        @foreach($phones as $index => $phone)
                        @php
                            $phoneNumber = is_array($phone) ? ($phone['number'] ?? '') : $phone;
                            $phoneType = is_array($phone) ? ($phone['type'] ?? 'phone') : 'phone';
                            $phoneDisplay = $phoneNumber . ' (' . ($phoneType === 'whatsapp' ? 'WhatsApp' : 'Telefon') . ')';
                        @endphp
                        <option value="{{ $phoneNumber }}" {{ App\Models\Setting::get('display_phone') == $phoneNumber ? 'selected' : '' }}>
                            {{ $phoneDisplay }}
                        </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Topbar'da (sitenin en üst kısmı) hangi telefon numarasının gösterileceğini seçin. Seçilmezse ilk numara otomatik gösterilir.</small>
                </div>
                
                <!-- E-posta Adresleri -->
                <div class="col-12 mb-3">
                    <label class="form-label">E-posta Adresleri <small class="text-muted">(Birden fazla e-posta adresi ekleyebilirsiniz)</small></label>
                    <div id="emails_container">
                        @php
                            $emailsJson = App\Models\Setting::get('emails', '[]');
                            $emails = is_string($emailsJson) ? json_decode($emailsJson, true) : [];
                            if (!is_array($emails)) $emails = [];
                            if (empty($emails)) $emails = ['info@ukelektronik.com'];
                        @endphp
                        @foreach($emails as $index => $email)
                        <div class="mb-2 email-item">
                            <div class="input-group">
                                <input type="email" class="form-control email-input" name="emails[]" value="{{ $email }}" placeholder="E-posta adresi (örn: info@ukelektronik.com)">
                                <button type="button" class="btn btn-danger remove-email">Sil</button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-success btn-sm mt-2" onclick="addEmailRow()">+ E-posta Ekle</button>
                </div>
                
                <!-- Topbar'da Görüntülenecek E-posta -->
                <div class="col-12 mb-3">
                    <label class="form-label">Topbar'da Görüntülenecek E-posta <small class="text-muted">(Sitenin üst kısmındaki topbar'da görüntülenecek e-posta adresi - sadece bir e-posta gösterilir)</small></label>
                    <select class="form-control" name="display_email" id="display_email">
                        <option value="">Seçiniz (İlk e-posta otomatik seçilir)</option>
                        @foreach($emails as $index => $email)
                        <option value="{{ $email }}" {{ App\Models\Setting::get('display_email') == $email ? 'selected' : '' }}>
                            {{ $email }}
                        </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Topbar'da (sitenin en üst kısmı) hangi e-posta adresinin gösterileceğini seçin. Seçilmezse ilk e-posta otomatik gösterilir.</small>
                </div>
                
                <div class="col-12 mb-3">
                    <label class="form-label">Adres</label>
                    <input type="text" class="form-control" name="address" value="{{ App\Models\Setting::get('address', 'Dörtyol, Hatay, Türkiye') }}">
                </div>
                
                <div class="col-12 mb-3">
                    <label class="form-label">Ürün Bilgilendirmesi WhatsApp Numarası <small class="text-muted">(Ürün sayfalarında bilgi almak için kullanılacak WhatsApp numarası)</small></label>
                    <input type="text" class="form-control" name="product_whatsapp" value="{{ App\Models\Setting::get('product_whatsapp', '') }}" placeholder="+90 555 123 45 67">
                    <small class="text-muted">Ürün bilgilendirmesi için kullanılacak WhatsApp numarasını girin. Örnek: +90 555 123 45 67</small>
                </div>
                
                <div class="col-12 mb-3">
                    <label class="form-label">Harita Iframe Kodu <small class="text-muted">(Google Maps embed iframe kodu)</small></label>
                    <textarea class="form-control" name="map_iframe" rows="5" placeholder="<iframe src=&quot;...&quot;></iframe>">{{ App\Models\Setting::get('map_iframe', '') }}</textarea>
                    <small class="text-muted">Google Maps'ten embed iframe kodunu buraya yapıştırın.</small>
                </div>
            </div>

            <hr class="my-4">

            <h6 class="mb-3">Sosyal Medya</h6>
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Facebook</label>
                    <input type="url" class="form-control" name="facebook" value="{{ App\Models\Setting::get('facebook') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Twitter</label>
                    <input type="url" class="form-control" name="twitter" value="{{ App\Models\Setting::get('twitter') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">LinkedIn</label>
                    <input type="url" class="form-control" name="linkedin" value="{{ App\Models\Setting::get('linkedin') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Instagram</label>
                    <input type="url" class="form-control" name="instagram" value="{{ App\Models\Setting::get('instagram') }}">
                </div>
            </div>

            <hr class="my-4">

            <h6 class="mb-3">Genel Ayarlar</h6>
            <div class="row mb-4">
                <div class="col-12 mb-3">
                    <label class="form-label">Site Başlığı</label>
                    <input type="text" class="form-control" name="site_title" value="{{ App\Models\Setting::get('site_title', 'UK Elektronik') }}">
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label">Site Açıklaması</label>
                    <textarea class="form-control" name="site_description" rows="3">{{ App\Models\Setting::get('site_description') }}</textarea>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Kaydet</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
@php
    $phonesJson = App\Models\Setting::get('phones', '[]');
    $phones = is_string($phonesJson) ? json_decode($phonesJson, true) : [];
    if (!is_array($phones)) $phones = [];
    if (empty($phones)) $phones = ['+90 (212) 123 45 67'];
    
    $emailsJson = App\Models\Setting::get('emails', '[]');
    $emails = is_string($emailsJson) ? json_decode($emailsJson, true) : [];
    if (!is_array($emails)) $emails = [];
    if (empty($emails)) $emails = ['info@ukelektronik.com'];
@endphp
<script>
let phoneCounter = {{ count($phones) }};
let emailCounter = {{ count($emails) }};

function updatePhoneRemoveButtons() {
    const items = document.querySelectorAll('.phone-item');
    items.forEach(item => {
        const removeBtn = item.querySelector('.remove-phone');
        if (removeBtn) {
            removeBtn.style.display = items.length > 1 ? 'block' : 'none';
        }
    });
    updateDisplayPhoneOptions();
}

function updateEmailRemoveButtons() {
    const items = document.querySelectorAll('.email-item');
    items.forEach(item => {
        const removeBtn = item.querySelector('.remove-email');
        if (removeBtn) {
            removeBtn.style.display = items.length > 1 ? 'block' : 'none';
        }
    });
}

function addPhoneRow() {
    const container = document.getElementById('phones_container');
    const newRow = document.createElement('div');
    newRow.className = 'mb-2 phone-item';
    newRow.innerHTML = `
        <div class="row g-2">
            <div class="col-md-6">
                <input type="text" class="form-control phone-input" name="phone_numbers[]" placeholder="Telefon numarası (örn: +90 (212) 123 45 67)">
            </div>
            <div class="col-md-4">
                <select class="form-control phone-type" name="phone_types[]">
                    <option value="phone">Telefon Araması</option>
                    <option value="whatsapp">WhatsApp</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger w-100 remove-phone">Sil</button>
            </div>
        </div>
    `;
    container.appendChild(newRow);
    
    newRow.querySelector('.remove-phone').addEventListener('click', function() {
        newRow.remove();
        updatePhoneRemoveButtons();
    });
    
    newRow.querySelector('.phone-input').addEventListener('input', function() {
        updateDisplayPhoneOptions();
    });
    
    newRow.querySelector('.phone-type').addEventListener('change', function() {
        updateDisplayPhoneOptions();
    });
    
    updatePhoneRemoveButtons();
}

function addEmailRow() {
    const container = document.getElementById('emails_container');
    const newRow = document.createElement('div');
    newRow.className = 'mb-2 email-item';
    newRow.innerHTML = `
        <div class="input-group">
            <input type="email" class="form-control email-input" name="emails[]" placeholder="E-posta adresi (örn: info@ukelektronik.com)">
            <button type="button" class="btn btn-danger remove-email">Sil</button>
        </div>
    `;
    container.appendChild(newRow);
    
    newRow.querySelector('.remove-email').addEventListener('click', function() {
        newRow.remove();
        updateEmailRemoveButtons();
        updateDisplayEmailOptions();
    });
    
    newRow.querySelector('.email-input').addEventListener('input', function() {
        updateDisplayEmailOptions();
    });
    
    updateEmailRemoveButtons();
    updateDisplayEmailOptions();
}

function updateDisplayPhoneOptions() {
    const displayPhoneSelect = document.getElementById('display_phone');
    const currentValue = displayPhoneSelect.value;
    const phones = [];
    
    document.querySelectorAll('.phone-item').forEach(item => {
        const input = item.querySelector('.phone-input');
        const select = item.querySelector('.phone-type');
        const number = input.value.trim();
        const type = select.value;
        if (number) {
            phones.push({number: number, type: type});
        }
    });
    
    displayPhoneSelect.innerHTML = '<option value="">Seçiniz</option>';
    phones.forEach(phone => {
        const option = document.createElement('option');
        option.value = phone.number;
        option.textContent = phone.number + ' (' + (phone.type === 'whatsapp' ? 'WhatsApp' : 'Telefon') + ')';
        if (phone.number === currentValue) {
            option.selected = true;
        }
        displayPhoneSelect.appendChild(option);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.remove-phone').forEach(btn => {
        btn.addEventListener('click', function() {
            this.closest('.phone-item').remove();
            updatePhoneRemoveButtons();
        });
    });
    
    document.querySelectorAll('.remove-email').forEach(btn => {
        btn.addEventListener('click', function() {
            this.closest('.email-item').remove();
            updateEmailRemoveButtons();
        });
    });
    
    document.querySelectorAll('.phone-input').forEach(input => {
        input.addEventListener('input', function() {
            updateDisplayPhoneOptions();
        });
    });
    
    document.querySelectorAll('.phone-type').forEach(select => {
        select.addEventListener('change', function() {
            updateDisplayPhoneOptions();
        });
    });
    
    document.querySelectorAll('.email-input').forEach(input => {
        input.addEventListener('input', function() {
            updateDisplayEmailOptions();
        });
    });
    
    updatePhoneRemoveButtons();
    updateEmailRemoveButtons();
    updateDisplayPhoneOptions();
    updateDisplayEmailOptions();
    
    document.querySelector('form').addEventListener('submit', function(e) {
        // Collect phones with types
        const phones = [];
        document.querySelectorAll('.phone-item').forEach(item => {
            const input = item.querySelector('.phone-input');
            const select = item.querySelector('.phone-type');
            const number = input.value.trim();
            const type = select.value;
            if (number) {
                phones.push({number: number, type: type});
            }
        });
        
        // Collect emails
        const emails = [];
        document.querySelectorAll('.email-input').forEach(input => {
            const value = input.value.trim();
            if (value) {
                emails.push(value);
            }
        });
        
        // Add hidden inputs
        const phonesInput = document.createElement('input');
        phonesInput.type = 'hidden';
        phonesInput.name = 'phones_json';
        phonesInput.value = JSON.stringify(phones);
        this.appendChild(phonesInput);
        
        const emailsInput = document.createElement('input');
        emailsInput.type = 'hidden';
        emailsInput.name = 'emails_json';
        emailsInput.value = JSON.stringify(emails);
        this.appendChild(emailsInput);
    });
});
</script>
@endsection

