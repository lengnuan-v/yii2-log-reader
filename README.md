yii2-log-reader
===============
yii2,extension,log,reader

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require --prefer-dist lengnuan-v/yii2-log-reader "dev-master"
```

or add

```
"lengnuan-v/yii2-log-reader": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
return [
    'log'         => [
        'class'   => 'lengnuan\logReader\Module',
        'layout'  => "@views/layouts/main",
        'aliases' => [
            'Backend'  => '@backend/runtime/logs/app.log.' . date('Ymd'),
            'Frontend' => '@frontend/runtime/logs/app.log.' . date('Ymd'),
            'Console'  => '@console/runtime/logs/app.log.' . date('Ymd'),
        ],
    ],
];
```

You can then access Log Reader using the following URL:

```php
http://localhost/path/to/index.php?r=log-reader
```

or if you have enabled pretty URLs, you may use the following URL:

```php
http://localhost/path/to/log-reader
```

History Usage
-----

For every day log view, you can config yii log like this: 

```php
[
    'class'   => 'yii\log\FileTarget',
    'levels'  => ['error', 'warning'],
    'logFile' => '@runtime/logs/app.log.' . date('Ymd'),
]
```
