<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        @include('admin.layouts.sidebar')

        <main class="flex-1 p-6 overflow-y-auto">
            @yield('content')
        </main>
    </div>

    @livewireScripts

    <script>
    document.addEventListener('livewire:init', () => {
        let token = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute('content');

        window.Livewire?.hooks?.request?.add((request) => {
            request.headers['X-CSRF-TOKEN'] = token;
        });
    });
</script>
</body>
</html>