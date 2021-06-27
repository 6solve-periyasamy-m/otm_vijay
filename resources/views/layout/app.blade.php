<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>OTM</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link href="css/app.css" rel="stylesheet" />
        <link href="css/dash.css" rel="stylesheet" />
        <style>
            body {
                font-family: 'Nunito';
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="relative flex flex-cols items-top min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
            @if (Route::has('login'))
                <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                    @auth
                        <a href="{{ url('/home') }}" class="text-sm text-gray-700 underline">Home</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 underline">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline">Register</a>
                        @endif
                    @endif
                </div>
            @endif
            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                <div class="flex flex-cols justify-center pt-8 sm:justify-start sm:pt-0">
                    <img src="http://octopustravelmatrix.local/images/octlogo.png" class="img-fluid img-logo" />
                    <h1 class="justify-center text-white">Octopus Travel Matrix</h1>
                </div>
            </div>
            @yield('content')
            <div class="ml-4  justify-center text-center text-sm text-gray-500 sm:text-right sm:ml-0">
                Copyright &copy; 2021 by Octopus Travel Matrix
            </div>
        </div>
    </body>
</html>
