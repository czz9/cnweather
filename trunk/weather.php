<?php
/**
天气预报
by xw
*/

    $file="http://php.weather.sina.com.cn/xml_new.php?password=DJOYnieT8234jlsK&day=0";
    $data=file_get_contents($file);
    $xml_parser = xml_parser_create();
    xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($xml_parser, XML_OPTION_SKIP_WHITE, 1);
    xml_parse_into_struct($xml_parser, $data, $vals);
    xml_parser_free($xml_parser);
 //   print_r($vals);

    $today = date("n\月j\日");
    $tomorrow = date("n\月j\日",time() + 24*60*60);
    $nextnextday = date("n\月j\日",time() + 2*24*60*60);    
    
    $time = "┃        ┃                    ┃                    ┃                    ┃\n"; 
    $time = substr_replace($time,$today,12 + 6,strlen($today));
    $time = substr_replace($time,$tomorrow,12 + 22 +6,strlen($tomorrow));
    $time = substr_replace($time,$nextnextday,12 + 22*2 +6,strlen($nextnextday));
    $head = "┏━━━━┳━━━━━━━━━━┳━━━━━━━━━━┳━━━━━━━━━━┓\n".$time.
	    "┣━━━━╋━━━━━┳━━━━╋━━━━━┳━━━━╋━━━━━━━━━━┫\n".
	    "┃ 城  市 ┃ 天气概况 ┃  气温  ┃ 天气概况 ┃  气温  ┃ 天气概况 ┃  气温  ┃\n".
	    "┣━━━━╋━━━━━╋━━━━╋━━━━━╋━━━━╋━━━━━╋━━━━┫\n";
	    "┃44444444┃ 333333333┃ 3333333┃ 333333333┃ 3333333┃ 333333333┃ 3333333┃\n";
    //echo $head;

    $num = -1;
    $row = "";
    $con = "";

    $cityStart = 2;
    $firstDay = 12;
    $weatherLen = 12;
    $dayLen = 22;

    for($j=0;$j<=2;$j++){
        $file="http://php.weather.sina.com.cn/xml_new.php?password=DJOYnieT8234jlsK&day=".$j;
        $data=file_get_contents($file);
        $xml_parser = xml_parser_create();
        xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, 0);
	//$data = iconv("GB2312","ISO-8859-1",$data);
        xml_parser_set_option($xml_parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($xml_parser, $data, $vals);
	xml_parser_free($xml_parser);
	$num = -1;
	for($i=0;$i<=count($vals)-1;$i++){
	    switch($vals[$i][tag]){
		case "Weather":
		    if($vals[$i][type] == "open"){
			$num++;
			$curCity = "";
			$curStat = "";
			$curTemp = "";
			if($j == 0){
			    $res[$num] = "┃        ┃          ┃        ┃          ┃        ┃          ┃        ┃\n";
//			    $res[$num] = "123456789012345678901234567890123456789012345678901234567890123456789012345678\n";
			}
		    }else{
			$start = $firstDay + $dayLen * $j;
			//echo iconv("UTF-8","GBK" ,$curCity.$curStat.$curTemp);
			$curCity = iconv("UTF-8","GB2312" ,$curCity);
			//$curStat = iconv("UTF-8","GBK" ,$curStat);
			//$curTemp1 = iconv("UTF-8","GBK" ,$curTemp1);
			$res[$num] = substr_replace($res[$num],$curCity,$cityStart,strlen($curCity));
			$res[$num] = substr_replace($res[$num],$curStat,$start,strlen($curStat));
			$res[$num] = substr_replace($res[$num],$curTemp,$start + $weatherLen,strlen($curTemp));
		        if($j == 2){
			    if($i==(count($vals)-3))
				$con = $con.$res[$num]."┗━━━━┻━━━━━┻━━━━┻━━━━━┻━━━━┻━━━━━┻━━━━┛\n";
			    else
				$con = $con.$res[$num]."┣━━━━╋━━━━━╋━━━━╋━━━━━╋━━━━╋━━━━━╋━━━━┫\n";
			}
		    }
		    break;
		
		case "city":
		    $curCity = $vals[$i][value];
		    break;
		
		case "status1":
		    $curStat = iconv("UTF-8","GB2312" ,$vals[$i][value]);
		    break;
		
		case "status2":
		    if($curStat!=iconv("UTF-8","GB2312" ,$vals[$i][value]))
			$curStat = $curStat."转".iconv("UTF-8","GB2312" ,$vals[$i][value]);
		    break;
		
		case "temperature1":
		    $curTemp = $vals[$i][value];
		    break;

		case "temperature2":
		    $curTemp = $curTemp."－".$vals[$i][value];
		    break;
	    }
	}
    }
    echo $head.$con;
?>
