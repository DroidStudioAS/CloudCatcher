<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield("title","CloudCatcher")</title>
    <link rel="stylesheet" href="{{asset("/css/main.css")}}">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script><!--JQuery import-->
    <!-- Bootstrap CSS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <!-- Bootstrap-datepicker JS -->
</head>
<body>
@include("header")
<div class="content-container">
    @yield("content")
</div>

</body>
</html>
