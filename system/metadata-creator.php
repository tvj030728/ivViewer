
<?php
$logindata = json_decode(file_get_contents('../config.json'), true);
$logindatauser = $logindata[user];
$logindatapass =$logindata[pass];
if ($_COOKIE['login'] == true) {}else{
	die(header("Location: ../login/"));
}
if (!isset($_COOKIE[$logindatauser])) {
	die(header("Location: ../login/"));
}
if (!isset($_COOKIE[$logindatapass])) {
	die(header("Location: ../login/"));
}

if (!isset($_GET['folder'])) {
	die("옳바르지 않은 피라미터");
}

/*

네이버 웹툰 메타데이터 긁어오기.

https://ivlis.kr/

*/

$basefolder = "../data/".$_GET['folder']."/";

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
        if($file == "." || $file == ".." || $file == ".DS_Store" || $file == "@eaDir") { continue; } else {
          array_push($processed, str_replace($_GET['folder']."-", "", $file));
        }
      }
      closedir($dh);
    }
  }

  $dir = $basefolder;
  if (is_dir($dir)){
    if ($dh = opendir($dir)){
      while (($file = readdir($dh)) !== false){
        if($file == "." || $file == ".." || $file == ".DS_Store" || $file == "@eaDir") { continue; } else {
          array_push($processing, $file);
        }
      }
      closedir($dh);
    }
  }

  $result = array_diff($processing, $processed);
  sort($result);
  die("<meta http-equiv='refresh' content='0;url=./metadata-creator.php?title=".$result[0]."&folder=".$_GET['folder']."'>");
} elseif($_GET['title'] == '') {
	die(header("Location: ./metadata.php?action=continue&finish=".$_GET['folder']));
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
						if ($result->find('strong')[0]->plaintext == $titleget) {
							array_push($list, str_replace('/webtoon/list.nhn?titleId=', '', $result->href));
						}
        }
      }

      $titleid = $list[0];

      //2차 메타데이터 검색
			if (!isset($list[0])) { // list 비어있으면
				//다음웹툰 검색 ㄱㄱ
				$data_str = file_get_contents('http://webtoon.daum.net/data/pc/search?q='.urlencode(str_replace(' ', '', $titleget)));
				$json = json_decode($data_str, true);

				foreach ($json[data][webtoon] as $webtoonidfromjson) {
				  if ($titleget == $webtoonidfromjson[title]) {
				    $list[] = $webtoonidfromjson[nickname];
				  }
				}

				$data_str = file_get_contents('http://webtoon.daum.net/data/pc/search?q='.urlencode($titleget));
				$json = json_decode($data_str, true);

				foreach ($json[data][webtoon] as $webtoonidfromjson) {
				  if ($titleget == $webtoonidfromjson[title]) {
				    $list[] = $webtoonidfromjson[nickname];
				  }
				}

				if(!isset($list[0])){ //다음 검색했는데 또 없다?
					$html = file_get_html('https://www.webtoonguide.com/ko/search?q='.$titleget);

					//title id 파싱
					foreach($html->find('div[class=comic-items]',0)->find('a') as $result){

					  if(strpos($result->href, '/ko/db/comic/') !== false) {
								$processcleanedtitle = str_replace(" [독점연재]", "", $result->find('div[class=title]')[0]->plaintext);
								$processcleanedtitle = str_replace(" [선연재]", "", $processcleanedtitle);
								$processcleanedtitle = str_replace("(컬러연재)", "", $processcleanedtitle);
								$processcleanedtitle = str_replace(" [완결]", "", $processcleanedtitle);
					      if ($processcleanedtitle == $titleget) {
					        array_push($list, str_replace('/ko/db/comic/', '', str_replace('?c_ref=search', '', $result->href)));
					      }
					  }
					}
					if (!isset($list[0])) { // 검색결과 또없음
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
						if (!is_dir('../metadata/titles/'.$_GET[folder].'-'.$titleget.'/')) {
							mkdir('../metadata/titles/'.$_GET[folder].'-'.$titleget.'/');
						}

						//metadata 폴더에 저장
						$myfile = fopen("../metadata/titles/".$_GET['folder']."-".$titleget."/titleid.txt", "w") or die("오류발생!");
						fwrite($myfile, '알 수 없음');
						fclose($myfile);

						$myfile = fopen("../metadata/titles/".$_GET['folder']."-".$titleget."/title.txt", "w") or die("오류발생!");
						fwrite($myfile, $titleget);
						fclose($myfile);

						$myfile = fopen("../metadata/titles/".$_GET['folder']."-".$titleget."/writer.txt", "w") or die("오류발생!");
						fwrite($myfile, '알 수 없음');
						fclose($myfile);

						$myfile = fopen("../metadata/titles/".$_GET['folder']."-".$titleget."/detail.txt", "w") or die("오류발생!");
						fwrite($myfile, '메타데이터를 추출 할 수 없습니다.<br>/metadata/titles/'.$_GET[folder].'-'.$titleget.'/ 에서 메타데이터를 수정할 수 있습니다.');
						fclose($myfile);

						$myfile = fopen("../metadata/titles/".$_GET['folder']."-".$titleget."/genre.txt", "w") or die("오류발생!");
						fwrite($myfile, '알 수 없음');
						fclose($myfile);

						copy("df.png", "../metadata/titles/".$_GET['folder']."-".$titleget."/thumb.jpg");

						$dir = "../metadata/titles/";
						if (is_dir($dir)){
							if ($dh = opendir($dir)){
								while (($file = readdir($dh)) !== false){
									if($file == "." || $file == ".." || $file == ".DS_Store" || $file == "@eaDir") { continue; } else {
										array_push($processed, str_replace($_GET['folder']."-", "", $file));
									}
								}
								closedir($dh);
							}
						}

						$dir = $basefolder;
						if (is_dir($dir)){
							if ($dh = opendir($dir)){
								while (($file = readdir($dh)) !== false){
									if($file == "." || $file == ".." || $file == ".DS_Store" || $file == "@eaDir") { continue; } else {
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
						<meta http-equiv='refresh' content='1;url=./metadata-creator.php?title=".$result[0]."&folder=".$_GET['folder']."'>";
					} else { //검색결과 발견
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

						$ch = curl_init('https://webcache.googleusercontent.com/search?q=cache:https://www.webtoonguide.com/ko/db/comic/'.$list[0]);
						curl_setopt($ch, CURLOPT_NOBODY, true);
						curl_exec($ch);
						$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
						if($retcode != 200) {
						   $responseheader = false;
						}
						else {
						   $responseheader = true;
						}
						curl_close($ch);

						if($responseheader == true) { // 구글 캐시 있으면

							$html = file_get_html('https://webcache.googleusercontent.com/search?q=cache:https://www.webtoonguide.com/ko/db/comic/'.$list[0]);
							if (!is_dir('../metadata/titles/'.$_GET[folder].'-'.$titleget.'/')) {
								mkdir('../metadata/titles/'.$_GET[folder].'-'.$titleget.'/');
							}
							$title = str_replace(" ", "", html_entity_decode($html->find('div[class=content ko ellipsis-line-1]',0)->plaintext));
							$writer = str_replace(" ", "", html_entity_decode($html->find('div[class=content ko ellipsis-line-1]',1)->plaintext));
							$genre = str_replace(" ", "", html_entity_decode($html->find('div[class=content ko ellipsis-line-1]',2)->plaintext));
							$detail = html_entity_decode($html->find('div[class=container section-box section-mt db-section mt-0]',0)->find('div[lang=ko]',0)->plaintext);
							$thumb = $html->find('meta[property=og:image]',0)->content;

							$genreprocess = explode(',', $genre);
							$count = 0;
							foreach($genreprocess as $genreprocessing){
								$pattern = '/([\xEA-\xED][\x80-\xBF]{2}|[a-zA-Z])+/';
								preg_match_all($pattern, $genreprocessing, $resultgenre);
								$put = implode('',$resultgenre[0]);
								if (!is_dir('../metadata/genre/'.$put.'/')) {
									mkdir('../metadata/genre/'.$put.'/');
								}
								if ($count == 0) {
									$genreforfile = $put;
								} else {
									$genreforfile = $genreforfile . " " . $put;
								}
								$count = $count + 1;
							}

							//metadata 폴더에 저장
							$myfile = fopen("../metadata/titles/".$_GET['folder']."-".$titleget."/titleid.txt", "w") or die("오류발생!");
							fwrite($myfile, $list[0]);
							fclose($myfile);

							$myfile = fopen("../metadata/titles/".$_GET['folder']."-".$titleget."/title.txt", "w") or die("오류발생!");
							fwrite($myfile, $title);
							fclose($myfile);

							$myfile = fopen("../metadata/titles/".$_GET['folder']."-".$titleget."/writer.txt", "w") or die("오류발생!");
							fwrite($myfile, $writer);
							fclose($myfile);

							$myfile = fopen("../metadata/titles/".$_GET['folder']."-".$titleget."/detail.txt", "w") or die("오류발생!");
							fwrite($myfile, $detail);
							fclose($myfile);

							$myfile = fopen("../metadata/titles/".$_GET['folder']."-".$titleget."/genre.txt", "w") or die("오류발생!");
							fwrite($myfile, $genreforfile);
							fclose($myfile);

							copy($thumb, "../metadata/titles/".$_GET['folder']."-".$titleget."/thumb.jpg");

							$dir = "../metadata/titles/";
							if (is_dir($dir)){
								if ($dh = opendir($dir)){
									while (($file = readdir($dh)) !== false){
										if($file == "." || $file == ".." || $file == ".DS_Store" || $file == "@eaDir") { continue; } else {
											array_push($processed, str_replace($_GET['folder']."-", "", $file));
										}
									}
									closedir($dh);
								}
							}

							$dir = $basefolder;
							if (is_dir($dir)){
								if ($dh = opendir($dir)){
									while (($file = readdir($dh)) !== false){
										if($file == "." || $file == ".." || $file == ".DS_Store" || $file == "@eaDir") { continue; } else {
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
							<br>웹툰 ID : $list[0]
							<br>웹툰 이름 : $titleget
							<br>작가 : $writer
							<br>상세정보 : $detail
							<br>장르 : $genreforfile
							<br>섬네일 주소 : $thumb
							<br>
							<br>해당 웹툰은 네이버 웹툰이 아닙니다. 결과가 부정확 할 수 있습니다.
							<script>history.pushState('', '', './metadata-creator.php');</script>
							<meta http-equiv='refresh' content='1;url=./metadata-creator.php?title=".$result[0]."&folder=".$_GET['folder']."'>";
						} else {
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
							if (!is_dir('../metadata/titles/'.$_GET[folder].'-'.$titleget.'/')) {
								mkdir('../metadata/titles/'.$_GET[folder].'-'.$titleget.'/');
							}

							//metadata 폴더에 저장
							$myfile = fopen("../metadata/titles/".$_GET['folder']."-".$titleget."/titleid.txt", "w") or die("오류발생!");
							fwrite($myfile, '알 수 없음');
							fclose($myfile);

							$myfile = fopen("../metadata/titles/".$_GET['folder']."-".$titleget."/title.txt", "w") or die("오류발생!");
							fwrite($myfile, $titleget);
							fclose($myfile);

							$myfile = fopen("../metadata/titles/".$_GET['folder']."-".$titleget."/writer.txt", "w") or die("오류발생!");
							fwrite($myfile, '알 수 없음');
							fclose($myfile);

							$myfile = fopen("../metadata/titles/".$_GET['folder']."-".$titleget."/detail.txt", "w") or die("오류발생!");
							fwrite($myfile, '메타데이터를 추출 할 수 없습니다.<br>/metadata/titles/'.$titleget.'/ 에서 메타데이터를 수정할 수 있습니다.');
							fclose($myfile);

							$myfile = fopen("../metadata/titles/".$_GET['folder']."-".$titleget."/genre.txt", "w") or die("오류발생!");
							fwrite($myfile, '알 수 없음');
							fclose($myfile);

							copy("df.png", "../metadata/titles/".$_GET['folder']."-".$titleget."/thumb.jpg");

							$dir = "../metadata/titles/";
							if (is_dir($dir)){
								if ($dh = opendir($dir)){
									while (($file = readdir($dh)) !== false){
										if($file == "." || $file == ".." || $file == ".DS_Store" || $file == "@eaDir") { continue; } else {
											array_push($processed, str_replace($_GET['folder']."-", "", $file));
										}
									}
									closedir($dh);
								}
							}

							$dir = $basefolder;
							if (is_dir($dir)){
								if ($dh = opendir($dir)){
									while (($file = readdir($dh)) !== false){
										if($file == "." || $file == ".." || $file == ".DS_Store" || $file == "@eaDir") { continue; } else {
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
							<br>메타데이터를 찾았으나, 구글 서버에 캐시되지 않아 가져올 수 없습니다.
							<script>history.pushState('', '', './metadata-creator.php');</script>
							<meta http-equiv='refresh' content='1;url=./metadata-creator.php?title=".$result[0]."&folder=".$_GET['folder']."'>";
						}
					}
				} else {
					//다음 검색 결과 있다?
					if (!is_dir('../metadata/')) {
						mkdir('../metadata/');
					}
					if (!is_dir('../metadata/genre/')) {
						mkdir('../metadata/genre/');
					}
					if (!is_dir('../metadata/titles/')) {
						mkdir('../metadata/titles/');
					}
					if (!is_dir('../metadata/titles/'.$_GET[folder].'-'.$titleget.'/')) {
						mkdir('../metadata/titles/'.$_GET[folder].'-'.$titleget.'/');
					}

					$data_str = file_get_contents('http://webtoon.daum.net/data/pc/webtoon/view/'.$list[0]);
					$json = json_decode($data_str, true);
						//장르 가져옴
						foreach ($json[data][webtoon][cartoon][genres] as $getgenrefromjson) {
						  if ($count == 0) {
						    $genreforfile = $getgenrefromjson[name];
						  } else {
						    $genreforfile = $genreforfile . " " . $getgenrefromjson[name];
						  }
							if (!is_dir('../metadata/genre/'.$getgenrefromjson[name].'/')) {
								mkdir('../metadata/genre/'.$getgenrefromjson[name].'/');
							}
						  $count++;
						}

					$detail = $json[data][webtoon][introduction];
					$writer = $json[data][webtoon][cartoon][artists][0][name];
					$thumb = $json[data][webtoon][pcRecommendImage][url];

					copy($thumb, "../metadata/titles/".$_GET['folder']."-".$titleget."/thumb.jpg");

					//metadata 폴더에 저장
					$myfile = fopen("../metadata/titles/".$_GET['folder']."-".$titleget."/titleid.txt", "w") or die("오류발생!");
					fwrite($myfile, $list[0]);
					fclose($myfile);

					$myfile = fopen("../metadata/titles/".$_GET['folder']."-".$titleget."/title.txt", "w") or die("오류발생!");
					fwrite($myfile, $title);
					fclose($myfile);

					$myfile = fopen("../metadata/titles/".$_GET['folder']."-".$titleget."/writer.txt", "w") or die("오류발생!");
					fwrite($myfile, $writer);
					fclose($myfile);

					$myfile = fopen("../metadata/titles/".$_GET['folder']."-".$titleget."/detail.txt", "w") or die("오류발생!");
					fwrite($myfile, $detail);
					fclose($myfile);

					$myfile = fopen("../metadata/titles/".$_GET['folder']."-".$titleget."/genre.txt", "w") or die("오류발생!");
					fwrite($myfile, $genreforfile);
					fclose($myfile);

					$processed = array();
					$processing = array();

					$dir = "../metadata/titles/";
					if (is_dir($dir)){
						if ($dh = opendir($dir)){
							while (($file = readdir($dh)) !== false){
								if($file == "." || $file == ".." || $file == ".DS_Store" || $file == "@eaDir") { continue; } else {
									array_push($processed, str_replace($_GET['folder']."-", "", $file));
								}
							}
							closedir($dh);
						}
					}

					$dir = $basefolder;
					if (is_dir($dir)){
						if ($dh = opendir($dir)){
							while (($file = readdir($dh)) !== false){
								if($file == "." || $file == ".." || $file == ".DS_Store" || $file == "@eaDir") { continue; } else {
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
					<br>웹툰 ID : $list[0]
					<br>웹툰 이름 : $titleget
					<br>작가 : $writer
					<br>상세정보 : $detail
					<br>장르 : $genreforfile
					<br>섬네일 주소 : $thumb
					<br>
					<br>다음 웹툰에서 본 작품의 메타데이터를 찾았습니다.
					<script>history.pushState('', '', './metadata-creator.php');</script>
					<meta http-equiv='refresh' content='1;url=./metadata-creator.php?title=".$result[0]."&folder=".$_GET['folder']."'>";
				}
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
      if (!is_dir('../metadata/titles/'.$_GET[folder].'-'.$titleget.'/')) {
        mkdir('../metadata/titles/'.$_GET[folder].'-'.$titleget.'/');
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
$baseurl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://".$_SERVER[HTTP_HOST].str_replace("metadata-creator", "proxy", $_SERVER["PHP_SELF"]);
      foreach (array_reverse($gqthumb) as $gq) {
        if(strpos($gq, "front") !== false or strpos($gq, "F.png") !== false) {
            $imgcreatefronturl = $gq;
            $imgcreatefront = $baseurl."?".$gq;
        }
        if(strpos($gq, "back") !== false or strpos($gq, "B.png") !== false) {
            $imgcreatebackurl = $gq;
            $imgcreateback = $baseurl."?".$gq;
        }
        if(strpos($gq, "object") !== false or strpos($gq, "BG") !== false) {
            $imgcreatebgurl = $gq;
            $imgcreatebg = $baseurl."?".$gq;
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

    imagepng($dest_image, "../metadata/titles/".$_GET['folder']."-".$titleget."/thumb.jpg");
    imagedestroy($dest_image);
    imagedestroy($process);
          //이미지 빈파일 제거.
    if (filesize("../metadata/titles/".$_GET['folder']."-".$titleget."/thumb.jpg") <= 2000) { // 서버마다 생성되는 빈 파일의 용량이 다른데 대충 1000~2000인듯.
      unlink("../metadata/titles/".$_GET['folder']."-".$titleget."/thumb.jpg");
      copy($thumb, "../metadata/titles/".$_GET['folder']."-".$titleget."/thumb.jpg");
      $gqthumbox = "0";
    }

//metadata 폴더에 저장
$myfile = fopen("../metadata/titles/".$_GET['folder']."-".$titleget."/titleid.txt", "w") or die("오류발생!");
fwrite($myfile, $titleid);
fclose($myfile);

$myfile = fopen("../metadata/titles/".$_GET['folder']."-".$titleget."/title.txt", "w") or die("오류발생!");
fwrite($myfile, $title);
fclose($myfile);

$myfile = fopen("../metadata/titles/".$_GET['folder']."-".$titleget."/writer.txt", "w") or die("오류발생!");
fwrite($myfile, $writer);
fclose($myfile);

$myfile = fopen("../metadata/titles/".$_GET['folder']."-".$titleget."/detail.txt", "w") or die("오류발생!");
fwrite($myfile, $detail);
fclose($myfile);

$myfile = fopen("../metadata/titles/".$_GET['folder']."-".$titleget."/genre.txt", "w") or die("오류발생!");
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
      if($file == "." || $file == ".." || $file == ".DS_Store" || $file == "@eaDir") { continue; } else {
        array_push($processed, str_replace($_GET['folder']."-", "", $file));
      }
    }
    closedir($dh);
  }
}

$dir = $basefolder;
if (is_dir($dir)){
  if ($dh = opendir($dir)){
    while (($file = readdir($dh)) !== false){
      if($file == "." || $file == ".." || $file == ".DS_Store" || $file == "@eaDir") { continue; } else {
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
<meta http-equiv='refresh' content='1;url=./metadata-creator.php?title=".$result[0]."&folder=".$_GET['folder']."'>";
 ?>
