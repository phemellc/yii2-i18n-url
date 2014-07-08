Yii2 i18n URL Manager
=====================
Internationalize your urls

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist pheme/yii2-i18n-url "*"
```

or add

```
"pheme/yii2-i18n-url": "*"
```

to the require section of your `composer.json` file.


Usage
-----


In your configuration file, add or replace your UrlManager component:

```php
	'components' => [
		'urlManager' => [
			'class' => 'pheme\i18n\I18nUrlManager',
			//'displaySourceLanguage' => true,
			//'languageParam' => 'lang',
			'languages' => ['en', 'gr'],
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			'rules' => [],
		],
        ...
	]
```