## Momentus

An Event Management & Reminder App

### To Run in Local Environment

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
npm run build
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

- Run que for mail & csv import:

```
php artisan queue:listen
```

- use [XAMPP](https://www.apachefriends.org/index.html), [MAMP](https://www.mamp.info/en/mamp/windows/) etc.
  or `php artisan serve` command to use run the application in localhost

### Sample CSV

- Can be found on root `event.csv` file

```
