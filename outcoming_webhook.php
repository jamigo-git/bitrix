<?php

// $deal_id = $_REQUEST['FIELDS']['ID'];

// postToChat(print_r($_REQUEST, true));


$deal_data = executeREST('crm.deal.get', array('ID' => intval($_REQUEST['data']['FIELDS']['ID'])));

postToChat(print_r($deal_data, true));

if (($deal_data['result']['CLOSED'] == 'Y') && ($deal_data['result']['OPPORTUNITY'] > 999999))
    postToChat('О боже! Мы продали на мильён за раз!', array(
        "ID" => 1,
        "BLOCKS" => Array(
            Array("IMAGE" => Array(
                "LINK" => "http://itd0.mycdn.me/image?id=838720761880&t=20&plc=WEB&tkn=*GY_bl00J4lx9APLGwDe4S5xVOc4",
            )),
            Array("LINK" => Array(
                "NAME" => "Мегасделка века",
                "DESC" => "Срочно принести сэйлу бутылочку шапманского!",
                "LINK" => 'https://'.$_REQUEST['auth']['domain'].'/crm/deal/show/'.$deal_data['result']['ID'].'/'
            )),
        )
    ));

function executeREST($method, $params) {

    $queryUrl = 'https://restapi.bitrix24.ru/rest/1/l1tz1xrukigrzvyu/'.$method.'.json';
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

function postToChat($message, $attach = array()) {

    $queryUrl = 'https://restapi.bitrix24.ru/rest/1/gbcy8j83o0pz1rjg/im.message.add.json';
    $queryData = http_build_query(
        array(
            "USER_ID" => 1,
            "MESSAGE" => $message,
            "ATTACH" => $attach
        )
    );

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