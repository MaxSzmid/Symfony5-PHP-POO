Welcome to a simple ABM with symfony 5.
I developed that web to learn about symfony framework. I used all of this tecnologies:

- [PHP 7](https://www.php.net/) for biult the backend.
- [Symfony 5](https://symfony.com/) - Framework.
- [JQUERY](https://jquery.com/) -For some utilities.
- [Composer](https://getcomposer.org/) for work with symfony and import bundles.
- [Bootstrap 4](https://getbootstrap.com/) for most of the styles of the website.
- [Xampp](https://www.apachefriends.org/es/index.html) For run local server with apache and database with MySQL.

# How to Install:

* **First of all you need to have Symfony and Composer installed**.
  - Symfony download [here](https://symfony.com/download)
  - Composer download [here](https://getcomposer.org/)
  - Some server like **apache**.

* After install Symfony and Composer you have to clone this repository into the folder for proyects of the server( example: /c/xampp/htdocs).
* Locate the console into the symfony proyect folder and execute this command: **```composer install```**
  (you are creating the vendor folder into the proyect, that folder have all the bundles and third party code).
* After that if you want to run the website, first go to [.env](https://github.com/MaxSzmid/Symfony5-PHP-POO/blob/master/.env) file located on the root folder and change the lane 32 to create a database ``` DATABASE_URL=mysql://YourUser:YourPassword@127.0.0.1:3306/TheNameOfThedatabase?serverVersion=5.7``` then execute te command ```php bin/console doctrine:database:create``` and after that execute ``` php bin/console doctrine:schema:update ```.
* Now run the local server and go to http://YOURLOCALHOST/Symfony5-PHP-POO/public/index.php/.

### If the command **```composer install```** throw an error, first execute **```composer update```** then execute again the other command.