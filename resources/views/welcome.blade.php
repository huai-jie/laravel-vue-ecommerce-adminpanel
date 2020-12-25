<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="/css/all.css">
        <script>
            (function () {
                window.Laravel = {
                    csrfToken: '{{ csrf_token() }}'
                };
            })();
        </script>
        <title>Laravel</title>
    </head>
    <body>
        <div id="app">
            <main-app></main-app>
        </div>
    </body>
    <script src={{mix('/js/app.js')}}></script>
</html>
