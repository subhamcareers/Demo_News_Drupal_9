# Demo_site_Drupal_9
This project is based on Content management system (CMS) and the platform is Drupal 9.0.3

# How to get sarted with this project?
First you need to set-up your Envronmnet for the project to work on then after setting up the environment you have to imoprt the database which is "DB_demosite_drupal_ver_9.0.3.sql".

# The requirements of Drupal 9

# Apache version requirement
If you are running Drupal 9 on Apache, at least version 2.4.7 is required.

# PHP version requirement
Drupal 9 requires at least PHP 7.3. PHP 7.4 is also supported but not required. PHP 8 is not yet supported, but work will be done to support PHP 8 as soon as possible.

# Database backend and other hosting requirements
If using Drupal 9 with MySQL or Percona, version 5.7.8+ is required.
If using Drupal 9 with MariaDB, version 10.3.7+ is required.
If using Drupal 9 with SQLite, version 3.26+ is required. (PHP 7.4 does not use the system provided SQLite, so take extra care to make sure your PHP is compiled with at least this version).
If using Drupal 9 with PostgreSQL, version 10 is required with the pg_trgm extension. 
A note on Drush
While Drupal core does not require Drush, many people do use Drush. As of this writing Drush will only provide Drupal 9 compatibility in Drush 1

# Xamp
If you want a quick set of instructions to follow, go to Simple install of Drupal on XAMPP. XAMPP from Apache Friends is the easiest way to get everything (Apache, PHP and MariaDB) on a Windows machine (XP and Vista). It is installed in a few minutes and you can start developing right away.

1. Download XAMPP from Apache Friends at www.apachefriends.org/en/xampwindows.html.
2. Extract the XAMPP files to a drive by double-clicking the XAMPP self-extracting zip archive. In the Extract to:box, type C:\ (or click the button on the right to select your C:\ drive). Click the Extract button.
3. Once the files are extracted, open the extracted folder C:\xampp and run setup_xampp.bat.
4. Double-click the xampp_control.exe to open the XAMPP control panel.
5. Click the Start buttons next to both Apache and MariaDB.
6. Test your XAMPP installation by opening the web browser and typing http://localhost or http://127.0.0.1 in the address box.
7. Run XAMPP services (Apache or Drupal. Once the XAMPP page appears, go to the language section on the lefthand side under Sprachen and select English.
8. Go to Tools on the lefthand side and click on phpMyAdmin. Create a new database called Drupal: under MySQL Connection Collation, select UTF8 unicode. Under Create New Database, type in Drupal.
9. Once you get the message “database drupal has been created”, close phpMyAdmin.
10. Set your password for MariaDB for the “root” user. Open your web browser, go to http://localhost/security. Scroll down and select http://localhost/security/xamppsecurity.php. Once the security consoles opens, type in your password and click the Password Changingbutton.
11. Restart MariaDB in the control panel by clicking the Stop button next to MariaDB, then the Start button again.
12. Extract Drupal files to the C:\xampp\htdocs folder. It would be easier for future use to rename the folder “Drupal”. Open the “Drupal” folder and copy the default.settings.php file to the same folder. Rename it settings.php. Open settings.php with Wordpad and type in $db_url = ‘mysql://root:admin@localhost.drupal’;. Then scroll down and enter $base_url = ‘http://localhost/drupal’;.
13. Install your Drupal site and configure.

# FusionLeaf Stack
FusionLeaf Stack is a preconfigured web stack which allows you to run Drupal very quickly without have to install or configure any additional software. FusionLeaf Stack makes it very easy to test Drupal without wasting any time trying to follow complicated tutorials. It includes Nginx, MySQL, PHP, and Memcached - Full instructions can be found here.

# Note: Be sure to change the default MySQL password, prior to setting up Drupal, to help prevent unauthorized access if your website will be public facing.

1. Download the latest version of FusionLeaf Stack.
2. Run FusionLeaf Studio.exe
3. Click Automation -> FusionLeaf -> Remove CMS from Localhost folder -> Click OK
4. Click Open Folder -> Webroot
5. Double click on the localhost folder
6. Delete index.php
7. Download and extract Drupal files to the localhost folder
8. Click Start to start Nginx, MySQL, and PHP
9. Click Browser -> http://localhost to view the Drupal initial configuration page
10. At the Set up database Drupal page, use the following database settings:
* Database name: test
* Database username: root
* Database password: (blank)

11. Finish the Drupal configuration and your website will be up and running

If any issues faced, you can visit the following references:

"https://drupal.stackexchange.com/questions/147718/drupal-forum-solutions"
"https://www.drupal.org/documentation"
"https://www.drupal.org/"
