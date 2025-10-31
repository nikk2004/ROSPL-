# Mobile_Shopee-E-Commerce-Website
Overview : 
Mobile Shopee is a PHP-based e-commerce website that allows users to browse, wishlist, and add mobile products to their cart.
It is built using PHP, MySQL, HTML, CSS, and Bootstrap, and runs on a local XAMPP server.

Features : 
User Registration & Login (future scope)
Product Listing and Categories
Add to Cart and Wishlist functionality
Dynamic Product Display from Database
Responsive UI built with Bootstrap
MySQL Database Integration

Tech Stack : 
Frontend: HTML, CSS, Bootstrap
Backend: PHP
Database: MySQL (phpMyAdmin)
Server: XAMPP (Apache + MySQL)

Installation & Setup : 
1️. Install Requirements : 
Download and install XAMPP
Install VS Code

2.Move Project Folder
Place your project folder inside: C:\xampp\htdocs\
Example: C:\xampp\htdocs\Mobile_Shopee

3️. Start XAMPP Services
Open XAMPP Control Panel → Start:
Apache
MySQL

4️. Create Database
Open phpMyAdmin: http://localhost/phpmyadmin
Create a new database named shopee
Import the SQL file:
    Click on Import
    Select shopee.sql (found in the project folder)
    Click Go

5️. Run the Project
Option 1 – Open in browser: http://localhost/Mobile_Shopee/index.php

Option 2 – Using VS Code Terminal:
If PHP is added to your PATH: php -S localhost:8000
