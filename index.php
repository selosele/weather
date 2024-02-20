<?php
  define("CTX", basename(__DIR__));
  $env = parse_ini_file('.env');

  // 초기 값
  $stnId = '184';
  $fromTmFc = date('Ymd') - 6;
  $toTmFc = date('Ymd');
  $numOfRows = 10;
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <title>기상정보문조회 서비스</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link rel="stylesheet" href="/<?php echo CTX ?>/css/layout.css">
  <link rel="stylesheet" as="style" crossorigin href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard@v1.3.9/dist/web/static/pretendard.min.css" />
</head>
<body>

  <div class="container">
    <h1 class="page_title">
      <a href="/<?php echo CTX ?>">기상정보문조회 서비스</a>
    </h1>

    <form id="frm" action="/<?php echo CTX ?>" method="get">
      <input type="hidden" name="pageNo" id="pageNo" value="1">

      <div class="search_util mb-3">
        <div class="row">
          <div class="col">
            <div class="form-group row">
              <label for="stnId" class="col-sm-5 col-form-label">지점코드</label>
              <div class="col-sm-7">
                <input type="text" id="stnId" name="stnId" value="<?php echo isset($_GET['stnId']) ? $_GET['stnId'] : $stnId ?>" class="form-control">
              </div>
            </div>
          </div>

          <div class="col">
            <div class="form-group row">
              <label for="fromTmFc" class="col-sm-5 col-form-label">시작 발표일자</label>
              <div class="col-sm-7">
                <input type="text" id="fromTmFc" name="fromTmFc" value="<?php echo isset($_GET['fromTmFc']) ? $_GET['fromTmFc'] : $fromTmFc ?>" class="form-control">
              </div>
            </div>
          </div>

          <div class="col">
            <div class="form-group row">
              <label for="toTmFc" class="col-sm-5 col-form-label">종료 발표일자</label>
              <div class="col-sm-7">
                <input type="text" id="toTmFc" name="toTmFc" value="<?php echo isset($_GET['toTmFc']) ? $_GET['toTmFc'] : $toTmFc ?>" class="form-control">
              </div>
            </div>
          </div>

          <div class="col">
            <button type="submit" class="btn btn-primary">조회</button>
          </div>
        </div>
      </div>

      <?php require_once('./router/getWthrWrnList.php') ?>
      
      <table class="table">
        <caption class="sr-only">기상정보문조회 - 지점코드, 제목, 발표시각(년월일시분), 발표번호(월별) 정보 제공</caption>
        <thead>
          <tr>
            <th scope="col">지점코드</th>
            <th scope="col">제목</th>
            <th scope="col">발표시각(년월일시분)</th>
            <th scope="col">발표번호(월별)</th>
          </tr>
        </thead>
        <tbody id="tbody" class="text-center">
          <?php
            $json = json_decode($response, true);

            if ("00" == $json["response"]["header"]["resultCode"]) {
              foreach ($json["response"]["body"]["items"]["item"] as $value) {
                echo '<tr>';
                echo    '<td>'.$value["stnId"].'</td>';
                echo    '<td class="text-start">'.$value["title"].'</td>';
                echo    '<td>'.$value["tmFc"].'</td>';
                echo    '<td>'.$value["tmSeq"].'</td>';
                echo '</tr>';
              }
            }
          ?>
        </tbody>
      </table>

      <?php

        // 현재 페이지 번호 가져오기
        $page = isset($_GET['pageNo']) ? $_GET['pageNo'] : 1;

        // 한 페이지에 보여질 게시글 수
        $perPage = $numOfRows;

        // 시작 게시글 인덱스 계산
        $start = ($page - 1) * $perPage;

        // 게시글 전체 개수
        $totalCount = $json["response"]["body"]["totalCount"];

        // 전체 페이지 수 계산
        $totalPages = ceil($totalCount / $perPage);

      ?>

      <nav aria-label="페이지 네비게이션">
        <ul class="pagination">
          <?php if($page > 1): ?>
            <li class="page-item">
              <a class="page-link" href="javascript:void(0);" onclick="goPage(1);">
                &lt;&lt;
              </a>
            </li>
            <li class="page-item">
              <a class="page-link" href="javascript:void(0);" onclick="goPage(<?php echo ($page - 1) ?>);">
                이전
              </a>
            </li>
          <?php endif ?>

          <?php for($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?php echo ($i == $page) ? 'active' : '' ?>">
              <a class="page-link" href="javascript:void(0);" onclick="goPage(<?php echo $i ?>);">
                <?php echo $i ?>
              </a>
            </li>
          <?php endfor ?>

          <?php if(($page * $perPage) < $totalCount): ?>
            <li class="page-item">
              <a class="page-link" href="javascript:void(0);" onclick="goPage(<?php echo ($page + 1) ?>);">
                다음
              </a>
            </li>
            <li class="page-item">
              <a class="page-link" href="javascript:void(0);" onclick="goPage(<?php echo $totalPages ?>);">
                &gt;&gt;
              </a>
            </li>
          <?php endif ?>
        </ul>
      </nav>
    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  <script>
    // 페이징 처리 함수
     function goPage(pageNo) {
       $('#pageNo').val(pageNo);
       $('#frm').submit();
    }

    // var url = 'http://apis.data.go.kr/1360000/WthrWrnInfoService/getWthrWrnList'; /*URL*/
    // var queryParams = '?' + encodeURIComponent('serviceKey') + '='+ 'jU7M7fOuvtjbSX5pvEl652qe6cY6wETwJhA2CoR6SQCrOAi9gwuTNKUMHkouoxjehshxB95B3dUAH%2FBngrpFLA%3D%3D'; /*Service Key*/
    // queryParams += '&' + encodeURIComponent('pageNo') + '=' + encodeURIComponent('1'); /* 페이지 번호 */
    // queryParams += '&' + encodeURIComponent('numOfRows') + '=' + encodeURIComponent('10'); /* 한 페이지 결과 수 */
    // queryParams += '&' + encodeURIComponent('dataType') + '=' + encodeURIComponent('JSON'); /* 데이터 타입(XML/JSON) */
    // queryParams += '&' + encodeURIComponent('stnId') + '=' + encodeURIComponent('184'); /* 지점코드 */
    // queryParams += '&' + encodeURIComponent('fromTmFc') + '=' + encodeURIComponent('20240214'); /* 시작 발표시각(년월일시분) */
    // queryParams += '&' + encodeURIComponent('toTmFc') + '=' + encodeURIComponent('20240219'); /* 종료 발표시각(년월일시분) */
    
    // $.ajax({
    //   type: 'GET',
    //   url: (url + queryParams),
    //   success: function(data) {
    //     if ('00' == data.response.header.resultCode) {
    //       var list = data.response.body.items.item;
    //       var html = '';
    //       for (var i = 0; i < list.length; i++) {
    //         html += `
    //           <tr>
    //             <td>${list[i].stnId}</td>
    //             <td class="text-start">${list[i].title}</td>
    //             <td>${list[i].tmFc}</td>
    //             <td>${list[i].tmSeq}</td>
    //           </tr>
    //         `;
    //       }
    //       $('#tbody').html(html);
    //     }
    //   },
    //   error: function(request, status, error) {
    //     console.log(error);
    //   }
    // });
  </script>
  
</body>
</html>