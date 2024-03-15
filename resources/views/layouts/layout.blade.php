<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield("title","CloudCatcher")</title>
    <link rel="stylesheet" href="{{asset("/css/main.css")}}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script><!--JQuery import-->
    <!-- Bootstrap CSS -->
    <!-- Bootstrap Datepicker CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">


    <!-- Bootstrap-datepicker JS -->
</head>
<body>
@include("header")
<div class="content-container">
    @yield("content")
</div>

</body>
</html>
