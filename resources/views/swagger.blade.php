<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Swagger UI</title>
        <link
            rel="stylesheet"
            type="text/css"
            href="{{ asset('vendor/swagger-ui/swagger-ui.css') }}"
        >
    </head>
    <body>
    <div id="swagger-ui"></div>
    <script src="{{ asset('vendor/swagger-ui/swagger-ui-bundle.js') }}"></script>
    <script src="{{ asset('vendor/swagger-ui/swagger-ui-standalone-preset.js') }}"></script>
    <script>
        window.onload = function () {
            const ui = SwaggerUIBundle({
                spec: {!! json_encode(Symfony\Component\Yaml\Yaml::parseFile(base_path('openapi.yaml'))) !!},
                dom_id: '#swagger-ui',
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset,
                ],
                layout: 'BaseLayout',
            });

            window.ui = ui;
        };
    </script>
    </body>
    </html>
