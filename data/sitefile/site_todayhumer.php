<?php
  $except = ''; //소스에서 다음 내용이 나오면 무시 (정규식)
  $cut_start = '<div class="viewContent">'; //소스 잘라내기 첫부분
  $cut_end = '<!--viewContent-->'; //소스 잘라내기 끝부분
  $title_cutpoint = "-"; //타이틀에서 이 문자가 나오면 cut한다 예) 제목 - site.com 일때
  $title_cutopt = 'end';
  $opts = [
      "http" => [
          "method" => "GET",
          "header" => "Accept-language: en\r\n".
          "User-Agent:  Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko)\r\n".
          "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n" /* HTTP HEADER */
      ]
  ];
 ?>
