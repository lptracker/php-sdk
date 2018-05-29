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

$leadData = [
    'name'   => 'LeadName',
    'source' => 'SDK (example)',
    'owner'  => 0
];

// Указание источника (необязательно)
$leadData['view'] = [
    'source'   => 'yandex',
    'campaign' => 'sale-up',
    'keyword'  => 'wood',
];

$options = [
    'callback' => false
];

//Создание лида
$lead = $api->createLead($contact, $leadData, $options);

$leadData['name'] = 'NewLeadName';

//Редактирование лида
$lead = $api->editLead($lead->getId(), $leadData);

$lead->setOwnerId(10);

//Редактирование лида через изменение объекта
$lead = $api->saveLead($lead);

$leadCustoms = $lead->getCustoms();

$custom = $leadCustoms[0];

$custom->setValue('');

//Редактирование кастомного поля для лида
$api->saveLeadCustom($custom);

//Редактирование кастомного поля для лида
$api->editLeadCustom($lead->getId(), $custom->getId(), 'Hey');