<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SurveilUCD') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('fslogo.png') }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <!-- DataTables JS -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>


    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        /* Custom styles for DataTables */
        table.dataTable thead th {
            background-color: #f3f4f6;
            color: #333;
        }

        table.dataTable tbody tr {
            background-color: #fff;
        }

        table.dataTable tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }

        table.dataTable tbody tr:hover {
            background-color: #f1f1f1;
        }

        .action-icons a {
            margin-right: 8px;
        }

        .action-icons a i {
            color: #4a5568;
        }

        .action-icons a:hover i {
            color: #2d3748;
        }

        .custom-button {
            background-color: #3182ce;
            color: white;
            font-weight: bold;
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
        }

        .custom-button:hover {
            background-color: #2b6cb0;
        }

        < !-- DataTables CSS --><link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">< !-- FontAwesome CSS --><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">< !-- Custom CSS --><style>

        /* Custom styles for DataTables */
        table.dataTable thead th {
            background-color: #1f2937;
            /* Dark gray background */
            color: #fff;
            /* White text color */
        }

        table.dataTable tbody tr {
            background-color: #f8fafc;
            /* Light gray background */
        }

        table.dataTable tbody tr:nth-child(even) {
            background-color: #e5e7eb;
            /* Slightly darker gray for even rows */
        }

        table.dataTable tbody tr:hover {
            background-color: #d1d5db;
            /* Gray color on hover */
        }

        .action-icons a {
            margin-right: 8px;
        }

        .action-icons a i {
            color: #1d4ed8;
            /* Blue color for icons */
        }

        .action-icons a:hover i {
            color: #3b82f6;
            /* Lighter blue on hover */
        }

        .custom-button {
            background-color: #1d4ed8;
            /* Blue color for the button */
            color: white;
            font-weight: bold;
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
        }

        .custom-button:hover {
            background-color: #3b82f6;
            /* Lighter blue on hover */
        }

        .custom-button {
            @apply bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded;
        }

        .action-icons i {
            font-size: 1.25rem;
            cursor: pointer;
        }

        .action-icons i.text-blue-600:hover {
            color: #2563EB;
            /* Tailwind blue-800 */
        }

        .action-icons i.text-red-600:hover {
            color: #DC2626;
            /* Tailwind red-800 */
        }
        .select2-container {
    width: 100% !important;
}

.select2-selection {
    min-height: 40px; /* Ajustez selon la hauteur souhaitée */
}
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
</body>

</html>
