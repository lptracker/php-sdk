# php-sdk
<img src="readme_media/logo.png" width="400"/>

PHP SDK для работы с Api платформы LPTracker.

[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/lptracker/php-sdk/badges/quality-score.png)](https://scrutinizer-ci.com/g/lptracker/php-sdk/)
[![Code Coverage](https://scrutinizer-ci.com/g/lptracker/php-sdk/badges/coverage.png)](https://scrutinizer-ci.com/g/lptracker/php-sdk/)

[![Latest Stable Version](https://poser.pugx.org/lptracker/php-sdk/v/stable)](https://packagist.org/packages/lptracker/php-sdk) [![Total Downloads](https://poser.pugx.org/lptracker/php-sdk/downloads)](https://packagist.org/packages/lptracker/php-sdk) [![License](https://poser.pugx.org/lptracker/php-sdk/license)](https://packagist.org/packages/lptracker/php-sdk)

**Документация по api доступна здесь [http://docs.direct.lptracker.ru](http://docs.direct.lptracker.ru).**

## Что оно умеет?

* Получение информации по проектам
* Работа с контактами
* Работа с лидами/сделками
* Работа с платежами
* Обработка полей конструктора


## Подключение sdk

##### Установка через [Composer](https://getcomposer.org/)

```
composer require lptracker/php-sdk
```

## Простой пример

```php
<?php
require(__DIR__ . '/vendor/autoload.php');

use lptracker\LPTracker\LPTracker;

$lptrackerApi = new LPTracker([
	'login' => 'login@example.com',
	'password' => 'superpassword'
]);
```
