<div align="center">
    <img src="/public/img/logo-big.svg">
</div>

## Installation
  
1. `git clone -b master https://github.com/akhileshdarjee/origin-cms.git`

2. Update new git project URL

3. `composer install`

4. `cp .env.example .env`

5. `php artisan key:generate`

6. (Optional) Naming your app - `php artisan app:name {YOUR_APP_NAME}`

7. Set database credentials in '.env' file

8. `composer dumpautoload -o`

9. `php artisan migrate:refresh --seed`

10. `php artisan storage:link`
  
  
## Permissions

### Local (Development)
  
```
cd /path/to/your/laravel/directory
sudo find . -type f -exec chmod 664 {} \;
sudo find . -type d -exec chmod 755 {} \;

sudo chown -R :www-data app/
sudo chmod -R ug+rwx app/
sudo chown -R :www-data bootstrap/cache
sudo chmod -R ug+rwx bootstrap/cache
sudo chown -R :www-data database/migrations
sudo chmod -R ug+rwx database/migrations
sudo chown -R :www-data storage/
sudo chmod -R ug+rwx storage/
sudo chown -R :www-data resources/lang
sudo chmod -R ug+rw resources/lang
```

### Production (Server)
  
```
cd /path/to/your/laravel/directory
sudo find . -type f -exec chmod 664 {} \;
sudo find . -type d -exec chmod 755 {} \;

sudo chown -R :www-data bootstrap/cache
sudo chmod -R ug+rwx bootstrap/cache
sudo chown -R :www-data storage/
sudo chmod -R ug+rwx storage/
sudo chown -R :www-data resources/lang
sudo chmod -R ug+rw resources/lang
```

  
Now, you've completed the configuration step :v:

11. Serve it on your local server, `php artisan serve --port=8081`
  
12. Hit the URL: http://localhost:8081/admin
  
Enjoy...!!! :thumbsup:
