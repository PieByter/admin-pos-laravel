<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dashboard' }}</title>


    @include('layouts.partials.styles')
    @stack('styles')
</head>

<body class="hold-transition sidebar layout-fixed">
    {{-- @php
        $user = auth()->user();
        $permissions = $user ? $user->getAllPermissions()->pluck('name')->toArray() : [];
    @endphp --}}
    @php
        $user = auth()->user();
        $permissions = [
            'items_view',
            'suppliers_view',
            'customers_view',
            'units_view',
            'item_categories_view',
            'item_groups_view',
            'unit_conversions_view',
            'pre_purchase_orders_view',
            'purchase_orders_view',
            'sales_orders_view',
            'returns_view',
            'users_view',
            'activity_logs_view',
            'transactions_view',
        ]; // Example permissions
    @endphp
    <div class="wrapper">
        @include('layouts.partials.navbar')
        @include('layouts.partials.sidebar')

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            {{ $slot }}
        </div>

        @include('layouts.partials.footer')
    </div>


    @stack('scripts')
    @include('layouts.partials.scripts')
</body>

</html>
