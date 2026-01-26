# Admin Panel Kullanım Kılavuzu

## Kurulum

1. **Veritabanı Migration'larını Çalıştırın:**
```bash
php artisan migrate
```

2. **Admin Kullanıcısı Oluşturun:**
```bash
php artisan db:seed --class=AdminUserSeeder
```

Veya manuel olarak:
- E-posta: `admin@ukelektronik.com`
- Şifre: `admin123`

## Admin Panel Erişimi

- URL: `/admin/login`
- Varsayılan Giriş Bilgileri:
  - E-posta: `admin@ukelektronik.com`
  - Şifre: `admin123`

## Özellikler

### 1. Blog Yönetimi
- Blog yazıları ekleme, düzenleme, silme
- Yayınlama/Taslak durumu kontrolü
- Kategori ve tarih yönetimi

### 2. Ürün Yönetimi
- Ürün ekleme, düzenleme, silme
- Fiyat ve kategori yönetimi
- Teknik özellikler ve özellikler (JSON formatında)
- Aktif/Pasif durumu

### 3. Hizmet Yönetimi
- Hizmet ekleme, düzenleme, silme
- İkon ve görsel yönetimi
- Özellikler ve avantajlar listesi

### 4. Proje Yönetimi
- Proje ekleme, düzenleme, silme
- Proje detayları (JSON formatında)
- Galeri görselleri
- Kategori yönetimi

### 5. SSS Yönetimi
- Soru-cevap ekleme, düzenleme, silme
- Sıralama yönetimi
- Aktif/Pasif durumu

### 6. Site Ayarları
- İletişim bilgileri (telefon, e-posta, adres)
- Sosyal medya linkleri
- Genel site ayarları

## Veritabanı Yapısı

### Tablolar:
- `blogs` - Blog yazıları
- `products` - Ürünler
- `services` - Hizmetler
- `projects` - Projeler
- `faqs` - Sık sorulan sorular
- `settings` - Site ayarları
- `users` - Admin kullanıcıları

## Notlar

- Tüm içerikler veritabanında saklanır
- JSON formatında veriler (özellikler, detaylar) textarea'dan otomatik olarak JSON'a dönüştürülür
- Slug'lar otomatik olarak başlıklardan oluşturulur
- Görseller URL olarak saklanır (dosya yükleme özelliği eklenebilir)

