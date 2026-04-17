# Deployment (Linux + Nginx)

## Requirements

- PHP >= 8.3 + extensions: ctype, curl, dom, fileinfo, mbstring, openssl, pdo, tokenizer, xml
- Composer >= 2.x, Node.js >= 18.x
- Nginx, PHP-FPM, MySQL/PostgreSQL

## Steps

### 1. Clone & Install

```bash
git clone <repository-url> /var/www/app
cd /var/www/app
composer install --no-dev --optimize-autoloader
npm install && npm run build
```

### 2. Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
```

### 3. Database & Optimize

```bash
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### 4. Permissions

```bash
chown -R www-data:www-data /var/www/app
chmod -R 755 /var/www/app
chmod -R 775 /var/www/app/storage /var/www/app/bootstrap/cache
```

### 5. Nginx Config

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/app/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

```bash
nginx -t && systemctl reload nginx
```

### 6. Queue Worker (Supervisor)

```ini
[program:laravel-worker]
command=php /var/www/app/artisan queue:work --tries=3
autostart=true
autorestart=true
numprocs=2
user=www-data
stdout_logfile=/var/www/app/storage/logs/worker.log
```

```bash
supervisorctl reread && supervisorctl update
```
