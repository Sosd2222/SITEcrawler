<?php
  include "./data/key/key.php";
  $settings_filepath = './data/settings.php'; //setting 파일 위치
  /* all request here */
  $request_key = $_REQUEST['key'];
  $title = $_REQUEST['title']; //title
  $icon = $_REQUEST['icon']; //icon(첫페이지 이미지)
  $pass = $_REQUEST['password']; //비번
  $checkpass = $_REQUEST['password2']; //비번확인
  $colorset = $_REQUEST['colorset']; //색조합
  $imgext = $_REQUEST['imgext'];
  $isget = $_REQUEST['isget'];

  if (file_exists($settings_filepath)) { //settings.php 파일존재 체크
    include './data/settings.php';
  }
  else {
    echo "설정파일 불러오기 실패 - {$settings_filepath} 이 존재하는지 확인해주세요. 리눅스의 경우 {$settings_filepath} 의 권한이
    707인지 확인해주세요.<br><a href=\"javascript:window.location.href=window.location.href\">새로고침</a>";
    return false;
  }
  if ($_REQUEST['check'] != $SET_password || $request_key != $key) { //password, key 값 비교
    return false;
  }
  else if ($_REQUEST['check'] == $SET_password) {
    if ($pass == '') {
      $hash = '';
    }
    else {
      $hash = md5($pass);
    }
  }
  if (!is_writable($settings_filepath)) {
    echo "{$settings_filepath} 의 쓰기 권한이 없습니다. 707 권한으로 바꾸어주세요
    <br><a href=\"javascript:window.location.href=window.location.href\">새로고침</a>";
    return false;
  }
  if ($pass != $checkpass) {
    echo "<script>alert(\"비밀번호가 같지 않습니다!\");history.back();</script>";
    return false;
  }

  /* 글작성할 파일 만들기 시작 */
  $txt .= "<?php\n";
  $txt .= "  \$SET_title = \"$title\"; //제목\n";
  $txt .= "  \$SET_colorset = \"$colorset\"; //colorset 색조합 black, white 중 1개\n";
  $txt .= "  \$SET_iconpath = \"$icon\"; //index.php 의 아이콘이미지 경로\n";
  $txt .= "  \$SET_password = \"$hash\"; //password md5 hash\n";
  $txt .= "  \$SET_imgpath = \"./html/img\"; //이미지 저장 경로\n";
  $txt .= "  \$SET_imgext = \"$imgext\"; //이미지 확장자\n";
  $txt .= "  \$SET_isget = \"$isget\"; //이미지 수집여부\n";
  $txt .= "  \$SET_htmlpath = \"./html\"; //html 저장경로\n";
  $txt .= "?>\n";

  $fp = fopen("./data/settings.php", "w+"); //파일 덮어쓰기
  fwrite($fp, $txt); //쓰기
  fclose($fp);
  echo "<script>history.back();</script>";
?>
