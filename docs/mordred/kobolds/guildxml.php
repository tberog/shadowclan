<?php

    /*
    *    guildxml.php
    *    a Dark Age of Camelot Guild XML data parser that returns a guild roster
    *     sortable with some parameters like name, Realm Points, Level, etc...
    *    for use on Dark Age of Camelot community websites.
    *
    *    Dark Age of Camelot (or DAoC) is a MMORPG
    *    (massively multiplayer online rolepaying game)
    *    developed by Mythic Entertainment (http://www.mythicentertainment.com)
    *
    *    Author: Julien COQUET
    *    a.k.a Glenfiddich Singlemalt on DAoC Merlin/Albion
    *    e-mail glenfiddich@purpledragons.net
    *    http://www.purpledragons.net/xml/
    *
    *    Adapted from generic PHP code from http://www.php.net
    *
    *    Developed in September of 2002 under the GNU GPL License.
    *    For more information, visit http://www.gnu.org/licenses/gpl.html
    *
    *    Enjoy and please link to our site
    *    http://www.purpledragons.net
    *
    *     Changelog:
    *     September 9, 2003 : added support for realm title selection according to realm in XML
    *     March 28, 2003 : added UTF8 support for GOA XML - Euro servers only
    *     Feb. 25, 2003 : finally added $param as $_GET['param']
	*
	*     XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
	*
	*     Heavily gutted and modified to just display guild ML progression
	*	  You should probably redownload the complete script from the URL above if you want to do something
	*     this
	*
	*     XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
    */


class guildXML {

    /*
    *    This class takes a Dark Age of Camelot Herald XML guild data file and parses it
    */

        var $xml_parser;
        var $xml_file;
        var $html;
        var $open_tag ;
        var $close_tag ;
        var $in_char;
        var $in_alliance;
        var $chars_array;
        var $alliance_array;
        var $current_tag ='';

    /*
    *    Class Constructor
    */

    function guildXML() {
        $this->xml_parser = "";
        $this->xml_file = "";
        $this->html = "";
    }

//Class Destructor (has to be invoked manually as PHP does not support destructors)
    function destroy() {
        xml_parser_free($this->xml_parser);
    }

//Class Members
    function concat($str) {
        $this->html .= $str;
    }

    function startElement($parser, $name, $attrs) {
        global $totalchars, $num_activechars,$num_inactivechars, $insideElement, $in_char, $current_tag, $i, $current_name;

        if ($in_char==1)
            $current_tag = $name;
		
        if ($name=='CHARACTER'){
            $in_alliance = 1;
        }
        if ($name=='CHARACTER'){
            $in_char=1;
            $totalchars++;
            if (sizeof($attrs)) {
                while (list($k, $v) = each($attrs)) {
                    if ($k=='NAME'){
  						$current_name = trim($v);
                    }

                 }
             }
        }
        if ($name=='GUILD'){
            if (sizeof($attrs)) {
                while (list($k, $v) = each($attrs)) {
                    if ($k=='NAME'){
  						echo "<h1>" . trim($v) . "</h1>\n";
                    }

                 }
             }
		}

    }

    function endElement($parser, $name) {
               global $close_tag, $current_tag, $in_char, $i;

                if ($name=='CHARACTER'){$i++; $in_char=0;}
                if ($name=='ALLIANCE'){$i++; $in_alliance=0;}
    }

    function characterData($parser, $data)
	{
        global $guildrp, $inactiverp, $current_tag, $in_char, $i, $current_name, $mlevels, $mpaths, $current_ml;
		//$content_output .= '<br>'.$current_tag.' = '.$data.'<br>';
		if (($in_char==1) && ($current_tag=='MLEVEL'))
		{
			if ($data != 0)
			{
				$current_ml = $data;
				//$mlevels[$data][] = $current_name;
			}
		}
		if (($in_char==1) && ($current_tag=='MPATH'))
		{
			if (trim($data) != "" && $data != "None" && $current_ml != 0)
			{
				//echo "$current_ml $data $current_name<br>";
				$mlevels[$current_ml][$current_name] = $data;
			}
		}
	}

    function parse() {

        $this->xml_parser = xml_parser_create();
        xml_set_object($this->xml_parser, &$this);
        // use case-folding so we are sure to find the tag in $map_array
        xml_parser_set_option($this->xml_parser, XML_OPTION_CASE_FOLDING, true);

        xml_set_element_handler($this->xml_parser, "startElement", "endElement");
        xml_set_character_data_handler($this->xml_parser, "characterData");
        //xml_set_processing_instruction_handler($this->xml_parser, "PIHandler");

        if (!($fp = fopen($this->xml_file, "r"))) {
            die("could not open XML input");
        }
        while ($data = fread($fp, 4096)) {
            if (!xml_parse($this->xml_parser, $data, feof($fp))) {
                die(sprintf("XML error: %s at line %d",
                xml_error_string(xml_get_error_code($this->xml_parser)),
                xml_get_current_line_number($this->xml_parser)));
            }
        }
    }
}
// End of class

// Main content


	if (!$_GET['s'])
	{
		echo "<p><a href=\"?s=Mordred&g=15\">Shadowclan Mordred</a></p>";
		echo "<p><a href=\"?s=Mordred&g=15&ubbcode=1\">Shadowclan Mordred with ubbcode</a></p>";
		die();
	}

    $guild = new guildXML(); //instantiate class
    $guild->xml_file = "http://www.camelotherald.com/guilds/" . $_GET['s'] . "/" . $_GET['g'] . ".xml";
    //$guild->xml_file = "http://www.camelotherald.com/guilds/Mordred/15.xml";
    // the xml_file property referes to you guild XML data file.
    // Example given is the Merlin/Albion guild Dragon's Blood

    $guild->parse();


	if ($_GET['ubbcode'])
	{
		for ($i=10; $i>0; $i--)
		{
			echo "<br>\n[size=24][color=violet][b]Master Level " . $i . "[/b][/color][/size]<br>\n";
			if (count($mlevels[$i]) > 0)
			{
				foreach($mlevels[$i] as $name => $path)
					print $name . " (" . $path . ")<br>\n";
			}
			else
				echo "-<br>\n";
		}
	}
	else
	{
		for ($i=10; $i>0; $i--)
		{
			echo "\n<h3>Master Level " . $i . "</h3>\n";
			if (count($mlevels[$i]) > 0)
			{
				foreach($mlevels[$i] as $name => $path)
					print $name . " (" . $path . ")<br>\n";
			}
			else
				echo "-<br>\n";
		}
	}

?>

