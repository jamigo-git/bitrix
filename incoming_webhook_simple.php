<?

    $queryUrl = 'https://restapi.bitrix24.ru/rest/1/ng5iy6mlibyijomy/user.current.json';
    // $queryData = http_build_query(array());

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $queryUrl,
        //CURLOPT_POSTFIELDS => $queryData,
    ));

    $result = curl_exec($curl);
    curl_close($curl);


    echo "<pre>";
    print_r($result);
    print_r(json_decode($result, true));
    echo "</pre>";
