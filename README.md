## Laravel, Vue, Inertia & Tailwind based boilerplate
A boilerplate built with Laravel, Vue, Inertia & Tailwind

### Installation
- install PHP dependencies via [composer](https://getcomposer.org/):
```
composer install
```
- install js dependencies via [npm](https://nodejs.org/en/):
```
npm install
```
- compile js & css assets:
```
npm run dev
```
- copy .env.example file & create a new .env file using terminal:
```
cp .env.example .env
```
- generate an application key:
```
php artisan key:generate
```
- set project configurations in .env file
- create a MySQL database
- create tables in database with default data:
```
php artisan migrate --seed
```
- use [XAMPP](https://www.apachefriends.org/index.html), [MAMP](https://www.mamp.info/en/mamp/windows/) etc. or `php artisan serve` command to use run the application in localhost

### Default Data
Roles:
```
1. System Admin
2. Super Admin
3. Admin
```

Users:
```
System Admin

Email: portaladmin@buyoniasoft.com
Password: Buyonia@2024
```
