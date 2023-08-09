const mix =require('laravel-mix')

mix.copy('node_modules/swagger-ui-dist', 'public/vendor/swagger-ui');

//mix.js('resources/js/swagger.js', 'public/js').version()
