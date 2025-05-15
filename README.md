# To_do_list_project
The following are the basic steps to setup the project:-


****Step1:-    Install  LARAVEL 9
**
First, make sure you have Composer installed on your system.
To create a new Laravel 9 project, run the following command in your terminal:
composer create-project --prefer-dist laravel/laravel todo-list "9.*"

**Step2:- 2. Set Up Connection Configuration to database 
**
Open the .env file and configure your database settings:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=todo_db
DB_USERNAME=root
DB_PASSWORD=
Now, create a database in MySQL named todo_db.

**Step3:- Create the  migration folder and migration file 
**
php artisan migrate 

Step4:-  Complete the code in views and controller file for the logics 

Step5:- Test application on local 
php artisan serve ->  http://127.0.0.1:8000/



Note: PLease get the code from master branch rather than main branch .Its empty branch

