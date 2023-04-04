<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="Pharmacy POS" />
        <meta name="keywords" content="pharmacy POS, Pigeon Soft" />
        <meta name="author" content="Pigeon Soft" />
        <link rel="icon" href="{{asset('uploads/'.$app_setting->logo)}}" type="image/x-icon" />
        <link rel="shortcut icon" href="{{asset('uploads/'.$app_setting->logo)}}" type="image/x-icon" />
        <title>@yield('title')</title>
        <!-- Google font-->
        @includeIf('layouts.admin.partials.css')
    </head>
    <body>
        <!-- Loader starts-->
        <div class="loader-wrapper">
            <div class="theme-loader">
                <div class="loader-p"></div>
            </div>
        </div>
        <!-- Loader ends-->
        <!-- error page start //-->
        @yield('content')
        <!-- error page end //-->
        <!-- latest jquery-->
        @includeIf('layouts.admin.partials.js')
    </body>
</html>
