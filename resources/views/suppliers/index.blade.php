@extends('layouts.layout')

@section('content')
    <div class="flex flex-col gap-4">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">SUPPLIERS</h1>
        <div class="flex items-center gap-4 justify-between">
            <input type="text" onchange="loadSuppliersData()" name="supplierSearch" id="supplierSearch"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                placeholder="Type supplier name" required="">
            @moduleAction('Supplier', 'Create')
                <button type="button" class="blue-button" onclick="openCreateSupplierModal('supplier')">Create new
                    supplier</button>
            @endmoduleAction
        </div>

        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Phone
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Address
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody id="supplier-tbody">
                </tbody>
            </table>
            <ul class="mt-4 flex justify-center space-x-2 rtl:space-x-reverse text-sm" id="supplier-pagination"></ul>
        </div>

    </div>

    @include('suppliers.form')
    @include('alerts.confirmation-delete')
    <script>
        $(document).ready(function() {
            loadSuppliersData();
        });
    </script>
@endsection
