<!-- Readable markdown preview in VS Code is CTRL + SHIFT + V -->
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>
<br>

> # Tiešsaistes bibliotēkas pārvaldības sistēma <sup><sub>LV</sub></sup>

## Apraksts

Tiešsaistes bibliotēkas pārvaldības sistēma, kas ļauj lietotājiem pārlūkot katalogu, aizņemties grāmatas, atgriezt aizņemtās grāmatas un nodrošina administratoriem grāmatu pārvaldības iespējas.
Šis projekts ir tikai mācību nolūkos!

## Funkcionalitāte jeb iespējas

- **Grāmatu katalogs:** Rāda pieejamās grāmatas ar to nosaukumiem, autoriem, izdošanas gadiem.
- **Grāmatu aizņemšana:** Lietotāji var aizņemties grāmatas uz noteiktu laiku.
- **Grāmatu atgriešana:** Lietotāji var atgriezt aizņemtās grāmatas.
- **Grāmatu pārvaldība:** Administrators var pievienot jaunas grāmatas katalogam, dzēst esošas grāmatas un rediģēt to informāciju.


## Izmantotās tehnoloģijas

- **MySQL**: Grāmatu un aizņemšanas datu glabāšana.
- **HTML/CSS**: Priekšgala struktūra un vizuālo elementu dizains.
- **JavaScript**: Dinamiskas front-end funkcionalitātes realizēšana, piemēram, grāmatu pievienošana grozam bez lapas pārlādēšanas.
- **Responsīvs dizains**: Pielāgojas dažādiem ekrāna izmēriem.

## Instalēšana

1. Izveidojiet arhīva dublējumkopiju:
   ```bash
   git clone https://github.com/your-username/online-library.git
2. Konfigurējiet datubāzi `.env` failā.
3. Instalējiet papildinājumus no kuriem programma ir atkarīga:
   ```bash
    composer install
    npm install
4. Veiciet MySQL datubāzes migrēšanu:
   ```bash
    php artisan migrate
5. Startējiet serveri:
   ```bash
    php artisan serve
6. Piekļūstiet lietojumprogrammai http://localhost:8000 :grinning:

<br><br>

> # Online Library Management System <sup><sub>EN</sub></sup>

## Description

An online library management system that allows users to browse the catalog, borrow books, return borrowed books, and provides administrators with book management capabilities.
This project is only for learning purposes!

## Features

- **Book Catalog:** Display available books with details.
- **Book Borrowing:** Users can borrow books.
- **Book Return:** Users can return borrowed books.
- **Book Management:** The page admin can add, delete, and edit book information.

## Technologies Used

- **MySQL**: Data storage.
- **HTML/CSS**: Front-end structure and design.
- **JavaScript**: Dynamic front-end functionalities.
- **Responsive Design**: Adaptable to different screen sizes.

## Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/your-username/online-library.git
2. Configure the database in the `.env` file.
3. Install dependencies:
   ```bash
    composer install
    npm install
4. Run migrations:
   ```bash
    php artisan migrate
5. Start the server:
   ```bash
    php artisan serve
6. Access the application at http://localhost:8000 :grinning:

## Initial development process
1. composer create-project laravel/laravel example-app
2. composer require laravel/ui
3. php artisan ui:auth
4. npm install (for running vite scripts idk)
5. npm run dev
6. edit the laravel app/providers/AppServiceProvider boot() function to handle any MySQL limitations
7. php artisan migrate (need to configure a connection to database in .env file)