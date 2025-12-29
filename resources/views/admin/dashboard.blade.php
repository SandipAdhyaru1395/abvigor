@extends('admin.partials.layout')
@push('styles')
    <style>
        .analysis-page-wrapper {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }

        .analysis-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .analysis-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.15);
        }

        .page-header {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .page-title {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .page-title-icon {
            font-size: 24px;
            color: #667eea;
        }

        .filters-section {
            background: #e9ecef;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
        }

        .filter-group {
            margin-bottom: 15px;
        }

        .filter-group label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }

        .filter-group select,
        .filter-group input {
            width: 100%;
            padding: 10px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .filter-group select:focus,
        .filter-group input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        /* Select2 Styling */
        .filters-section .select2-container {
            width: 100% !important;
        }

        .filters-section .select2-container--default .select2-selection--single {
            height: auto !important;
            min-height: 38px;
            border: 1px solid #adb5bd;
            border-radius: 8px;
            padding: 0;
            font-size: 0.9rem;
            font-family: 'Segoe UI', sans-serif;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.03);
            transition: border-color 0.2s, box-shadow 0.2s, background-color 0.2s;
            background-color: #fff;
            display: flex;
            align-items: center;
        }

        .filters-section .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 1.5;
            padding: 8px 12px;
            color: #212529;
            width: 100%;
        }

        .filters-section .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100%;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
        }

        .filters-section .select2-container--default.select2-container--focus .select2-selection--single,
        .filters-section .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
            outline: none;
        }

        .filters-section .select2-container--default .select2-selection--single:hover {
            border-color: #adb5bd;
        }

        .filters-section .select2-dropdown {
            border: 1px solid #adb5bd;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-top: 4px;
        }

        .filters-section .select2-container--default .select2-results__option {
            padding: 8px 12px;
            font-size: 0.9rem;
        }

        .filters-section .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #667eea;
        }

        .filters-section .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid #adb5bd;
            border-radius: 6px;
            padding: 6px 10px;
            font-size: 0.9rem;
        }

        .filters-section .select2-container--default .select2-search--dropdown .select2-search__field:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.15);
        }

        .analysis-type-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .analysis-tab {
            padding: 12px 24px;
            background: #ffffff;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            color: #555;
        }

        .analysis-tab:hover {
            border-color: #667eea;
            color: #667eea;
        }

        .analysis-tab.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: #667eea;
            color: white;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .stat-card.blue {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .stat-card.green {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }

        .stat-card.orange {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .stat-card.purple {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .stat-card h3 {
            font-size: 14px;
            font-weight: 600;
            margin: 0 0 10px 0;
            opacity: 0.9;
        }

        .stat-card .stat-value {
            font-size: 32px;
            font-weight: 700;
            margin: 0;
        }

        .chart-container {
            position: relative;
            height: 400px;
            margin-bottom: 30px;
        }

        .data-table-container {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }

        .data-table thead {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
        }

        .data-table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }

        .data-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #f0f0f0;
        }

        .data-table tbody tr:hover {
            background: #f8f9fa;
        }

        .btn-apply-filters {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-apply-filters:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        .btn-reset-filters {
            background: #6c757d;
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-reset-filters:hover {
            background: #5a6268;
        }

        .loading-spinner {
            display: none;
            text-align: center;
            padding: 40px;
        }

        .loading-spinner.active {
            display: block;
        }

        /* Fix datepicker calendar z-index to appear above header */
        .datepicker,
        .datepicker-dropdown {
            z-index: 1050 !important;
        }

        /* Date input with clear button */
        .filter-group .position-relative {
            position: relative;
        }

        .filter-group .position-relative input {
            padding-right: 35px;
        }

        .filter-group .position-relative button {
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .filter-group .position-relative button:hover {
            color: #dc3545 !important;
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .analysis-type-tabs {
                flex-direction: column;
            }

            .analysis-tab {
                width: 100%;
                text-align: center;
            }
        }
    </style>
@endpush

@section('content')
    <div class="analysis-page-wrapper">
        <div class="admin container-fluid py-2">
            @include('admin.partials.sidebar')
            <div class="admin main-content p-4">
                <div class="analysis-card">
                    <!-- Filters Section -->
                    <div class="filters-section">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="filter-group">
                                    <label><i class="fas fa-tags"></i> Brand</label>
                                    <select id="brandFilter" class="form-select select2">
                                        <option value="">All Brands</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="filter-group">
                                    <label><i class="fas fa-box"></i> Product</label>
                                    <select id="productFilter" class="form-select select2">
                                        <option value="">All Products</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="filter-group">
                                    <label><i class="fas fa-user"></i> User</label>
                                    <select id="userFilter" class="form-select select2">
                                        <option value="">All Users</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="filter-group">
                                    <label><i class="fas fa-map-marker-alt"></i> State</label>
                                    <select id="stateFilter" class="form-select select2">
                                        <option value="">All States</option>
                                        @foreach($states as $state)
                                            <option value="{{ $state }}">{{ $state }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="filter-group">
                                    <label><i class="fas fa-calendar"></i> Start Date</label>
                                    <div class="position-relative">
                                        <input type="text" id="startDateFilter" class="form-control datepicker" placeholder="DD/MM/YYYY" readonly autocomplete="off">
                                        <button type="button" class="btn btn-sm btn-link position-absolute end-0 top-50 translate-middle-y me-2 p-0" onclick="clearDate('startDateFilter')" style="border: none; background: none; color: #6c757d; text-decoration: none; z-index: 10;" title="Clear date">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="filter-group">
                                    <label><i class="fas fa-calendar"></i> End Date</label>
                                    <div class="position-relative">
                                        <input type="text" id="endDateFilter" class="form-control datepicker" placeholder="DD/MM/YYYY" readonly autocomplete="off">
                                        <button type="button" class="btn btn-sm btn-link position-absolute end-0 top-50 translate-middle-y me-2 p-0" onclick="clearDate('endDateFilter')" style="border: none; background: none; color: #6c757d; text-decoration: none; z-index: 10;" title="Clear date">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <div class="filter-group w-100">
                                    <button class="btn-apply-filters w-100" onclick="loadAnalysisData()">
                                        <i class="fas fa-filter"></i> Apply Filters
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <div class="filter-group w-100">
                                    <button class="btn-reset-filters w-100" onclick="resetFilters()">
                                        <i class="fas fa-redo"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Analysis Type Tabs -->
                    <div class="analysis-type-tabs">
                        <div class="analysis-tab active" data-type="overview" onclick="switchAnalysisType('overview')">
                            <i class="fas fa-chart-pie"></i> Overview
                        </div>
                        <div class="analysis-tab" data-type="brand_wise" onclick="switchAnalysisType('brand_wise')">
                            <i class="fas fa-tags"></i> Brand Wise
                        </div>
                        <div class="analysis-tab" data-type="product_wise" onclick="switchAnalysisType('product_wise')">
                            <i class="fas fa-box"></i> Product Wise
                        </div>
                        <div class="analysis-tab" data-type="date_wise" onclick="switchAnalysisType('date_wise')">
                            <i class="fas fa-calendar-alt"></i> Date Wise
                        </div>
                        <div class="analysis-tab" data-type="state_wise" onclick="switchAnalysisType('state_wise')">
                            <i class="fas fa-map-marker-alt"></i> State Wise
                        </div>
                        <div class="analysis-tab" data-type="user_wise" onclick="switchAnalysisType('user_wise')">
                            <i class="fas fa-users"></i> User Wise
                        </div>
                    </div>

                    <!-- Loading Spinner -->
                    <div class="loading-spinner" id="loadingSpinner">
                        <i class="fas fa-spinner fa-spin fa-3x" style="color: #667eea;"></i>
                        <p>Loading analysis data...</p>
                    </div>

                    <!-- Stats Cards -->
                    <div class="stats-grid" id="statsGrid" style="display: none;">
                        <div class="stat-card blue">
                            <h3>Total Orders</h3>
                            <p class="stat-value" id="totalOrders">0</p>
                        </div>
                        <div class="stat-card green">
                            <h3>Total Quantity</h3>
                            <p class="stat-value" id="totalQuantity">0</p>
                        </div>
                        <div class="stat-card orange">
                            <h3>Total Products</h3>
                            <p class="stat-value" id="totalProducts">0</p>
                        </div>
                        <div class="stat-card purple">
                            <h3>Total Users</h3>
                            <p class="stat-value" id="totalUsers">0</p>
                        </div>
                    </div>

                    <!-- Charts Container -->
                    <div id="chartsContainer" style="display: none;">
                        <div class="chart-container">
                            <canvas id="mainChart"></canvas>
                        </div>
                    </div>

                    <!-- Data Table Container -->
                    <div id="dataTableContainer" style="display: none;">
                        <div class="data-table-container">
                            <table class="data-table" id="analysisTable">
                                <thead id="tableHead">
                                </thead>
                                <tbody id="tableBody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        let currentAnalysisType = 'overview';
        let mainChart = null;

        // Load data on page load
        $(document).ready(function() {
            // Initialize Select2 for all dropdowns
            initializeSelect2();
            loadAnalysisData();
        });

        function initializeSelect2() {
            // Brand dropdown
            $('#brandFilter').select2({
                placeholder: 'Select Brand',
                allowClear: true,
                width: '100%'
            });

            // Product dropdown (initially disabled, depends on brand)
            $('#productFilter').select2({
                placeholder: 'Select Product',
                allowClear: true,
                width: '100%',
                disabled: true
            });

            // User dropdown with AJAX (exactly like in add.blade.php)
            // Wait a bit to ensure global Select2 initialization is done, then initialize
            setTimeout(function() {
                // Destroy default Select2 initialization first if exists
                if ($('#userFilter').hasClass('select2-hidden-accessible')) {
                    $('#userFilter').select2('destroy');
                }
                
                $('#userFilter').select2({
                    placeholder: 'Select User',
                    allowClear: true,
                    width: '100%',
                    minimumInputLength: 0, // Show initial results without typing
                    ajax: {
                        url: "{{ route('admin.order.search.users') }}",
                        dataType: 'json',
                        delay: 0, // No delay for immediate loading
                        data: function (params) {
                            return {
                                search: params.term || '', // Search term
                                page: params.page || 1 // Page number for pagination
                            };
                        },
                        processResults: function (data, params) {
                            params.page = params.page || 1;
                            return {
                                results: data.results || [],
                                pagination: {
                                    more: data.pagination ? data.pagination.more : false
                                }
                            };
                        },
                        cache: true
                    }
                });

                // Force initial load when dropdown opens
                $('#userFilter').on('select2:open', function() {
                    var $select2 = $(this).data('select2');
                    if ($select2) {
                        setTimeout(function() {
                            var $searchField = $select2.$dropdown.find('.select2-search__field');
                            if ($searchField.length) {
                                $searchField.focus();
                                // Trigger keyup to initiate search with empty term
                                $searchField.trigger('keyup');
                            }
                        }, 100);
                    }
                });
            }, 100);

            // State dropdown
            $('#stateFilter').select2({
                placeholder: 'Select State',
                allowClear: true,
                width: '100%'
            });

            // Load products when brand changes
            $('#brandFilter').on('change', function() {
                const brandId = $(this).val();
                const productSelect = $('#productFilter');
                
                // Clear previous selection
                productSelect.val(null).trigger('change');
                
                if (brandId) {
                    // Show loading state
                    productSelect.prop('disabled', false);
                    productSelect.empty();
                    productSelect.append('<option value="">Loading products...</option>');
                    productSelect.trigger('change');
                    
                    // Load products for selected brand
                    $.ajax({
                        url: "{{ route('admin.order.products.by.brand') }}",
                        type: 'GET',
                        data: { brand_id: brandId },
                        cache: false, // Disable cache to ensure fresh data
                        success: function(response) {
                            // Destroy Select2 if it exists to ensure clean update
                            if (productSelect.hasClass('select2-hidden-accessible')) {
                                productSelect.select2('destroy');
                            }
                            
                            productSelect.empty();
                            productSelect.append('<option value="">All Products</option>');
                            if (response.data && response.data.length > 0) {
                                response.data.forEach(function(product) {
                                    var displayText = (product.product_code ? product.product_code + ' - ' : '') + product.title;
                                    productSelect.append('<option value="' + product.id + '">' + displayText + '</option>');
                                });
                            } else {
                                productSelect.append('<option value="">No products found</option>');
                            }
                            
                            // Reinitialize Select2
                            productSelect.select2({
                                placeholder: 'Select Product',
                                allowClear: true,
                                width: '100%'
                            });
                        },
                        error: function(xhr) {
                            console.error('Error loading products:', xhr);
                            // Destroy Select2 if it exists
                            if (productSelect.hasClass('select2-hidden-accessible')) {
                                productSelect.select2('destroy');
                            }
                            productSelect.empty();
                            productSelect.append('<option value="">Error loading products</option>');
                            // Reinitialize Select2
                            productSelect.select2({
                                placeholder: 'Select Product',
                                allowClear: true,
                                width: '100%'
                            });
                            toastr.error('Failed to load products for selected brand');
                        }
                    });
                } else {
                    // Destroy Select2 if it exists
                    if (productSelect.hasClass('select2-hidden-accessible')) {
                        productSelect.select2('destroy');
                    }
                    productSelect.prop('disabled', true);
                    productSelect.empty();
                    productSelect.append('<option value="">All Products</option>');
                    // Reinitialize Select2
                    productSelect.select2({
                        placeholder: 'Select Product',
                        allowClear: true,
                        width: '100%',
                        disabled: true
                    });
                }
            });
        }

        function clearDate(dateInputId) {
            $('#' + dateInputId).datepicker('setDate', null);
            $('#' + dateInputId).val('');
        }

        function switchAnalysisType(type) {
            currentAnalysisType = type;
            $('.analysis-tab').removeClass('active');
            $(`.analysis-tab[data-type="${type}"]`).addClass('active');
            loadAnalysisData();
        }

        function resetFilters() {
            $('#brandFilter').val(null).trigger('change');
            $('#productFilter').val(null).trigger('change');
            $('#userFilter').val(null).trigger('change');
            $('#stateFilter').val(null).trigger('change');
            $('#startDateFilter').datepicker('setDate', null);
            $('#endDateFilter').datepicker('setDate', null);
            loadAnalysisData();
        }

        function loadAnalysisData() {
            $('#loadingSpinner').addClass('active');
            $('#statsGrid').hide();
            $('#chartsContainer').hide();
            $('#dataTableContainer').hide();

            // Get date values and convert from dd/mm/yyyy to yyyy-mm-dd format
            var startDate = $('#startDateFilter').val();
            var endDate = $('#endDateFilter').val();
            
            // Convert date format if datepicker format is dd/mm/yyyy
            if (startDate) {
                var parts = startDate.split('/');
                if (parts.length === 3) {
                    startDate = parts[2] + '-' + parts[1] + '-' + parts[0];
                }
            }
            
            if (endDate) {
                var parts = endDate.split('/');
                if (parts.length === 3) {
                    endDate = parts[2] + '-' + parts[1] + '-' + parts[0];
                }
            }

            const filters = {
                product_id: $('#productFilter').val() || '',
                user_id: $('#userFilter').val() || '',
                state: $('#stateFilter').val() || '',
                brand_id: $('#brandFilter').val() || '',
                start_date: startDate || '',
                end_date: endDate || '',
                analysis_type: currentAnalysisType
            };

            $.ajax({
                url: '{{ route('admin.analysis.data') }}',
                type: 'GET',
                data: filters,
                success: function(response) {
                    if (response.status) {
                        renderAnalysisData(response.data);
                    } else {
                        toastr.error('Failed to load analysis data');
                    }
                },
                error: function() {
                    toastr.error('Error loading analysis data');
                },
                complete: function() {
                    $('#loadingSpinner').removeClass('active');
                }
            });
        }

        function renderAnalysisData(data) {
            if (currentAnalysisType === 'overview') {
                renderOverview(data);
            } else if (currentAnalysisType === 'product_wise') {
                renderProductWise(data);
            } else if (currentAnalysisType === 'date_wise') {
                renderDateWise(data);
            } else if (currentAnalysisType === 'state_wise') {
                renderStateWise(data);
            } else if (currentAnalysisType === 'user_wise') {
                renderUserWise(data);
            } else if (currentAnalysisType === 'brand_wise') {
                renderBrandWise(data);
            }
        }

        function renderOverview(data) {
            // Update stats
            if (data.summary) {
                $('#totalOrders').text(data.summary.total_orders || 0);
                $('#totalQuantity').text(data.summary.total_quantity || 0);
                $('#totalProducts').text(data.summary.total_products || 0);
                $('#totalUsers').text(data.summary.total_users || 0);
                $('#statsGrid').show();
            }

            // Render charts
            if (data.top_products && data.top_products.length > 0) {
                renderChart('mainChart', {
                    type: 'bar',
                    data: {
                        labels: data.top_products.map(p => p.product_name),
                        datasets: [{
                            label: 'Total Quantity',
                            data: data.top_products.map(p => p.total_qty),
                            backgroundColor: 'rgba(102, 126, 234, 0.8)',
                            borderColor: 'rgba(102, 126, 234, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Top Products by Quantity'
                            },
                            legend: {
                                display: false
                            }
                        }
                    }
                });
                $('#chartsContainer').show();
            }

            // Render date wise trend
            if (data.date_wise && data.date_wise.length > 0) {
                // You can add another chart here for date trend
            }
        }

        function renderProductWise(data) {
            if (data && data.length > 0) {
                renderTable(['Product Name', 'Total Quantity', 'Order Count', 'Avg Qty/Order'], 
                    data.map(item => [
                        item.product_name,
                        item.total_quantity,
                        item.order_count,
                        item.avg_quantity_per_order
                    ])
                );

                renderChart('mainChart', {
                    type: 'bar',
                    data: {
                        labels: data.map(d => d.product_name),
                        datasets: [{
                            label: 'Total Quantity',
                            data: data.map(d => d.total_quantity),
                            backgroundColor: 'rgba(102, 126, 234, 0.8)',
                            borderColor: 'rgba(102, 126, 234, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Product Wise Analysis'
                            }
                        }
                    }
                });
                $('#chartsContainer').show();
            }
        }

        function renderDateWise(data) {
            if (data && data.length > 0) {
                renderTable(['Date', 'Total Quantity', 'Order Count', 'Product Count', 'User Count'],
                    data.map(item => [
                        item.date,
                        item.total_quantity,
                        item.order_count,
                        item.product_count,
                        item.user_count
                    ])
                );

                renderChart('mainChart', {
                    type: 'line',
                    data: {
                        labels: data.map(d => d.date),
                        datasets: [{
                            label: 'Total Quantity',
                            data: data.map(d => d.total_quantity),
                            borderColor: 'rgba(102, 126, 234, 1)',
                            backgroundColor: 'rgba(102, 126, 234, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Date Wise Trend'
                            }
                        }
                    }
                });
                $('#chartsContainer').show();
            }
        }

        function renderStateWise(data) {
            if (data && data.length > 0) {
                renderTable(['State', 'Total Quantity', 'Order Count', 'Product Count', 'User Count'],
                    data.map(item => [
                        item.state,
                        item.total_quantity,
                        item.order_count,
                        item.product_count,
                        item.user_count
                    ])
                );

                renderChart('mainChart', {
                    type: 'doughnut',
                    data: {
                        labels: data.map(d => d.state),
                        datasets: [{
                            data: data.map(d => d.total_quantity),
                            backgroundColor: [
                                'rgba(102, 126, 234, 0.8)',
                                'rgba(118, 75, 162, 0.8)',
                                'rgba(17, 153, 142, 0.8)',
                                'rgba(56, 239, 125, 0.8)',
                                'rgba(240, 147, 251, 0.8)',
                                'rgba(245, 87, 108, 0.8)',
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'State Wise Distribution'
                            }
                        }
                    }
                });
                $('#chartsContainer').show();
            }
        }

        function renderUserWise(data) {
            if (data && data.length > 0) {
                renderTable(['User Name', 'Email', 'State', 'Total Quantity', 'Order Count', 'Product Count', 'Avg Qty/Order'],
                    data.map(item => [
                        item.user_name,
                        item.user_email,
                        item.user_state || 'N/A',
                        item.total_quantity,
                        item.order_count,
                        item.product_count,
                        item.avg_quantity_per_order
                    ])
                );

                renderChart('mainChart', {
                    type: 'bar',
                    data: {
                        labels: data.map(d => d.user_name),
                        datasets: [{
                            label: 'Total Quantity',
                            data: data.map(d => d.total_quantity),
                            backgroundColor: 'rgba(102, 126, 234, 0.8)',
                            borderColor: 'rgba(102, 126, 234, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'User Wise Analysis'
                            },
                            legend: {
                                display: false
                            }
                        }
                    }
                });
                $('#chartsContainer').show();
            }
        }

        function renderBrandWise(data) {
            if (data && data.length > 0) {
                renderTable(['Brand Name', 'Total Quantity', 'Order Count', 'Product Count', 'User Count', 'Avg Qty/Order'],
                    data.map(item => [
                        item.brand_name,
                        item.total_quantity,
                        item.order_count,
                        item.product_count,
                        item.user_count,
                        item.avg_quantity_per_order
                    ])
                );

                renderChart('mainChart', {
                    type: 'doughnut',
                    data: {
                        labels: data.map(d => d.brand_name),
                        datasets: [{
                            data: data.map(d => d.total_quantity),
                            backgroundColor: [
                                'rgba(102, 126, 234, 0.8)',
                                'rgba(118, 75, 162, 0.8)',
                                'rgba(17, 153, 142, 0.8)',
                                'rgba(56, 239, 125, 0.8)',
                                'rgba(240, 147, 251, 0.8)',
                                'rgba(245, 87, 108, 0.8)',
                                'rgba(79, 172, 254, 0.8)',
                                'rgba(0, 242, 254, 0.8)',
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Brand Wise Distribution'
                            }
                        }
                    }
                });
                $('#chartsContainer').show();
            }
        }

        function renderChart(canvasId, config) {
            const canvas = document.getElementById(canvasId);
            if (mainChart) {
                mainChart.destroy();
            }
            mainChart = new Chart(canvas, config);
        }

        function renderTable(headers, rows) {
            let thead = '<tr>';
            headers.forEach(header => {
                thead += `<th>${header}</th>`;
            });
            thead += '</tr>';
            $('#tableHead').html(thead);

            let tbody = '';
            rows.forEach(row => {
                tbody += '<tr>';
                row.forEach(cell => {
                    tbody += `<td>${cell}</td>`;
                });
                tbody += '</tr>';
            });
            $('#tableBody').html(tbody);
            $('#dataTableContainer').show();
        }
    </script>
@endpush
