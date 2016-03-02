Start project for laravel 5.2.*, below are the commands to kickstart your project:  
  
1. `git clone -b master --single-branch https://gitlab.com/achieveee/laravel-crud.git`

2. Now firstly change git origin url to your app git repo url as later you might end in getting git push errors.

3. `composer install`

4. Create '.env' file inside your project

5. `php artisan key:generate`

6. (Optional) Naming your app - `php artisan app:name {YOUR_APP_NAME}`

7. Now create database for your Laravel app and include it's config in '.env' file

8. `php artisan migrate:refresh --seed`
  
  
**Permissions**
  
`sudo chown -R :www-data {app-directory-path}`  
`sudo chmod -R ug+rw {app-directory-path}/app/storage`  

  
Now, you've completed the configuration step :v:

9. Serve it on your local server, `php artisan serve --port=8081`
  
10. Hit this URL: http://localhost:8081/login  
  
> ## Login Credentials:

**Login ID**: admin  
**Password**: admin@111  
  
  
Enjoy...!!! :thumbsup: