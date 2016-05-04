Yii2 Setting
=========
Yii2 Setting for other application

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require navatech/yii2-setting "@dev"
```

or add

```
"navatech/yii2-setting": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

### Migration

Migration run

```php
yii migrate --migrationPath=@navatech/setting/migrations
```

### Config /common/config/main.php to use Yii::$app->setting
```php
    'components' => [
        'setting' => [
            'class' => 'navatech\setting\Setting',
        ],
    ],
```

### Config backend modules in backend/config/main.php to manage settings

```php
    'modules' => [
        'setting' => [
            'class' => 'navatech\setting\Module',
            'controllerNamespace' => 'navatech\setting\controllers'
        ],
    ],
```


### Config at backend
backend : http://you-domain/backend/web/setting

### Use Your Setting
Once you set the value at the backend. Simply access your setting by the following code (auto-suggest code worked):

```php
echo Yii::$app->setting->get('siteName');
echo Yii::$app->setting->siteName;
```
