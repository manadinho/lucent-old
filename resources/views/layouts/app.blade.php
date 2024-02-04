<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Lucent') }}</title>

        <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('css/simple-notify.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
        <link href="{{ asset('vendor/bladewind/css/animate.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('vendor/bladewind/css/bladewind-ui.min.css') }}" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <style>
        .modal-cancel-btn{
            position: absolute;
            margin-right: 14px !important;
            right: 2%;
            border-radius: 50%;
            padding: 5px 12px;
            top: 1%;
        }
        .filter-btn{
            background: #e5e7eb;
            color: gray;
        }
        .active-filter-btn {
            background-color: gray !important;
            color: #fff !important;
            cursor: pointer;
        }
        .active-trace {
            background: #FF241F !important;
        }

        .active-trace p {
            color: #fffffe !important;

        }
        .text-ellipsis{
            text-overflow: ellipsis
        }
        .project-card{
            box-shadow: 3px 3px 14px -2px gray;
        }
        .card-title{
            padding: 5px 10px;
            color: #1f2937;
            border-radius: 4px;
            font-weight: 900;
        }
        .no-data-found-text{
            font-weight: 900;
        }
    </style>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        <x-confirm-dialog></x-confirm-dialog>
    </body>

    <script src="{{ asset('vendor/bladewind/js/helpers.js') }}"></script>
    <script src="{{ asset('js/simple-notify.min.js') }}"></script>

    <!-- Toast messages -->
    @if(session('toast'))
        <script>
            
                new Notify ({
                    title: "{{ ucwords(session('status')) }}",
                    text: "{{ ucwords(session('message')) }}",
                    status: "{{ session('status') }}",
                    autoclose: true,
                })
            
        </script>
    @endif

    @if($errors->any())
        <script>
                
                new Notify ({
                    title: "Validation Error!",
                    text: "{{ $errors->all()[0] }}",
                    status: "error",
                    autoclose: true,
                })
            
        </script> 
    @endif

    <script>

        /**
         * Displays a confirmation dialog and prevents the default event behavior.
         * 
         * @param {Event} event - The event triggered by an action (e.g., click).
         * @param {HTMLElement} element - The element associated with the event.
         * 
         * @author Muhammad Imran Israr <mimranisrar6@gmail.com>
         */
        function confirmBefore(event, element, message = null, delete_btn_txt = null) {
            event.preventDefault();

            message ??= 'You will lose all of your data by deleting this. This action cannot be undone.';

            delete_btn_txt ??= 'Delete';


            document.querySelector('.confirm-dialog').style.display = 'block';

            document.querySelector('#confirm-dialog-message').textContent = message;

            const cancelBtn = document.querySelector('#confirm-cancel-btn');

            const deleteBtn = document.querySelector('#confirm-delete-btn');

            deleteBtn.textContent = delete_btn_txt;

            cancelBtn.addEventListener('click', function() {
                document.querySelector('.confirm-dialog').style.display = 'none';
            })

            deleteBtn.addEventListener('click', function() {
                window.location.href = element.getAttribute('href');
            })
        }
        

    </script>
</html>
