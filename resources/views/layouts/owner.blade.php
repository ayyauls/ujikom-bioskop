<!DOCTYPE html>
<html lang="en">
<head>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">

    @include('layouts.navbarr')

    <main class="py-6">
        @yield('content')
    </main>

</body>
</html>
