<?php
if ($_COOKIE['login'] == true) {}else{
	header("Location: ../login/");
}

/*

네이버 웹툰 메타데이터 긁어오기.

https://ivlis.kr/

*/

$basefolder = "/data/naver/";

if (!isset($_GET['title'])) {
  $processing = array();
  $processed = array();

  if (!is_dir('../metadata/')) {
    mkdir('../metadata/');
  }
  if (!is_dir('../metadata/genre/')) {
    mkdir('../metadata/genre/');
  }
  if (!is_dir('../metadata/titles/')) {
    mkdir('../metadata/titles/');
  }

  $dir = "../metadata/titles/";
  if (is_dir($dir)){
    if ($dh = opendir($dir)){
      while (($file = readdir($dh)) !== false){
        if($file == "." || $file == "..") { continue; } else {
          array_push($processed, $file);
        }
      }
      closedir($dh);
    }
  }

  $dir = "../".$basefolder."/";
  if (is_dir($dir)){
    if ($dh = opendir($dir)){
      while (($file = readdir($dh)) !== false){
        if($file == "." || $file == "..") { continue; } else {
          array_push($processing, $file);
        }
      }
      closedir($dh);
    }
  }

  $result = array_diff($processing, $processed);
  sort($result);
  die("<meta http-equiv='refresh' content='0;url=./metadata-creator.php?title=".$result[0]."'>");
} elseif($_GET['title'] == '') {
	die(header("Location: ../?response=metafin"));
}

$titleget = $_GET['title'];

//기본 데이터 불러오기

      //파싱툴 기본
      include('simple_html_dom.php');

      //웹툰 검색페이지
      $html = file_get_html('https://m.comic.naver.com/search/result.nhn?searchType=WEBTOON&keyword='.urlencode($titleget));

      //title id 파싱
      $list = array();
      foreach($html->find('a') as $result){
        if(strpos($result->href, 'list.nhn') !== false) {
            array_push($list, str_replace('/webtoon/list.nhn?titleId=', '', $result->href));
        }
      }

      $titleid = $list[0];

      //제목 없는 만화 기본 값으로 생성
      if (!isset($list[0])) {
      $processing = array();
      $processed = array();

      if (!is_dir('../metadata/')) {
        mkdir('../metadata/');
      }
      if (!is_dir('../metadata/genre/')) {
        mkdir('../metadata/genre/');
      }
      if (!is_dir('../metadata/titles/')) {
        mkdir('../metadata/titles/');
      }
      if (!is_dir('../metadata/titles/'.$titleget.'/')) {
        mkdir('../metadata/titles/'.$titleget.'/');
      }

      //metadata 폴더에 저장
      $myfile = fopen("../metadata/titles/".$titleget."/titleid.txt", "w") or die("오류발생!");
      fwrite($myfile, '알 수 없음');
      fclose($myfile);

      $myfile = fopen("../metadata/titles/".$titleget."/title.txt", "w") or die("오류발생!");
      fwrite($myfile, $titleget);
      fclose($myfile);

      $myfile = fopen("../metadata/titles/".$titleget."/writer.txt", "w") or die("오류발생!");
      fwrite($myfile, '알 수 없음');
      fclose($myfile);

      $myfile = fopen("../metadata/titles/".$titleget."/detail.txt", "w") or die("오류발생!");
      fwrite($myfile, '메타데이터를 추출 할 수 없습니다.<br>/metadata/titles/'.$titleget.'/ 에서 메타데이터를 수정할 수 있습니다.');
      fclose($myfile);

      $myfile = fopen("../metadata/titles/".$titleget."/genre.txt", "w") or die("오류발생!");
      fwrite($myfile, '알 수 없음');
      fclose($myfile);

      copy("df.png", "../metadata/titles/".$titleget."/thumb.jpg");

      $dir = "../metadata/titles/";
      if (is_dir($dir)){
        if ($dh = opendir($dir)){
          while (($file = readdir($dh)) !== false){
            if($file == "." || $file == "..") { continue; } else {
              array_push($processed, $file);
            }
          }
          closedir($dh);
        }
      }

      $dir = "../".$basefolder."/";
      if (is_dir($dir)){
        if ($dh = opendir($dir)){
          while (($file = readdir($dh)) !== false){
            if($file == "." || $file == "..") { continue; } else {
              array_push($processing, $file);
            }
          }
          closedir($dh);
        }
      }

      $result = array_diff($processing, $processed);
      sort($result);

      echo "
      <h1>메타데이터를 생성중입니다.</h1>
      <h2>작업이 끝날때까지 본 페이지를 닫지 말아주세요.<h2>
      <br>
      <br>
      <br><h2>".$titleget." 생성완료</h2>
      <br>웹툰 ID : 알 수 없음
      <br>웹툰 이름 : $titleget
      <br>작가 : 알 수 없음
      <br>상세정보 : 알 수 없음
      <br>장르 : 알 수 없음
      <br>섬네일 주소 : 알 수 없음
      <br>
      <br>해상도 좋은 섬네일을 발견 시, 하단에서 추출해 생성합니다.
      <br>Front : 알 수 없음
      <br>Back : 알 수 없음
      <br>BG : 알 수 없음
      <script>history.pushState('', '', './metadata-creator.php');</script>
      <meta http-equiv='refresh' content='1;url=./metadata-creator.php?title=".$result[0]."'>";
      }

      //웹툰 상세주소(pc버전 기준)
      $html = file_get_html('https://comic.naver.com/webtoon/list.nhn?titleId='.$titleid);

      //제목
      foreach($html->find('title') as $element){
        $title = str_replace(" :: 네이버 만화", "", $element->plaintext);
      }

      //작가
      foreach($html->find('span[class=wrt_nm]') as $element){
        $writer = preg_replace("/\s+/", "", $element->plaintext);
      }

      //상세설명
      $detail = array();
      foreach($html->find('div[class=detail]') as $element){
        foreach($element->find('p') as $element1){
          array_push($detail,$element1);
        }
      }
      $detail = $detail[0];

      //장르
      foreach($html->find('span[class=genre]') as $element){
        $genre = preg_replace("/\s+/", "", $element->plaintext);
      }
      $genreforfile = str_replace(",", " ", $genre);
      $genre = explode(',', $genre);


      //폴더 없으면 생성
      if (!is_dir('../metadata/')) {
        mkdir('../metadata/');
      }
      if (!is_dir('../metadata/genre/')) {
        mkdir('../metadata/genre/');
      }
      if (!is_dir('../metadata/titles/')) {
        mkdir('../metadata/titles/');
      }
      if (!is_dir('../metadata/titles/'.$titleget.'/')) {
        mkdir('../metadata/titles/'.$titleget.'/');
      }

      //섬네일
      $thumb = array();
      foreach($html->find('img') as $element){
        array_push($thumb,$element->src);
      }
      $thumb = $thumb[0];

      //고화질 섬네일 찾아 생성
        //모바일 페이지
        $gqthumbox = "1";
        $html = file_get_html('https://m.comic.naver.com/webtoon/list.nhn?titleId='.$titleid);
      $gqthumb = array();
      define("WIDTH", 436);
      define("HEIGHT", 348);
      $dest_image = imagecreatetruecolor(WIDTH, HEIGHT);
      imagesavealpha($dest_image, true);
      $trans_background = imagecolorallocatealpha($dest_image, 0, 0, 0, 127);
      imagefill($dest_image, 0, 0, $trans_background);
      $process = imagecreatefrompng('bg.png');
      imagecopy($dest_image, $process, 0, 0, 0, 0, WIDTH, HEIGHT);
      foreach($html->find('div[class=area_thumbnail]') as $element){
        foreach($element->find('img') as $element1){
          array_push($gqthumb, $element1->src);
        }
      }
			$baseurl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://".$_SERVER[HTTP_HOST];
      foreach (array_reverse($gqthumb) as $gq) {
        if(strpos($gq, "front") !== false or strpos($gq, "F.png") !== false) {
            $imgcreatefronturl = $gq;
            $imgcreatefront = $baseurl."/system/proxy.php?".$gq;
        }
        if(strpos($gq, "back") !== false or strpos($gq, "B.png") !== false) {
            $imgcreatebackurl = $gq;
            $imgcreateback = $baseurl."/system/proxy.php?".$gq;
        }
        if(strpos($gq, "BG") !== false) {
            $imgcreatebgurl = $gq;
            $imgcreatebg = $baseurl."/system/proxy.php?".$gq;
        }
      }

      $process = imagecreatefrompng($imgcreatebg);
      imagecopy($dest_image, $process, 0, 0, 0, 0, WIDTH, HEIGHT);
      sleep(1);
      $process = imagecreatefrompng($imgcreateback);
      imagecopy($dest_image, $process, 0, 0, 0, 0, WIDTH, HEIGHT);
      sleep(1);
      $process = imagecreatefrompng($imgcreatefront);
      imagecopy($dest_image, $process, 0, 0, 0, 0, WIDTH, HEIGHT);
      sleep(1);

    imagepng($dest_image, "../metadata/titles/".$titleget."/thumb.jpg");
    imagedestroy($dest_image);
    imagedestroy($process);
          //이미지 빈파일 제거.
    if (filesize("../metadata/titles/".$titleget."/thumb.jpg") <= 2000) { // 서버마다 생성되는 빈 파일의 용량이 다른데 대충 1000~2000인듯.
      unlink("../metadata/titles/".$titleget."/thumb.jpg");
      copy($thumb, "../metadata/titles/".$titleget."/thumb.jpg");
      $gqthumbox = "0";
    }

//metadata 폴더에 저장
$myfile = fopen("../metadata/titles/".$titleget."/titleid.txt", "w") or die("오류발생!");
fwrite($myfile, $titleid);
fclose($myfile);

$myfile = fopen("../metadata/titles/".$titleget."/title.txt", "w") or die("오류발생!");
fwrite($myfile, $title);
fclose($myfile);

$myfile = fopen("../metadata/titles/".$titleget."/writer.txt", "w") or die("오류발생!");
fwrite($myfile, $writer);
fclose($myfile);

$myfile = fopen("../metadata/titles/".$titleget."/detail.txt", "w") or die("오류발생!");
fwrite($myfile, $detail);
fclose($myfile);

$myfile = fopen("../metadata/titles/".$titleget."/genre.txt", "w") or die("오류발생!");
fwrite($myfile, $genreforfile);
fclose($myfile);

foreach ($genre as $put) {
  if (!is_dir('../metadata/genre/'.$put.'/')) {
    mkdir('../metadata/genre/'.$put.'/');
  }
}


//새로운거 찾기
$processing = array();
$processed = array();


$dir = "../metadata/titles/";
if (is_dir($dir)){
  if ($dh = opendir($dir)){
    while (($file = readdir($dh)) !== false){
      if($file == "." || $file == "..") { continue; } else {
        array_push($processed, $file);
      }
    }
    closedir($dh);
  }
}

$dir = "../".$basefolder."/";
if (is_dir($dir)){
  if ($dh = opendir($dir)){
    while (($file = readdir($dh)) !== false){
      if($file == "." || $file == "..") { continue; } else {
        array_push($processing, $file);
      }
    }
    closedir($dh);
  }
}

$result = array_diff($processing, $processed);
sort($result);
echo "
<h1>메타데이터를 생성중입니다.
<h2>작업이 끝날때까지 본 페이지를 닫지 말아주세요.<h2>
<br>
<br>
<br><h2>".$titleget." 생성완료</h2>
<br>웹툰 ID : $titleid
<br>웹툰 이름 : $titleget
<br>작가 : $writer
<br>상세정보 : $detail
<br>장르 : $genreforfile
<br>섬네일 주소 : $thumb
<br>
<br>해상도 좋은 섬네일을 발견 시, 하단에서 추출해 생성합니다.
<br>Front : $imgcreatefronturl
<br>Back : $imgcreatebackurl
<br>BG : $imgcreatebgurl
<script>history.pushState('', '', './metadata-creator.php');</script>
<meta http-equiv='refresh' content='1;url=./metadata-creator.php?title=".$result[0]."'>";
 ?>
