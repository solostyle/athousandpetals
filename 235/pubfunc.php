<?

//----------------------------------------------------------------------------
// Publication ---------------------------------------------------------------
// ---------------------------------------------------------------------------

//Publish RSS or Atom feed
//RSS 2.0, Atom 1.0
function publish_feed($rss_or_atom, $num_of_entries) {

	$content = make_feed($rss_or_atom, $num_of_entries);
	$url_rel = $rss_or_atom . ".xml";
	$url_abs = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $url_rel;
	$mode = 'w';

	print $content . '<br />';

	if (!$handle = fopen($url_rel, $mode)) {
		 echo "Cannot open file, or file exists ($url_rel)";
		 exit;
	}

	// Write $content to our opened file.
	if (fwrite($handle, $content) === FALSE) {
		echo "Cannot write to file ($url_rel)";
		exit;
	}

	print "<p>Success. Wrote to file (<a href=\"$url_abs\">$url_rel</a>).</p>";

	fclose($handle);
}

//Accumulate XML for RSS or Atom feed
function make_feed($rss_or_atom, $lim) {
	$now  = my_mktime();
	$now_f = strftime('%G-%m-%d %H:%M:%S',$now);
	$now_d = dcdateformat($now_f);
	//accumulate content;
	if ($rss_or_atom == "atom") $content = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
<feed xmlns=\"http://www.w3.org/2005/Atom\">
<link href=\"http://athousandpetals.com/atom.xml\" rel=\"self\" />

 <title>AThousandPetals.com</title>
 <subtitle>Yoga Training, Meditation Classes, and Massage Therapy</subtitle>
 <link href=\"http://athousandpetals.com/\"/>
 <updated>$now_d</updated>
 <author>
   <name>Admin</name>
   <email>admin@athousandpetals.com</email>
 </author>
 <id>tag:athousandpetals.com,2011-04-14:/20110414153523456</id>";
 	else $content = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
 <rss version=\"2.0\" xmlns:dc=\"http://purl.org/dc/elements/1.1/\">
  <channel>
    <title>athousandpetals.com</title>
    <link>http://athousandpetals.com/</link>
    <description>Yoga Training, Meditation Classes, and Massage Therapy</description>
    <language>en-us</language>";

	$getentries = "SELECT * FROM `articles` ORDER BY `time` DESC LIMIT $lim";
	$entrieslist = mysql_query($getentries);
	//$num_entries = mysql_num_rows($entrieslist);
	// now, for each entry, publish xml
	while ($entryarray = mysql_fetch_array($entrieslist))
		$content .= make_xml_entry($rss_or_atom, $entryarray);
	mysql_free_result($entrieslist);

	if ($rss_or_atom == "atom") $content .= "</feed>";
	else $content .= "  </channel></rss>";
	
	return $content;
}

//Makes the RSS XML for each individual entry
function make_xml_entry($rss_or_atom, $entryarray) {
    $entrydate = dcdateformat($entryarray[2]);
    $entryurl = make_url($entryarray[0]);
    $entrytext = substr(strip_tags($entryarray[4]),0,300);
    $content = "";

    switch ($rss_or_atom) {
    case "rss":
	    $content .= "<item>";
	    $content .= "<title>$entryarray[3]</title>";
	    $content .= "<link>\"$entryurl\"</link>";
    	    $content .= "<description>$entrytext</description>";
	    $content .= "<dc:creator>$entryarray[1]</dc:creator>";
	    $content .= "<dc:date>$entrydate</dc:date>";
	    $content .= "<guid isPermaLink=\"true\">$entryurl</guid>";
	    $content .= "</item>";
	    break;
    case "atom":
    	    $content .= "<entry>";
	    $content .= "<title>$entryarray[3]</title>";
	    $content .= "<link href=\"$entryurl\" />";
	    $content .= "<id>$entryarray[0]</id>";
	    $content .= "<summary>$entrytext</summary>";
	    $content .= "<author><name>$entryarray[1]</name></author>";
	    $content .= "<updated>$entrydate</updated>";
	    $content .= "</entry>";
	    break;
    }
    return $content;
}

// DC format for XML date
// input is the fetched value from the database, timestamp(14) 2004-09-30 06:05:52
// output is 2006-04-15T23:54:00Z format
function dcdateformat($entrydate) {
	$date = str_replace(" ","T",$entrydate);
	$date .= "Z";
	return $date;
}


// Unix server-adjusted timestamp
// My current dreamhost server uses Pacific
// Add 2 to get Central
function my_mktime() {
    // for chicago
		//$time = mktime(date("H")+2, date("i")-11); // for some reason the time is 11 min fast too!
		// for chennai
		$time = mktime(date("H")+14, date("i")-41);
		print ('chennai time is ' . $time);
    return $time;
}


?>
