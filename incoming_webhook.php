<?

$step = intval($_REQUEST['step']);
$records = intval($_REQUEST['records']);
$result = '';

switch ($step) {
    case 1:

        $result = generateContacts($records);
        break;

    case 2:
        $result = generateCompanies($records);
        break;

    case 3:
        $result = generateDeals($records);
        break;

}

function generateCompanies($record_count) {

    $batch = array();
    $res = '';

    for ($i = 1; $i <= $record_count; $i++) {
        $batch['cmd_'.$i] =
            'crm.company.add?'.http_build_query(
                array(
                    "fields" => array(
                        "TITLE" => 'Рога и Копыта ['.$i.']',
                        "UF_CRM_1491842015" => '1'
                    )
                )
            );

        if ($i % 50 == 0) {
            $res .= '>>> '.print_r(executeHook(array('cmd' => $batch)), true);
            $batch = array();
        }
    }

    if (count($batch) > 0) $res .= '>>> '.print_r(executeHook(array('cmd' => $batch)), true);

    return 'cоздали компании:<br/><br/>'.$res;
}

function generateDeals($record_count) {

    // сначала получаем контакты
    $batch = array(); $contact_ids = array();

    for ($i = 1; $i <= ceil($record_count / 50); $i++) {
        $batch['get_'.$i] =
            'crm.contact.list?'.http_build_query(
                array(
                    "filter" => array(
                        "UF_CRM_1491842010" => '1'
                    ),
                    "start" => '$next[get_'.($i-1).']'
                )
            );
    }

    $contacts = executeHook(array('cmd' => $batch));

    // получаем компании
    $batch = array(); $companies_ids = array();;

    for ($i = 1; $i <= ceil($record_count / 50); $i++) {
        $batch['get_'.$i] =
            'crm.company.list?'.http_build_query(
                array(
                    "filter" => array(
                        "UF_CRM_1491842015" => '1'
                    ),
                    "start" => '$next[get_'.($i-1).']'
                )
            );
    }

    $companies = executeHook(array('cmd' => $batch));
    //echo "<pre>";
    //print_r($batch);
    //print_r($companies);
    //echo "</pre>";

    //echo "контакты: <br/><br/>";
    for ($i = 1; $i <= ceil($record_count / 50); $i++) {
        for ($j = 0; $j < 50; $j++) {
            $contact_ids[] = $contacts['result']['result']['get_'.$i][$j]['ID'].', ';
        }
    }

    //echo "компании: <br/><br/>";
    for ($i = 1; $i <= ceil($record_count / 50); $i++) {
        for ($j = 0; $j < 50; $j++) {
            $companies_ids[] = $companies['result']['result']['get_'.$i][$j]['ID'].', ';
        }
    }

    $batch = array();
    $res = '';

    for ($i = 1; $i <= $record_count; $i++) {
        $batch['cmd_'.$i] =
            'crm.deal.add?'.http_build_query(
                array(
                    "fields" => array(
                        "TITLE" => "Продажа холодильника ".$i,
                        "OPPORTUNITY" => rand(5000, 45000),
                        "COMPANY_ID" => $companies_ids[$i - 1],
                        "CONTACT_ID" => $contact_ids[$i - 1],
                        "UF_CRM_1491842020" => '1'
                    )
                )
            );

        if ($i % 50 == 0) {
            $res .= '>>> '.print_r(executeHook(array('cmd' => $batch)), true);
            $batch = array();
        }
    }

    if (count($batch) > 0) $res .= '>>> '.print_r(executeHook(array('cmd' => $batch)), true);

    return 'создали сделки:<br/><br/>'.$res;
}

function generateContacts($record_count) {

    $batch = array();
    $res = '';

    for ($i = 1; $i <= $record_count; $i++) {
        $batch['cmd_'.$i] =
            'crm.contact.add?'.http_build_query(
                array(
                    "fields" => array(
                        "NAME" => "Иван ".$i,
                        "LAST_NAME" => "Иванов ".$i,
                        "PHONE" => array(
                            array("VALUE" => "555888", "VALUE_TYPE" => "WORK" )
                        ),
                        "UF_CRM_1491842010" => '1'
                    )
                )
        );

        if ($i % 50 == 0) {
            $res .= '>>> '.print_r(executeHook(array('cmd' => $batch)), true);
            $batch = array();
        }
    }

    if (count($batch) > 0) $res .= '>>> '.print_r(executeHook(array('cmd' => $batch)), true);

    return 'создали контакты:<br/><br/>'.$res;
}

function executeHook($params) {


    $queryUrl = 'https://sbercloud.bitrix24.ru/rest/8/pxxbs4ledsk8kwq7/batch.json';
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


    //echo "<pre>";
    //print_r($result);
    //echo "</pre>";

    return json_decode($result, true);

}

echo $result;