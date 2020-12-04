<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ERP - USDNCI</title>
    <script src="https://code.jquery.com/jquery-3.5.1.js"
            integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
            crossorigin="anonymous"></script>
    <link href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"  rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style type="text/css">
      .invalid__form{
        width: 100%;
        margin-top: 0.25rem;
        font-size: 80%;
        color: #f44336;
      }
      div.toast.toast-error{
        background-color:#f44336;
      }
      div.toast.toast-success{
        background-color: #4caf50;
      }
      div.toast.toast-warning{
            background-color: #f6c23e !important;
        }
        div.toast.toast-info{
            background-color: #36b9cc !important;
        }
    </style>
</head>

<body class="header-fixed">
    <div id='loader'>
        <div class="spinner"></div>
    </div>
    <div id="app"></div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
