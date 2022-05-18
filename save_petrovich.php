<?php

$task = executeREST('task.item.add', array('TASKDATA' => array('TITLE' => 'Петрович опасносте!',
    RESPONSIBLE_ID => 1)));

// ура

executeREST('task.checklistitem.add', array($task['result'], array('TITLE' => 'Купить рассольчик в магазине на углу')));
executeREST('task.checklistitem.add', array($task['result'], array('TITLE' => 'Занести рассольчик утром Петровичу')));
executeREST('task.checklistitem.add', array($task['result'], array('TITLE' => 'Выгнать собутыльников')));
executeREST('task.checklistitem.add', array($task['result'], array('TITLE' => 'Освежить Петровича')));
executeREST('task.checklistitem.add', array($task['result'], array('TITLE' => 'Оставить Петровича в покое до понедельника')));

function executeREST($method, $params) {

    $queryUrl = 'https://restapi.bitrix24.ru/rest/1/6rnvzqe0ojsqih2p/'.$method.'.json';
    $queryData = http_build_query($params);

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $queryUrl,
        CURLOPT_POSTFIELDS => $queryData,
    ));

    $result = curl_exec($curl);
    curl_close($curl);

    return json_decode($result, true);

}