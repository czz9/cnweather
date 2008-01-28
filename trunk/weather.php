<?php
/**
天气预报
by xw
*/

//  未来三天时间
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

    $num = 3;
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
        xml_parser_set_option($xml_parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($xml_parser, $data, $vals);
	xml_parser_free($xml_parser);
	$num = 3;
	for($i=0;$i<=count($vals)-1;$i++){
	    switch($vals[$i][tag]){
		case "Weather":
		    if($vals[$i][type] == "open"){
			$num++;
			$curCity = "";
			$curStat = "";
			$curTemp = "";
		    }else{
			$start = $firstDay + $dayLen * $j;
			$curCity = iconv("UTF-8","GB2312" ,$curCity);
			$temp = $num;
			if($curCity == "北京"){;
			    $num = 0;
			}else if($curCity == "上海"){
			    $num = 1;
			}else if($curCity == "天津"){
			    $num = 2;
			}else if($curCity == "重庆"){
			    $num = 3;
			}
			if($j == 0){
			    $res[$num] = "┃        ┃          ┃        ┃          ┃        ┃          ┃        ┃\n";
			//  $res[$num] = "123456789012345678901234567890123456789012345678901234567890123456789012345678\n";
			}
			$res[$num] = substr_replace($res[$num],$curCity,$cityStart,strlen($curCity));
			$res[$num] = substr_replace($res[$num],$curStat,$start,strlen($curStat));
			$res[$num] = substr_replace($res[$num],$curTemp,$start + $weatherLen,strlen($curTemp));
			if($num != $temp) $num = $temp - 1;
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
    for($i = 0; $i <= count($res) - 1; $i++){
	    if($i == (count($res) - 1))
		$con = $con.$res[$i]."┗━━━━┻━━━━━┻━━━━┻━━━━━┻━━━━┻━━━━━┻━━━━┛\n";
	    else
		$con = $con.$res[$i]."┣━━━━╋━━━━━╋━━━━╋━━━━━╋━━━━╋━━━━━╋━━━━┫\n";
    }
    echo $head.$con;
?>
