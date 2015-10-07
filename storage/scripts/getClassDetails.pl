#!/usr/bin/perl
# by Stephen Wetzel May 03 2015
#Requires cURL is installed

#Use getListOfClasses.pl first to get a list of URLs of detail pages for each course.
#This script will download class details from the urls listed in the class_urls table.
#It will only update the classes for the given term and year (currently set below), and will take quite a while to run.  The enroll counts can be gotten from getListOfClasses.pl, so this script should only be needed daily or even less.

use strict;
use warnings;
use DBI;

#use autodie; #die on file not found
$|++; #autoflush disk buffer

# This script works directly with our SQLite DB  
my $dbFile = '../database.sqlite';
my $dsn      = "dbi:SQLite:dbname=$dbFile";
my $user     = "";
my $password = "";
my $dbh = DBI->connect($dsn, $user, $password, {
	PrintError       => 0,
	RaiseError       => 1,
	AutoCommit       => 1,
});
my $baseUrl = 'https://duapp2.drexel.edu/webtms_du/app?component=courseDetails&page=CourseSearchResult&service=direct&session=T';
my $sessionId = '2357A293F0608215F6D989A989D17BE1';
my $body=''; #response body
my $count = 0;
my $year = 2016;
my $term = 'Winter';

my $temp = `curl -s -D -  --data 'formids=term%2CcourseName%2CcrseNumb%2Ccrn&component=searchForm&page=Home&service=direct&submitmode=submit&submitname=&term=1&courseName=test&crseNumb=&crn=' -X POST https://duapp2.drexel.edu/webtms_du/app -o /dev/null`; #Note the lack of &session=T, that's important
$temp =~ m/Set-Cookie: JSESSIONID=([A-F0-9]{32})/ or die "Can't find JSESSIONID";
$sessionId = $1; #found the current session ID

#get the list of urls from the DB:
my $sth = $dbh->prepare("SELECT url FROM class_urls WHERE term = '$term' AND year = '$year'");
$sth->execute();

while (my $thisUrl = $sth->fetchrow_array())
{#go through each class detail url that we found in the db
	chomp($thisUrl);
	my $curlRequest = "curl --header 'cookie: JSESSIONID=$sessionId;' -X GET \"$baseUrl$thisUrl\" 2>/dev/null";
	
	#print "\n$curlRequest";
	$body = `$curlRequest`; #get response body from curl
	
	my ($crn, $subject, $cNum, $credits, $section, $title, $campus, $prof, $type, $comments, $time, $day, $desc, $preq, $coreq, $method, $max, $enroll, $building, $room) = 
	('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
	
	$body =~ m/CRN<\/td>\s+<td class.+>(\d+)<\/td>/ and $crn = $1;	
	$body =~ m/Subject Code<\/td>\s+<td class.+>([^<>]+)<\/td>/ and $subject = $1;
	$body =~ m/Course Number<\/td>\s+<td class.+>([^<>]+)<\/td>/ and $cNum = $1;
	$body =~ m/Section<\/td>\s+<td class.+>([^<>]+)<\/td>/ and $section = $1;
	$body =~ m/Credits<\/td>\s+<td class.+>([^<>]+)<\/td>/ and $credits = $1;
	$body =~ m/Title<\/td>\s+<td class.+>([^<>]+)<\/td>/ and $title = $1;
	$title =~ s/&amp;/&/g;
	$body =~ m/Campus<\/td>\s+<td class.+>([^<>]+)<\/td>/ and $campus = $1;
	$body =~ m/>Max Enroll<\/td>\s+<td class.+>([^<>]+)<\/td>/ and $max = $1;
	$body =~ m/>Enroll<\/td>\s+<td class.+>([^<>]+)<\/td>/ and $enroll = $1;
	$body =~ m/Instructor\(s\)<\/td>\s+<td class.+>([^<>]+)<\/td>/ and $prof = $1;
	$body =~ m/Instruction Type<\/td>\s+<td class.+>([^<>]+)<\/td>/ and $type = $1;
	$body =~ m/Instruction Method<\/td>\s+<td class.+>([^<>]+)<\/td>/ and $method = $1;
	$type =~ s/&amp;/&/g;
	#$body =~ m/Section Comments<\/td>\s+<td class.+>([^<>]+)<\/td>/ and $comments = $1;
	
	$body =~ m/<td align="center" >(\d{2}:\d{2} [amp]{2} - \d{2}:\d{2} [amp]{2})<\/td>/ and $time = $1;
	$body =~ m/<td align="center" >([MTWRFSTBD]{1,6})<\/td>/ and $day = $1;
	$body =~ m/<td align="left" >([A-Z]{1,10})<\/td>/ and $building = $1;
	
	$body =~ m/<div class="courseDesc">(.+)<\/div>/ and $desc = $1;
	$body =~ m/<div class="subpoint"><B>Pre-Requisites:<\/B> <span>(.+)<\/span><\/div>/ and $preq = $1;
	$body =~ m/<div class="subpoint"><B>Co-Requisites:<\/B> <span>(.+)<\/span><\/div>/ and $coreq = $1;
	
	$body =~ m/<div align="left">Schedule for (\w+) Quarter (\d\d)-\d\d<\/div>/ and $term = $1 and $year = $2; 
	if ($term ne 'Fall') { $year++; } #every term but fall takes place in the second year
	$year += 2000;
	
	$preq =~ s/<[\/a-zA-Z]+>//g; #remove <span> tags
	$preq =~ s/\s+/ /g; #remove duplicate whitespace
	$coreq =~ s/<[\/a-zA-Z]+>//g; #remove <span> tags
	$coreq =~ s/\s+/ /g; #remove duplicate whitespace
	
	print "$count \t$subject \t$cNum \t$crn \t$title\n";
	
	if (int($crn) > 0) {
		$dbh->do('INSERT OR REPLACE INTO classes (year, term, subject_code, course_no, crn, instr_method, section, credits, course_title, campus, instructor, instr_type, time, day, pre_reqs, co_reqs, description, max_enroll, enroll, building) 
		VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', undef, 
		$year, $term, $subject, $cNum, int($crn), $method, $section, $credits, $title, $campus, $prof, $type, $time, $day, $preq, $coreq, $desc, $max, $enroll, $building);
	}
	
	$count++;
	#sleep(1);
}

$dbh->disconnect;
print "\nDone\n\n";
