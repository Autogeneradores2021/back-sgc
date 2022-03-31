<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="{!! asset('css/bootstrap.min.css') !!}" >
    
  </head>
  <body>
    <div class="container">
        @yield('content')
    </div>
    <script type="text/javascript" src="{!! asset('js/bootstrap.bundle.min.js') !!}"></script>
  </body>
</html>