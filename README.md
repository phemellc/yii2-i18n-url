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
		// The keys will become labels on the language switcher widget
		// 'languages' => ['English' => 'en', 'Ελληνικά' => 'gr']
		'enablePrettyUrl' => true,
		'showScriptName' => false,
		'rules' => [],
	],
	...
]
```

Example of changing the language

```php
<?= Html::a('Ελληνικά', ['site/index', 'lang' => 'gr']); ?>
```

Example of using the language switcher

```php
<?= pheme\i18n\widgets\LanguageSwitcher::widget(); ?>
```
