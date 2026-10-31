# Marginalia Books (Laravel)

A full-stack online bookshop built with **Laravel** and **Blade**. Visitors can browse books and reading accessories, save favourites, fill a cart and check out. Shop owners manage everything from an admin panel.

The frontend is part of this project (Blade views, Tailwind CSS and Alpine.js), so there is nothing separate to build.

## Features

**Storefront**
- Home page with carousel, offers, categories, FAQ and newsletter signup
- Books and accessories lists with search, category, price and stock filters, sorting and pagination
- Product pages with related items
- Wishlist and shopping cart
- About and Contact pages (contact messages are saved in the admin inbox)
- Light and dark mode, scroll animations, responsive layout

**Accounts and checkout**
- Register and log in (checkout needs an account)
- Pay by **cash on delivery** or **card through Stripe Checkout**
- "My orders" page with order status

**Admin panel** (`/admin`)
- Dashboard
- Add, edit and delete products (with cover image upload)
- Manage categories
- View orders and update their status
- Inbox for contact messages and newsletter subscribers

## Tech stack

- PHP and Laravel
- Blade templates
- Tailwind CSS (CDN) and Alpine.js
- SQLite by default (any Laravel-supported database works)
- Stripe PHP SDK for card payments

## Getting started

Requirements: [Git](https://git-scm.com), PHP and [Composer](https://getcomposer.org).

```bash
# 1. Download the project
git clone https://github.com/salvamuhsin/marginalia-laravel.git
cd marginalia-laravel

# 2. Install dependencies
composer install

# 3. Create your environment file and app key
cp .env.example .env
php artisan key:generate

# 4. Create the database tables and sample data
php artisan migrate --seed

# 5. Make uploaded images (product covers) public
php artisan storage:link

# 6. Start the site
php artisan serve
```

Open http://127.0.0.1:8000.

On Windows use `copy .env.example .env` instead of `cp`.

## Admin panel

The admin panel is at `/admin`. Only users marked as admin can open it.

### What you can do there

- See a dashboard of the shop
- Add, edit and delete products, and upload cover images
- Manage categories
- View orders and update their status
- Read contact messages and newsletter subscribers in the inbox

### Log in as admin

Seeding (`php artisan migrate --seed`) creates a demo admin:


1. Go to http://127.0.0.1:8000/login and log in.
2. Open http://127.0.0.1:8000/admin.

### Create your own admin

```bash
php artisan shop:admin you@example.com --name="Your Name" --password="YourStrongPassword"
```


## Useful commands

| Command | What it does |
|---|---|
| `php artisan migrate --seed` | Creates tables, demo admin and sample catalog |
| `php artisan shop:admin {email}` | Creates an admin or promotes a user |
| `php artisan shop:export` | Exports the current products and categories to `database/seeders/data/catalog.json` |
| `php artisan storage:link` | Links `storage/app/public` to `public/storage` |
| `php artisan serve` | Runs the site locally |

## Project structure

```
app/Http/Controllers        Public pages, cart, wishlist, checkout, auth
app/Http/Controllers/Admin  Dashboard, products, categories, orders, inbox
app/Console/Commands        shop:admin and shop:export
app/Models                  Product, Category, Order and more
config/shop.php             Shop settings
database/migrations         Table definitions
database/seeders            Demo admin, shop and catalog data
resources/views             Blade templates (layout, pages, admin)
routes/web.php              All routes
public/images               Banners and illustrations
```


## React frontend

A static React version of the storefront (no backend, deployable on Netlify) lives in a separate repository: `marginalia-react`. It uses data exported with `php artisan shop:export`. Admin, real accounts and saved orders exist only in this Laravel project.

