#!/usr/bin/perl
# by Stephen Wetzel July 29 2015
#Requires cURL is installed

#This will get a list of all the department course listings for next year, it saves them to a DB
#You must run this before running getListOfClassesFromSubjects.pl
#You should only need to run this once a year, when they update the current year.  Although you might as well run it more often.


use strict;
use warnings;
use DBI;
use HTML::Entities;

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

my $baseUrl = "https://duapp2.drexel.edu";
my $sessionId = '2357A293F0608215F6D989A989D17BE1';
my $body=''; #response body
my @termNames = ('', 'Fall', 'Winter', 'Spring', 'Summer');

my $year = 2016;
my $term = 'Winter';

my $firstUrl = "https://duapp2.drexel.edu/webtms_du/app?page=Home&service=page";

sub getTimeStamp {
	my ($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst)=localtime(time);
	return sprintf("%04d-%02d-%02d %02d:%02d:%02d",$year+1900,$mon+1,$mday,$hour,$min,$sec);
}

#get the JSESSIONID:
my $temp = `curl -s -D -  --data 'formids=term%2CcourseName%2CcrseNumb%2Ccrn&component=searchForm&page=Home&service=direct&submitmode=submit&submitname=&term=1&courseName=test&crseNumb=&crn=' -X POST https://duapp2.drexel.edu/webtms_du/app -o /dev/null`; #Note the lack of &session=T, that's important
$temp =~ m/Set-Cookie: JSESSIONID=([A-F0-9]{32})/ or die "Can't find JSESSIONID";
$sessionId = $1; #found the current session ID

#we will first have to loop through the departments on the left side
#then we will go to the page for each of those and grab all the subject urls and store them in the DB

my $tempCurlRequest = "curl -s -D - --header 'cookie: JSESSIONID=$sessionId;' -X GET '$firstUrl' 2>/dev/null";
print "$tempCurlRequest\n";
$body = `$tempCurlRequest`; #get response body from curl

my @termUrls = ();
while ($body =~ m/<a href="(\/webtms_du\/app\?component=quarterTermDetails&amp;page=Home[^<>"]+?)">/g)
{#get all the urls for next year's terms from the main tms page
	push @termUrls, decode_entities($1);
}

foreach my $termUrl (@termUrls)
{#go through each term and get department urls
	my @departmentUrls = ();
	$tempCurlRequest = "curl -s -D - --header 'cookie: JSESSIONID=$sessionId;' -X GET '$baseUrl$termUrl' 2>/dev/null";
	print "$tempCurlRequest\n";
	$body = `$tempCurlRequest`; #get response body from curl

	$body =~ m/<div align="left">Schedule for (\w+) Quarter (\d\d)-\d\d<\/div>/ and $term = $1 and $year = $2; 
	if ($term ne 'Fall') { $year++; } #every term but fall takes place in the second year
	$year += 2000;

	print "\n\nDownloading data for $term $year\n\n";

	while ($body =~ m/<a href="(\/webtms_du\/app\?component=collSubj&amp;page=CollegesSubjects[^<>"]+?)">/g)
	{#put each department url in the array
		push @departmentUrls, decode_entities($1);
	}

	my @subjectUrls = ();
	#it turns out the only thing that changes is the number at the end of the url, but this way is more robust if they change things
	foreach my $thisUrl (@departmentUrls)
	{#go through each department and find all the subject urls
		$tempCurlRequest = "curl -s -D - --header 'cookie: JSESSIONID=$sessionId;' -X GET '$baseUrl$thisUrl' 2>/dev/null";
		print "$tempCurlRequest\n";
		$body = `$tempCurlRequest`; #get response body from curl
		while ($body =~ m/<a href="(\/webtms_du\/app\?component=subjectDetails&amp;page=CollegesSubjects[^<>"]+?)">/g)
		{#gather subject urls
			push @subjectUrls, decode_entities($1);
		}
	}

	my $timestamp = getTimeStamp();
	foreach my $thisUrl (@subjectUrls)
	{#save data to db
		#print "$thisUrl\n";
		$dbh->do('INSERT OR REPLACE INTO subject_urls (year, term, url, timestamp) 
		VALUES (?, ?, ?, ?)', undef, $year, $term, $thisUrl, $timestamp);	
	}
}

$dbh->disconnect;
print "\nDone\n\n";
