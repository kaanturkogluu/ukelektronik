@extends('layouts.app')

@section('title', 'Enerji Hesaplama - UK Elektronik')

@section('content')
    <!-- Page Header Start -->
    <div class="container-fluid page-header py-5 mb-5">
        <div class="container py-5">
            <h1 class="display-3 text-white mb-3 animated slideInDown">Enerji Hesaplama</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="text-white" href="{{ route('home') }}">Ana Sayfa</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">Enerji Hesaplama</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Energy Calculator Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="bg-white rounded shadow-sm p-5">
                        <div class="text-center mb-5">
                            <h3 class="mb-3">Enerji Tüketimi Hesaplama</h3>
                            <p class="text-muted">Cihazlarınızı ekleyin ve toplam enerji tüketimini hesaplayın.</p>
                        </div>

                        <!-- Add Device Form -->
                        <div class="card border-primary mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fa fa-plus-circle me-2"></i>Yeni Cihaz Ekle</h5>
                            </div>
                            <div class="card-body">
                                <div id="formError" class="alert alert-danger" style="display: none;" role="alert">
                                    <i class="fa fa-exclamation-circle me-2"></i><span id="errorMessage"></span>
                                </div>
                                <form id="energyForm">
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label fw-bold mb-2">Elektrikli Cihaz</label>
                                            <select class="form-select form-select-lg" id="deviceSelect" required>
                                                <option value="">Cihaz Seçiniz</option>
                                                <option value="1200" data-name="KLİMA">KLİMA</option>
                                                <option value="50" data-name="BUZDOLABI">BUZDOLABI</option>
                                                <option value="100" data-name="TV">TV</option>
                                                <option value="1000" data-name="ÇAMAŞIR MAKİNASI">ÇAMAŞIR MAKİNASI</option>
                                                <option value="1200" data-name="BULAŞIK MAKİNASI">BULAŞIK MAKİNASI</option>
                                                <option value="90" data-name="AYDINLATMA">AYDINLATMA</option>
                                                <option value="1000" data-name="ELEKTRİKLİ SÜPÜRGE">ELEKTRİKLİ SÜPÜRGE</option>
                                                <option value="1000" data-name="FIRIN">FIRIN</option>
                                                <option value="2000" data-name="ISITICI">ISITICI</option>
                                                <option value="150" data-name="BİLGİSAYAR">BİLGİSAYAR</option>
                                                <option value="800" data-name="ÜTÜ">ÜTÜ</option>
                                                <option value="2000" data-name="KETTLE">SU ISITICI</option>
                                                <option value="500" data-name="MİKRODALGA">MİKRODALGA</option>
                                                <option value="300" data-name="KAHVE MAKİNESİ">KAHVE MAKİNESİ</option>
                                            </select>
                                        </div>

                                        <div class="col-md-2 mb-3">
                                            <label class="form-label fw-bold mb-2">Adet</label>
                                            <input type="number" class="form-control form-control-lg" id="qty" min="1" value="1" placeholder="1" required>
                                            <small class="text-muted">Cihaz sayısı</small>
                                        </div>

                                        <div class="col-md-2 mb-3">
                                            <label class="form-label fw-bold mb-2">Gündüz Saati</label>
                                            <input type="number" class="form-control form-control-lg" id="dayHours" min="0" max="24" step="0.5" placeholder="Örn: 6" required>
                                            <small class="text-muted">Saat/gün</small>
                                        </div>

                                        <div class="col-md-2 mb-3">
                                            <label class="form-label fw-bold mb-2">Gece Saati</label>
                                            <input type="number" class="form-control form-control-lg" id="nightHours" min="0" max="24" step="0.5" placeholder="Örn: 4" required>
                                            <small class="text-muted">Saat/gün</small>
                                        </div>

                                        <div class="col-md-3 mb-3 d-flex align-items-end">
                                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                                <i class="fa fa-plus me-2"></i>Ekle
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Added Devices List -->
                        <div id="devicesContainer" class="mb-4" style="display: none;">
                            <h5 class="mb-3"><i class="fa fa-list me-2"></i>Eklenen Cihazlar</h5>
                            <div id="devicesList" class="row g-3">
                                <!-- Devices will be added here -->
                            </div>
                        </div>

                        <!-- Total Summary -->
                        <div id="totalSummary" class="card border-success mb-4" style="display: none;">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="fa fa-calculator me-2"></i>Toplam Enerji Tüketimi ve Sistem İhtiyacı</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3 mb-4">
                                    <div class="col-md-3">
                                        <div class="text-center p-4 rounded shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: 2px solid #667eea;">
                                            <small class="text-white d-block mb-2" style="opacity: 0.9; font-weight: 500;">Gündüz Toplam</small>
                                            <h3 class="mb-0 text-white fw-bold" id="totalDayEnergy">0 kWh</h3>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center p-4 rounded shadow-sm" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); border: 2px solid #1e3c72;">
                                            <small class="text-white d-block mb-2" style="opacity: 0.9; font-weight: 500;">Gece Toplam</small>
                                            <h3 class="mb-0 text-white fw-bold" id="totalNightEnergy">0 kWh</h3>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center p-4 rounded shadow-sm" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border: 2px solid #11998e;">
                                            <small class="text-white d-block mb-2" style="opacity: 0.9; font-weight: 500;">Günlük Toplam</small>
                                            <h3 class="mb-0 text-white fw-bold" id="totalDaily">0 kWh</h3>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center p-4 rounded shadow-sm" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border: 2px solid #f5576c;">
                                            <small class="text-white d-block mb-2" style="opacity: 0.9; font-weight: 500;">Toplam Güç</small>
                                            <h3 class="mb-0 text-white fw-bold" id="totalPowerW">0 W</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="text-center p-4 rounded shadow-sm" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border: 2px solid #f5576c;">
                                            <small class="text-white d-block mb-2" style="opacity: 0.9; font-weight: 500;">Gerekli Panel Sayısı</small>
                                            <h2 class="mb-0 text-white fw-bold" id="panelCount">0</h2>
                                            <small class="text-white" style="opacity: 0.8;">(610W paneller)</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center p-4 rounded shadow-sm" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border: 2px solid #4facfe;">
                                            <small class="text-white d-block mb-2" style="opacity: 0.9; font-weight: 500;">Gerekli Akü (Ah)</small>
                                            <h2 class="mb-0 text-white fw-bold" id="batteryCount">0</h2>
                                            <small class="text-white" style="opacity: 0.8;">(52V aküler)</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center p-4 rounded shadow-sm" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); border: 2px solid #43e97b;">
                                            <small class="text-white d-block mb-2" style="opacity: 0.9; font-weight: 500;">Gerekli İnverter Sayısı</small>
                                            <h2 class="mb-0 text-white fw-bold" id="inverterCount">0</h2>
                                            <small class="text-white" style="opacity: 0.8;">(kW cinsinden)</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 text-center">
                                    <button class="btn btn-danger" onclick="clearAllDevices()">
                                        <i class="fa fa-trash me-2"></i>Tümünü Temizle
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Empty State -->
                        <div id="emptyState" class="text-center py-5">
                            <i class="fa fa-plug fa-4x text-muted mb-3"></i>
                            <p class="text-muted">Henüz cihaz eklenmedi. Yukarıdaki formdan cihaz ekleyerek başlayın.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Energy Calculator End -->
@endsection

@section('scripts')
<script>
    // Sabitler
    const SUN_HOURS = 5.5;
    const PANEL_W = 610;
    const BATTERY_DIV = 52;
    const NIGHT_FACTOR = 1.5;
    const INVERTER_FACTOR = 1.25;

    let devices = [];
    let deviceCounter = 0;

    function showError(message) {
        const errorDiv = document.getElementById('formError');
        const errorMessage = document.getElementById('errorMessage');
        if (errorDiv && errorMessage) {
            errorMessage.textContent = message;
            errorDiv.style.display = 'block';
            setTimeout(function() {
                errorDiv.style.display = 'none';
            }, 5000);
        }
    }

    function hideError() {
        const errorDiv = document.getElementById('formError');
        if (errorDiv) {
            errorDiv.style.display = 'none';
        }
    }

    function addDevice() {
        const deviceSelect = document.getElementById('deviceSelect');
        const qtyInput = document.getElementById('qty');
        const dayHoursInput = document.getElementById('dayHours');
        const nightHoursInput = document.getElementById('nightHours');

        if (!deviceSelect || !qtyInput || !dayHoursInput || !nightHoursInput) {
            showError('Form elemanları bulunamadı.');
            return false;
        }

        const watt = parseFloat(deviceSelect.value);
        const qty = parseFloat(qtyInput.value) || 1;
        const dayHours = parseFloat(dayHoursInput.value) || 0;
        const nightHours = parseFloat(nightHoursInput.value) || 0;

        // Validation
        if (!watt || watt === 0) {
            showError('Lütfen bir cihaz seçin.');
            deviceSelect.focus();
            return false;
        }

        if (qty < 1) {
            showError('Adet en az 1 olmalıdır.');
            qtyInput.focus();
            return false;
        }

        if (dayHours < 0 || dayHours > 24) {
            showError('Gündüz çalışma saati 0-24 arası olmalıdır.');
            dayHoursInput.focus();
            return false;
        }

        if (nightHours < 0 || nightHours > 24) {
            showError('Gece çalışma saati 0-24 arası olmalıdır.');
            nightHoursInput.focus();
            return false;
        }

        if (dayHours === 0 && nightHours === 0) {
            showError('Gündüz veya gece çalışma saatinden en az biri 0\'dan büyük olmalıdır.');
            dayHoursInput.focus();
            return false;
        }

        hideError();

        // Get device name
        const selectedOption = deviceSelect.options[deviceSelect.selectedIndex];
        const deviceName = selectedOption.getAttribute('data-name') || selectedOption.text;

        // Hesaplama: powerW = watt * qty
        const powerW = watt * qty;
        const dayWh = powerW * dayHours;
        const nightWh = powerW * nightHours;
        const totalWh = dayWh + nightWh;

        // Create device object
        const device = {
            id: deviceCounter++,
            name: deviceName,
            watt: watt,
            qty: qty,
            powerW: powerW,
            dayHours: dayHours,
            nightHours: nightHours,
            dayWh: dayWh,
            nightWh: nightWh,
            totalWh: totalWh,
            dayKwh: dayWh / 1000,
            nightKwh: nightWh / 1000,
            totalKwh: totalWh / 1000
        };

        // Add to devices array
        devices.push(device);

        // Add device card to UI
        addDeviceCard(device);

        // Update totals
        calculateAndDisplayTotals();

        // Show containers
        document.getElementById('devicesContainer').style.display = 'block';
        document.getElementById('totalSummary').style.display = 'block';
        document.getElementById('emptyState').style.display = 'none';

        // Reset form
        deviceSelect.value = '';
        qtyInput.value = '1';
        dayHoursInput.value = '';
        nightHoursInput.value = '';
        deviceSelect.focus();

        return false;
    }

    function addDeviceCard(device) {
        const devicesList = document.getElementById('devicesList');
        
        const card = document.createElement('div');
        card.className = 'col-md-6 col-lg-4';
        card.id = 'device-card-' + device.id;
        
        card.innerHTML = `
            <div class="card border-primary h-100">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <strong>${device.name}</strong>
                    <button class="btn btn-sm btn-light" onclick="removeDevice(${device.id})" title="Sil">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <small class="text-muted">Birim Güç:</small>
                        <strong>${device.watt} W</strong>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Adet:</small>
                        <strong>${device.qty}</strong>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Toplam Güç:</small>
                        <strong>${device.powerW} W</strong>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Gündüz:</small>
                        <strong>${device.dayHours} saat/gün</strong>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Gece:</small>
                        <strong>${device.nightHours} saat/gün</strong>
                    </div>
                    <hr>
                    <div class="mb-2">
                        <small class="text-muted">Gündüz Enerji:</small>
                        <strong class="text-primary">${device.dayKwh.toFixed(2)} kWh</strong>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Gece Enerji:</small>
                        <strong class="text-info">${device.nightKwh.toFixed(2)} kWh</strong>
                    </div>
                    <div>
                        <small class="text-muted">Günlük Toplam:</small>
                        <strong class="text-success">${device.totalKwh.toFixed(2)} kWh</strong>
                    </div>
                </div>
            </div>
        `;
        
        devicesList.appendChild(card);
    }

    function removeDevice(deviceId) {
        // Remove from array
        devices = devices.filter(d => d.id !== deviceId);
        
        // Remove card from UI
        const card = document.getElementById('device-card-' + deviceId);
        if (card) {
            card.remove();
        }
        
        // Update totals
        calculateAndDisplayTotals();
        
        // Hide containers if no devices
        if (devices.length === 0) {
            document.getElementById('devicesContainer').style.display = 'none';
            document.getElementById('totalSummary').style.display = 'none';
            document.getElementById('emptyState').style.display = 'block';
        }
    }

    function clearAllDevices() {
        if (devices.length === 0) return;
        
        if (confirm('Tüm cihazları silmek istediğinize emin misiniz?')) {
            devices = [];
            deviceCounter = 0;
            document.getElementById('devicesList').innerHTML = '';
            document.getElementById('devicesContainer').style.display = 'none';
            document.getElementById('totalSummary').style.display = 'none';
            document.getElementById('emptyState').style.display = 'block';
            calculateAndDisplayTotals();
        }
    }

    function calculateAndDisplayTotals() {
        // Başlangıç değerleri
        let gt = 0;           // gündüz toplam enerji (Wh)
        let gc = 0;           // gece toplam enerji (Wh)
        let totalPowerW = 0;   // toplam anlık güç (W)

        if (devices.length === 0) {
            document.getElementById('totalDayEnergy').textContent = '0 kWh';
            document.getElementById('totalNightEnergy').textContent = '0 kWh';
            document.getElementById('totalDaily').textContent = '0 kWh';
            document.getElementById('totalPowerW').textContent = '0 W';
            document.getElementById('panelCount').textContent = '0';
            document.getElementById('batteryCount').textContent = '0';
            document.getElementById('inverterCount').textContent = '0';
            return;
        }

        // Her cihaz için döngü
        devices.forEach(device => {
            totalPowerW += device.powerW;
            gt += device.dayWh;
            gc += device.nightWh;
        });

        // Toplam enerji (kWh)
        const dayKwh = gt / 1000;
        const nightKwh = gc / 1000;
        const totalKwh = (gt + gc) / 1000;

        // Panel hesabı: (gt + (gc * NIGHT_FACTOR)) / SUN_HOURS / PANEL_W
        const panelRaw = (gt + (gc * NIGHT_FACTOR)) / SUN_HOURS / PANEL_W;
        let panelCount = Math.ceil(panelRaw);
        panelCount = Math.max(panelCount, 1);

        // Akü hesabı: (gc * NIGHT_FACTOR) / BATTERY_DIV
        const batteryRaw = (gc * NIGHT_FACTOR) / BATTERY_DIV;
        let batteryCount = Math.ceil(batteryRaw);
        batteryCount = Math.max(batteryCount, 1);

        // İnverter hesabı: (totalPowerW * INVERTER_FACTOR) / 1000
        const inverterRawKw = (totalPowerW * INVERTER_FACTOR) / 1000;
        let inverterCount = Math.ceil(inverterRawKw);
        inverterCount = Math.max(inverterCount, 1);

        // Ekranda göster
        document.getElementById('totalDayEnergy').textContent = dayKwh.toFixed(2) + ' kWh';
        document.getElementById('totalNightEnergy').textContent = nightKwh.toFixed(2) + ' kWh';
        document.getElementById('totalDaily').textContent = totalKwh.toFixed(2) + ' kWh';
        document.getElementById('totalPowerW').textContent = totalPowerW.toFixed(0) + ' W';
        document.getElementById('panelCount').textContent = panelCount;
        document.getElementById('batteryCount').textContent = batteryCount;
        document.getElementById('inverterCount').textContent = inverterCount;
    }

    document.addEventListener('DOMContentLoaded', function() {
        console.log('Energy calculator loaded');
        
        const form = document.getElementById('energyForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                e.stopPropagation();
                addDevice();
                return false;
            });
        }

        // Input değiştiğinde hata mesajını gizle
        const deviceSelect = document.getElementById('deviceSelect');
        const qtyInput = document.getElementById('qty');
        const dayHoursInput = document.getElementById('dayHours');
        const nightHoursInput = document.getElementById('nightHours');
        
        if (deviceSelect) {
            deviceSelect.addEventListener('change', hideError);
        }
        if (qtyInput) {
            qtyInput.addEventListener('input', hideError);
        }
        if (dayHoursInput) {
            dayHoursInput.addEventListener('input', hideError);
        }
        if (nightHoursInput) {
            nightHoursInput.addEventListener('input', hideError);
        }
    });
</script>
@endsection

@section('styles')
<style>
    .form-select-lg,
    .form-control-lg {
        padding: 0.75rem 1rem;
        font-size: 1rem;
    }
    
    .card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    #devicesList .card {
        animation: slideIn 0.3s ease-out;
    }
    
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endsection
