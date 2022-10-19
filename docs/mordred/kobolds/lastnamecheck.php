<?php

$insideitem = false;
$activechars = "";
$tag = "";
$laston = "";
$race = "";
$class= "";
$name    ="";
$guildname    ="";
$rank    ="";
$level    ="";
$da_race = "";
$nobnames = array();
$count=0;


function startElement($parser, $tagName, $attrs) {    
global $insideitem,$tag, $laston, $name, $rank ,$da_race,$class,$activechars,$guildname;
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
global $insideitem, $tag, $laston ,$class,$name,$rank,$da_race,$level,$nobnames;

	if ($tagName == "CHARACTER") {

	
			$pos = strrpos($name, " ");
			if ($pos ) { 					// If last name
				$name = $name . ", Rank " . $rank;
    			array_push ($nobnames, $name);  // add name to list
			}
		
		$laston = "";        
		$class = "";
		$rank = "";
		$da_race = "";
		$name = "";
		$level = "";
		$insideitem = false;    
	}
}



// gotta have this for the php-NUKE block stuff to work
global $nobnames,$name;

// Create an XML parser
$xml_parser = xml_parser_create();

// Set the functions to handle opening and closing tags
xml_set_element_handler($xml_parser, "startElement", "endElement");

// Set the function to handle blocks of character data
xml_set_character_data_handler($xml_parser, "characterData");


// Open the XML file for reading
$fp = fopen("http://www.camelotherald.com/guilds/Mordred/15.xml","r")  or die("Error reading RSS data.");

// Read the XML file 4KB at a time
while ($data = fread($fp, 4096))    
// Parse each 4KB chunk with the XML parser created above
xml_parse($xml_parser, $data, feof($fp)) 
		or die(sprintf("XML error: %s at line %d",
			xml_error_string(xml_get_error_code($xml_parser)),
				xml_get_current_line_number($xml_parser)));
// Close the XML filef
fclose($fp);

print <<<EOD
<body text="#CCCCCC" bgcolor="#000000" link="#999999" vlink="#999999" alink="#CCCCCC" background="marble4.jpg">
<blockquote>
<p>&nbsp;</p>
<font face="Arial, Helvetica, sans-serif"><br>
<font color="#FFFFFF" size="+2">Clan Members with Last Names</font></font> 
<p>&nbsp;</p>
<p><font face="Arial, Helvetica, sans-serif" size="+2">
EOD;

// Sort the list of Nob names and print it
sort ($nobnames);
foreach ($nobnames as $name) {
   print "$name\n</BR>";
}

print <<<EOD
<p>&nbsp;</p>
</blockquote>
</body>
</html>
EOD;

// Free up memory used by the XML parser
xml_parser_free($xml_parser);



// end of file

?>
