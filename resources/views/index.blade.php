<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>{{ config('app.name') }}</title>

    @vite(['resources/scss/main.scss', 'resources/js/main.js'])

    <script>
        window.AppConfig = {
            name: '{{ config('app.name') }}',
            logo: '{{ config('app.logo') }}',
            url: '{{ config('app.url') }}',
            csrf: '{{ csrf_token() }}',
            defaultLocale: '{{ config('app.locale') }}',
            defaultTimezone: '{{ config('app.timezone') }}',
            locales: {
                en: {!! json_encode(\Illuminate\Support\Facades\Lang::get('frontend', [], 'en')) !!},
            }
        }
    </script>
</head>

<body>
<noscript>
    <strong>We're sorry but this application doesn't work properly without JavaScript enabled. Please enable it to continue.</strong>
</noscript>

<div id="app"></div>

</body>

</html>
