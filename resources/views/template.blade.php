<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name') }}</title>
    <style>
        body {
            margin: 0;
        }

        .header {
            background-color: #2D2438;
            padding: 12px;
        }

        .header svg {
            height: 27px;
            width: 27px;
        }

        .left {
            display: inline-block;
            text-align: left;
        }

        .left .icon {
            display: inline-block;
            margin-right: 16px;
        }

        .right {
            position: relative;
            float: right;
        }

        .right .icon {
            display: inline-block;
            margin-left: 16px;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-box {
            background-color: #f1f1f1;

        }

        .dropdown-content {
            display: none;
            position: fixed;
            min-width: 160px;
            margin-left: -100px;
            padding-top: 12px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #ddd;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }
    </style>
</head>

<body>
    <header class="header">
        
    </header>
    <div class="container">
        @yield('content')
    </div>
</body>

</html>