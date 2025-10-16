@extends('supper-admin')
@php
    $page = 'Dashboard';
    $meta_title = 'Dashboard - BRJ';
    $meta_description = 'Dashboard - BRJ';
    $mainPage = '';
    $mainPageLink = '';
@endphp
@push('style')
    <style>
        #chartdiv {
            width: 100%;
            height: 450px;
            padding: 0px;
            margin: 0px;
        }
    </style>
@endpush

@section('content')
    @include('supper-admin.inc.head')
    <div class="z-10 body max-sm:p-[10px]">
        @include('components.breadcrumb')

        {{-- Today Sales --}}
        <div class="bg-white shadow-lg p-5 mt-5">
            <div class="flex justify-between">
                <h1 class="font-bold text-2xl">Sales Overview</h1>
                <div class="flex items-center gap-3 mt-2 sm:mt-0">
                    <div>
                        <button class="border px-3 py-1.5 flex" id="dateSelector">
                            <div>Today</div>
                            <img class="pl-1" src="{{ asset('media/icons/arrow_down_black.svg') }}">
                        </button>
                    </div>
                </div>
            </div>
            <p class="w-full text-left text-gray-500">Sales Summary</p>
            <div class="mt-3 flex gap-2 lg:gap-3 xl:flex-nowrap flex-wrap">
                {{-- Total sales --}}
                <div class="p-2 lg:p-4 flex items-center gap-3 border w-[170px] xl:w-full md:w-[190px]">
                    <div class="bg-rose-500 p-2"><img class="size-4 xl:size-5" src="/media/icons/bars_icon.svg"
                            alt="total income icon"></div>
                    <div class="flex flex-col">
                        <h3 class="text-xs h-[18px] text-gray-500">Total Income</h3>
                        <p class="text-xs xl:text-base font-bold">Rs. 2000</p>
                    </div>
                </div>

                {{-- Total Orders --}}
                <div class="p-2 lg:p-4 flex items-center gap-3 border w-[170px] xl:w-full md:w-[190px]">
                    <div class="bg-red-400 p-2"><img class="size-4 xl:size-5" src="/media/icons/union.svg"
                            alt="total-expense-icon"></div>
                    <div class="flex flex-col">
                        <h3 class="text-xs h-[18px] text-gray-500">Total Expense</h3>
                        <p class="text-xs xl:text-base font-bold">Rs. 3000</p>
                    </div>
                </div>

                {{-- Inventory --}}
                <div class="py-4 pl-4 flex items-center gap-3 border w-[170px] xl:w-full md:w-[190px]">
                    <div class="bg-green-500 p-2"><img class="size-4 xl:size-5" src="/media/icons/PL_icon.svg"
                            alt="profit-loss-icon"></div>
                    <div class="flex flex-col">
                        <h3 class="text-xs h-[18px] text-gray-500">Profit/Loss</h3>
                        <p class="text-xs xl:text-base font-bold">Rs. 3000</p>
                    </div>
                </div>

                {{-- Total sales --}}
                <div class="p-2 lg:p-4 flex items-center gap-3 border w-[170px] xl:w-full md:w-[190px]">
                    <div class="bg-violet-500 p-2"><img class="size-4 xl:size-5" src="/media/icons/cash_icon.svg"
                            alt="total sales"></div>
                    <div class="flex flex-col">
                        <h3 class="text-xs h-[18px] text-gray-500">Deposit (Cash)</h3>
                        <p class="text-xs xl:text-base font-bold">Rs. 3000</p>
                    </div>
                </div>

                {{-- Total Orders --}}
                <div class="p-2 lg:p-4 flex items-center gap-3 border w-[170px] xl:w-full md:w-[190px]">
                    <div class="p-2 bg-teal-500"><img class="size-4 xl:size-5" src="/media/icons/credit_card_icon.svg"
                            alt="total sales"></div>
                    <div class="flex flex-col">
                        <h3 class="text-xs text-gray-500">Deposit (Card)</h3>
                        <p class="font-bold">Rs. 3000</p>
                    </div>
                </div>

                {{-- Inventory --}}
                {{-- <div class="p-2 lg:p-4 flex gap-3 border w-[170px] xl:w-full md:w-[190px]">
                    <img class="size-7 xl:size-9" src="/media/icons/cart.svg" alt="total sales">
                    <div class="flex flex-col">
                        <h3 class="text-xs h-[18px] text-gray-500">Inventory</h3>
                        <p class="font-bold">Rs. 1,405,337.00</p>
                    </div>
                </div> --}}
            </div>
        </div>

        <div class="xl:gap-5 xl:flex">
            <!-- Detailed Stats -->
            <div class="w-full bg-white shadow-lg mt-5 px-7 pt-7 pb-4">
                <h2 class="text-3xl font-bold mb-6">Detailed stats</h2>
                <div class="grid grid-cols-[180px_1px_186px] lg:grid-cols-[200px_1px_200px] justify-between gap-5">
                    <div class="border-b">
                        <div class="flex items-center gap-4 pb-4">
                            <div class="bg-rose-500 p-2"><img class="size-5 xl:size-7" src="/media/icons/bars_icon.svg"
                                    alt="sale-invoice-icon"></div>
                            <div class="flex-1">
                                <p class="font-medium text-[#909090]">Sale Invoices</p>
                                <span class="text-xl font-bold">32</span>
                            </div>
                        </div>
                    </div>

                    <div class="w-[1px] h-12 border-l"></div>

                    <div class="border-b ">
                        <div class="flex items-center gap-4 pb-4">
                            <div class="bg-yellow-500 p-2"><img class="size-5 xl:size-7" src="/media/icons/bars_icon.svg"
                                    alt="purchase-invoice-icon"></div>
                            <div class="flex-1">
                                <p class="font-medium text-[#909090]">Purchase Invoices</p>
                                <span class="text-xl font-bold">12</span>
                            </div>
                        </div>
                    </div>


                    <div class="">
                        <div class="flex items-center gap-4 pb-4">
                            <div class="bg-emerald-600 p-2"><img class="size-5 xl:size-7"
                                    src="/media/icons/add_user_icon.svg" alt="customer-icon"></div>
                            <div class="">
                                <p class="font-medium text-[#909090]">Total Customers</p>
                                <span class="text-xl font-bold">566</span>
                            </div>
                        </div>
                    </div>

                    <div class="w-[1px] h-12 border-l"></div>

                    <div class="">
                        <div class="flex items-center gap-4 pb-4">
                            <div class="p-2 bg-gray-500"><img class="size-7" src="/media/icons/users.svg"
                                    alt="dealers-icon"></div>
                            <div class="flex-1">
                                <p class="font-medium text-[#909090]">Total Dealers</p>
                                <span class="text-xl font-bold">23</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Quick Links -->
            <div class="w-full bg-white shadow-lg mt-5 px-7 pt-7 pb-4">
                <h2 class="text-3xl font-bold mb-6">Quick Links</h2>
                <div class="grid grid-cols-[180px_1px_186px] lg:grid-cols-[200px_1px_200px] justify-between gap-5">
                    <div class="border-b ">
                        <a href="#">
                            <div class="flex items-center gap-4 pb-4">
                                <div class="p-2 bg-cyan-500"><img class="size-7" src="/media/icons/inventory-white.svg"
                                        alt="inventory-icon"></div>
                                <div class="flex-1">
                                    <p class="font-medium text-[#909090]">Add Items</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="w-[1px] h-12 border-l"></div>

                    <div class="border-b">
                        <a href="#">
                            <div class="flex items-center gap-4 pb-4">
                                <div class="p-2 bg-red-400"><img class="size-7" src="/media/icons/pos-dark.svg"
                                        alt="pos-icon"></div>
                                <div class="flex-1">
                                    <p class="font-medium text-[#909090]">POS</p>
                                </div>
                            </div>
                        </a>
                    </div>


                    <div class="">
                        <a href="#">
                            <div class="flex items-center gap-4 pb-4">
                                <div class="p-2 bg-teal-700"><img class="size-7" src="/media/icons/stock.svg"
                                        alt="stock-icon"></div>
                                <div class="flex-1">
                                    <p class="font-medium text-[#909090]">Stock</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="w-[1px] h-12 border-l"></div>

                    <div class="">
                        <a href="#">
                            <div class="flex items-center gap-4 pb-4">
                                <div class="p-2 bg-gray-500"><img class="size-7" src="/media/icons/order.svg"
                                        alt="purchase-icon"></div>
                                <div class="flex-1">
                                    <p class="font-medium text-[#909090]">Purchase Order</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Transactions Table --}}
        <div class="bg-white shadow-lg mt-5 p-5 overflow-x-auto">

            <div class="flex justify-between items-center mb-5 w-full">
                <h2 class="text-2xl font-bold">Transactions</h2>
                <a class="cursor-pointer text-primary underline" href="#"><button
                        class="border py-1.5 px-3">View All</button></a>
            </div>

            <table class="w-full min-w-[1024px]">
                <thead class="text-xs text-left text-gray-500 uppercase">
                    <tr class="border-b">
                        <th scope="col" class="px-4 py-3">Invoice Number</th>
                        <th scope="col" class="px-4 py-3">Invoice Date</th>
                        <th scope="col" class="px-4 py-3">Sub Total</th>
                        <th scope="col" class="px-4 py-3">Discount Amount</th>
                        <th scope="col" class="px-4 py-3">Tax Amount</th>
                        <th scope="col" class="px-4 py-3">Grand Total</th>
                        <th scope="col" class="px-4 py-3">Payment Due</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b">
                        <td class="px-4 py-3">1</td>
                        <td class="px-4 py-3">01-03-2025</td>
                        <td class="px-4 py-3">1001-John Doe</td>
                        <td class="px-4 py-3">Rs. 50,000.00</td>
                        <td class="px-4 py-3">Rs. 2,000.00</td>
                        <td class="px-4 py-3">Rs. 5,000.00</td>
                        <td class="px-4 py-3">Rs. 53,000.00</td>
                    </tr>

                    <tr class="border-b">
                        <td class="px-4 py-3">2</td>
                        <td class="px-4 py-3">05-03-2025</td>
                        <td class="px-4 py-3">1002-Jane Smith</td>
                        <td class="px-4 py-3">Rs. 75,000.00</td>
                        <td class="px-4 py-3">Rs. 3,000.00</td>
                        <td class="px-4 py-3">Rs. 7,500.00</td>
                        <td class="px-4 py-3">Rs. 79,500.00</td>
                    </tr>

                    <tr class="border-b">
                        <td class="px-4 py-3">3</td>
                        <td class="px-4 py-3">10-03-2025</td>
                        <td class="px-4 py-3">1003-Michael Johnson</td>
                        <td class="px-4 py-3">Rs. 60,000.00</td>
                        <td class="px-4 py-3">Rs. 2,500.00</td>
                        <td class="px-4 py-3">Rs. 6,000.00</td>
                        <td class="px-4 py-3">Rs. 63,500.00</td>
                    </tr>

                    <tr class="border-b">
                        <td class="px-4 py-3">4</td>
                        <td class="px-4 py-3">15-03-2025</td>
                        <td class="px-4 py-3">1004-Emily Davis</td>
                        <td class="px-4 py-3">Rs. 85,000.00</td>
                        <td class="px-4 py-3">Rs. 4,000.00</td>
                        <td class="px-4 py-3">Rs. 8,500.00</td>
                        <td class="px-4 py-3">Rs. 89,500.00</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
@endsection
@push('script')
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

    <script>
        am5.ready(function() {
            // Create root element
            var root = am5.Root.new("chartdiv");
            root._logo.dispose();

            // Set themes
            root.setThemes([
                am5themes_Animated.new(root)
            ]);

            // Create chart
            var chart = root.container.children.push(am5xy.XYChart.new(root, {
                panX: false, // Disable panning
                panY: false, // Disable panning
                wheelX: "none", // Disable wheel zoom
                wheelY: "none", // Disable wheel zoom
                pinchZoomX: false, // Disable pinch zoom
                paddingLeft: 0
            }));

            chart.setAll({
                paddingLeft: -10,
                paddingRight: 0,
                paddingTop: 0,
                paddingBottom: 0
            });
            // Add cursor
            var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {
                behavior: "none" // No zoom or pan behavior
            }));
            cursor.lineY.set("visible", false);

            // Generate random data
            var date = new Date();
            date.setHours(0, 0, 0, 0);
            var value = 100;

            function generateData() {
                value = Math.round((Math.random() * 10 - 5) + value);
                am5.time.add(date, "day", 1);
                return {
                    date: date.getTime(),
                    value: value
                };
            }

            function generateDatas(count) {
                var data = [];
                for (var i = 0; i < count; ++i) {
                    data.push(generateData());
                }
                return data;
            }

            // Create axes
            var xAxis = chart.xAxes.push(am5xy.DateAxis.new(root, {
                maxDeviation: 0.5,
                baseInterval: {
                    timeUnit: "day",
                    count: 1
                },
                renderer: am5xy.AxisRendererX.new(root, {
                    minGridDistance: 80,
                    minorGridEnabled: true,
                    pan: "none" // Disable panning
                }),
                tooltip: am5.Tooltip.new(root, {})
            }));

            var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
                maxDeviation: 1,
                renderer: am5xy.AxisRendererY.new(root, {
                    pan: "none" // Disable panning
                })
            }));

            // Disable grid lines
            xAxis.get("renderer").grid.template.set("visible", false);
            yAxis.get("renderer").grid.template.set("visible", false);

            // Hide axis labels
            xAxis.get("renderer").labels.template.set("visible", false);
            yAxis.get("renderer").labels.template.set("visible", false);

            // Add series
            var series = chart.series.push(am5xy.SmoothedXLineSeries.new(root, {
                name: "Series",
                xAxis: xAxis,
                yAxis: yAxis,
                valueYField: "value",
                valueXField: "date",
                tooltip: am5.Tooltip.new(root, {
                    labelText: "{valueY}"
                })
            }));

            series.fills.template.setAll({
                visible: true,
                fillOpacity: 0.2
            });

            series.bullets.push(function() {
                return am5.Bullet.new(root, {
                    locationY: 0,
                    sprite: am5.Circle.new(root, {
                        radius: 4,
                        stroke: root.interfaceColors.get("background"),
                        strokeWidth: 2,
                        fill: series.get("fill")
                    })
                });
            });

            // Remove scrollbar (optional)
            // chart.set("scrollbarX", am5.Scrollbar.new(root, {
            //     orientation: "horizontal"
            // }));

            var data = generateDatas(50);
            series.data.setAll(data);

            // Make stuff animate on load
            series.appear(1000);
            chart.appear(1000, 100);
        }); // end am5.ready()


        // Initialize variables
        let selectedStartDate, selectedEndDate;

        // Load dates from localStorage if available
        var initialStartDate = moment('2025-03-01 10:00:00');
        var initialEndDate = moment('2025-03-10 18:30:00');

        // Update the button's text
        updateButtonDisplay(initialStartDate, initialEndDate);
        // Date Range Picker Configuration
        $("#dateSelector").daterangepicker({
                startDate: initialStartDate,
                endDate: initialEndDate,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf(
                        'month')],
                },
            },
            function(start, end) {
                // Callback for date selection
                selectedStartDate = start;
                selectedEndDate = end;

                // Update button display
                updateButtonDisplay(start, end);

                // Store selected dates in localStorage
                // localStorage.setItem('trialSelectedStartDatee', start.format('YYYY-MM-D'));
                // localStorage.setItem('trialSelectedEndDatee', end.format('YYYY-MM-D'));
                // Redirect to the balancesheet route with the selected dates as query parameters
                const startDate = start.format('YYYY-MM-DD');
                const endDate = end.format('YYYY-MM-DD');
                const route = '{{ route('dashboard') }}'; // Blade template syntax to get the named route URL
                window.location.href = `${route}?start_date=${startDate}&end_date=${endDate}`;
                // Trigger data fetch
                // fetchData(start, end);
            }
        );

        // Function to update button display
        function updateButtonDisplay(start, end) {
            const dateSelectorButton = $("#dateSelector div"); // Select the <div> inside the button
            const startDate = start.format('MMM D, YYYY');
            const endDate = end.format('MMM D, YYYY');

            if (startDate === endDate) {
                dateSelectorButton.text(startDate); // Show single date if both are the same
            } else {
                dateSelectorButton.text(`${startDate} - ${endDate}`); // Show date range
            }
        }
    </script>
@endpush
