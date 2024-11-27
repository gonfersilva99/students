*Version 1.2. Update 2020-04-27*
# Yii2 Students Web App

* [Configuring Xdebug in PHP](#configuring-xdebug-in-php)
* [Define ER Model and create database](#define-ER-model-and-create-database)
* [Initial configuration for Students App](#initial-configuration-for-students-app)
* [Define layout and static pages](#define-layout-and-static-pages)
* [Use Gii to generate models and CRUD](#use-gii-to-generate-models-and-crud)
* [Use a DropDownList to choose a program in a degree](#use-a-dropdownlist-to-choose-a-program-in-a-degree)
* [Authentication module (extension): Yii2 user](#authentication-module-extension-yii2-user)

## Configuring Xdebug in PHP
Edit php.ini and add the following lines in the end:
```
[xdebug]
zend_extension="C:\xampp\php\ext\php_xdebug-3.x.x.dll"
xdebug.mode=debug
```

In PhpStorm, goto menu File | Settings... | PHP | Debug | Debug port, type 9003

## Define ER Model and create database
1. Open mysql command with bash shell (Linux, git bash), cmd (windows) or terminal in PhpStorm:
   ```bash
   $ mysql -u root -p
   ```
1. Create a database (schema):
   ```sql
   CREATE DATABASE students;
   ```

1. Create a database user:
   ```sql
   CREATE USER dw@localhost identified by 'mypass123!"#';
   ```

1. Grant access to user on database:
   ```sql
   GRANT ALL PRIVILEGES ON students.* to dw@localhost;
   ```

1. Define the following ER model and synchronize it with the database in MySQL Workbench.
![students](uploads/6607a33c3bceebdf79424fb23cb6bc50/students.png)


[students.mwb](uploads/68c8a83fdb0907c9f592092f7fe5d377/students.mwb)

   **ATENTION**
   MySQL Workbench 8 synchronization might fail. To solve the problem in MySQL Workbench:
   * Go to Menu `Edit` -> `Preferences...`
   * On the left pane, select `MySQL` inside `Modeling`
   * In the `Default Target MySQL Version` textbox, type: 5.6

## Initial configuration for Students App


1. Open git bash or cmd

1. Create Project
   ```bash
   $ composer create-project --prefer-dist yiisoft/yii2-app-basic students-yii
   ```

1. Create gitlab project

1. Open in PhpStorm

1. Create git repository in PhpStorm: `VCS | Create Git Repository`

1. Start PHP test web server using Terminal in PhpStorm: 
    ```bash
   php -S localhost:80 -t web
    ```

1. In PhpStorm Add a configuration "PHP Web Page" for localhost/students-yii

1. Config pretty urls: https://www.yiiframework.com/doc/guide/2.0/en/runtime-routing#using-pretty-urls
 
 

1. Configure database in `config/db.php`:
   ``` php
   <?php

   return [
       'class' => 'yii\db\Connection',
       'dsn' => 'mysql:host=localhost;dbname=students',
       'username' => 'dw',
       'password' => 'mypass123!"#',
       'charset' => 'utf8',
   ];
   ```


## Define layout and static pages 

1. Check layout documentation: https://www.yiiframework.com/doc/guide/2.0/en/structure-views#layouts
1. Add FAQ and Contacts pages: https://www.yiiframework.com/doc/guide/2.0/en/start-hello#saying-hello



## Use Gii to generate models and CRUD

1. Generate the models with gii: https://www.yiiframework.com/doc/guide/2.0/en/start-gii#generating-ar

1. Generate the CRUD with Gii: https://www.yiiframework.com/doc/guide/2.0/en/start-gii#generating-crud
   For CRUD Generator use:
   1. Model Class
      ```
      app\models\Subject
      ```
   1. Search Model Class
      ```
      app\models\Search\SubjectSearch
      ```
   1. Controller Class
      ```
      app\controllers\SubjectController
      ```
   1. Enable Pjax

1. Access `http://localhost/students-yii/program

1. Replace Yii2 GridView for Kartik GridView





## Use a DropDownList to choose a program in a degree

In `models/Program.php`, add:
```php
    public static function getAllAsArray(){
        $query=Program::find()
            ->orderBy([
                'name' => SORT_ASC,
            ]);
        $items = $query->asArray()->all();
        $data=ArrayHelper::map($items, 'id', 'name');
        return($data);
    }
```

In `views/subject/_form.php`:

Remove:
```php
    <?= $form->field($model, 'program_id')->textInput() ?>
```

Add:
```php
    <?php
    $programs = Program::getAllAsArray();
    echo $form->field($model, "program_id")
        ->dropDownList(
            $programs, ['prompt' => Yii::t('app', '-- Select Program --')]);
    ?>
```

In `views/subject/index.php`:
remove:
```php
            'program_id',
```

add:
```php
            'program.name',
```



## Authentication module (extension): Yii2 user


* Install from: https://github.com/amnah/yii2-user

  An email account (gmail, outlook, etc.) to send confirmation emails is advisable. Be careful because credentials are exposed.
   
  * Add `"amnah/yii2-user": "^5.0"` to `composer.json` and then type `composer update`
  * Configure `config/web.php`:
    ```php
    'components' => [          
        ...  
        'user' => [
            'class' => 'amnah\yii2\user\components\User',
        ],
    ],
    'modules' => [
        ...
        'user' => [
            'class' => 'amnah\yii2\user\Module',
            // set custom module properties here ...
        ],
    ],
    ```
  * You need configure `mailer` to send confirmation emails
  
  * If no confirmation email is needed add in `config/web.php` instead:
    ```php
    'user' => [
                'class' => 'amnah\yii2\user\Module',
                // set custom module properties here ...
                'requireEmail' => false,
                'requireUsername' => true
            ],
    ```  

  * Run migration in the command line:
  ```bash
  php yii migrate --migrationPath=@vendor/amnah/yii2-user/migrations
  ```
  
* Is advisable to reverse engineer ER model in MySQL Workbench to include module tables

* Old user code (from Yii2 instalation) might be deleted

* Add new role:
    * Add column `can_student` to `role` table
    
    * Add row with Student role

* Checking permissions:
```php
if (!Yii::$app->user->can("admin")) {
    throw new HttpException(403, 'You are not allowed to perform this action.');
}
// --- or ----
$user = User::findOne(1);
if ($user->can("admin")) {
    // do something
};
```

* Restricting permission in controller:
```php
class SubjectController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'list-subscribed'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    [
                        'actions' => ['list'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['list-subscribed'],
                        'allow' => true,
                        'roles' => ['student'],
                    ],
                ],
            ],
           ...
        ];
    }
```

* Examples for extending module:
  * Define our own User class (MyUser). Add in `config/web.php`:
    ```php
    // app/config/web.php
    'components' => [
        'user' => [
            'class' => 'app\components\MyUser',
            'identityClass' => 'app\models\MyUser',
        ],
    ],
    ```

  * Define our own module, controller and models classes. Add in `config/web.php`:
    ```php
    'modules' => [
        'user' => [
            'class' => 'app\modules\MyModule',
            'controllerMap' => [
                'default' => 'app\controllers\MyDefaultController',
            ],
            'modelClasses'  => [
                'User' => 'app\models\MyUser', // note: don't forget component user::identityClass above
                'Profile' => 'app\models\MyProfile',
            ],
            'emailViewPath' => '@app/mail/user', // example: @app/mail/user/confirmEmail.php
        ],
    ],
    ```


  * Extending/customizing views. Add in `config/web.php`:
    ```php
    'components' => [
          'view' => [
                'theme' => [
                    'pathMap' => [
                        '@vendor/amnah/yii2-user/views' => '@app/views/user', // example: @app/views/user/default/login.php
                    ],
                ],
            ],
        ]
    ```

# Backup database

```cmd
c:\xampp\mysql\bin\mysqldump -u root -p <database> > assets\<database>.sql
```

# Restore database

```cmd
c:\xampp\mysql\bin\mysql -u root -p <database> < assets\<database>.sql
```
