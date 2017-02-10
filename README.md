# Schedulizer

Official repository of the Drexel Schedulizer

# Installation Instructions

### Installing DBI and SQLite for Perl

    aptitude install libdbi-perl

    apt-get install libdbd-sqlite3-perl

# Updating The Database To a New Semester

### To update a new year

Put the site into maintenance mode. Perform at the root i.e. `/schedulizer/`:

    php artisan down

To put site back up after the database is updated:

    php artisan up

### How to update the database for new semester's class information

In order to update the data from an old semester to a new semester, you must delete the previous term's data first, before you add in the new one.

Navigate to `/database/`, then open the SQLite database:

    sqlite3 database.sqlite

Once you're in, you can manually delete the data from each table:

    DELETE FROM classes;

    DELETE FROM class_urls;

    DELETE FROM subject_urls;

Perform a quick check to see if the data is all gone:

    SELECT COUNT(*) FROM classes;

Open up the scripts under `/storage/scripts/` and change the `year` and `term` variable. Make sure all the `year` and `term` variables match up in all three scripts. Make sure once you've scraped the site, the variables in the database tables under `term` and `year` also match up. Meaning, if it is currently Fall 2016, then make sure the `term` and `year` in the three scripts AND the database tables are `fall` and `2016`. 

Next, run the following scripts [depending on whether it is current or
next year]. These scripts are under `/storage/scripts/`. Read below for an explanation on which ones to use!

    ./getYearLinks.pl; ./getListOfClassesForThisYear.pl; ./getClassDetails.pl

    ./getYearLinks.pl; ./getListOfClassesForNextYear.pl; ./getClassDetails.pl

Note: The use of "this" year defines the academic year. So "this" year starts at the Fall term and ends at the Summer term. The use of "next" year also starts at "Fall" and ends at "Summer". This is super important to know, since if you use the wrong script depending on which term you are on, you will get wrong or no data. 

Example:

Currently Spring 2017. To update Summer 2017, use `getListOfClassesForThisYear.pl`

Currently Summer 2017. To update Fall 2017, use `getListOfClassesForNextYear.pl`

### Detailed script explanation

1. `getYearLinks.pl`
    - This gets all the links for this [or] next year 
    - run before any new term to get all links for the year.
    - check the URL inside for `Next` or lack of thereof
2. `getListOfClassesForThisYear.pl`
    - This gets the class URLs for THIS year by searching the vowels
      'aeiou' and then retrieving the detailed class URLs. 
    - use this to update the list of class subjects (i.e. ECE..
      CS..) for the CURRENT year. So don't use this if you are
      currently in Summer term, and you want to update the Fall
      term.  
3. `getListOfClassesForNextYear.pl` 
    - This gets all the class URLs for next year using the subject
      URLs scraped from `getYearLinks.pl`
    - use this to update the list of class subjects for the
      NEXT year. If you are currently in Summer term, and
      want to update Fall classes, use this.
4. `getClassDetails.pl`
    - This downloads all detailed class information using the
      `class_urls` table's links

