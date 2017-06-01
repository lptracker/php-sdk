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

foreach ($projectList as $project) {
    //Получение проекта по id
    $oneProject = $api->getProject($project->getId());

    //Получение полей конструктора для проекта
    $projectCustoms2 = $api->getProjectCustoms($oneProject->getId());
}