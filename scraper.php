<?
// This is a template for a PHP scraper on Morph (https://morph.io)
// including some code snippets below that you should find helpful

// require 'scraperwiki.php';
// require 'scraperwiki/simple_html_dom.php';
//
// // Read in a page
// $html = scraperwiki::scrape("http://foo.com");
//
// // Find something on the page using css selectors
// $dom = new simple_html_dom();
// $dom->load($html);
// print_r($dom->find("table.list"));
//
// // Write out to the sqlite database using scraperwiki library
// scraperwiki::save_sqlite(array('name'), array('name' => 'susan', 'occupation' => 'software developer'));
//
// // An arbitrary query against the database
// scraperwiki::select("* from data where 'name'='peter'")

// You don't have to do things with the ScraperWiki library. You can use whatever is installed
// on Morph for PHP (See https://github.com/openaustralia/morph-docker-php) and all that matters
// is that your final data is written to an Sqlite database called data.sqlite in the current working directory which
// has at least a table called data.
?>


<html>
<head>
<meta http-equiv="refresh" content="30" />
</head>
<body>
<?php
define('TYPE_FORUMINDEX', 0);
define('TYPE_GIVEAWAY', 1);
define('TYPE_FORUMTHREAD', 2);
define('TYPE_UNKNOWN', 3);
define('TYPE_DEEPSCAN', 4);
define('TYPE_STEAMPAGE', -1);

date_default_timezone_set("America/Los_Angeles");

$source= 'sg_1';
scraperwiki::attach($source);  

$data = scraperwiki::select("url, depth FROM pool ORDER BY type, depth  LIMIT 1");
if(count($data) == 1){
    $url = $data[0]['url'];
    $depth = $data[0]['depth'];
    print "top: <a href='$url'>$url</a> @ $depth";
    print "<br/><br/>";
}


$data = scraperwiki::select("count(*) AS base FROM blacklist");
if(count($data) == 1){
    $count = $data[0]['base'];
    print "blacklisted: $count";
    print "<br/><br/>";
}

print "<table>";           
print "<tr>";
print   "<th>Type</th>";
print   "<th>Count</th>";

$data = scraperwiki::select("type, count(type) as entries FROM pool GROUP BY type");
$types = array(
    TYPE_STEAMPAGE => 'steam page',
    TYPE_FORUMINDEX => 'forum index',
    TYPE_GIVEAWAY => 'giveaway',
    TYPE_FORUMTHREAD => 'forum thread',
    TYPE_UNKNOWN => 'unknown',
    TYPE_DEEPSCAN => 'deepscan'
);

if(!empty($data)){
    foreach($data as $d){
        $type = $types[$d['type']];
        $entries = $d['entries'];
    
        print "<tr>";
        print   "<td>$type</td>";
        print   "<td>$entries</td>";
        print "</tr>";
    }
} else {
    print "<tr>";
    print   "<td colspan='2'>no entry found</td>";
    print "</tr>";    
}

print "</table>";

print "<br/><br/>";

print "<table>";           
print "<tr>";
print   "<th>Giveaway</th>";
print   "<th>Date</th>";

$data = scraperwiki::select("* FROM giveaway ORDER BY date DESC");

if(!empty($data)){
    foreach($data as $d){
        $url = $d['url'];
        $date = date("d.m.Y G:i", $d['date']);
        $title = $d['title'];
    
        print "<tr>";
        print   "<td><a href='$url'>$title</a></td>";
        print   "<td>$date</td>";
        print "</tr>";
    }
} else {
    print "<tr>";
    print   "<td colspan='2'>no entry found</td>";
    print "</tr>"; 
}

print "</table>";
?>
</body>
</html>