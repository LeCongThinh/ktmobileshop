download resource from github
- Open command line
+ Step 1: git clone https://github.com/LeCongThinh/ktmobileshop.git
+ Step 2: composer install
- Open project in vscode
+ Step 3: change file name .env .example --> .env
- Open xampp, then open phpMyAdmin
+ Step 4: create database name
- Open project in vscode
+ Step 5: change database name "DB_DATABASE" in file .env
- Open command line
+ Step 6: php artisan migrate
+ Step 7: open phpMyAdmin and import file: provinces.sql, districts.sql, wards.sql
+ Step 8: php artisan key:generate
+ Step 9: php artisan storage:link
+ Step 10: php artisan db:seed --class=DatabaseSeeder
+ Step 11: php artisan serve