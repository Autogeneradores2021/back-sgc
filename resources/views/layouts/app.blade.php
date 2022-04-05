<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="{!! asset('css/bootstrap.min.css') !!}" >
    <style>
      img {
        width: 100%;
      }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>

    
  </head>
  <body>
    <div class="container">
        @yield('content')
    </div>
    <script type="text/javascript" src="{!! asset('js/bootstrap.bundle.min.js') !!}"></script>
  </body>
</html>