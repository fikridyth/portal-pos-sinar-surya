{{-- PWA --}}
<link rel="manifest" href="/manifest.json">

<link rel="icon" type="image/x-icon" href="{{ asset('assets/images/zen-favicon.png') }}">
<!-- Font Awesome -->
{{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" /> --}}
{{-- <link href="/assets/css/all.min.css" rel="stylesheet" /> --}}
<link href="{{ asset('assets/css/all.min.css') }}" rel="stylesheet" />

<!-- Google Fonts -->
{{-- <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" /> --}}
{{-- <link href="/assets/css/css.css" rel="stylesheet" /> --}}
<link href="{{ asset('assets/css/css.css') }}" rel="stylesheet" />

<!-- MDB -->
{{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.min.css" rel="stylesheet" /> --}}
{{-- <link href="/assets/css/mdb.min.css" rel="stylesheet" /> --}}
<link href="{{ asset('assets/css/mdb.min.css') }}" rel="stylesheet" />

<!-- Include DataTables CSS -->
{{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css"> --}}
{{-- <link rel="stylesheet" href="/assets/css/jquery.dataTables.min.css"> --}}
<link rel="stylesheet" href="{{ asset('assets/css/jquery.dataTables.min.css') }}">

{{-- <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" /> --}}
{{-- <link rel="stylesheet" type="text/css" href="/assets/css/daterangepicker.css" /> --}}
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/daterangepicker.css') }}" />

{{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" /> --}}
{{-- <link href="/assets/css/select2.min.css" rel="stylesheet" /> --}}
<link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" />

{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> --}}
{{-- <link href="/assets/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> --}}
<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

<style>
    /* for readonly like disabled */
    .readonly-input {
        background-color: #e9ecef;
        cursor: not-allowed;
        border: 1px solid #ced4da;
    }

    /* for a tag blur like disabled */
    .disabled-link {
        pointer-events: none; /* Prevents clicking */
        cursor: not-allowed; /* Shows a not-allowed cursor */
        text-decoration: none; /* Removes underline */
        opacity: 0.5; /* Makes the link look faded */
    }

    .col-0-5 {
        flex: 0 0 5.33%; /* Lebar 1/12 dari lebar kontainer (5.33% x 12 = 100%) */
        max-width: 5.33%;
    }

    .col-0-7 {
        flex: 0 0 7.33%; /* Lebar 1/12 dari lebar kontainer (7.33% x 12 = 100%) */
        max-width: 7.33%;
    }

    .col-1-5 {
        flex: 0 0 12.33%; /* Lebar 1/12 dari lebar kontainer (12.33% x 12 = 100%) */
        max-width: 12.33%;
    }

    .col-2-5 {
        flex: 0 0 20.33%; /* Lebar 1/12 dari lebar kontainer (12.33% x 12 = 100%) */
        max-width: 20.33%;
    }

    .col-7-5 {
        flex: 0 0 61.33%; /* Lebar 1/17 dari lebar kontainer (17.33% x 17 = 100%) */
        max-width: 61.33%;
    }

    .table thead th {
        background-color: blue !important;
        border: 3px solid #eee;
        color: #eee;
    }

    .table {
        width: 100%;
        border-collapse: collapse; /* Prevents double borders */
    }

    .table tbody.top {
        display: block; /* Use this only for scrolling */
        height: 55vh; /* Maintain the desired height */
        overflow-y: auto; /* Enable vertical scrolling */
    }

    .table thead, .table tbody tr {
        display: table; /* Keep the layout of the thead and tr */
        width: 100%; /* Ensure full width */
        table-layout: fixed; /* Prevent column width issues */
    }

    .table tbody tr {
        margin: 0;
    }

    .table tbody td {
        background-color: black;
        border: 3px solid #eee;
        border-bottom: none;
        border-top: none;
        color: #eee;
        font-size: 16px;
        padding: 2px; 
        line-height: 1.2; 
    }
    
    .table tbody.bottom td {
        background-color: black;
        border: 3px solid #eee;
        color: #eee;
        font-size: 16px;
        height: 5px;
        padding: 2px;
    }
</style>