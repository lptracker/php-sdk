# php-sdk
<img src="readme_media/logo.png" width="400"/>

PHP SDK для работы с Api платформы LPTracker.

[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/lptracker/php-sdk/badges/quality-score.png)](https://scrutinizer-ci.com/g/lptracker/php-sdk/)

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

require_once 'vendor/autoload.php';

use LPTracker\LPTracker;

$api = new LPTracker([
    'login'    => 'andyhwp32@gmail.com',
    'password' => 'tEXyLH2W',
    'service'  => 'testService'
]);

//Получить список проектов
$projects = $api->getProjectList();

foreach ($projects as $project) {
    echo $project->__toString()."\n";
}

$details = [
    [
        'type' => 'email',
        'data' => 'contact@example.com'
    ]
];

$contactData = [
    'name'       => 'Максим',
    'profession' => 'повар',
    'site'       => 'somecontactsite.ru'
];

$contact = $api->createContact($projects[0]->getId(), $details, $contactData);

$leadData = [
    'name' => 'Макс',
    'source' => 'Sdk'
];

$options = [
    'callback' => false
];

$lead = $api->createLead($contact, $leadData, $options);
```
