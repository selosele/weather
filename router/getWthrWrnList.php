<?php

  $ch = curl_init();
  // URL
  $url = 'http://apis.data.go.kr/1360000/WthrWrnInfoService/getWthrWrnList';
  // Service Key
  $queryParams = '?' . urlencode('serviceKey') . '=' . $env['SERVICE_KEY'];
  // 페이지 번호
  $queryParams .= '&' . urlencode('pageNo') . '=' . urlencode(isset($_GET['pageNo']) ? $_GET['pageNo'] : 1);
  // 한 페이지 결과 수
  $queryParams .= '&' . urlencode('numOfRows') . '=' . urlencode(strval($numOfRows));
  // 데이터 타입(XML/JSON)
  $queryParams .= '&' . urlencode('dataType') . '=' . urlencode('JSON');
  // 지점코드
  $queryParams .= '&' . urlencode('stnId') . '=' . urlencode(isset($_GET['stnId']) ? $_GET['stnId'] : $stnId);
  // 시작 발표시각(년월일시분)
  $queryParams .= '&' . urlencode('fromTmFc') . '=' . urlencode(isset($_GET['fromTmFc']) ? $_GET['fromTmFc'] : $fromTmFc);
  // 종료 발표시각(년월일시분)
  $queryParams .= '&' . urlencode('toTmFc') . '=' . urlencode(isset($_GET['toTmFc']) ? $_GET['toTmFc'] : $toTmFc);

  curl_setopt($ch, CURLOPT_URL, ($url . $queryParams));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_HEADER, FALSE);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
  $response = curl_exec($ch);
  curl_close($ch);

  // var_dump($response);

?>