# Production Deployment Rehberi

Bu rehber, UK Elektronik projesini canlı sunucuya (production) aktarırken yapılması gereken adımları içerir.

## Gereksinimler

- PHP >= 8.2
- Composer
- MySQL/MariaDB
- Node.js ve npm (opsiyonel, sadece frontend asset'leri için)

## Deployment Adımları

### 1. Projeyi Sunucuya Yükleyin

```bash
# Git ile çekme (önerilen)
git clone <repository-url> /path/to/project
cd /path/to/project

# Veya FTP/SFTP ile dosyaları yükleyin
```

### 2. Composer Paketlerini Yükleyin

**ÖNEMLİ:** Production ortamında `--no-dev` flag'i ile sadece production paketleri yüklenmelidir.

```bash
composer install --no-dev --optimize-autoloader
```

Bu komut:
- Sadece production paketlerini yükler (dev paketleri hariç)
- Autoloader'ı optimize eder
- `vendor/` klasörünü oluşturur

### 3. Environment Dosyasını Oluşturun

```bash
cp .env.example .env
php artisan key:generate
```

`.env` dosyasını düzenleyin:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 4. Veritabanı Migration'larını Çalıştırın

```bash
php artisan migrate --force
```

**Not:** `--force` flag'i production ortamında zorunludur.

### 5. Storage Link Oluşturun

```bash
php artisan storage:link
```

Bu komut `public/storage` klasörünü `storage/app/public` klasörüne bağlar.

### 6. Cache ve Optimizasyon

Production ortamında cache'leri optimize edin:

```bash
# Config cache
php artisan config:cache

# Route cache
php artisan route:cache

# View cache
php artisan view:cache

# Event cache (Laravel 11+)
php artisan event:cache
```

### 7. Dosya İzinlerini Ayarlayın

```bash
# Storage ve cache klasörlerine yazma izni
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 8. Frontend Asset'leri (Opsiyonel)

Eğer Vite kullanıyorsanız:

```bash
npm install
npm run build
```

**Not:** Production'da `npm run dev` kullanmayın, sadece `npm run build` kullanın.

### 9. Web Sunucu Yapılandırması

#### Apache (.htaccess zaten mevcut)

`public/` klasörünü document root olarak ayarlayın.

#### Nginx

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/project/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## Özet Komutlar (Tek Seferde)

```bash
# 1. Composer paketlerini yükle
composer install --no-dev --optimize-autoloader

# 2. Environment dosyası
cp .env.example .env
php artisan key:generate
# .env dosyasını düzenleyin

# 3. Migration
php artisan migrate --force

# 4. Storage link
php artisan storage:link

# 5. Cache optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. İzinler
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 7. Frontend (opsiyonel)
npm install
npm run build
```

## Güncelleme (Update) İşlemi

Projeyi güncellerken:

```bash
# 1. Kodları güncelle
git pull origin main

# 2. Composer paketlerini güncelle
composer install --no-dev --optimize-autoloader

# 3. Migration varsa
php artisan migrate --force

# 4. Cache'leri temizle ve yeniden oluştur
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Frontend (varsa)
npm run build
```

## Önemli Notlar

1. **`.env` dosyasını asla Git'e commit etmeyin** (zaten .gitignore'da)
2. **`vendor/` klasörünü Git'e commit etmeyin** (zaten .gitignore'da)
3. **`node_modules/` klasörünü Git'e commit etmeyin** (zaten .gitignore'da)
4. **Production'da `APP_DEBUG=false` olmalı**
5. **Composer'da `--no-dev` flag'ini kullanın** (güvenlik ve performans için)
6. **Storage ve cache klasörlerine yazma izni verin**

## Sorun Giderme

### Composer hatası alıyorsanız:
```bash
composer install --no-dev --optimize-autoloader --no-interaction
```

### Permission denied hatası:
```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### Migration hatası:
```bash
php artisan migrate:fresh --force  # DİKKAT: Tüm verileri siler!
# Veya
php artisan migrate --force
```

## Güvenlik Kontrol Listesi

- [ ] `.env` dosyası güvenli bir yerde ve doğru yapılandırılmış
- [ ] `APP_DEBUG=false` production'da
- [ ] Veritabanı şifreleri güçlü
- [ ] Storage ve cache klasörleri dışarıdan erişilemez
- [ ] SSL sertifikası kurulu (HTTPS)
- [ ] Gereksiz dosyalar silinmiş (test dosyaları, vb.)


