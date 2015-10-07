#!/usr/bin/perl
# by Stephen Wetzel July 29 2015
#Requires cURL is installed

#This is a counterpart to getListOfClasses.pl.  That script only gets the data for the current year.  This script uses subject list urls found by getDepartmentLists.pl to download the data for next year.
#Note this will only update the one term listed below.

use strict;
use warnings;
use DBI;
use HTML::Entities;
use FindBin qw( $RealBin );

#use autodie; #die on file not found
$|++; #autoflush disk buffer

# This script works directly with our SQLite DB  
my $dbFile = "$RealBin/../database.sqlite";
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
my $year = 2016;
my $term = 'Winter';

#It seems on the TMS search page, the terms 1-4 are always fall through summer of this academic year.
#Next year is terms 5-8, but don't seem to work

my @termNames = ('', 'Fall', 'Winter', 'Spring', 'Summer');

sub getTimeStamp {
	my ($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst)=localtime(time);
	return sprintf("%04d-%02d-%02d %02d:%02d:%02d",$year+1900,$mon+1,$mday,$hour,$min,$sec);
}

#get the JSESSIONID:
my $temp = `curl -s -D -  --data 'formids=term%2CcourseName%2CcrseNumb%2Ccrn&component=searchForm&page=Home&service=direct&submitmode=submit&submitname=&term=1&courseName=test&crseNumb=&crn=' -X POST https://duapp2.drexel.edu/webtms_du/app -o /dev/null`; #Note the lack of &session=T, that's important
$temp =~ m/Set-Cookie: JSESSIONID=([A-F0-9]{32})/ or die "Can't find JSESSIONID";
$sessionId = $1; #found the current session ID

#these hashes will use the crn as the key
my %maxEnrolls;
my %enrolls;
my %urls;
my $timestamp = getTimeStamp();
my @allUrls;

#get the list of urls from the DB:
my $sth = $dbh->prepare("SELECT url FROM subject_urls WHERE term = '$term' AND year = '$year'");
$sth->execute();
while (my $thisUrl = $sth->fetchrow_array())
{
	my $tempCurlRequest = "curl -s -D - --header 'cookie: JSESSIONID=$sessionId;' -X GET '$baseUrl$thisUrl' 2>/dev/null";
	print "\n\n$tempCurlRequest";
	$body = `$tempCurlRequest`; #get response body from curl	
	my $newUrlCount = 0;
	while ($body =~ m/.+<p title="Max enroll=(\d+); Enroll=(\d+).+&amp;page=CourseList&amp;service=direct&amp;session=T(&amp;sp=.+;sp=\d+)">(\d+)<\/a>/g)
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
	VALUES (?, ?, ?, ?, ?)', undef, $year, $term, $crn, $thisUrl, $timestamp);
	
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


$dbh->disconnect;
print "\nDone\n\n";
