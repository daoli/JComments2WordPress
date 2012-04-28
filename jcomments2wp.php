<?php

$username = "username";
$password = "password";
$database = "database";

mysql_connect('localhost', $username, $password);
@mysql_select_db($database) or die( "Unable to select database");
@mysql_set_charset('utf8');

$query = "SELECT DISTINCT object_id FROM jos_jcomments";
echo $query; 
print "
";
$pids = mysql_query($query);

if (!$pids) {
	echo mysql_error();
}
$num = mysql_numrows($pids);
$i = 0;
while ($i < $num) {
	$pid = mysql_result($pids, $i, "object_id");
	$query = "SELECT created FROM  `jos_content` WHERE id = " . $pid;
	echo $query; 
	print "
	";
	$created = mysql_query($query);
	if (!$created) {
		echo mysql_error();
	}
	$ct = mysql_result($created, 0, "created");
	$query = "SELECT ID FROM  `wp_posts` WHERE  `post_date` =  '" . $ct . "' AND post_type =  'post'";
	echo $query; 
	print "
	";
	$wpids = mysql_query($query);
	if (!$wpids) {
		echo mysql_error();
	}
	$wpid = mysql_result($wpids, 0, "ID");
	$query = "SELECT * FROM jos_jcomments WHERE object_id = " . $pid;
	echo $query; 
	print "
	";
	$comments = mysql_query($query);
	$comments_count = mysql_numrows($comments);
	$j = 0;
	while ($j < $comments_count) {
		$author = mysql_result($comments, $j, "name");
		$email = mysql_result($comments, $j, "email");
		$url = mysql_result($comments, $j, "homepage");
		$ip = mysql_result($comments, $j, "ip");
		$cdate = mysql_result($comments, $j, "date");
		$content = mysql_result($comments, $j, "comment");
		$content = mysql_real_escape_string($content);
		
		$query = "INSERT INTO wp_comments (comment_post_ID, comment_author, comment_author_email, comment_author_url, comment_author_IP, comment_date, comment_date_gmt, comment_content) VALUES (" . $wpid . ", '" . $author . "', '" . $email . "', '" . $url . "', '" . $ip . "', '" . $cdate . "', '" . $date . "', '" . $content . "')";
		echo $query; 
		print "
		";
		mysql_query($query);
		$j++;
	}
	$query = "UPDATE wp_posts SET comment_count = " . $comments_count . " WHERE ID = " . $wpid;
	echo $query; 
	print "
	";
	mysql_query($query);

	$i++;
}
mysql_close();
?>

