<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="{{asset("/css/main.css")}}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script><!--JQuery import-->
</head>
@include('header')
<body>
    <div class="admin_container">
        @yield("admin-content")
    </div>
</body>
</html>



