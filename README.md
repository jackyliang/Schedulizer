# Schedulizer

Official repository of the Drexel Schedulizer. Drexel Schedulizer uses the Laravel PHP framework and Homestead for its development environment. It is currently hosted on Digital Ocean in the $5/month droplet.

# Installation Instructions

### Installing DBI and SQLite for Perl

    aptitude install libdbi-perl

    apt-get install libdbd-sqlite3-perl

### Installing Laravel

Use the following Laravel installation [instructions](https://laravel.com/docs/4.2)

### Installing Homestead

Use the following Homestead installation [instructions](https://laravel.com/docs/5.4/homestead)

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

> Note: The use of "this" year defines the academic year. So "this" year starts at the Fall term and ends at the Summer term. The use of "next" year also starts at "Fall" and ends at "Summer". This is super important to know, since if you use the wrong script depending on which term you are on, you will get wrong or no data. 

Example:

Currently Spring 2017. To update Summer 2017, use `getListOfClassesForThisYear.pl`

Currently Summer 2017. To update Fall 2017, use `getListOfClassesForNextYear.pl`

### Detailed script explanation

1. `getYearLinks.pl`
    - This gets all the links for this [or] next year 
    - run before any new term to get all links for the year.
    - Inside this script contains a Drexel TMS URL. If scraping next year's classes, append a `Next` to the URL. Otherwise, leave it. If confused, go to Drexel TMS and look at the URL for next year's data and take that URL
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

# Contributing

Contributing to Schedulizer can be in many forms:

- Donations (hosting isn't free!)
- Filing AND fixing bugs (together). Please don't just file bugs and don't fix them.
- Creating new features for the site. You can get some inspiration from open issues
- Refactoring code to make it easier for the next developer
- Making the UI look more beautiful

After setting up your development environment, pulled data, and tested the feature ensuring minimal bugs, please create a new issue [here](https://github.com/jackyliang/Schedulizer/issues/new) and the team will perform a feature + code review before merging. There's only one person on the "team" so I am not sure why I used the word team here.

## Built with Laravel PHP Framework

[![License](https://poser.pugx.org/laravel/framework/license.svg)](https://packagist.org/packages/laravel/framework)

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as authentication, routing, sessions, queueing, and caching.

Laravel is accessible, yet powerful, providing powerful tools needed for large, robust applications. A superb inversion of control container, expressive migration system, and tightly integrated unit testing support give you the tools you need to build any application with which you are tasked.

## Official Documentation

Documentation for the framework can be found on the [Laravel website](http://laravel.com/docs).

### License

The Laravel framework and Schedulizer is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

