#!/usr/bin/perl
# by Stephen Wetzel May 03 2015
#Requires cURL is installed

#This script will use the search function on TMS to get a list of urls for class detail pages.  The search only works for the current year.  The results are stored in the database in the class_urls table.  It will also update enroll counts.  Uses a search for courses that contain the letter 'a', then 'e' and so on.
#For next year, see getDepartmentLists.pl and getListOfClassesFromSubjects.pl

use strict;
use warnings;
#use List::MoreUtils qw(any uniq);
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

my $url = "https://duapp2.drexel.edu/webtms_du/app";
my $sessionId = '2357A293F0608215F6D989A989D17BE1';
my $body=''; #response body
my $data='formids=term%2CcourseName%2CcrseNumb%2Ccrn&component=searchForm&page=Home&service=direct&session=T&submitmode=submit&submitname=&crseNumb=&crn=&courseName=';
my $year = 2015; #the script updates these below
my $term = 'Winter';

#It seems on the TMS search page, the terms 1-4 are always fall through summer of this academic year.
#Next year is terms 5-8, but don't seem to work

my @termNames = ('', 'Fall', 'Winter', 'Spring', 'Summer');
my @letters = ('a', 'e', 'i', 'o', 'u', 'y'); #assuming every course contains one of these
#my @letters = ('z');


sub getTimeStamp {
	my ($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst)=localtime(time);
	return sprintf("%04d-%02d-%02d %02d:%02d:%02d",$year+1900,$mon+1,$mday,$hour,$min,$sec);
}

#get the JSESSIONID:
my $temp = `curl -s -D -  --data 'formids=term%2CcourseName%2CcrseNumb%2Ccrn&component=searchForm&page=Home&service=direct&submitmode=submit&submitname=&term=1&courseName=test&crseNumb=&crn=' -X POST https://duapp2.drexel.edu/webtms_du/app -o /dev/null`; #Note the lack of &session=T, that's important
$temp =~ m/Set-Cookie: JSESSIONID=([A-F0-9]{32})/ or die "Can't find JSESSIONID";
$sessionId = $1; #found the current session ID

for (my $termNum = 1; $termNum <= 4; $termNum++)
{#go through each term in the current year
	#these hashes will use the crn as the key
	my %maxEnrolls;
	my %enrolls;
	my %urls;
	my $timestamp = getTimeStamp();
	if ($termNum == 2) { $year++; } #term 2 is winter
	print "\n\nDownloading data for $termNames[$termNum] $year";
	my @allUrls;
	foreach my $letter (@letters)
	{
		my $tempCurlRequest = "curl --header 'cookie: JSESSIONID=$sessionId;' --data '$data$letter&term=$termNum' -X POST $url 2>/dev/null";
		print "\n\n$tempCurlRequest";
		$body = `$tempCurlRequest`; #get response body from curl
		print "\nLetter: $letter";
		my @newUrls = ();
		
		my $newUrlCount = 0;
		while ($body =~ m/.+<p title="Max enroll=(\d+); Enroll=(\d+).+&amp;page=CourseSearchResult&amp;service=direct&amp;session=T(&amp;sp=.+;sp=0)">(\d+)<\/a>/g)
		{
			my $crn = int($4);
			my $maxEnroll = $1;
			my $enroll = $2;
			my $detailUrl = decode_entities($3);
			$detailUrl = decode_entities($detailUrl);
			$urls{$crn} = $detailUrl;
			$maxEnrolls{$crn} = $maxEnroll;
			$enrolls{$crn} = $enroll;
			$newUrlCount++;
		}
		while ($body =~ m/.+<p title="FULL">.+&amp;page=CourseList&amp;service=direct&amp;session=T(&amp;sp=.+;sp=\d+)">(\d+)<\/a>/g)
		{#a second regex to check for full classes
			my $crn = int($2);
			my $maxEnroll = 0;
			my $enroll = "CLOSED";
			my $detailUrl = decode_entities($1);
			$detailUrl = decode_entities($detailUrl);
			$urls{$crn} = $detailUrl;
			$maxEnrolls{$crn} = $maxEnroll;
			$enrolls{$crn} = $enroll;
			$newUrlCount++;
		}
		print "\nURLs Found: ", $newUrlCount;
	}
	
	foreach my $crn (keys %urls)
	{#put the data in the db
		my $thisUrl = $urls{$crn};
		my $thisMax = $maxEnrolls{$crn};
		my $thisEnroll = $enrolls{$crn};
		$dbh->do('INSERT OR REPLACE INTO class_urls (year, term, crn, url, timestamp) 
		VALUES (?, ?, ?, ?, ?)', undef, $year, $termNames[$termNum], $crn, $thisUrl, $timestamp);
		
		if ($thisEnroll eq 'CLOSED')
		{#this section is full
			$dbh->do('UPDATE classes SET 
			enroll = ?
			WHERE crn = ? AND year = ?', undef, $thisEnroll, $crn, $year);
		}
		else
		{#this section is not full
			#update the enroll counts in the main table
			$dbh->do('UPDATE classes SET 
			max_enroll = ?, enroll = ?
			WHERE crn = ? AND year = ?', undef, $thisMax, $thisEnroll, $crn, $year);
		}
	}
}


$dbh->disconnect;
print "\nDone\n\n";
