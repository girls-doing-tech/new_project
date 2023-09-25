<!-- resources/views/app.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('css/navbar.css') }}" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="container">


          <!-- <label for="navbar-toggle" class="navbar-toggler">&#9776;</label>-->
            <ul class="navbar-menu">

                <li><a href="{{ route('login') }}">login</a></li>
                <li><a href="{{ route('register') }}">register</a></li>
            </ul>
        </div>
    </nav>



</body>
</html>
