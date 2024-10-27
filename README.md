download resource from github
- cmd
+ git clone........
+ composer install
--> project vscode
+ change file .env .example --> .env
--> php admin
+ create database name
--> project vscode
+ change database name file .env
- cmd
+ php artisan migrate
+ open phpmyadmin and import file: provinces.sql, districts.sql, wards.sql
+ php artisan key:generate
+ php artisan storage:link
+ php artisan db:seed --class=DatabaseSeeder
+ php artisan serve