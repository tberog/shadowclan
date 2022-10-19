<?php
//BG NOTE: Things to fix: Temp count line 214, class listing pages, and various other little things.
//MML 
//MML// 7/22/02 added in class lookup per rank and sorting by last_on
//MML// 7/13/02 cleaned up the code
//MML// 7/11/02 addded in tribe info
//MML// 7/09/02 code modified to parse out guild XML info for Shadowclan.

// DAoC Server Status v1.0, msw, 12/2001
// Simple script to print out a very basic server status page using the xml
// from Camelot Herald. Check out http://www.camelotherald.com/xml.php
//
// I use this code in a 'block' on my php-Nuke website (http://home.greycouncil.org/)
// and it works fine. Should work as a simple webpage for others that have
// php/xml support.
//
// Hope this helps ya out, I won't support ya, don't ask me questions!
// I myself spent about a hour or so reading the tutorials the Mythic guys
// listed on their website. If you can't figure this out or have problems visit there.
// 
// Tutorial for XML/PHP http://www.webmasterbase.com/article/560
//
// To see the plain version hit http://home.greycouncil.org/test-xml/daoc-servers.php
//
// To see the php-Nuke BLOCK version hit http://home.greycouncil.org/
//
// This ain't by any means the best way to do this I am sure but it works.
//
// Run this like you would any other php script on your website.... that is
// all...See ya in game (well, when they fix/add a few things!)
$guildcount=0;
$insideitem = false;
$activechars = "";
$keepowned = "";
$timestamp = "";
$guildrp = "";
$tag = "";
$laston = "";
$race = "";
$class= "";
$name    ="";
$guildname    ="";
$rank    = "";
$level    =0;
$da_race = "";
$count=0;
$a=0;


function startElement($parser, $tagName, $attrs) {    
global $insideitem,$tag, $laston, $name, $rank ,$da_race,$class,$activechars,$guildname,$guildrp,$keep,$timestamp;
	if ($insideitem) {
		$tag = $tagName;
	} 
	if ($tagName == "CHARACTER") 
	{
	$insideitem = true;    
	while (list ($key, $val) = each ($attrs)) {
		switch($key) {
		case "NAME": $name=$val; break;
		case "LASTON": $laston=$val; break;
		} // end case
	} // end while
	}

	if ($tagName == "GUILD") 
	{
	$insideitem = true;    
	while (list ($key, $val) = each ($attrs)) {
		switch($key) {
		case "ACTIVECHARS": $activechars=$val; break;
		case "NAME": $guildname=$val; break;
		case "GUILDRP": $guildrp=$val; break;
		case "KEEPOWNED": $keep=$val; break;
		case "TIMESTAMP": $timestamp=$val; break;
		} // end case
	} // end while
	}
}

function characterData($parser, $data) {    
global $insideitem, $tag, $laston,$rank,$name,$class,$da_race,$level;
	if ($insideitem) {
	   switch ($tag) {            
		case "RACE":$da_race .=$data; break;            
		case "CLASS": $class .=$data; break;
		case "GUILDRANK": $rank .=$data; break;
		case "LEVEL": $level .=$data; break;
		}    
	}
}


function endElement($parser, $tagName) {    
global $guild,$guildcount,
$insideitem, $tag, $laston ,$class,$name,$rank,$da_race,
$level,$count,
$activechars,$guildname;
	if ($tagName == "GUILD") {
	}
	if ($tagName == "CHARACTER") {
		if($name) {
			$class=trim($class);
			$guild[$guildcount]["name"]=$name;
			$guild[$guildcount]["level"]=$level;
			$guild[$guildcount]["class"]=$class;
			$guild[$guildcount]["rank"]=$rank;
			$guild[$guildcount]["laston"]=$laston;
			$guild[$guildcount]["lastonnum"]= $temp=Last_on_number($laston);   
			$guildcount++;
		}

		$laston = "";        
		$class = "";
		$da_race = "";
		$name = "";
		$level = "";
		$rank = "";
		$insideitem = false;    
	}
}
//----------------------------------------------------------------------------------
function Ranklevel_Table($Rank,$title) {    
global $guild,$newbies;
$total=count ($guild);
$newbies=0;
$classarray[Hero] = 0;
$classarray[Valewalker] = 0;
$classarray[Animist] = 0;
$classarray[Warden] = 0;
$classarray[Druid] = 0;
for($count=0;$count<=$total;$count++){
	if((strcmp($guild[$count]["class"],"Hero")==0)&&($guild[$count]["rank"]==$Rank)) {
		$classarray[Hero]++; 
	}
	else if((strcmp($guild[$count]["class"],"Heroine")==0)&&($guild[$count]["rank"]==$Rank)) {
		$classarray[Hero]++; 
	}
	else if((strcmp($guild[$count]["class"],"Animist")==0)&&($guild[$count]["rank"]==$Rank)) {
		$classarray[Animist]++; 
	}
	else if((strcmp($guild[$count]["class"],"Valewalker")==0)&&($guild[$count]["rank"]==$Rank)) {
		$classarray[Valewalker]++; 
	}
	else if((strcmp($guild[$count]["class"],"Warden")==0)&&($guild[$count]["rank"]==$Rank)) {
		$classarray[Warden]++; 
	}
	else if((strcmp($guild[$count]["class"],"Druid")==0)&&($guild[$count]["rank"]==$Rank)) {
		$classarray[Druid]++; 
	}
	else if($guild[$count]["rank"]==$Rank) {$newbies++;}
}
print "<TABLE BORDER=1 WIDTH=0%>\n<TR>\n";
$total=array_sum ($classarray);
print"<td colspan=3>$title($total)</td>";
print"<tr>";
print  "<TD><B>Class</B></TD><TD><B>Total</B></TD><TD><B>Percent</B></TD>\n";
//ksort ($classarray); //accending sort
arsort ($classarray); //decending sort
while($element = each($classarray)) {
	print "<TR><TD><a href=\"sc_sylvans.php?page=ClassRank&ClassR=" .$element["key"] ."&Tribe=$Rank\">" .$element["key"] ."</a></td>";
	print "<td>" .$element["value"] ."</td>";
	if ($total) {
		$temp=($element["value"]/$total)*100 ;$temp=number_format($temp,0);
		}
	print "<td>$temp%</td>";
	}
print "</tr></TABLE>\n";
//print $newbies ."less than level 5";
print"<p>";
return $newbies;
}

//----------------------------------------------------------------------------------
function Classlevel_Table($lowrange,$highrange,$title) {    
global $guild,$newbies;
$total=count ($guild);
$newbies=0;
$classarray[Hero] = 0;
$classarray[Valewalker] = 0;
$classarray[Animist] = 0;
$classarray[Warden] = 0;
$classarray[Druid] = 0;
for($count=0;$count<=$total;$count++){
	if(strcmp($guild[$count]["class"],"Hero")==0) {
		if(($guild[$count]["level"]>$lowrange)&&($guild[$count]["level"]<$highrange)) { $classarray[Hero]++; }
	}
	else if(strcmp($guild[$count]["class"],"Heroine")==0) {
		if(($guild[$count]["level"]>$lowrange)&&($guild[$count]["level"]<$highrange)) { $classarray[Hero]++; }
	}
	else if(strcmp($guild[$count]["class"],"Animist")==0) {
		if(($guild[$count]["level"]>$lowrange)&&($guild[$count]["level"]<$highrange)) { $classarray[Animist]++; }
	}
	else if(strcmp($guild[$count]["class"],"Valewalker")==0) {
		if(($guild[$count]["level"]>$lowrange)&&($guild[$count]["level"]<$highrange)) { $classarray[Valewalker]++; }
	}
	else if(strcmp($guild[$count]["class"],"Warden")==0) {
		if(($guild[$count]["level"]>$lowrange)&&($guild[$count]["level"]<$highrange)) { $classarray[Warden]++; }
	}
	else if(strcmp($guild[$count]["class"],"Druid")==0) {
		if(($guild[$count]["level"]>$lowrange)&&($guild[$count]["level"]<$highrange)) { $classarray[Druid]++; }
	}
	else {$newbies++;}
}
$total=(array_sum ($classarray));
print "<TABLE BORDER=1 WIDTH=0%>\n<TR>\n";
print"<td colspan=3>$title($total)</td>";
print"<tr>";
print  "<TD><B>Class</B></TD><TD><B>Total</B></TD><TD><B>Percent</B></TD>\n";
//ksort ($classarray); //accending sort
arsort ($classarray); //decending sort
while($element = each($classarray)) {
	print "<TR><TD>" .$element["key"] ."</td>";
	print "<td>" .$element["value"] ."</td>";
	//$temp=($element["value"]/$total)*100 ;$temp=number_format($temp,0);
	//print "<td>$temp%</td>";
	}
print "</tr></TABLE>\n";
//print $newbies ."less than level 5";
print"<p>";
return $total;
}

//----------------------------------------------------------------------------------
function Rank_Table($Rank,$text,$Class) {    
global $guild;
$classcount=0;
$total=count ($guild);
if ($Class){
	switch($Rank) {
	case "9": $text="Tulie " .$Class;break;
	case "8": $text="Laire " .$Class;break;
	case "7": $text="Yavie " .$Class;break;
	case "6": $text="Hrive " .$Class;break;
	default: $text="";
	}
sscanf($Rank,"%d",$Rank);
$count1=0; $count2=0; $count3=0; $count4=0; $count5=0;
if($Class=="Heroine"){$Class="Hero";}
for($count=0;$count<=$total;$count++){
	if((strstr($guild[$count]["class"],$Class))&&($guild[$count]["rank"]==$Rank)) {
		if($guild[$count]["lastonnum"]==1) {
                $recentarray[$count1]["rank"]=$guild[$count]["rank"];
                $recentarray[$count1]["name"]=$guild[$count]["name"];
                $recentarray[$count1]["class"]=$guild[$count]["class"];
                $recentarray[$count1]["level"]=$guild[$count]["level"];
                $recentarray[$count1]["laston"]=$guild[$count]["laston"];
		$count1++;
		}
		if($guild[$count]["lastonnum"]==2) {
                $day3array[$count2]["rank"]=$guild[$count]["rank"];
                $day3array[$count2]["name"]=$guild[$count]["name"];
                $day3array[$count2]["class"]=$guild[$count]["class"];
                $day3array[$count2]["level"]=$guild[$count]["level"];
                $day3array[$count2]["laston"]=$guild[$count]["laston"];
		$count2++;
		}
		if($guild[$count]["lastonnum"]==3) {
                $day7array[$count3]["rank"]=$guild[$count]["rank"];
                $day7array[$count3]["name"]=$guild[$count]["name"];
                $day7array[$count3]["class"]=$guild[$count]["class"];
                $day7array[$count3]["level"]=$guild[$count]["level"];
                $day7array[$count3]["laston"]=$guild[$count]["laston"];
		$count3++;
		}
		if($guild[$count]["lastonnum"]==4) {
                $week2array[$count4]["rank"]=$guild[$count]["rank"];
                $week2array[$count4]["name"]=$guild[$count]["name"];
                $week2array[$count4]["class"]=$guild[$count]["class"];
                $week2array[$count4]["level"]=$guild[$count]["level"];
                $week2array[$count4]["laston"]=$guild[$count]["laston"];
		$count4++;
		}
		if($guild[$count]["lastonnum"]==5) {
                $inactivearray[$count5]["rank"]=$guild[$count]["rank"];
                $inactivearray[$count5]["name"]=$guild[$count]["name"];
                $inactivearray[$count5]["class"]=$guild[$count]["class"];
                $inactivearray[$count5]["level"]=$guild[$count]["level"];
                $inactivearray[$count5]["laston"]=$guild[$count]["laston"];
		$count5++;
		}
        }
}
}
else {
$count1=0; $count2=0; $count3=0; $count4=0; $count5=0;
for($count=0;$count<=$total;$count++){
        if($Rank==$guild[$count]["rank"]) {
		if($guild[$count]["lastonnum"]==1) {
                $recentarray[$count1]["rank"]=$guild[$count]["rank"];
                $recentarray[$count1]["name"]=$guild[$count]["name"];
                $recentarray[$count1]["class"]=$guild[$count]["class"];
                $recentarray[$count1]["level"]=$guild[$count]["level"];
                $recentarray[$count1]["laston"]=$guild[$count]["laston"];
		$count1++;
		}
		if($guild[$count]["lastonnum"]==2) {
                $day3array[$count2]["rank"]=$guild[$count]["rank"];
                $day3array[$count2]["name"]=$guild[$count]["name"];
                $day3array[$count2]["class"]=$guild[$count]["class"];
                $day3array[$count2]["level"]=$guild[$count]["level"];
                $day3array[$count2]["laston"]=$guild[$count]["laston"];
		$count2++;
		}
		if($guild[$count]["lastonnum"]==3) {
                $day7array[$count3]["rank"]=$guild[$count]["rank"];
                $day7array[$count3]["name"]=$guild[$count]["name"];
                $day7array[$count3]["class"]=$guild[$count]["class"];
                $day7array[$count3]["level"]=$guild[$count]["level"];
                $day7array[$count3]["laston"]=$guild[$count]["laston"];
		$count3++;
		}
		if($guild[$count]["lastonnum"]==4) {
                $week2array[$count4]["rank"]=$guild[$count]["rank"];
                $week2array[$count4]["name"]=$guild[$count]["name"];
                $week2array[$count4]["class"]=$guild[$count]["class"];
                $week2array[$count4]["level"]=$guild[$count]["level"];
                $week2array[$count4]["laston"]=$guild[$count]["laston"];
		$count4++;
		}
		if($guild[$count]["lastonnum"]==5) {
                $inactivearray[$count5]["rank"]=$guild[$count]["rank"];
                $inactivearray[$count5]["name"]=$guild[$count]["name"];
                $inactivearray[$count5]["class"]=$guild[$count]["class"];
                $inactivearray[$count5]["level"]=$guild[$count]["level"];
                $inactivearray[$count5]["laston"]=$guild[$count]["laston"];
		$count5++;
		}
        }
}
}
$recentcount=count ($recentarray);
$day3count=count ($day3array);
$day7count=count ($day7array);
$week2count=count ($week2array);
$inactivecount=count ($inactivearray);
$classcount=$recentcount+$day3count+$day7count+$week2count+$inactivecount;
print "
<TABLE BORDER=1 width=95% align=center cellspacing=0 cellpadding=2>
<tr><td colspan=4><center><b>$text ($classcount)</b></td></tr>
<tr><td><b>Name</td><td><b>Class</td><td><b>Level</td><td><b>Last On</td></tr>
<tr><td></td>
</tr>
";
	for($count=0;$count<$recentcount;$count++){
		print "<TR><TD><font color=FFFFFF>" ;
		print $recentarray[$count]["name"];
		print "</font></td><td>";
		print $recentarray[$count]["class"];
		print "</td><td>";
		print $recentarray[$count]["level"];
		print "</td><td>";
		print $recentarray[$count]["laston"];
		print "</td></tr>";
	}
	for($count=0;$count<$day3count;$count++){
		print "<TR><TD><font color=FFFFFF>" ;
		print $day3array[$count]["name"];
		print "</font></td><td>";
		print $day3array[$count]["class"];
		print "</td><td>";
		print $day3array[$count]["level"];
		print "</td><td>";
		print $day3array[$count]["laston"];
		print "</td></tr>";
	}
	for($count=0;$count<$day7count;$count++){
		print "<TR><TD><font color=FFFFFF>" ;
		print $day7array[$count]["name"];
		print "</font></td><td>";
		print $day7array[$count]["class"];
		print "</td><td>";
		print $day7array[$count]["level"];
		print "</td><td>";
		print $day7array[$count]["laston"];
		print "</td></tr>";
	}
	for($count=0;$count<$week2count;$count++){
		print "<TR><TD><font color=FFFFFF>" ;
		print $week2array[$count]["name"];
		print "</font></td><td>";
		print $week2array[$count]["class"];
		print "</td><td>";
		print $week2array[$count]["level"];
		print "</td><td>";
		print $week2array[$count]["laston"];
		print "</td></tr>";
	}
	for($count=0;$count<$inactivecount;$count++){
		print "<TR><TD><font color=FFFFFF>" ;
		print $inactivearray[$count]["name"];
		print "</font></td><td>";
		print $inactivearray[$count]["class"];
		print "</td><td>";
		print $inactivearray[$count]["level"];
		print "</td><td>";
		print $inactivearray[$count]["laston"];
		print "</td></tr>";
	}
print"</td></tr></table>";
}
//----------------------------------------------------------------------------------

function Last_on_number($s) {    
if(strcmp($s,"Recently")==0){return 1;}
else if(strcmp($s,"3_Days")==0){return 2;}
else if(strcmp($s,"7_Days")==0){return 3;}
else if(strcmp($s,"2_Weeks")==0){;return 4;}
else {return 5;}
}

// ###########################################################################
// Create an XML parser
$xml_parser = xml_parser_create();

// Set the functions to handle opening and closing tags
xml_set_element_handler($xml_parser, "startElement", "endElement");

// Set the function to handle blocks of character data
xml_set_character_data_handler($xml_parser, "characterData");


// print "<hr>\n";

// Open the XML file for reading
$fp = fopen("http://www.camelotherald.com/guilds/Mordred/501.xml","r")  or die("Error reading RSS data.");

// Read the XML file 4KB at a time
while ($data = fread($fp, 4096))    
// Parse each 4KB chunk with the XML parser created above
xml_parse($xml_parser, $data, feof($fp)) 
		or die(sprintf("XML error: %s at line %d",
			xml_error_string(xml_get_error_code($xml_parser)),
				xml_get_current_line_number($xml_parser)));
// Close the XML filef
fclose($fp);
// Free up memory used by the XML parser
xml_parser_free($xml_parser);

// ###########################################################################
// HTML CODE

print <<<EOD
<HTML>
<HEAD>
	<TITLE>Roster</TITLE>
	<LINK REL="StyleSheet" HREF="hecili.css" TYPE="text/css" />
</HEAD>
<BODY text="#CCCCCC" bgcolor="#000000" link="#FFFFFF" vlink="#FFFFFF" alink="#FFFFFF" background="bkgrnd.jpg">
<center>
<H2>Hekili Sylvans</H2>
</center>
EOD;

print "
<TABLE BORDER=1 WIDTH=100%>\n
<tr><td>\n
<a href=\"sc_sylvans.php?page=\"><b>$guildname Tribes</b></a><td> \n ";
print $guildtemp=count ($guild);
print" members<br>$guildrp guild realm points<br> $keep claimed\n
<tr><td width=20% valign=top>\n
<p>\n";
$plus30=Classlevel_Table(29,51,"Members at level 30++");     
$plus20=Classlevel_Table(19,30,"Members at level 20-29");     
$plus10=Classlevel_Table(9,20,"Members at level 10-19");     
$less10=Classlevel_Table(4,10,"Members at level 5-9");     

print "Members at level 1-4 (" .$temp=$guildtemp-($less10+$plus10+$plus20+$plus30) .")\n
<p>\n";

print "<td valign=top>";
// start of frame
if(strcmp($page,"ClassRank")==0) {
	Rank_Table($Tribe, "",$ClassR) ;}
else if(strcmp($page,"Tulie")==0) {
        Rank_Table(9,"Tulie Tribe",""); }
else if(strcmp($page,"Laire")==0) {
        Rank_Table(8, "Laire Tribe","") ;}
else if(strcmp($page,"Yavie")==0) {
        Rank_Table(7, "Yavie Tribe","") ;}
else if(strcmp($page,"Hrive")==0) {
        Rank_Table(6, "Hrive Tribe","") ;}
else{
print "<TABLE BORDER=1 WIDTH=100% cellpadding=0 cellspacing=0>\n<TR><td valign=top width=50%>\n";

print "<TABLE BORDER=1 WIDTH=100%>\n<TR>\n";
print "<td width=30%><center><a href=\"sc_sylvans.php?page=Tulie\"><font size=+1>Tulie Tribe</font></a>";
print "</TABLE>\n";
print"<p align=center>";
Rank_Table(5,"Ingwe","") ;    
print "<BR>\n";
$temp=Ranklevel_Table(9,"Class breakdown");     
print "<p align=center>Members at level 1-4 ($temp)";

print "<td valign=top>";
print "<TABLE BORDER=1 WIDTH=100%>\n<TR>\n";
print "<td width=30%><center><a href=\"sc_sylvans.php?page=Laire\"><font size=+1>Laire Tribe</font></a>";
print "</TABLE>\n";
print"<p align=center>";
Rank_Table(4,"Ingwe","") ;  
print "<BR>\n";  
Ranklevel_Table(8,"Class breakdown");     

print "<tr>";
print "<td valign=top width=50%>";
print "<TABLE BORDER=1 WIDTH=100%>\n<TR>\n";
print "<td width=30%><center><a href=\"sc_sylvans.php?page=Yavie\"><font size=+1>Yavie Tribe</font></a>";
print "</TABLE>\n";
print"<p align=center>";
Rank_Table(3,"Ingwe","") ;    
print "<BR>\n";
Ranklevel_Table(7,"Class breakdown");     
print"<p>";

print "<td valign=top>";
print "<TABLE BORDER=1 WIDTH=100%>\n<TR>\n";
print "<td width=30%><center><a href=\"sc_sylvans.php?page=Hrive\"><font size=+1>Hrive Tribe</font></a>";
print "</TABLE>\n";
print"<p align=center>";
Rank_Table(2,"Ingwe","") ;    
print "<BR>\n";
Ranklevel_Table(6,"Class breakdown");     

print "</td></tr>";
print "</TABLE>\n<p>\n";
}

// bottom bar
print "<tr><td colspan=2 align=right>$timestamp last update</td></tr>";
print "</TABLE>\n";



// end of file

?>


