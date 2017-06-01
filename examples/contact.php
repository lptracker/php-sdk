<?php

require_once '..\vendor\autoload.php';

use LPTracker\LPTracker;

$api = new LPTracker([
    'login'    => 'login@example.com',
    'password' => 'password',
    'service'  => 'SDK (example)'
]);

//Получение списка проектов
$projectList = $api->getProjectList();

$project = $projectList[0];

$details = [
    [
        'type' => 'email',
        'data' => 'contact@example.com'
    ]
];

$contactData = [
    'name'       => 'Name',
    'profession' => 'prof',
    'site'       => 'somecontactsite.ru'
];

//Создание контакта
$contact = $api->createContact($project->getId(), $details, $contactData);

$details = [
    [
        'type' => 'email',
        'data' => 'another@example.com'
    ]
];

//Редактирование контакта
$contact = $api->editContact($contact->getId(), $details, $contactData);

$contact->setName('NewName');

//Редактирование контакта через изменение объекта
$contact = $api->saveContact($contact);