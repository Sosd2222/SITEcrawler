<!-- 파일 뷰 -->
<head>
  <style>
  a:link {text-decoration: none; color: #FFFFFF}
  a:hover {text-decoration: underline; color: #00FFFF}
  a:visited {text-decoration: underline; color: #2EFEF7}
  a:link.red {text-decoration: none; color: #FFFFFF}
  a:hover.red {text-decoration: underline; color: #FF0000}
  a:visited.red {text-decoration: underline; color: #FF0000}
  a:link.main {text-decoration: none; color: #FF0000}
  a:hover.main {text-decoration: none; color: #FF0000}
  a:visited.main {text-decoration: none; color: #FF0000}
  span.folder {
    color:#F3FF00
  }
  span.main {
    font-size:25px;
    color:#00FFFF;
  }
  body {
  background-color: black;
  color:#FFFFFF;
  }
  input[type="text"],
  input[type="submit"],
  textarea,
  select {
    background-color: #000000;
    color: #FFFFFF;
    border-radius: 5px;
    border #000000;
  }

  </style>
</head>
<?php
  $search = $_REQUEST['s'];
  echo '<div align="center">';
  echo '<a href="./index.php" class="main"><span class="main">FileSearcher</span><br></a>';
  echo '<br>';
  echo '<form action="index.php">';
  echo '<span class="folder">search: </span><input type="text" name="s"'.'>';
  echo '<br><br><br>';

  if ($handle = opendir('.'))
  {
    while (false !== ($entry = readdir($handle)))
    {
      if ($entry != "." && $entry != ".." && $entry != "index.php" && $entry != "view.php" && $entry != '.html' && strpos($entry, ".") !== false)
      {
        $temp[] = "$entry";
      }
    }
    closedir($handle);
  }
  sort($temp);
  for ($s=0; $s<count($temp); $s++)
  {
    $temp_s[$s] = preg_replace('/(_)/', ' ', $temp[$s]);
    $temp_s[$s] = preg_replace('/(\$\$)/', '?', $temp_s[$s]);
    $temp_final .= $temp_s[$s]." <a href=\"$temp[$s]\" class=\"red\" target=\"_blank\"><u>View</u></a><br>\n";
  }

  if ($search == '')
  {
    echo $temp_final;
  }
  else
  {
    $search2 = explode(' ', $search);

    $match = "/.*?$search2[0].*?<br>/";
    preg_match_all($match, $temp_final, $result);
    $count = count($result[0]);

    echo '<span class="folder">search result for: '.$search.'<br>'.$count.' result found<br><br></span>';
    for ($i=0; $i<$count; $i++)
    {
      $finalresult .= $result[0][$i]."\n<br>";
    }

    if (1 < count($search2))
    {
      $match2 = "/.*?$search2[1].*?<br>/";
      preg_match_all($match2, $finalresult, $result2);
      for ($y=0; $y<count($result2[0]); $y++)
      {
        echo $result2[0][$y];
      }
    }
    else
    {
      echo $finalresult;
    }

  }
  echo '</div>';
?>
