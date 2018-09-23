<?php
  include "./data/key/key.php";
  $pass = $_REQUEST['pass'];
  $settings_filepath = './data/settings.php'; //setting 파일 위치
  if (file_exists($settings_filepath)) { //settings.php 파일존재 체크
    include './data/settings.php';
  }
  else {
    echo "설정파일 불러오기 실패 - {$settings_filepath} 이 존재하는지 확인해주세요. 리눅스의 경우 {$settings_filepath} 의 권한이
    707인지 확인해주세요.<br><a href=\"javascript:window.location.href=window.location.href\">새로고침</a>";
    return false;
  }
  if ($SET_password != '' && $pass == '') { //password 확인
    echo "<div align=\"center\"><span style=\"font-size: 2em;\">Password?</span><br><br><form action=\"./setting.php\"
    method=\"post\"><input type=\"password\" name=\"pass\">&nbsp;<input type=\"submit\" value=\"submit\"></form></div>";
    return false;
  }
  else if ($SET_password != '' && $pass != '') {
    $md5_hash = md5($pass); //md5
    if ($md5_hash != $SET_password) {
      header("Location: ./setting.php");
      return false;
    }
  }
  // 디렉토리에 있는 파일명 가져오기 함수
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

    if (!is_writable($settings_filepath)) {
      echo "{$settings_filepath} 의 쓰기 권한이 없습니다. 707 권한으로 바꾸어주세요
      <br><a href=\"javascript:window.location.href=window.location.href\">새로고침</a>";
      return false;
    }
    /* colorset 따라 css파일 선택 */
    if ($SET_colorset == 'black') {
      echo "<link rel=\"stylesheet\" href=\"./data/css/black.css\">\n"; //black
    }
    else {
      echo "<link rel=\"stylesheet\" href=\"./data/css/white.css\">\n"; //white
    }

    $sitefile_list = filename("./data/sitefile"); //./data/sitefile 에 있는 파일 목록 가져오기
    for ($i=0; $i<count($sitefile_list); $i++) {
      // "site_" 문자열 "" 로 치환 $sitefile_name[$i] 배열에 저장
      $sitefile_name[$i] = preg_replace("/(site_|.php)/", "", $sitefile_list[$i]);
    }

  ?>
<head>
  <title>Setting Page</title>
  <link rel="shortcut icon" href="./data/img/favicon.ico">
</head>
<style>
  /* setting 에만 적용되는 고유 스타일 */
  span.head {
    font-size: 2em;
  }
  input[type="text"],
  input[type="password"] {
  <?php /* colorset*/ if ($SET_colorset == 'black') { echo "background-color: #000000;\n\tcolor: #ffffff;\n"; } else { echo "background-color: #ffffff;\n\tcolor: #000000;\n"; }?>
  border-radius: 5px;
  width : 300px;
  height: 30px;
  }
  input[type="submit"] {
    <?php /* colorset*/ if ($SET_colorset == 'black') { echo "background-color: #000000;\n\tcolor: #ffffff;\n"; } else { echo "background-color: #ffffff;\n\tcolor: #000000;\n"; }?>
    border-radius: 5px;
    width : 60px;
    height: 30px;
  }
  select {
    <?php /* colorset*/ if ($SET_colorset == 'black') { echo "background-color: #000000;\n\tcolor: #ffffff;\n"; } else { echo "background-color: #ffffff;\n\tcolor: #000000;\n"; }?>
    border-radius: 5px;
    width : auto;
    height: 30px;
  }
</style>
<body>
<div align="center">
  <form action="setting_update.php" method="post">
  <?php
    echo "\t<strong><span class=\"head\">Setting Page</span></strong><br><br><br>\n";
    echo "\t타이틀 값: <input type=\"text\" name=\"title\" value=\"$SET_title\"><br><br>\n";
    echo "\t아이콘 경로: <input type=\"text\" name=\"icon\" value=\"$SET_iconpath\"><br><br>\n";
    echo "\t이미지 기본확장자: <input type=\"text\" name=\"imgext\" value=\"$SET_imgext\"><br><br>\n";
    echo "\tReset Password: <br><br>New pass: <input type=\"password\" name=\"password\" maxlength=\"15\"><br>Check pass: <input type=\"password\" name=\"password2\" maxlength=\"15\"><br><br>\n";
    echo "\t색조합(black or white): <select name=\"colorset\">\n";
    if ($SET_colorset == 'black') { echo "\t<option selected>black</option><option>white</option>\n"; }
    else { echo "\t<option>black</option><option selected>white</option>\n"; }
    echo "\t</select><br><br>\n";
    echo "\t이미지 수집여부: <select name=\"isget\">\n";
    if ($SET_isget == 'yes') { echo "\t<option yes>yes</option><option>no</option>\n"; }
    else { echo "\t<option>no</option><option selected>yes</option>\n"; }
    echo "\t</select><br><br>\n";
    echo "\t<input type=\"submit\" value=\"확인\">\n";
    echo "\t<input type=\"hidden\" name=\"check\" value=\"$md5_hash\">\n";
    echo "\t<input type=\"hidden\" name=\"key\" value=\"$key\">\n";
  ?>
  </form>
</div>
</body>
