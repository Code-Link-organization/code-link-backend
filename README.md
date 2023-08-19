<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>



## CodeLink


- CodeLink is designed to help those interested in the field of Software find projects that match their interests and connect with experienced professionals to bridge the gap between theory and practice.

### Installation

1- Clone the project

```bash
  git clone https://github.com/EmanElhelaly11/CodeLink.git
```

2- Set up the configuration

1. Rename `.env.example` to `.env`.

2. Create a new database in your local database management system (e.g., phpMyAdmin).

3. Update the `DB_DATABASE` value in `.env` with the name of the database you created. For example: `DB_DATABASE=codelink`


3- Install dependencies

```bash
  composer install
  php artisan key:generate
```
4- Run database migrations

```bash
  php artisan migrate 
  php artisan migrate:fresh
```

5- Launch the application

```bash
  php artisan serve 
```