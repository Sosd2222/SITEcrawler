# SITEcrawler

링크를 넣으면 본문 내용을 가져오는 PHP 파싱(크롤링) 스크립트입니다.

### 설명
- 출처 표기만 하신다면 자유롭게 수정/배포하실수 있습니다.
- 리눅스에서 정상작동하기 위해 사용하기전 chmod 707 -R <디렉토리> 를 해주시기 바랍니다. 

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
