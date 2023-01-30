<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Start

cd database

cat > databases.sqlite

cd ../

cp .env.example .env 

composer update \
npm install && npm run dev \
php artisan key:generate \
php artisan migrate \
php artisan storage:link \
php artisan test --group company_test \
php artisan test --group employee_test \
php artisan db:seed \
php artisan serve 
