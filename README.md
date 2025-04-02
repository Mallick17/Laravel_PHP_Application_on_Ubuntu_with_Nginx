# Deployment Documentation: Laravel PHP Application on Ubuntu with Nginx

This document outlines the step-by-step process for deploying a Laravel PHP application on an Ubuntu server using Nginx as the web server, PHP 8.2, MySQL as the database, and Composer for dependency management. The deployment assumes a fresh Ubuntu instance (e.g., an AWS EC2 instance with IP `13.233.13.127`) and includes setting up the environment, installing dependencies, configuring the application, and serving it via Nginx.

---

## Prerequisites
- Ubuntu server (e.g., 20.04 or 22.04 LTS)
- Root or sudo privileges
- Internet access for package downloads
- A Git repository containing the Laravel application (e.g., `https://bitbucket.org/kalaiselvan1610/task-management-application.git`)

---

## Deployment Steps

### 1. Update the System
**Command:**
```bash
sudo apt update && sudo apt upgrade -y
```
- **Purpose**: Updates the package index and upgrades all installed packages to their latest versions to ensure the system is secure and up-to-date.

---

### 2. Install Nginx
**Command:**
```bash
apt install nginx -y
```
- **Purpose**: Installs Nginx, a high-performance web server, to serve the Laravel application over HTTP.

---

### 3. Install Software Properties Common
**Command:**
```bash
apt install -y software-properties-common
```
- **Purpose**: Installs tools to manage additional software repositories, which will be used to add a PHP repository.

---

### 4. Add PHP Repository
**Command:**
```bash
add-apt-repository ppa:ondrej/php -y
```
- **Purpose**: Adds the Ondřej Surý PPA, which provides the latest PHP versions (e.g., PHP 8.2) not available in Ubuntu’s default repositories.

---

### 5. Update Package Index Again
**Command:**
```bash
apt update -y
```
- **Purpose**: Refreshes the package index to include the newly added PHP repository.

---

### 6. Install PHP 8.2 and Required Extensions
**Command:**
```bash
apt install -y php8.2 php8.2-cli php8.2-fpm php8.2-mysql php8.2-mbstring php8.2-xml php8.2-zip php8.2-curl php8.2-gd
```
- **Purpose**: Installs PHP 8.2 and essential extensions for Laravel:
  - `php8.2-fpm`: PHP FastCGI Process Manager for Nginx integration.
  - `php8.2-mysql`: MySQL database support.
  - `php8.2-mbstring`, `php8.2-xml`, `php8.2-zip`, `php8.2-curl`, `php8.2-gd`: Required by Laravel for string handling, XML parsing, file compression, HTTP requests, and image processing.

---

### 7. Verify PHP Installation
**Command:**
```bash
php -v
```
- **Purpose**: Confirms that PHP 8.2 is installed correctly by displaying its version.

---

### 8. Install Composer
**Command:**
```bash
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
```
- **Purpose**: Downloads and installs Composer, the PHP dependency manager, globally in `/usr/local/bin` for managing Laravel dependencies.

---

### 9. Install Git
**Command:**
```bash
sudo apt install git -y
```
- **Purpose**: Installs Git to clone the Laravel application repository.

---

### 10. Install MySQL Server
**Command:**
```bash
apt install mysql-server
```
- **Purpose**: Installs MySQL, the database server used by the Laravel application.

---

### 11. Access MySQL
**Command:**
```bash
mysql -u root -p
```
- **Purpose**: Logs into the MySQL server as the root user to configure the database (e.g., create a database and user). Password prompt follows this command.
- **Assumed Actions** (not shown in history):
  - Create a database: `CREATE DATABASE laravel;`
  - Create a user: `CREATE USER 'root'@'localhost' IDENTIFIED BY 'password';`
  - Grant privileges: `GRANT ALL PRIVILEGES ON laravel.* TO 'root'@'localhost';`
  - Apply changes: `FLUSH PRIVILEGES;`
  - Exit: `EXIT;`

---

### 12. Check Nginx Status
**Command:**
```bash
systemctl status nginx.service
```
- **Purpose**: Verifies that Nginx is running and active.

---

### 13. Clone the Laravel Application
**Command:**
```bash
git clone https://bitbucket.org/kalaiselvan1610/task-management-application.git /var/www/task
```
- **Purpose**: Clones the Laravel application repository into `/var/www/task`, the directory where the application will reside.

---

### 14. Navigate to Application Directory
**Command:**
```bash
cd /var/www/task/
```
- **Purpose**: Changes the working directory to the cloned Laravel application folder.

---

### 15. List Directory Contents (Optional)
**Command:**
```bash
ls
```
- **Purpose**: Verifies the contents of the cloned repository (e.g., checks for `composer.json`, `.env.example`, etc.).

---

### 16. Install Laravel Dependencies
**Command:**
```bash
composer install
```
- **Purpose**: Installs all PHP dependencies listed in `composer.json` for the Laravel application.

---

### 17. Create Environment File
**Command:**
```bash
cp .env.example .env
```
- **Purpose**: Copies the example environment file (`.env.example`) to `.env`, which will be configured for the application.

---

### 18. Edit Environment File
**Command:**
```bash
vi .env
```
- **Purpose**: Opens the `.env` file in the `vi` editor to configure environment variables such as database credentials, application URL, and app key.
- **Key Configurations** (from provided `.env`):
  - `APP_NAME=Laravel`: Application name.
  - `APP_ENV=local`: Environment (should be `production` for live servers).
  - `APP_URL=http://localhost`: Base URL (should be updated to the server IP or domain, e.g., `http://13.233.13.127`).
  - `DB_CONNECTION=mysql`, `DB_HOST=127.0.0.1`, `DB_DATABASE=laravel`, `DB_USERNAME=root`, `DB_PASSWORD=password`: Database connection details.

<details>
    <summary>Click to view Complete Environment File</summary>

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:Vr1SdqIpmPx6rxcWsfQFiCeiU7S3w5ahyaiibjSl4Ao=
APP_DEBUG=true
APP_TIMEZONE=UTC
APP_URL=http://localhost

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
# APP_MAINTENANCE_STORE=database

PHP_CLI_SERVER_WORKERS=4

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=password

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database
CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"
```

- **Notes**: Update `APP_URL` to the server’s IP or domain (e.g., `http://13.233.13.127`) and set `APP_ENV=production` for a live environment.
    
</details>

---

### 19. Display Environment File (Verification)
**Command:**
```bash
cat .env
```
- **Purpose**: Displays the contents of `.env` to verify the configuration.

---

### 20. Generate Application Key
**Command:**
```bash
php artisan key:generate
```
- **Purpose**: Generates a unique application key for Laravel (stored in `APP_KEY` in `.env`), used for encryption and security.

---

### 21. Run Database Migrations
**Command:**
```bash
php artisan migrate
```
- **Purpose**: Executes Laravel migrations to create the database schema (tables) defined in the application’s migration files.

---

### 22-24. Set Directory Permissions
**Commands:**
```bash
chown -R www-data:www-data /var/www/html/laravel
chown -R www-data:www-data /var/www/html/task
chown -R www-data:www-data /var/www/task
```
- **Purpose**: Changes ownership of the application directories to the `www-data` user and group (Nginx’s default user) to ensure the web server can access files. Note: Commands 22 and 23 seem redundant or incorrect (`/var/www/html/laravel` and `/var/www/html/task` may not exist); only command 24 (`/var/www/task`) is relevant here.

---

### 25. Set Storage Directory Permissions
**Command:**
```bash
chmod -R 775 /var/www/task/storage/
```
- **Purpose**: Grants read, write, and execute permissions to the `storage` directory (used for logs, sessions, etc.) for the owner and group.

---

### 26-33. Configure Nginx
**Commands:**
```bash
cd /etc/nginx/sites-available/
ls
rm default
vi default
cd ../sites-enabled/
vi default
cat default
```
- **Purpose**: Configures Nginx to serve the Laravel application:
  1. Navigates to `/etc/nginx/sites-available/` and removes the default configuration file (`rm default`).
  2. Creates a new `default` file with `vi default`.
  3. Links it to `sites-enabled` (assumed, though not explicitly shown; typically done via `ln -s` or manual editing).
  4. Verifies the configuration with `cat default`.
- **Nginx Configuration** (from provided block):
  - `listen 80`: Listens on port 80 (HTTP).
  - `server_name 13.233.13.127`: Associates the server with the IP address.
  - `root /var/www/task/public`: Sets the document root to Laravel’s `public` directory.
  - `index index.php`: Specifies the default file.
  - `location /`: Rewrites requests to `index.php` for Laravel’s front-controller routing.
  - `location ~ \.php$`: Configures PHP processing via FastCGI (PHP-FPM).

<details>
    <summary>Click to view the NGINX Configuration File</summary>

```nginx
server {
    listen 80;
    server_name 13.233.13.127;
    root /var/www/task/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
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
        fastcgi_pass unix:/var/run/php/php-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```
    
</details>
---

### 34. Restart Nginx
**Command:**
```bash
systemctl restart nginx.service
```
- **Purpose**: Restarts Nginx to apply the new configuration.

---


---

## Post-Deployment Checklist
1. **Test the Application**: Access `http://13.233.13.127` in a browser to confirm the Laravel app loads.
2. **Secure MySQL**: Run `sudo mysql_secure_installation` to set a root password and remove insecure defaults.
3. **Update `.env`**:
   - Change `APP_ENV` to `production`.
   - Set `APP_DEBUG=false` for security.
   - Update `APP_URL` to the public IP or domain.
4. **Optimize Laravel**: Run `php artisan config:cache` and `php artisan route:cache` for performance.
5. **Enable HTTPS**: Configure SSL with Let’s Encrypt or another certificate authority.

---

## Troubleshooting Tips
- **Nginx Errors**: Check logs at `/var/log/nginx/error.log`.
- **PHP Errors**: Review Laravel logs at `/var/www/task/storage/logs/`.
- **Database Issues**: Ensure MySQL is running (`systemctl status mysql`) and credentials in `.env` match.

---

This deployment process ensures a fully functional Laravel application served via Nginx on an Ubuntu server.
