var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    // Compiling SASS to this file
    mix.sass('app.scss');

    // Merge these files to one.
    mix.styles([
        'vendor/bootstrap.css',
        'app.css'
    ], null, 'public/css')
});
