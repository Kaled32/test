<?
// This is a template for a PHP scraper on Morph (https://morph.io)
// including some code snippets below that you should find helpful

require 'scraperwiki.php';
require 'scraperwiki/simple_html_dom.php';

// Read in a page
$html = scraperwiki::scrape("https://www.crunchbase.com/organization/zendesk");

// Find something on the page using css selectors
$dom = new simple_html_dom();
$dom->load($html);
echo $html;
print_r($dom->find("div.details")); //div.large h5

// Write out to the sqlite database using scraperwiki library
//scraperwiki::save_sqlite(array('name'), array('name' => 'susan', 'occupation' => 'software developer'));

// An arbitrary query against the database
//scraperwiki::select("* from data where 'name'='peter'")

