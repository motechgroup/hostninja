# HostNinja Shared Hosting Deployment Guide (PHP 8.3 & MySQL / PostgreSQL)

This guide provides step-by-step instructions to set up and run **HostNinja** on a shared hosting server (such as cPanel, DirectAdmin, or Plesk) running **PHP 8.3**.

---

## 1. Prerequisites Check
Ensure your shared hosting account has:
- **PHP 8.3** active (selectable in cPanel under **Select PHP Version** or **MultiPHP Manager**).
- Required PHP Extensions enabled: `pdo`, `pdo_mysql` (or `pdo_pgsql`), `openssl`, `mbstring`, `tokenizer`, `xml`, `curl`, `json`, `fileinfo`, `zip`, `gd`.
- A MySQL / MariaDB or PostgreSQL database created in your hosting control panel.

---

## 2. Git Clone or Deployment
Via cPanel **Git™ Version Control** or SSH Terminal:

```bash
cd ~/public_html  # or your target domain root folder
git clone https://github.com/motechgroup/hostninja.git .
```

---

## 3. Environment Configuration (`.env`)
Copy the example environment file to `.env`:

```bash
cp .env.example .env
```

Edit your `.env` file and set the following parameters:

### For MySQL / MariaDB:
```env
APP_NAME=HostNinja
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cpaneluser_hostninjadb
DB_USERNAME=cpaneluser_dbuser
DB_PASSWORD=your_database_password
```

### For PostgreSQL:
```env
APP_NAME=HostNinja
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=cpaneluser_hostninjadb
DB_USERNAME=cpaneluser_dbuser
DB_PASSWORD=your_database_password
```

---

## 4. Install Dependencies & Build Application
Run the following commands in the SSH terminal (or cPanel Terminal):

```bash
# 1. Install Composer dependencies
composer install --no-dev --optimize-autoloader

# 2. Generate application key
php artisan key:generate

# 3. Create storage symlink
php artisan storage:link

# 4. Run database migrations and seed initial data
php artisan migrate --force
php artisan db:seed --class=HostNinjaSeeder --force

# 5. Cache configurations & routes for maximum performance on shared hosting
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 5. Directory & File Permissions
Ensure the following directories have write permissions (755 or 775 depending on your web host):

```bash
chmod -R 775 storage bootstrap/cache
```

---

## 6. Cron Job Setup (Laravel Scheduler)
In cPanel under **Cron Jobs**, add a cron job running every minute to process queues, domain syncs, and registrar tasks:

```cron
* * * * * cd /home/username/public_html && php artisan schedule:run >> /dev/null 2>&1
```
*(Replace `/home/username/public_html` with your actual full directory path)*

---

## 7. Troubleshooting Common Shared Hosting Issues
- **Livewire / Asset SSL Mixed Content Warning**: Ensure `APP_URL` in `.env` begins with `https://`. The root `.htaccess` and `AppServiceProvider` automatically enforce HTTPS schemes in production.
- **Database Index Limits**: `AppServiceProvider` sets `Schema::defaultStringLength(191)` automatically to prevent key length errors on older MySQL/MariaDB engines.
- **Cron Jobs Not Executing**: Ensure you specify the full path to PHP 8.3 in cPanel cron (e.g. `/usr/local/bin/php` or `/opt/cpanel/ea-php83/root/usr/bin/php`).
