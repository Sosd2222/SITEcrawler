# SITEcrawler

링크를 넣으면 본문 내용을 가져오는 PHP 파싱(크롤링) 스크립트입니다. <br>
실행영상: (https://www.youtube.com/watch?v=RPcl___21xI)

### NEW
settings.php 의 $SET_htmlpath 와 $SET_imgpath 변경시 .html 파일을 저장할때 문제점이 발견되었습니다.<br>
현재 직접 경로설정은 불가능합니다

### 설명
- 출처 표기만 하신다면 자유롭게 수정/배포하실수 있습니다.
- 리눅스에서 정상작동하기 위해 사용하기전 chmod 707 -R <디렉토리> 를 해주시기 바랍니다. 

### 작동방식
- index.php 에서 링크를 입력받은뒤 crawler.php 에서 결과를 보여줍니다 (source 처리) <br>
- setting.php 는 ./data/settings.php 를 설정하는 역할, setting_update.php 는 업데이트 역할을 합니다

### ./data/sitefile
./data/sitefile 폴더에 들어가보면 site_<사이트 이름> 이 있습니다. 
이 파일들은 크롤링할 사이트의 기본 설정이 들어있습니다.

### 수정법
```php
<?php
  $except = ''; //소스에서 다음 내용이 나오면 무시 (정규식)
  $cut_start = '<div class="con_inner">'; //소스 잘라내기 첫부분
  $cut_end = '</section>'; //소스 잘라내기 끝부분
  $title_cutpoint = "-"; //타이틀에서 이 문자가 나오면 cut한다 예) 제목 - site.com 일때
  $title_cutopt = 'start'; //title 자르기 기준($title_cutpoint) 로 자른 title 값중 앞을 선택 or 뒤를 선택.
  $opts = [
      "http" => [
          "method" => "GET",
          "header" => "Accept-language: en\r\n".
          "User-Agent:  Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko)\r\n".
          "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n" /* HTTP HEADER */
      ]
  ];
 ?>
```
기본적인 sitefile은 이렇게 생겼습니다.<br/>
사이트를 수정하시려면 파싱할 사이트에 맞추어 파일을 작성한다음 site_<사이트이름>.php 로 저장하면 됩니다.

### 파싱결과
crawler.php 로 파싱된 데이터는 crawler.php 의 $content[0] 에 제목, $content[1] 에 글내용이 저장됩니다.<br>
사진 이름은 <b>저장할경로(세팅파일) + / + img_이미지번호 + 글제목 + _ + 랜덤문자열10자리 + 이미지확장자</b> 로 저장됩니다<br>
글내용 HTML은 <b>저장할경로(세팅파일) + / + 글제목 + .html</b> 으로 저장됩니다<br>
만약 이미 같은 글제목의 글이 파싱되었다면 "<i>이미 저장된 글이여서 저장하지 않았습니다.</i>" 라는 메시지를 나타냅니다.
