
<?php
	if($_GET["title_type"]=="All")
     $url='http://www.imdb.com/search/title?title='.urlencode($_GET["title"]).'&title_type=feature,tv_series,game';
	else if($_GET["title_type"]=="Feature")
     $url='http://www.imdb.com/search/title?title='.urlencode($_GET["title"]).'&title_type=feature';
	else if($_GET["title_type"]=="TV")
     $url='http://www.imdb.com/search/title?title='.urlencode($_GET["title"]).'&title_type=tv_series';
	else
     $url='http://www.imdb.com/search/title?title='.urlencode($_GET["title"]).'&title_type=game';
	 $content=file_get_contents($url);
	 if(strpos($content,'No results'))
  	{  
    	 if($_POST["select"]=="All Types")
        echo "<h1>Not found in any types!</h1>";
		 else if($_POST["select"]=="Feature Film")
	    echo "<h1>No movies found!</h1>";
		 else if($_POST["select"]=="TV Series")
	    echo "<h1>No TV Series found!</h1>";
		 else
	    echo "<h1>No Video Game found!</h1>";
   	}
	else
  	{
//	$url="http://www.imdb.com/search/title?title=spiderman&title_type=feature";
//	$content=file_get_contents($url);
//	print_r($content);

	header("Content-type:text/xml;charset=urf-8");
//	print_r($_GET["title_type"]);
 	 print_r("<rsp stat='ok'>");
  	$m="/<img src=\".+?\" height/";
  	preg_match_all($m,$content,$n,PREG_SET_ORDER);
  	$title="/(.+?)<\/a>\s+<span class=\"year_type\">(.+?)<\/span>/";
 	 preg_match_all($title,$content,$titles,PREG_SET_ORDER);
 	 $time="/<span class=\"year_type\">.+?<\/span><br>/";
 	 preg_match_all($time,$content,$times,PREG_SET_ORDER);
  	$dir="/(<span class=\"credit\">\s+.+?<a href=\".+?\">.+?<\/a>(, <a href=\".+?\">.+?<\/a>)*)|(<span class=\"credit\">\s+.+?<a href=\".+?\">.+?<\/a>)|(<\/div>\s+<span class=\"genre\">)/";
  	preg_match_all($dir,$content,$dirs,PREG_SET_ORDER);
  	$rate="/(<span class=\"value\">.+?<\/span>)|(<div class=\"rating-ineligible\">.+?<\/a>)/";
  	preg_match_all($rate,$content,$rates,PREG_SET_ORDER);
  	if(count($n)>5) 
     $j=5;
 	else
     $j=count($n);
	 print_r("<results total=\"".$j."\">");
  	for($i=0;$i<$j;$i++)
  	{
   	print_r("<result ");
   	print_r("cover=".preg_replace("/<img src=/",'',preg_replace("/ height/",'',$n[$i][0])));
   	print_r(" title=".'"'.trim(strip_tags(strip_tags($titles[$i][0],"<a href>"),"<span>")).'"');
   	preg_match("/[0-9?]+/",$times[$i][0],$timess);
   	print_r(" year=".'"'.$timess[0].'"');
   	if(!strpos($content,"Dir")|!strpos($dirs[$i][0],"Dir")) 
      $dirss="N/A";
  	else
      $dirss=strip_tags(preg_replace("/Dir: /",'',$dirs[$i][0]),"<a href>");
   	print_r(" director=".'"'.trim($dirss).'"');
   	print_r(" rating=".'"'.strip_tags($rates[$i][0],"<a href>").'"');
   	preg_match("/\/title\/.+?\//",$titles[$i][0],$details);
   	print_r(" details=\"http://www.imdb.com".$details[0]."\"/>");
   	}
   	print_r("</results></rsp>");
   	}
?>	

