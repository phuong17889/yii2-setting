Yii2 Setting
=========
Yii2 Setting for other application

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require phuong17889/yii2-setting "*"
```

or add

```
"phuong17889/yii2-setting": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

### Migration

Migration run

```php
yii migrate --migrationPath=@phuong17889/setting/migrations
```

### Config /common/config/main.php to use Yii::$app->setting
```php
    'components' => [
        'setting' => [
            'class' => 'phuong17889\setting\Setting',
        ],
    ],
```

### Config backend modules in backend/config/main.php to manage settings

```php
    'modules'    => [
   		'setting'  => [
			'class'               => 'phuong17889\setting\Module',
			'controllerNamespace' => 'phuong17889\setting\controllers',
			'enableMultiLanguage' => false,//set true if phuong17889/yii2-multi-language installed and want to translate setting
   		],
   		'gridview' => [
            'class' => '\kartik\grid\Module',
   		],
   		'roxymce'  => [
            'class' => '\phuong17889\roxymce\Module',
   		],
    ],
```


### Config at backend
backend : http://you-domain/backend/web/setting
####Attention:
- Store Range required if type in (select, multiselect, checkbox, radio), supported string with comma, json, callback function.  
              Example:  
               - String: 1,2,3 or A,bcd,ef  
               - Json: {"0" : "abc", "1" : "def"}  
               - Callback: app\helpers\ArrayHelper::getItems()  
Just create simple static function named `getItems` in `app\helpers\ArrayHelper`
 ~~~
 namespace app\helpers;

 class ArrayHeper {

    public static function getItems(){
        return [
            0     => "abc",
            1     => "def",
            "ghi" => 2,
        ];
    }

 }
 ~~~

### Use Your Setting
Once you set the value at the backend. Simply access your setting by the following code (auto-suggest code worked):

```php
echo Yii::$app->setting->get('siteName');
echo Yii::$app->setting->siteName;
```
