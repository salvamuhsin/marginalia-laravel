# Bookshop (Laravel)

A book and accessories shop with a product catalogue, categories, a cart/orders flow, and an admin panel for managing products.


## Tech Stack
Laravel 11, PHP, Blade, SQLite

## Run Locally
3. composer install
4. cp .env.example .env
5. php artisan key:generate
6. php artisan migrate --seed
7. php artisan storage:link
8. php artisan serve