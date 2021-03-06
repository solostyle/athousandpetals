<?php

//List of functions included:
// make_entry() publish the full entry
// show_preview() show the preview
// Tables are by week
// They are named yearweek, i.e. 2004.04.14 would be 200415
// WTF?
//	2007: Monday is first day of week
//	2006: Sunday is first day of week


// Connect to the database server
function select_db($server, $usr, $pw, $dbname) {
   mysql_connect($server,$usr,$pw) or die("Couldn't connect!<br />");
   mysql_select_db($dbname);
}

function desc($a, $b) {
   if ($a == $b) {
       return 0;
   }
   return ($a < $b) ? 1 : -1;
}


//Get the array of tables and sort from most recent to least
function get_tables($last_table) {

	$gettables = "SHOW TABLES LIKE '200%'";

	$tableslist = mysql_query($gettables);
	$tablesarray = array();

	$num_tables = mysql_num_rows($tableslist);
	for ($i=0; $i < $num_tables; $i++) {
		$row = mysql_fetch_array($tableslist);
		// allow only those newer (greater) than or equal to $last_table
		if ($row[0] >= $last_table) array_push($tablesarray, $row[0]);
	}
	usort($tablesarray, desc);
	mysql_free_result($tableslist);
	return $tablesarray;
}


// Show (preview) the new entries since last sign in
function show_new($last_time, $user_id) {

	$time_limit = "WHERE (`time` > '$last_time')";

	//limit the number of tables based on date
	// find $last_time as week
	$last_table = strftime('%Y%W', strtotime($last_time));

	$tables = get_tables($last_table);
	//show posts
	$k = 0;		// table counter
	$content = "";	// content accumulator
	while ($k<count($tables)) {
		$table = $tables[$k]; //name of the latest table
		$getentries = "SELECT * FROM `$table` $time_limit ORDER BY `time` DESC";
		$entrieslist = mysql_query($getentries);
		//$num_entries = mysql_num_rows($entrieslist);
		$k++; // next table
		while ($entryarray = mysql_fetch_array($entrieslist)) {
			$content .= show_preview($entryarray);
		}
		mysql_free_result($entrieslist);
	}
	return $content;

}


// Display desired # of most recent post titles or entries
function preview_recent() {
	$tables = get_tables(0);
	//show posts
	$j = 0;		// entry counter
	$k = 0;		// table counter
	$content = "";	// content accumulator
	while ($j<5 AND $k<count($tables)) {
		$table = $tables[$k]; //name of the latest table
		$limit = 5 - $j;
		$getentries = "SELECT * FROM `$table` ORDER BY `time` DESC LIMIT $limit";
		$entrieslist = mysql_query($getentries);
		$num_entries = mysql_num_rows($entrieslist);
		$j+=$num_entries; //increment $j
		$k++; //and $k
		while ($entryarray = mysql_fetch_array($entrieslist)) {
			$content .= show_preview($entryarray);
		}
		mysql_free_result($entrieslist);
	}
	return $content;
}

//Display entries from a table (week) to edit or view
function show_week($week, $content_to_show) {
	$content = "";
	$getentries = "SELECT * FROM `$week` ORDER BY `time` DESC";
	$entrieslist = mysql_query($getentries);
	while ($entryarray = mysql_fetch_array($entrieslist)) {
		switch ($content_to_show) {
			case "show": //used by weekly archive.php
				$content .= show_preview($entryarray);
				break;
			case "republish":
				$content .= list_entry_republish($entryarray);
				break;
		}
	}
	mysql_free_result($entrieslist);
	return $content;
}

// List weeks in dropdown menu
function list_weeks() {
	$tables = get_tables(0);
	$k = 0; //table counter
	$content = '<p><select name="week[]" multiple="multiple" size="10">';
	while ($k<count($tables)) {
		$table = $tables[$k];
		// convert table format to Month day-day Year
		$f_table = ISOdate_to_date($table . '0');
		$content .= '<option value="' . $table . '">' . $f_table . '</option>';
		$k++;
	}
	$content .= '</select></p>';
	return $content;
}


// Create this weeks log table
function create_table_week($dateYmd) {
   $gettablename = "SELECT DATE_FORMAT($dateYmd, '%x%v')"; //date(Ymd);
   $tablenameresult = mysql_query($gettablename);
   $tablenamerow = mysql_fetch_row($tablenameresult);
   $tablename = $tablenamerow[0];

   if (!mysql_query("DESCRIBE $tablename")) {    // if $tablename doesnt exist, create it
     $query = "CREATE TABLE `$tablename` (id varchar(255) not null primary key DEFAULT 'tag:iam.solostyle.net,', username varchar(50) not null, time timestamp(14) not null, title varchar(50) not null, entry text not null)";
      mysql_query($query);
	echo "created table $tablename";
   }
   return $tablename;
}

//Convert tags to urls for page links
function tag_to_url($tag) {
	//tag:iam.solostyle.net,2006-04-04:/location
	$location = strrchr($tag,":");			//:/location
	$location = substr($location,1);		///location
	$location = str_replace("\\","",$location); 	//to get rid of escape characters!
	$pos = strpos($tag,",");			//probably 24th
	$url1 = substr($tag,0,$pos);			//tag:iam.solostyle.net
	$url1 .= $location . ".php";			//tag:iam.solostyle.net/location.php
	$url = str_replace("tag:","http://",$url1);	//http://iam.solostyle.net/location.php
	return $url;
}

function nl2p_or_br($text) {
  $text_with_p = "<p>" . str_replace("\r\n\r\n", "</p><p>", $text) . "</p>";
  $text_with_p_and_br = str_replace("\r\n", "<br />", $text_with_p);
  return $text_with_p_and_br;
}

// Make the entries
function make_entry($row) {
	$date = parse_date($row[2]);
	$time = parse_time($row[2]);
	$url = tag_to_url($row[0]);

	$entry = stripslashes($row[4]);
	$entry = nl2p_or_br($entry);
	$title = stripslashes($row[3]);

	$content = "
		<div class=\"publishedentry\">
			<h2>$title</h2>
			<h3>$date @ $time</h3>
			$entry
			<div style=\"clear:both; padding-bottom: 0.25em;\"></div>
			<p>
				<em><a name=\"bot\" href=\"http://iam.solostyle.net/comment.php\">comments</a></em>
			</p>
		</div>";

	// categories form
//	$catarray = explode(",",$row[5]);
//	$content = '<p><form name="category_form" method="post" action="http://' . $_SERVER['HTTP_HOST'] . '/category.php">
//		<select name="category" size="1">';
//	while ($k<count($catarray)) {
//		$cat = $catarray[$k];
//		$content .= '<option value="' . $cat . '">' . $cat . '</option>';
//		$k++;
//	}
//	$content .= '</select><input type="submit" value="Go" /></form></p>';

	// get the comment rows
	$get_comments = "SELECT * FROM `comments` WHERE `post_tag`='$row[0]' ORDER BY `time`";
	$comments = mysql_query($get_comments);
	while ($comment_row = mysql_fetch_array($comments)) {
		$content .= show_comment($comment_row);
	}
	mysql_free_result($comments);

	return $content;
}

// Make the comment
function make_comment($row) { //tag, post_tag, name, website, email, comment, time
	$date = parse_date($row[6]);
	$time = parse_time($row[6]);
	$url = tag_to_url($row[1]);


	$comment = stripslashes(strip_tags($row[5]));
	$comment = nl2p_or_br($comment);
	$website = $row[3];

	$content = "
			<div>
				<h3>$date @ $time by <a href=\"$website\">$row[2]</a></h3>
				<p>$comment</p>
				<div style=\"clear:both; padding-bottom: 0.25em;\"></div>
			</div>";
	return $content;
}

// Show the full entry


// Show the entries
function show_preview($row) {
	$date = parse_date($row[2]);
	$time = parse_time($row[2]);
	$url = tag_to_url($row[0]);

	$text = substr(strip_tags($row[4]), 0, 200) . "...<a href=\"$url\">full</a>";
	$text = stripslashes($text);
	$title = stripslashes($row[3]);
	$content = "
	<div>
		<div class=\"preview\">
			<h2>$title</h2>
			<h3>$date @ $time</h3>
			<p>$text</p>
		</div>
	</div>";
	return $content;
}

// Show the comment
function show_comment($row) {//tag, post_tag, name, website, email, comment, time
	$date = parse_date($row[6]);
	$time = parse_time($row[6]);
	$url = tag_to_url($row[1]);

	$comment = strip_tags($row[5]);
	$title = stripslashes($row[2]);

	$content = "
		<div class=\"comment\">
			<h2> <a href=\"$url\">$title</a></h2>
			<h3>$date @ $time by $row[2]</h3>
			<p>$comment</p>
			<div style=\"clear:both; padding-bottom: 0.25em;\"></div>
		</div>";
	return $content;
}


// Format the entry for editing
function list_entry_republish($row) {
	$date = parse_date($row[2]);
	$time = parse_time($row[2]);
	$url = tag_to_url($row[0]);

	$entry = substr(strip_tags($row[4]), 0, 200) . "...<a href=\"$url\">full</a>";

	$content = "
		<div class=\"preview\">
			<h2><input type=\"checkbox\" name=\"republish[]\" value=\"${row[0]}\">&nbsp;$row[3]</h2>
			<h3>$date &nbsp; $time</h3>
			<p>$entry</p>
		</div>";
	return $content;
}


//Create tag based on POST data
function create_tag($title, $year, $month, $date) {
	$tagtitle = parse_title($title);
	$tag = "tag:" . $_SERVER['HTTP_HOST'] . "," . $year . "-" . $month . "-" . $date . ":/" . $year . "/" . $month . "/" . $tagtitle;
	return $tag;
}

// Parse Time
// used in retrieving values from database and displaying entries
// input is the fetched value from the database, timestamp(14) 2004-09-30 06:05:52
function parse_time($time) {
   $hr = substr($time, 11, 2);
   $mn = substr($time, 14, 2);
   return "$hr:$mn";
}

// Parse Date
// used in retrieving values from database and displaying entries
// input is the fetched value from the database, timestamp(14)
function parse_date($time) {
   $yr = substr($time, 0, 4);
   $mo = substr($time, 5, 2);
   $da = substr($time, 8, 2);
   $month = monthname($mo);

   return "$da $month $yr ";
}


// Insert a Record
// takes a tablename and two arrays
function insert_record($tablename,$fields,$values) {
   if (count($fields) != count($values)) {
      print("Error: length of arrays are not equal.");
      print count($fields);
      print count($values);
   }
   else {
      $sqlstatement = "INSERT INTO `$tablename` (`$fields[0]`";
      for ($i=1; $i<=count($fields)-1; $i++)
         $sqlstatement .= ",`$fields[$i]`";
      $sqlstatement .= ") Values ('$values[0]'";
      for ($j=1; $j<=count($values)-1; $j++)
         $sqlstatement .= ",'$values[$j]'";
      $sqlstatement .= ");";
print $sqlstatement;
      mysql_query($sqlstatement);
     }
}

//Creates a Header for publishing PHP files
function head() {
	$head = "<?		session_start();
	include '../../235/dbfunctions.php';
	include '../../235/storevars.php';
	include '../../inc/header.php';?>";

	return $head;
}

//Creates a Footer for publishing PHP files
function foot() {
	$foot = "
	<?	include '../../comment.html';
	include '../../right.html';
	include '../../inc/footer.php';	?>";
	return $foot;
}

//Publish one entry onto its php page
//can be called after INSERTING $mode = 'x' or EDITING $mode = 'w+'
//gets data from the same place as insert array and writes it to a file
function publish_entry($entryarray, $mode) {

	$url_abs = tag_to_url($entryarray[0]);
print "<p>here's the tag: $entryarray[0]<br />here's the url: $url_abs</p>";
	$url_rel = strstr($url_abs,".net/");
	$url_rel = substr($url_rel, 5);

	$head = head();
	$foot = foot();
	$content = $head . "<? \$_SESSION['entry_id'] = \"$entryarray[0]\"; ?>" . make_entry($entryarray) . $foot;

print $content;

	if (!$handle = fopen($url_rel, $mode)) {
		 echo "Cannot open file, or file exists ($url_rel)";
		 exit;
	}

	// Write $content to our opened file.
	if (fwrite($handle, $content) === FALSE) {
		echo "Cannot write to file ($url_rel)";
		exit;
	}

	fclose($handle);
}

//Publish one comment onto its php page
//can be called after INSERTING $mode = 'x' or EDITING $mode = 'w'
//gets data from the same place as insert array and writes it to a file
function publish_comment($entryarray, $mode) {

	$url_abs = tag_to_url($entryarray[0]);
	$url_rel = strstr($url_abs,".net/");
	$url_rel = substr($url_rel, 5);

	$head = head();
	$foot = foot();
	$content = $head . make_comment($entryarray, 0 , 0) . $foot;

	//print $content;

	if (!$handle = fopen($url_rel, $mode)) {
		 echo "Cannot open file, or file exists ($url_rel)";
		 exit;
	}

	// Write $content to our opened file.
	if (fwrite($handle, $content) === FALSE) {
		echo "Cannot write to file ($url_rel)";
		exit;
	}

	fclose($handle);
}

//Publish RSS or Atom feed
//RSS 2.0, Atom 1.0
function publish_feed($rss_or_atom, $num_of_entries) {
	$content = make_feed($rss_or_atom, $num_of_entries);
	$url_rel = $rss_or_atom . ".xml";
	$url_abs = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $url_rel;
	$mode = 'w';

	//print $content;

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
function make_feed($rss_or_atom, $num_to_show) {
	$now  = mktime(date("H")-8, date("i"), date("s"), date("m"), date("d"), date("Y"));
	$now_f = strftime('%G-%m-%d %H:%M:%S',$now);
	$now_d = dcdateformat($now_f);
	//accumulate content;
	if ($rss_or_atom == "atom") $content = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
<feed xmlns=\"http://www.w3.org/2005/Atom\">
<link href=\"http://iam.solostyle.net/atom.xml\" rel=\"self\" />

 <title>iam.solostyle.net</title>
 <subtitle>Me.</subtitle>
 <link href=\"http://iam.solostyle.net/\"/>
 <updated>$now_d</updated>
 <author>
   <name>solostyle</name>
   <email>solo@solostyle.net</email>
 </author>
 <id>tag:iam.solostyle.net,2006-04-16:/20060416063829735</id>";
 	else $content = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
 <rss version=\"2.0\" xmlns:dc=\"http://purl.org/dc/elements/1.1/\">
  <channel>
    <title>iam.solostyle.net</title>
    <link>http://iam.solostyle.net/</link>
    <description>Me.</description>
    <language>en-us</language>";

	$tables = get_tables(0);

	//show posts
	$j = 0;		// entry counter
	$k = 0;		// table counter

	while ($j<$num_to_show AND $k<count($tables)) {
		$table = $tables[$k]; //name of the latest table
		$limit = $num_to_show - $j;
		$getentries = "SELECT * FROM `$table` ORDER BY `time` DESC LIMIT $limit";
		$entrieslist = mysql_query($getentries);
		$num_entries = mysql_num_rows($entrieslist);
		$j+=$num_entries; //increment $j
		$k++; //and $k
		// now, for each entry, publish xml
		while ($entryarray = mysql_fetch_array($entrieslist))
			$content .= make_xml_entry($rss_or_atom, $entryarray);
		mysql_free_result($entrieslist);
	}
	if ($rss_or_atom == "atom") $content .= "</feed>";
	else $content .= "  </channel></rss>";
	return $content;
}

//Makes the RSS XML for each individual entry
function make_xml_entry($rss_or_atom, $entryarray) {
    $entrydate = dcdateformat($entryarray[2]);
    $entryurl = tag_to_url($entryarray[0]);
    $entrytext = substr(strip_tags($entryarray[4]),0,300);
	//accumulator time, username, date, title, entry
	$content = "";
    if ($rss_or_atom == "rss") {
    	$content .= "<item>";
    	//title
    	$content .= "<title>$entryarray[3]</title>";
    	//link
    	$content .= "<link>http://iam.solostyle.net/</link>";
    	//description
    	$content .= "<description>$entrytext</description>";
    	//creator
    	$content .= "<dc:creator>$entryarray[1]</dc:creator>";
    	//date
    	$content .= "<dc:date>$entrydate</dc:date>";
    	//permalink
    	$content .= "<guid isPermaLink=\"true\">$entryurl</guid>";
    	$content .= "</item>";
    }
    else if ($rss_or_atom == "atom") {
	    $content .= "<entry>";
	    //title
	    $content .= "<title>$entryarray[3]</title>";
	    //link
	    $content .= "<link href=\"$entryurl\" />";
	    //id
	    $content .= "<id>$entryarray[0]</id>";
	    //summary
	    $content .= "<summary>$entrytext</summary>";
	    //author
	    $content .= "<author><name>$entryarray[1]</name></author>";
	    // updated
	    $content .= "<updated>$entrydate</updated>";
	    $content .= "</entry>";
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

//Converts title to url/tag
function parse_title($title) {
	$special = array("!", "@", "$", "%", "^", "&", "*", ":", "'", ";", "<", ">", ",", ".", "?", "/", chr(34));
	$name = strtolower($title);
	if (substr($name, 0, 3) == "the") $name = substr($name, 3);
	$name = str_replace($special, "", $name);
	$name = str_replace(" ", "-", $name);
	return $name;
}
///////////////EDIT THIS PART /////////////////
// Get One entry to edit
// returns an array of all the values
function get_entry($tag) {
	$table = get_table_from_tag($tag);
	print $table;
	print $tag;
	$result = mysql_query("SELECT * FROM `$table` WHERE `id` = '$tag'");
	$entry = mysql_fetch_array($result, MYSQL_NUM);
	mysql_free_result($result);
	if (!$entry) print("That entry doesn't exist anymore!");
	return $entry;
}

// Delete a Record, but never the table
function delete_record($tag) {
	$table = get_table_from_tag($tag);
	mysql_query("DELETE FROM `$table` WHERE `id` = '$tag'");
}

// Update a Record, takes a tablename two arrays
function update_record($table,$fields,$values,$id) {
	$sqlstatement = "UPDATE `$table` SET `$fields[0]` = '$values[0]'";
	for ($i=1; $i<=count($fields)-1; $i++)
		$sqlstatement .= ", `$fields[$i]` = '$values[$i]'";
	$sqlstatement .= " WHERE `$fields[0]` = '$id'";
	mysql_query($sqlstatement);
}

// Gets tablename from an ID/tag
function get_table_from_tag($tag) {
	$substring = strstr($tag, ","); 	// ,2006-04-18:/2006/04/something
	$date = substr($substring, 1,10);	// 2006-04-18
	$date = str_replace("-","",$date);	// 20060418
	$gettablename = "SELECT DATE_FORMAT($date, '%x%v')";
	$tablenameresult = mysql_query($gettablename);
	$tablenamerow = mysql_fetch_row($tablenameresult);
	$table = $tablenamerow[0];
	return $table;
}

function randomkey($length) {
   $pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
   for($i=0;$i<$length;$i++) {
     $key .= $pattern{rand(0,35)};
   }
   return $key;
}

// takes a two digit month number and returns the month name
function monthname($num) {
	switch ($num) {
	case "01":
	   $month = "January";
	   break;
	case "02":
	   $month = "February";
	   break;
	case "03":
	   $month = "March";
	   break;
	case "04":
	   $month = "April";
	   break;
	case "05":
	   $month = "May";
	   break;
	case "06":
	   $month = "June";
	   break;
	case "07":
	   $month = "July";
	   break;
	case "08":				//so weird!!!
	   $month = "August";
	   break;
	case "09":
	   $month = "September";
	   break;
	case "10":
	   $month = "October";
	   break;
	case "11":
	   $month = "November";
	   break;
	case "12":
	   $month = "December";
	   break;
	}
	return $month;
}

// Converts an ISO year,week,day number to a real date
// 2006 is not starting on monday, starting on sunday! a day earlier.
function ISOdate_to_date($isoformat) {
	//this years january 1st, find the week number
	$isoyear = substr($isoformat, 0, 4);
	$thefirst = "01/01/" . $isoyear;
	$dayofJan1 = strftime('%w', strtotime($thefirst)); // 1=monday; 4=thursday; 0=sunday
	if ($dayofJan1 <= 4) //Jan 1 is in week 01 if thursday or earlier
		$isodays = substr($isoformat, 4, 2) * 7 - 7;
	else $isodays = substr($isoformat, 4, 2) * 7;
	//mktime args: hr, min, sec, mon, day, yr
	$date_start = mktime(0, 0, 0, 1, 1 + $isodays, $isoyear);
	$date_end = mktime(0, 0, 0, 1, 1 + $isodays + 6, $isoyear);
	$f_date_start = strftime('%d', $date_start);
	$f_month_start = strftime('%b', $date_start);
	$f_year_start = strftime('%Y', $date_start);
	$f_date_end = strftime('%d', $date_end);
	$f_month_end = strftime('%b', $date_end);
	$f_year_end = strftime('%Y', $date_end);
	return $f_date_start . " " . $f_month_start . " " . $f_year_start . " - " . $f_date_end . " " . $f_month_end . " " . $f_year_end;
}

// Generates a password with no ambiguous characters
function gen_pw($length) {
	//warning: i took out ambiguous-looking characters
	$template = "23456789abcdefghjkmnopqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ!@#$%^&*()[]{}|?~";

	for ($a = 0; $a < $length; $a++) {
		   $b = rand(0, strlen($template) - 1);
		   $pw .= $template[$b];
	}

	return $pw;
}

function show_category_entries($category) {
	$tables = get_tables();
	$content = "";
	while ($k < count($tables)) {
		$table = $tables[$k];
		$getentries = "SELECT * FROM `$table` ORDER BY `time` DESC";
		$entrieslist = mysql_query($getentries);
		while ($entryarray = mysql_fetch_array($entrieslist)) {
			$catarray = explode(",", $entryarray[5]);
			if (in_array($category, $catarray))
				$content .= show_preview($entryarray);
		}
	}
	mysql_free_result($entrieslist);
	return $content;
}

?>