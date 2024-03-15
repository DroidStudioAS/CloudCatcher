<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield("title","CloudCatcher")</title>
    <link rel="stylesheet" href="{{asset("/css/main.css")}}">
</head>
<body>
@include("header")
<div class="content-container">
    @yield("content")
</div>

</body>
</html>
