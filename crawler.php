<?php
  function encode_hr($str) { //한글 문자를 urlencode 시키는 함수
    preg_match_all('/[\x{1100}-\x{11ff}\x{3130}-\x{318f}\x{ac00}-\x{d7af}]+/u', $str, $matches);
    foreach($matches as $key2 => $val2) {
      $cnt = count($val2);
        if($cnt > 0) {
          foreach($val2 as $key3 => $val3) {
            $str = str_replace($val3, urlencode($val3), $str);
          }
        }
      }
    return $str;
  }
  function GenerateString($length) { //랜덤 문자열 생성
    $characters .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $characters .= "1234567890";
    $string_generated = "";
    $nmr_loops = $length;
    while ($nmr_loops--) {
      $string_generated .= $characters[mt_rand(0, strlen($characters))];
    }
      return $string_generated;
    }
    function filename($directory) {
  	 $results = array();
  	 $handler = opendir($directory);
  	 while ($file = readdir($handler)) {
  	   if ($file != '.' && $file != '..' && is_dir($file) != '1') {
  		   $results[] = $file;
       }
  	 }
  	closedir($handler);
  	return $results;
  }
  $pass = $_REQUEST['pass'];
  $site = $_REQUEST['site'];
  $link = $_REQUEST['link'];

  $settings_filepath = './data/settings.php'; //setting 파일 위치
  if (file_exists($settings_filepath)) { //settings.php 파일존재 체크
    include './data/settings.php';
  }
  else {
    echo "설정파일 불러오기 실패 - {$settings_filepath} 이 존재하는지 확인해주세요. 리눅스의 경우 {$settings_filepath} 의 권한이
    707인지 확인해주세요.<br><a href=\"javascript:window.location.href=window.location.href\">새로고침</a>";
    return false;
  }
  if ($SET_colorset == 'black') {
    echo "<a href=\"./index.php\" style=\"color:#ffffff; font-size:2em;\">MAIN</a><br><br>";
  }
  else {
    echo "<a href=\"./index.php\" style=\"color:#000000; font-size:2em;\">MAIN</a><br><br>";
  }
  if ($SET_colorset == 'black') {
    echo "<link rel=\"stylesheet\" href=\"./data/css/black.css\">";
  }
  else {
    echo "<link rel=\"stylesheet\" href=\"./data/css/white.css\">";
  } //colorset 따라 css파일 선택

  if ($pass != $SET_password) { //password 값 비교
    return false;
  }

  if ($site == "사이트 선택...") { //사이트 체크
    echo "<script>alert(\"사이트를 선택해주세요!\");history.back();</script>";
    return false;
  }
  if ($link == '') {
    echo "<script>alert(\"링크를 넣어주세요!\");history.back();</script>";
    return false;
  }
  if (!is_writable($SET_imgpath)) {
    echo "<script>alert(\"$SET_imgpath 의 쓰기 권한이 없습니다! 쓰기 권한 설정을 해주세요!!\");history.back();</script>";
    return false;
  }
  if (!is_writable($SET_htmlpath)) {
    echo "<script>alert(\"$SET_htmlpath 의 쓰기 권한이 없습니다! 쓰기 권한 설정을 해주세요!!\");history.back();</script>";
    return false;
  }
  if (!preg_match('/(http:\/\/|https:\/\/)/', $link)) {
    echo "<script>alert(\"올바른 링크가 아닙니다!\");history.back();</script>";
    return false;
  }

  $link = encode_hr($link); //링크에 있는 한글 인코딩

  /* 웹페이지 타이틀값 뽑아오기 */
  $sitefile = "./data/sitefile/site_{$site}.php"; //사이트 설정 경로

  if (!file_exists($sitefile)) {
    echo "<script>alert(\"사이트 설정이 존재하지 않습니다.\");history.back();</script>";
  }
  else {
    include $sitefile;
  }

  if ($opts != '') {
    $context = stream_context_create($opts);
    $source = file_get_contents($link, false, $context);
  }
  else {
    $source = file_get_contents($link);
  }

  $title_temp = explode('<title>', $source);
  $title_temp = explode('</title>', $title_temp[1]);
  $title = $title_temp[0]; //타이틀값
  $title_temp_3 = "<title>$title</title>"; //title for <title>
  $title_replace = str_replace(' ', '_', $title); //공백을 _ 로 치환
  $title_replace = preg_replace('/\?/', '$$', $title_replace);

  if ($title_cutpoint != '') {
    $title_temp_2 = explode($title_cutpoint, $title);
    if ($title_cutopt == 'end') { //잘라낸 뒤쪽값 사용
      $title = $title_temp_2[1];
    }
    else {
      $title = $title_temp_2[0]; //잘라낸 앞쪽값 사용
    }
  }
  if ($except != '') {
    if (preg_match($except, $source) || $source == '') {
      echo "<script>alert(\"존재하지 않는 글입니다.\");history.back();</script>"; //except 포함 시 뒤돌아가기
    }
  }
  if ($cut_start != '') {
    $source_temp = explode($cut_start, $source);
    if ($cut_end != '') {
      $source_temp = explode($cut_end, $source_temp[1]); //잘라내기
      $source_final = $source_temp[0]; //final
    }
    else {
      $source_final = $source_temp[1];
    }
  }
  preg_match_all('/img src\s*=\s*("|\')(.+?)("|\')/', $source_final, $img); //이미지 매칭
  $directory_html = filename($SET_htmlpath); //hrml 경로에 있는 파일이름 가져오기
  for ($i=0; $i<count($directory_html); $i++) {
    //html 디렉토리에 있는 파일 이름중 지금 저장할 title 값과 매칭되는 것이 있으면 $results_match 를 1로 바꾼다
    if (strpos($directory_html[$i], $title_replace) !== false) { //match
      $results_match = 1;
    }
  }
  if ($SET_isget == 'yes' && $results_match != 1) { //이미지 설정
    $opt_img = [ //http header for img
      "http" => [
          "method" => "GET",
          "header" => "User-Agent:  Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko)\r\n". //user agent
          "Referer: $link\r\n" /* HTTP HEADER */
      ]
    ];
    $context_img = stream_context_create($opt_img);
    for ($i=0; $i<count($img[2]); $i++) {
      $randstring = GenerateString(10); //랜덤 문자열 10자리
      $imgpath = $SET_imgpath."/"."img_{$i}_".$title_replace."_".$randstring.$SET_imgext; //이미지경로+img[$i]+랜덤_문자열_10자리.확장자
      file_put_contents($imgpath, file_get_contents($img[2][$i], false, $context_img)); //get contents
      $img_temp_source[$i] = str_replace($img[2][$i], $imgpath, $source_final); //replace img src="original" => src="final"
      $source_final = $img_temp_source[$i];
    }
  }
  $source_final = $title_temp_3.$source_final; //<title> 붙이기
  $source_final = preg_replace('/Array/', '', $source_final); //remove 'Array'
  $source_final = preg_replace('/>\s+</', '><', $source_final); //태그 사이 공백 replace
  $source_final = preg_replace('/<script[\s\S]+?<\/script>/', '', $source_final); //script 태그 없에기
  $source_final = preg_replace('/<iframe[\s\S]+?<\/iframe>/', '', $source_final); //iframe 을 이용한 광고 제거
  $source_final = preg_replace('/\r?\n\s*\n/', '', $source_final);
  $source_final_forhtml = preg_replace('/.\/html\//', './', $source_final); //./html/img 를 ./img 로 치환
  $pathforhtml = $SET_htmlpath."/".$title_replace.".html"; //html path


  /* NEW REQUEST */
  echo "\t<form action=\"./crawler.php\" method=\"post\">\n";
  echo "<input type=\"hidden\" value=\"$pass\" name=\"pass\">";
  echo "\tNEW REQUEST: <input type=\"text\" name=\"link\" class=\"request\">&nbsp;";
  echo "<select class=\"request\" name=\"site\">";
  echo "\n<option selected>사이트 선택...</option>";
  /* 디렉토리 ./data/sitefile(이 폴더는 사이트 크롤링 정보들이 저장되어 있음) 에 있는 파일 목록을 가져온다 */
  $sitefile_list = filename("./data/sitefile");
  for ($i=0; $i<count($sitefile_list); $i++)
  {
    $sitefile_name[$i] = preg_replace("/(site_|.php)/", "", $sitefile_list[$i]); // 파일목록에서 "site_" 문자열 "" 로 치환
    echo "<option>".$sitefile_name[$i]."</option>\n";
  }
  echo "</select>";
  echo "&nbsp;<input type=\"submit\" class=\"request\" value=\"GET\">";
  echo "</form>";
  echo "<br>";
  /* NEW REQUEST END */


  if ($results_match == 1) { //$results_match 가 1인 경우
    if ($SET_colorset == 'black') { echo "<span style=\"color:#FFFF00;\"><i>이미 저장된 글이여서 저장하지 않았습니다.</i></span><br>"; }
    else { echo "<span style=\"color:#000000;\"><i>이미 저장된 글이여서 저장하지 않았습니다.</i></span><br>"; }
    $read = file_get_contents($pathforhtml);
    $read = preg_replace('/.\/img/', './html/img', $read);
    $content[1] = $read;
  }
  else {
    file_put_contents($pathforhtml, "\xEF\xBB\xBF". $source_final_forhtml); //utf-8 로 저장
    $read = file_get_contents($pathforhtml);
    $read = preg_replace('/.\/img/', './html/img', $read);
    $content[1] = $read;
  }

  $content[0] = $title; //제목
  if ($SET_colorset == 'black') { echo "<span style=\"color:#FFFF00;\">REQUESTED LINK: ".$link."</span><br><br>"; }
  else { echo "<span style=\"color:#000000;\">REQUESTED LINK: ".$link."</span><br><br>"; }
  echo "Saved HTML: ";
  if ($SET_colorset == 'black') {
    echo "<a href=\"$pathforhtml\" target=\"_blank\" style=\"color:#ffffff;\">View</a><br><br>";
  }
  else {
    echo "<a href=\"$pathforhtml\" target=\"_blank\" style=\"color:#000000;\">View</a><br><br>";
  }
  echo "제목: ".$content[0]."<br>";
  echo "\n\n<br><br>내용:\n<br>".$content[1];
?>
