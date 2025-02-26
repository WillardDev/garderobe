# Wardrobe Management Application

A modern web application for managing your clothing collection, built with Laravel and Vue.js. Track, categorize, and organize your clothing items with an intuitive user interface.

## Features

- Track clothing items with details (brand, color, size, category)
- Upload images for each clothing item
- View your collection with filters and sorting
- Color matching and outfit suggestions
- Dashboard with statistics about your wardrobe

## Tech Stack

- Backend: Laravel 11
- Frontend: Vue 3 with Vue Router
- CSS: Tailwind CSS
- Build Tool: Vite

## Requirements

- PHP 8.1 or higher
- Composer
- Node.js and npm/yarn
- MySQL or compatible database

## Installation

### 1. Clone the repository

```bash
git clone <repository-url>
cd wardrobe-app
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Install JavaScript dependencies

```bash
npm install
```

### 4. Configure environment

Copy the `.env.example` file to `.env` and configure your database connection:

```bash
cp .env.example .env
```

Edit the `.env` file and update the database connection details:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wardrobe
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Generate application key

```bash
php artisan key:generate
```

### 6. Run database migrations

```bash
php artisan migrate
```

### 7. Create storage link

This creates a symbolic link from `public/storage` to `storage/app/public`:

```bash
php artisan storage:link
```

### 8. Prepare clothing images folder

Create a folder for clothing images in the storage directory:

```bash
mkdir -p storage/app/public/clothes
```

Add your clothing images to this directory (`storage/app/public/clothes`). The application will look for images here when generating clothing items. For optimal results, name your images to match the clothing type (e.g., "t-shirt.jpg", "jeans.png", etc.).

### 9. Seed the database

The application comes with seeders to populate the database with sample data:

```bash
php artisan db:seed
```

This will run the following seeders in order:
- UserSeeder - Creates user accounts
- CategorySeeder - Creates clothing categories
- ClothingItemSeeder - Generates clothing items using the images in storage/app/public/clothes

### 10. Start the development server

In one terminal, start the Laravel development server:

```bash
php artisan serve
```

In another terminal, start the Vite development server:

```bash
npm run dev
```

### 11. Access the application

Open your browser and navigate to:

```
http://localhost:8000
```

## Development

### Building for production

```bash
npm run build
```

### Customizing seeders

You can modify the seeders in the `database/seeders` directory to change the sample data. The `ClothingItemSeeder` will use the images in the `storage/app/public/clothes` directory to generate clothing items.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
