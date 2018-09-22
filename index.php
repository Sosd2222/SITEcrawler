<?php
  /*
    SITEcrawler made by SOSD
    VER 1.0.0
    설명: @사이트 크롤링 스크립트입니다.
    초보자도 사용할수 있도록 최대한 편리하게 만들었습니다.@
    기능: 등록된 사이트의 링크를 넣으시면 자동으로 그 사이트
    의 글 본문과 이미지를 서버로 가져옵니다. 글 내용은 서버에
    .html 형식으로 저장됩니다.
    ---------------------------------------------------
    제작자: Sosd(KDW)
    제작시작: 2018-09-08
    ---------------------------------------------------
  */

  $pass = $_REQUEST['pass']; //password
  $settings_filepath = './data/settings.php'; //setting 파일 위치
  $request_type = $_REQUEST["type"]; //setting 페이지 로드용
  $version = "V1.0.0";

  /* 각종 오류처리 & 초기설정 코드 */
  if (file_exists($settings_filepath)) { //settings.php 파일존재 체크
    include './data/settings.php';
  }
  else {
    echo "설정파일 불러오기 실패 - {$settings_filepath} 이 존재하는지 확인해주세요. 리눅스의 경우 {$settings_filepath} 의 권한이
    707인지 확인해주세요.<br><a href=\"javascript:window.location.href=window.location.href\">새로고침</a>";
    return false;
  }
  if(!ini_get('allow_url_fopen') ) { //allow_url_fopen 허용여부
    echo 'php.ini 에서 allow_url_fopen 을 on 으로 바꾸어주세요.<br><a href=\"javascript:window.location.href=window.location.href\">새로고침</a>';
    return false;
  }
  if ($request_type == 'setting') //setting 페이지
  {
    header("Location: ./setting.php"); //redirect
    return false;
  }
  if ($SET_password != '' && $pass == '') { //password 확인
    echo "<div align=\"center\"><span style=\"font-size: 2em;\">Password?</span><br><br><form action=\"./index.php\"
    method=\"post\"><input type=\"password\" name=\"pass\">&nbsp;<input type=\"submit\" value=\"submit\"></form></div>";
    return false;
  }
  else if ($SET_password != '' && $pass != '') {
    $md5_hash = md5($pass); //md5
    if ($md5_hash != $SET_password) {
      header("Location: ./index.php");
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
?>

<!DOCTYPE html>
<html>
  <head>
    <!--
    ███████╗██╗████████╗███████╗ ██████╗██████╗  █████╗ ██╗    ██╗██╗     ███████╗██████╗
    ██╔════╝██║╚══██╔══╝██╔════╝██╔════╝██╔══██╗██╔══██╗██║    ██║██║     ██╔════╝██╔══██╗
    ███████╗██║   ██║   █████╗  ██║     ██████╔╝███████║██║ █╗ ██║██║     █████╗  ██████╔╝
    ╚════██║██║   ██║   ██╔══╝  ██║     ██╔══██╗██╔══██║██║███╗██║██║     ██╔══╝  ██╔══██╗
    ███████║██║   ██║   ███████╗╚██████╗██║  ██║██║  ██║╚███╔███╔╝███████╗███████╗██║  ██║
    ╚══════╝╚═╝   ╚═╝   ╚══════╝ ╚═════╝╚═╝  ╚═╝╚═╝  ╚═╝ ╚══╝╚══╝ ╚══════╝╚══════╝╚═╝  ╚═╝ <?php echo $version."\n"; ?>
    -->
    <script src="./data/jquery/jquery-3.2.1.min.js"></script>
    <script>
    $(document).ready(function() {
      var placeholderTarget = $('.textbox input[type="text"], .textbox input[type="password"]');

      //포커스시
      placeholderTarget.on('focus', function(){
        $(this).siblings('label').fadeOut('fast');
      });

      //포커스아웃시
      placeholderTarget.on('focusout', function(){
        if($(this).val() == ''){
          $(this).siblings('label').fadeIn('fast');
        }
      });
    });
    </script>
    <meta charset="utf-8">
    <link rel="shortcut icon" href="./data/img/favicon.ico">
    <title><?php echo $SET_title //title 값 ?></title>
    <?php if ($SET_colorset == 'black') { echo "<link rel=\"stylesheet\" href=\"./data/css/black.css\">"; }
    else { echo "<link rel=\"stylesheet\" href=\"./data/css/white.css\">"; } //colorset 따라 css파일 선택 ?>
  </head>
  <body>
    <div align="center">
      &nbsp;&nbsp;<img src=<?php if (file_exists($SET_iconpath)) { echo "\"$SET_iconpath\">"."<br><br>"; } else {  } ?>

        <form action="crawler.php" method="post">
          &nbsp;&nbsp;<select class="site" name="site">
            <option selected>사이트 선택...</option>
            <?php
              /* 디렉토리 ./data/sitefile(이 폴더는 사이트 크롤링 정보들이 저장되어 있음) 에 있는 파일 목록을 가져온다 */
              $sitefile_list = filename("./data/sitefile");
              for ($i=0; $i<count($sitefile_list); $i++)
              {
                $sitefile_name[$i] = preg_replace("/(site_|.php)/", "", $sitefile_list[$i]); // 파일목록에서 "site_" 문자열 "" 로 치환
                echo "<option>".$sitefile_name[$i]."</option>\n";
              }
            ?>

          </select>
          <div class="textbox">
            <label for="input_start">게시글 링크</label>
            <input type="text" id="input_start" name="link">
          </div>
           &nbsp;&nbsp;<input type="submit" value="GET">
           <input type="hidden" name="pass" value="<?php echo $md5_hash; //비번 ?>">
        </form><br>
        &nbsp;&nbsp;<a href="<?php echo $SET_htmlpath; ?>" target="_blank" style="<?php if ($SET_colorset == 'black') { echo "color: #ffffff;"; } else { echo "color:#000000;"; } ?> text-decoration:none;">| View Data |</a><br><br>
        <span class="setting" style="line-height: 1.7;">
          &nbsp;&nbsp;<a href="./index.php?type=setting" class="set" target="_blank">설정<br><?php if ($SET_colorset == 'black')
          { echo "&nbsp;&nbsp;<img src=\"./data/img/setting_white.png\" width=\"30px\" height=\"30px\">\n"; } else
          { echo "&nbsp;&nbsp;<img src=\"./data/img/setting_black.png\" width=\"30px\" height=\"30px\">\n"; }?>
        </span>
    </div>
  </body>
</html>
