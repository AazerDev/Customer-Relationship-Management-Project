@extends('supper-admin')

@php
    $page = 'POS';
    $meta_title = 'POS - BRJ';
    $meta_description = 'POS - BRJ';
    $mainPage = '';
    $mainPageLink = '';
@endphp
@push('style')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush
@section('content')
    <div class="h-screen px-3 xl:overflow-y-hidden">
        <div class="flex xl:flex-row flex-col justify-between xl:items-center">
            <div class="flex flex-wrap gap-2">
                <div title="Go Back" class="input flex flex-col w-fit static cursor-pointer" onclick="window.history.back()">
                    <label for="input" class="text-blue-500 text-xs font-semibold relative top-2 ml-[7px] px-[3px] bg-[#e6f6fe] w-fit">Back</label>
                    <div class="border-blue-500 flex justify-center items-center px-[10px] py-[11px] text-xs bg-[#e6f6fe] border w-[48px] ">
                        <img src="{{ asset('media/icons/arrow_blue.svg') }}" alt="" class="rotate-180">
                    </div>
                </div>
                <div class="input flex flex-col sm:w-fit w-[83%] static">
                    <label for="input"
                        class="text-blue-500 text-xs font-semibold relative top-2 ml-[7px] px-[3px] bg-[#e6f6fe] w-fit">Date:</label>
                    <input id="date" type="date" name="date"
                        class="border-blue-500 text-blue-500 input px-[10px] py-[10px] text-xs bg-[#e6f6fe] border sm:w-[210px] w-full focus:outline-none placeholder:text-black/25" />
                </div>
                <div class="input flex flex-col sm:w-fit w-full static">
                    <label for="input"
                        class="text-blue-500 text-xs font-semibold relative top-2 ml-[7px] px-[3px] bg-[#e6f6fe] w-fit">Biller:</label>
                    <select id="biller" name="biller"
                        class="border-blue-500 text-blue-500 input px-[10px] py-[10px] text-xs bg-[#e6f6fe] border sm:w-[210px] w-full focus:outline-none">
                        <option value="John Doe">John Doe</option>
                    </select>
                </div>
                <div class="input flex flex-col sm:w-fit w-full static">
                    <label for="input"
                        class="text-blue-500 text-xs font-semibold relative top-2 ml-[7px] px-[3px] bg-[#e6f6fe] w-fit">Table:</label>
                    <select id="table" name="table"
                        class="border-blue-500 text-blue-500 input px-[10px] py-[10px] text-xs bg-[#e6f6fe] border sm:w-[210px] w-full focus:outline-none">
                        <option value="Table 1">Table 1</option>
                        <option value="Table 2">Table 2</option>
                    </select>
                </div>
                <div class="input flex flex-col sm:w-fit w-[85.5%] static">
                    <label for="input"
                        class="text-blue-500 text-xs font-semibold relative top-2 ml-[7px] px-[3px] bg-[#e6f6fe] w-fit">Customer:</label>
                    <select id="biller" name="biller"
                        class="border-blue-500 text-blue-500 input px-[10px] py-[10px] text-xs bg-[#e6f6fe] border sm:w-[210px] w-full focus:outline-none">
                        <option value="John Doe">Walkin Customer</option>
                        <option value="John Doe">John doe</option>
                    </select>
                </div>
                <div title="Add Customer"
                    class="flex justify-center items-center ml-1 mt-4 static p-1 cursor-pointer border border-blue-500 custom-model beep"
                    target-model="#add-customer">
                    <img src="{{ asset('media/icons/plus-blue.svg') }}" alt="">
                </div>

            </div>
            <div class="flex">
                <div id="fullscreenButton" title="Full Screen"
                    class="flex justify-center items-center xl:ml-2 mt-4 static p-2 cursor-pointer border border-blue-500">
                    <img id="fullscreenIcon" src="{{ asset('media/icons/fullscreen.svg') }}" width="20" alt="">
                </div>


                <div title="Print Last Invoice"
                    class="flex justify-center items-center ml-2 mt-4 static p-2 cursor-pointer border border-blue-500 ">
                    <img src="{{ asset('media/icons/invoice.svg') }}" width="20" alt="">
                </div>
                <div title="Cash Register Details" target-model="#register-details"
                    class="flex justify-center items-center ml-2 mt-4 static p-2 cursor-pointer border border-blue-500 custom-model beep">
                    <img src="{{ asset('media/icons/cash-register.svg') }}" width="20" alt="">
                </div>
                <div title="Today Sale & profit" target-model="#sale-report"
                    class="flex justify-center items-center ml-2 mt-4 static p-2 cursor-pointer border border-blue-500 custom-model beep">
                    <img src="{{ asset('media/icons/sale-report.svg') }}" width="20" alt="">
                </div>
                <div title="POS Settings"
                    class="flex justify-center items-center ml-2 mt-4 static p-2 cursor-pointer border border-blue-500 ">
                    <img src="{{ asset('media/icons/Settings-black.svg') }}" width="20" alt="">
                </div>
            </div>
        </div>
        <div class="lg:grid flex flex-col-reverse grid-cols-2 gap-y-4 mt-2 lg:h-[93%]">
            {{-- left Side: Cart --}}
            <div class="bg-white h-full shadow-lg relative xl:mr-5">
                <div class="border-b p-4 sticky top-0 left-0 bg-white z-10 flex justify-between items-center">
                    <button
                        class="flex items-center justify-center space-x-2 border h-[40px] px-7 bg-[#ff8f66] text-[#fff] transition-all custom-model beep"
                        target-model="#holded-carts">
                        <img src="{{ asset('media/icons/cart-hold.svg') }}" alt="">
                        <span class="whitespace-nowrap">Holded Carts</span>
                    </button>
                </div>
                <div class="p-5 h-[50vh] overflow-y-auto">
                    <table id="myTable"
                        class="table table-hover min-w-[600px] table-striped order-list table-fixed w-full border-collapse border border-gray-300 ui-keyboard-input ui-widget-content ui-corner-all"
                        aria-haspopup="true" role="textbox">
                        <thead class="table-header-group bg-lightGray">
                            <tr class="border-b border-gray-300">
                                <th class="py-2 px-4 text-left 3xl:w-[40%]">Product</th>
                                <th class="py-2 px-4 text-left">Price</th>
                                <th class="py-2 px-4 text-left">Quantity</th>
                                <th class="py-2 px-4 text-left">Subtotal</th>
                                <th class="py-2 px-1 text-left">Remove</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-id">
                            @for ($i = 1; $i <= 9; $i++)
                                <tr class="border-b border-gray-200 even:bg-lightGray">
                                    <td class="py-2 px-4">
                                        <div class="flex md:flex-row flex-col items-center gap-3 cursor-pointer custom-model beep"
                                            target-model="#product">
                                            <p class="text-primary hover:underline">Product {{ $i }}</p>
                                            <span class="p-1 bg-primary/20">
                                                <img src="{{ asset('media/icons/edit-blue.svg') }}" alt=""
                                                    width="10">
                                            </span>
                                        </div>
                                    </td>
                                    <td class="py-2 px-4 price">$10.00</td>
                                    <td class="py-2 px-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex items-center w-fit border gap-3">
                                                <button
                                                    class="hover:bg-black/20 border-r px-2.5 h-[30px] decrease">-</button>
                                                <span class="px-3 qty">1</span>
                                                <button
                                                    class="hover:bg-black/20 border-l px-2.5 h-[30px] increase">+</button>
                                            </div>

                                        </div>
                                    </td>
                                    <td class="py-2 px-4 subtotal">$10.00</td>
                                    <td width="10%">
                                        <button class="bg-[#f42a42] px-2 h-[30px] delete-row">
                                            <img src="{{ asset('media/icons/Trash-white.svg') }}" alt=""
                                                class="min-w-3">
                                        </button>
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
                <div class="border-b p-4 bg-white z-10">
                    <div class="bg-[#f3f4f6] grid grid-cols-3 md:gap-x-20 gap-y-2 p-2">
                        <div class="flex items-center">
                            <div class="input flex flex-col w-fit static ml-2">
                                <label for="input"
                                    class="text-black/50 text-xs font-semibold relative top-2 ml-[7px] px-[3px] bg-[#f3f4f6] w-fit">Items:</label>
                                <input type="number" name="items" value="9" readonly
                                    class="border-black/50 text-black input px-[10px] cursor-default py-[8px] font-bold bg-[#f3f4f6] border w-full focus:outline-none placeholder:text-black/25" />
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="input flex flex-col w-fit ml-2 static">
                                <label for="input"
                                    class="text-black/50 text-xs font-semibold relative top-2 ml-[7px] px-[3px] bg-[#f3f4f6] w-fit">Total:</label>
                                <input type="number" name="total" value="2599.98" readonly
                                    class="border-black/50 text-black input px-[10px] py-[8px] cursor-default font-bold bg-[#f3f4f6] border w-full focus:outline-none placeholder:text-black/25" />
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="input flex flex-col w-fit relative ml-2">
                                <div class="absolute right-2 top-1/2 -mt-1 bg-[#f3f4f6] p-1">
                                    <img src="{{ asset('media/icons/edit.svg') }}" alt="">
                                </div>
                                <label for="input"
                                    class="text-black text-xs font-semibold relative top-2 ml-[7px] px-[3px] bg-[#f3f4f6] w-fit">Discount:</label>
                                <input type="number" name="discount" value="0.00"
                                    class="border-black text-black input px-[10px] py-[8px] font-bold bg-[#f3f4f6] border w-full focus:outline-none placeholder:text-black/25" />
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="input flex flex-col w-fit relative ml-2">
                                <div class="absolute right-2 top-1/2 -mt-1 bg-[#f3f4f6] p-1">
                                    <img src="{{ asset('media/icons/edit.svg') }}" alt="">
                                </div>
                                <label for="input"
                                    class="text-black text-xs font-semibold relative top-2 ml-[7px] px-[3px] bg-[#f3f4f6] w-fit">Coupon:</label>
                                <input type="number" name="coupon" value="0.00"
                                    class="border-black text-black input px-[10px] py-[8px] font-bold bg-[#f3f4f6] border w-full focus:outline-none placeholder:text-black/25" />
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="input flex flex-col w-fit relative ml-2">
                                <div class="absolute right-2 top-1/2 -mt-1 bg-[#f3f4f6] p-1">
                                    <img src="{{ asset('media/icons/edit.svg') }}" alt="">
                                </div>
                                <label for="input"
                                    class="text-black text-xs font-semibold relative top-2 ml-[7px] px-[3px] bg-[#f3f4f6] w-fit">Tax:</label>
                                <input type="number" name="tax" value="0.00"
                                    class="border-black text-black input px-[10px] py-[8px] font-bold bg-[#f3f4f6] border w-full focus:outline-none placeholder:text-black/25" />
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="input flex flex-col w-fit relative ml-2">
                                <div class="absolute right-2 top-1/2 -mt-1 bg-[#f3f4f6] p-1">
                                    <img src="{{ asset('media/icons/edit.svg') }}" alt="">
                                </div>
                                <label for="input"
                                    class="text-black text-xs font-semibold relative top-2 ml-[7px] px-[3px] bg-[#f3f4f6] w-fit">Shipping:</label>
                                <input type="number" name="shipping" value="0.00"
                                    class="border-black text-black input px-[10px] py-[8px] font-bold bg-[#f3f4f6] border w-full focus:outline-none placeholder:text-black/25" />
                            </div>
                        </div>
                    </div>
                    <div
                        class="w-full flex py-2 justify-center items-center bg-primary/20 my-4 text-3xl text-primary font-bold">
                        Grand Total 2599.98
                    </div>
                    <div class="flex flex-wrap sm:flex-nowrap items-center gap-2">
                        <button
                            class="flex items-center justify-center space-x-2 border h-[40px] sm:w-[80%] w-full bg-[#00d2ca] text-[#fff] transition-all custom-model beep"
                            target-model="#card">
                            <img src="{{ asset('media/icons/card.svg') }}" alt="">
                            <span class="whitespace-nowrap">Card</span>
                        </button>
                        <button
                            class="flex items-center justify-center space-x-2 border h-[40px] sm:w-[60%] w-full px-7 bg-[#2c9743] text-[#fff] transition-all custom-model beep"
                            target-model="#cash">
                            <img src="{{ asset('media/icons/cash.svg') }}" alt="">
                            <span class="whitespace-nowrap">Cash</span>
                        </button>
                        <button
                            class="flex items-center justify-center space-x-2 border h-[40px] sm:w-[80%] w-full bg-[#b649ff] text-[#fff] transition-all custom-model beep"
                            target-model="#deposit">
                            <img src="{{ asset('media/icons/deposit.svg') }}" width="20" alt="">
                            <span class="whitespace-nowrap">Deposit</span>
                        </button>
                        <button
                            class="flex items-center justify-center space-x-2 border h-[40px] w-full bg-[#ffb055] text-[#fff] transition-all">
                            <img src="{{ asset('media/icons/pause.svg') }}" alt="">
                            <span>Hold Cart</span>
                        </button>
                        <button
                            class="flex items-center justify-center space-x-2 border h-[40px] sm:w-[80%] w-full bg-[#ff4949] text-[#fff] transition-all">
                            <img src="{{ asset('media/icons/replay.svg') }}" alt="">
                            <span class="whitespace-nowrap">Cancel</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- right Side: Product List --}}
            <div class="h-full overflow-y-auto bg-white shadow-lg">
                <div>
                    <div class="bg-white sticky top-0 left-0 z-10 p-4">
                        <div class="flex items-center gap-7 h-full">
                            <div class="w-full">
                                <label for="category" class="font-bold block text-sm md:text-base pb-2">Select Category</label>
                                <select name="category" class="w-full h-[36px] border border-[#d1d1d1]">
                                    <option value="all">All</option>
                                    <option value="category1">Category 1</option>
                                    <option value="category2">Category 2</option>
                                    <option value="category3">Category 3</option>
                                </select>
                            </div>
                            <div class="w-full">
                                <label for="subcategory" class="font-bold block text-sm md:text-base pb-2">Select Sub-Category</label>
                                <select name="subcategory" class="w-full h-[36px] border border-[#d1d1d1]">
                                    <option value="all">All</option>
                                    <option value="subcategory1">Sub Category 1</option>
                                    <option value="subcategory2">Sub Category 2</option>
                                    <option value="subcategory3">Sub Category 3</option>
                                </select>
                            </div>
                        </div>
                        <div class="w-full h-full mt-3">
                            <label for="Search" class="font-bold block pb-2">Search</label>
                            <input type="text" name="Search" placeholder="Search . . ."
                                class="w-full h-[36px] px-2 border border-[#d1d1d1]">
                        </div>
                    </div>
                    <div class="grid md:grid-cols-4 sm:grid-cols-3 grid-cols-2 gap-4 my-3 px-4">
                        @php
                            $images = [
                                'https://images.unsplash.com/photo-1674027392842-29f8354e236c?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTZ8fHJhbmRvbSUyMHByb2R1Y3RzfGVufDB8fDB8fHww',
                                'https://images.unsplash.com/photo-1623904156282-99690f6d30f4?q=80&w=2098&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                                'https://plus.unsplash.com/premium_photo-1731601050008-268d29b9867d?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NXx8cmFuZG9tJTIwcHJvZHVjdHN8ZW58MHx8MHx8fDA%3D',
                                'https://images.unsplash.com/photo-1605125207352-e8512d068ed7?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8N3x8cmFuZG9tJTIwcHJvZHVjdHN8ZW58MHx8MHx8fDA%3D',
                                'https://images.unsplash.com/photo-1655234819617-70f673ef2ac6?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTF8fHJhbmRvbSUyMHByb2R1Y3RzfGVufDB8fDB8fHww',
                                'https://images.unsplash.com/photo-1613827101907-37bf39fbb837?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MjR8fHJhbmRvbSUyMHByb2R1Y3RzfGVufDB8fDB8fHww',
                                'https://images.unsplash.com/photo-1589071634525-012168f30ad5?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MjZ8fHJhbmRvbSUyMHByb2R1Y3RzfGVufDB8fDB8fHww',
                                'https://images.unsplash.com/photo-1594549208400-128687ad97ef?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MzJ8fHJhbmRvbSUyMHByb2R1Y3RzfGVufDB8fDB8fHww',
                                'https://plus.unsplash.com/premium_photo-1676559916183-d4033c0b51ed?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mzd8fHJhbmRvbSUyMHByb2R1Y3RzfGVufDB8fDB8fHww',
                                'https://plus.unsplash.com/premium_photo-1681487929886-4c16ad2f2387?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NDF8fHJhbmRvbSUyMHByb2R1Y3RzfGVufDB8fDB8fHww',
                                'https://images.unsplash.com/photo-1582642780691-edbb1f20870b?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NDN8fHJhbmRvbSUyMHByb2R1Y3RzfGVufDB8fDB8fHww',
                                'https://plus.unsplash.com/premium_photo-1731601050008-268d29b9867d?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NXx8cmFuZG9tJTIwcHJvZHVjdHN8ZW58MHx8MHx8fDA%3D',
                            ];
                        @endphp

                        @foreach ($images as $index => $image)
                            @php
                                $price = 1000 + $index * 100; // Optional: predictable price
                            @endphp
                            <div class="bg-white p-2 border border-gray-200">
                                <div class="w-full sm:h-[180px] h-[70px] bg-lightGray_2">
                                    <img class="w-full h-full object-cover object-center" src="{{ $image }}"
                                        alt="product image">
                                </div>
                                <div class="flex items-center mt-4 justify-between">
                                    <p class="font-bold text-sm">Product no. {{ $index + 1 }}</p>
                                    <p class="font-bold text-sm">Rs. {{ $price }}</p>
                                </div>
                            </div>
                        @endforeach
                        <div class="md:col-span-4 sm:col-span-3 col-span-2 flex justify-center items-center">
                            <button
                                class="flex items-center justify-center space-x-2 border h-[40px] w-full bg-primary text-[#fff] transition-all">
                                <img src="{{ asset('media/icons/arrow_down_black.svg') }}" width="20" alt=""
                                    class="invert">
                                <span class="whitespace-nowrap">Load More</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- -------------------- Modals -------------------- --}}
    <audio id="beepSound" src="{{ asset('media/beep-07a.mp3') }}" preload="auto"></audio>

    <div id="holded-carts" class="model-custom flex hidden">
        <div class="model-custom-inner">
            <div class="header">
                <h2 class="text-xl font-bold">Holded Carts</h2>
                <button class="modelClose">
                    <img src="{{ asset('media/icons/cross.svg') }}" alt="">
                </button>
            </div>
            <div class="popupBody !p-2">
                <div class="col-span-2 w-full">
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[600px]">
                            <thead>
                                <tr>
                                    <th class="text-left pr-3 p-1 border">Date</th>
                                    <th class="text-left px-3 p-1 border">Reference</th>
                                    <th class="text-left px-3 p-1 border">Customer</th>
                                    <th class="text-left px-3 p-1 border">Grand Total</th>
                                    <th class="text-left pl-3 p-1 border">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="pr-3 p-1 border">10-04-2025</td>
                                    <td class="px-3 p-1 border">posr-20250410-121639</td>
                                    <td class="px-3 p-1 border">Walk in Customer</td>
                                    <td class="px-3 p-1 border">2549.99</td>
                                    <td class="pl-3 p-1 border whitespace-nowrap">
                                        <button class="p-2 bg-primary/20">
                                            <img src="{{ asset('media/icons/edit.svg') }}" alt="">
                                        </button>
                                        <button class="p-2 bg-red-600/20">
                                            <img src="{{ asset('media/icons/Trash-red.svg') }}" alt="">
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="popupFooter">
                <button class="modelClose px-4 py-2 bg-red-500 text-white">Close</button>
            </div>
        </div>
    </div>

    <div id="add-customer" class="model-custom flex hidden">
        <div class="model-custom-inner">
            <div class="header">
                <h2 class="text-xl font-bold">Add Customer</h2>
                <button class="modelClose">
                    <img src="{{ asset('media/icons/cross.svg') }}" alt="">
                </button>
            </div>
            <div class="popupBody">
                <div>
                    <label for="name" class="block">Name</label>
                    <input type="text" name="name" placeholder="Enter Customer Name"
                        class="w-full px-3 py-2 border-b outline-none bg-secondary_2">
                </div>
                <div>
                    <label for="email" class="block">Email</label>
                    <input type="email" name="email" placeholder="Enter Customer Email"
                        class="w-full px-3 py-2 border-b outline-none bg-secondary_2">
                </div>
                <div>
                    <label for="number" class="block">Phone Number</label>
                    <input type="number" name="number" placeholder="Enter Customer Number"
                        class="w-full px-3 py-2 border-b outline-none bg-secondary_2">
                </div>
                <div>
                    <label for="address" class="block">Address</label>
                    <input type="text" name="address" placeholder="Enter Customer Address"
                        class="w-full px-3 py-2 border-b outline-none bg-secondary_2">
                </div>
            </div>
            <div class="popupFooter">
                <button class="modelClose px-4 py-2 bg-red-500 text-white">Close</button>
                <button class="px-4 py-2 bg-blue-500 text-white ml-3">Submit</button>
            </div>
        </div>
    </div>

    <div id="register-details" class="model-custom flex hidden">
        <div class="model-custom-inner">
            <div class="header">
                <h2 class="text-xl font-bold">Cash Register Details</h2>
                <button class="modelClose">
                    <img src="{{ asset('media/icons/cross.svg') }}" alt="">
                </button>
            </div>
            <div class="popupBody">
                <div class="col-span-2">
                    <p class="text-gray-600 mb-2">Please review the transaction and payments.</p>

                    <div class="bg-gray-100 p-3 font-semibold mb-2">Cash in Hand: <span class="float-right">200</span>
                    </div>

                    <div class="mb-2 border-b pb-2">
                        <p class="flex justify-between p-2 bg-secondary_2/50">Total Sale Amount: <span>1543.12</span></p>
                        <p class="flex justify-between p-2 bg-secondary_2">Total Payment: <span>1299.99</span></p>
                    </div>

                    <div class="mb-2 border-b pb-2">
                        <p class="flex justify-between p-2 bg-secondary_2">Cash Payment: <span>0</span></p>
                        <p class="flex justify-between p-2 bg-secondary_2/50">Credit Card Payment: <span>0</span></p>
                        <p class="flex justify-between p-2 bg-secondary_2">Cheque Payment: <span>1299.99</span></p>
                        <p class="flex justify-between p-2 bg-secondary_2/50">Gift Card Payment: <span>0</span></p>
                        <p class="flex justify-between p-2 bg-secondary_2">Deposit Payment: <span>0</span></p>
                        <p class="flex justify-between p-2 bg-secondary_2/50">Total Sale Return: <span>0</span></p>
                        <p class="flex justify-between p-2 bg-secondary_2">Total Expense: <span>0</span></p>
                    </div>

                    <div class="font-semibold text-lg mb-4 bg-gray-100 p-2">Total Cash: <span
                            class="float-right">1499.99</span></div>
                </div>
            </div>
            <div class="popupFooter">
                <button class="modelClose px-4 py-2 bg-red-500 text-white">Close Regsiter</button>
            </div>
        </div>
    </div>

    <div id="sale-report" class="model-custom flex hidden">
        <div class="model-custom-inner">
            <div class="header">
                <h2 class="text-xl font-bold">Today's Sale</h2>
                <button class="modelClose">
                    <img src="{{ asset('media/icons/cross.svg') }}" alt="">
                </button>
            </div>
            <div class="popupBody">
                <div class="col-span-2">
                    <p class="text-gray-600 mb-2">Please review the transaction and payments.</p>

                    <div class="bg-gray-100 p-2 font-semibold mb-2"> Total Sale Amount: <span
                            class="float-right">200</span>
                    </div>

                    <div class="mb-2 border-b pb-2">
                        <p class="flex justify-between p-2 bg-secondary_2/50">Cash Payment: <span>1543.12</span></p>
                        <p class="flex justify-between p-2 bg-secondary_2">Credit Card Payment: <span>1299.99</span></p>
                    </div>

                    <div class="mb-2 border-b pb-2">
                        <p class="flex justify-between p-2 bg-secondary_2">Cheque Payment: <span>0</span></p>
                        <p class="flex justify-between p-2 bg-secondary_2/50">Gift Card Payment: <span>0</span></p>
                        <p class="flex justify-between p-2 bg-secondary_2">Deposit Payment: <span>1299.99</span></p>
                        <p class="flex justify-between p-2 bg-secondary_2/50">Total Payment: <span>0</span></p>
                        <p class="flex justify-between p-2 bg-secondary_2">Total Sale Return: <span>0</span></p>
                        <p class="flex justify-between p-2 bg-secondary_2/50">Total Expense: <span>0</span></p>
                    </div>

                    <div class="font-semibold text-lg mb-4 bg-gray-100 p-2">Total Cash: <span
                            class="float-right">1499.99</span></div>
                </div>
            </div>
            <div class="popupFooter">
                <button class="modelClose px-4 py-2 bg-red-500 text-white">Close Report</button>
            </div>
        </div>
    </div>

    <div id="product" class="model-custom flex hidden">
        <div class="model-custom-inner">
            <div class="header">
                <h2 class="text-xl font-bold">Update Product</h2>
                <button class="modelClose">
                    <img src="{{ asset('media/icons/cross.svg') }}" alt="">
                </button>
            </div>
            <div class="popupBody">
                <p class="text-gray-600 mb-2 col-span-2">2021 Apple 12.9-inch iPad Pro Wi-Fi 512GB 2035892312345 | In
                    Stock: -4
                    1250.00</p>
                <div>
                    <label for="quantity" class="block">Quantity</label>
                    <input type="number" name="quantity" value="1" placeholder="Enter Quantity"
                        class="w-full px-3 py-2 border-b outline-none bg-secondary_2">
                </div>
                <div>
                    <label for="unit_discount" class="block">Unit Discount</label>
                    <input type="number" name="unit_discount" value="0.00" placeholder="Enter Unit Discount"
                        class="w-full px-3 py-2 border-b outline-none bg-secondary_2">
                </div>
                <div>
                    <label for="unit_price" class="block">Unit Price</label>
                    <input type="number" name="unit_price" value="1029.00" placeholder="Enter Unit Price"
                        class="w-full px-3 py-2 border-b outline-none bg-secondary_2">
                </div>
                <div>
                    <label for="tax_rate" class="block">Tax Rate</label>
                    <select name="tax_rate" class="w-full px-3 py-2 border-b outline-none bg-secondary_2">
                        <option value="0">0%</option>
                        <option value="5">5%</option>
                        <option value="10">10%</option>
                        <option value="15">15%</option>
                        <option value="20">20%</option>
                    </select>
                </div>
                <div>
                    <label for="p_unit" class="block">Product Unit</label>
                    <select name="p_unit" class="w-full px-3 py-2 border-b outline-none bg-secondary_2">
                        <option value="0">Piece</option>
                        <option value="5">Kg</option>
                        <option value="10">Litre</option>
                        <option value="15">Gram</option>
                        <option value="20">Dozen</option>
                    </select>
                </div>
            </div>
            <div class="popupFooter">
                <button class="modelClose px-4 py-2 bg-blue-500 text-white">Update</button>
            </div>
        </div>
    </div>

    <div id="card" class="model-custom flex hidden">
        <div class="model-custom-inner">
            <div class="header">
                <h2 class="text-xl font-bold">Finalize Sale</h2>
                <button class="modelClose">
                    <img src="{{ asset('media/icons/cross.svg') }}" alt="">
                </button>
            </div>
            <div class="popupBody">
                <div>
                    <label for="card_number" class="block">Card Number</label>
                    <input type="text" name="card_number" placeholder="Enter Card Number"
                        class="w-full px-3 py-2 border-b outline-none bg-secondary_2">
                </div>
                <div>
                    <label for="card_holder_name" class="block">Card Holder Name</label>
                    <input type="text" name="card_holder_name" placeholder="Enter Card Holder Name"
                        class="w-full px-3 py-2 border-b outline-none bg-secondary_2">
                </div>
                <div>
                    <label for="card_type" class="block">Card Type</label>
                    <select name="card_type" class="w-full px-3 py-2 border-b outline-none bg-secondary_2">
                        <option value="Visa">Visa</option>
                        <option value="MasterCard">Master Card</option>
                    </select>
                </div>
                <div>
                    <label for="payment_receiver" class="block">Payment Receiver</label>
                    <input type="text" name="payment_receiver" placeholder="Enter Payment Receiver Name"
                        class="w-full px-3 py-2 border-b outline-none bg-secondary_2">
                </div>
                <div class="col-span-2">
                    <label for="payment_note" class="block">Payment Note</label>
                    <textarea type="text" name="payment_note" placeholder="Enter Payment Receiver Name"
                        class="w-full px-3 py-2 border-b outline-none bg-secondary_2"></textarea>
                </div>
                <div>
                    <label for="sale_note" class="block">Sale Note</label>
                    <textarea type="text" name="sale_note" placeholder="Enter Sale Note"
                        class="w-full px-3 py-2 border-b outline-none bg-secondary_2"></textarea>
                </div>
                <div>
                    <label for="staff_note" class="block">Staff Note</label>
                    <textarea type="text" name="staff_note" placeholder="Enter Staff Note"
                        class="w-full px-3 py-2 border-b outline-none bg-secondary_2"></textarea>
                </div>
                <div class="col-span-2 font-semibold bg-secondary p-3 grid md:grid-cols-3 md:gap-10 gap-5">
                    <div>
                        <h2 class="text-lg text-white/80">Total Payable:</h2>
                        <p class="text-4xl text-white">0.00 </p>
                    </div>
                    <div>
                        <h2 class="text-lg text-white/80">Total Paying:</h2>
                        <p class="text-4xl text-white">0.00 </p>
                    </div>
                    <div>
                        <h2 class="text-lg text-white/80">Change:</h2>
                        <p class="text-4xl text-white">0.00 </p>
                    </div>
                </div>
            </div>
            <div class="popupFooter space-x-2">
                <button class="px-4 py-2 bg-red-600 text-white modelClose">Close</button>
                <button class="px-4 py-2 bg-primary text-white">Finalize Sale</button>
            </div>
        </div>
    </div>

    <div id="cash" class="model-custom flex hidden">
        <div class="model-custom-inner">
            <div class="header">
                <h2 class="text-xl font-bold">Finalize Sale</h2>
                <button class="modelClose">
                    <img src="{{ asset('media/icons/cross.svg') }}" alt="">
                </button>
            </div>
            <div class="popupBody">
                <div>
                    <label for="cash_received" class="block">Cash Received </label>
                    <input type="number" name="cash_received" placeholder="Enter Cash Received "
                        class="w-full px-3 py-2 border-b outline-none bg-secondary_2">
                </div>
                <div>
                    <label for="payment_receiver" class="block">Payment Receiver</label>
                    <input type="text" name="payment_receiver" placeholder="Enter Payment Receiver Name"
                        class="w-full px-3 py-2 border-b outline-none bg-secondary_2">
                </div>
                <div class="col-span-2">
                    <label for="payment_note" class="block">Payment Note</label>
                    <textarea type="text" name="payment_note" placeholder="Enter Payment Receiver Name"
                        class="w-full px-3 py-2 border-b outline-none bg-secondary_2"></textarea>
                </div>
                <div>
                    <label for="sale_note" class="block">Sale Note</label>
                    <textarea type="text" name="sale_note" placeholder="Enter Sale Note"
                        class="w-full px-3 py-2 border-b outline-none bg-secondary_2"></textarea>
                </div>
                <div>
                    <label for="staff_note" class="block">Staff Note</label>
                    <textarea type="text" name="staff_note" placeholder="Enter Staff Note"
                        class="w-full px-3 py-2 border-b outline-none bg-secondary_2"></textarea>
                </div>
                <div class="col-span-2 font-semibold bg-secondary p-3 grid md:grid-cols-3 md:gap-10 gap-5">
                    <div>
                        <h2 class="text-lg text-white/80">Total Payable:</h2>
                        <p class="text-4xl text-white">0.00 </p>
                    </div>
                    <div>
                        <h2 class="text-lg text-white/80">Total Paying:</h2>
                        <p class="text-4xl text-white">0.00 </p>
                    </div>
                    <div>
                        <h2 class="text-lg text-white/80">Change:</h2>
                        <p class="text-4xl text-white">0.00 </p>
                    </div>
                </div>
            </div>
            <div class="popupFooter space-x-2">
                <button class="px-4 py-2 bg-red-600 text-white modelClose">Close</button>
                <button class="px-4 py-2 bg-primary text-white">Finalize Sale</button>
            </div>
        </div>
    </div>

    <div id="deposit" class="model-custom flex hidden">
        <div class="model-custom-inner">
            <div class="header">
                <h2 class="text-xl font-bold">Finalize Sale</h2>
                <button class="modelClose">
                    <img src="{{ asset('media/icons/cross.svg') }}" alt="">
                </button>
            </div>
            <div class="popupBody">
                <div class="col-span-2">
                    <label for="payment_receiver" class="block">Payment Receiver</label>
                    <input type="text" name="payment_receiver" placeholder="Enter Payment Receiver Name"
                        class="w-full px-3 py-2 border-b outline-none bg-secondary_2">
                </div>
                <div class="col-span-2">
                    <label for="payment_note" class="block">Payment Note</label>
                    <textarea type="text" name="payment_note" placeholder="Enter Payment Receiver Name"
                        class="w-full px-3 py-2 border-b outline-none bg-secondary_2"></textarea>
                </div>
                <div>
                    <label for="sale_note" class="block">Sale Note</label>
                    <textarea type="text" name="sale_note" placeholder="Enter Sale Note"
                        class="w-full px-3 py-2 border-b outline-none bg-secondary_2"></textarea>
                </div>
                <div>
                    <label for="staff_note" class="block">Staff Note</label>
                    <textarea type="text" name="staff_note" placeholder="Enter Staff Note"
                        class="w-full px-3 py-2 border-b outline-none bg-secondary_2"></textarea>
                </div>
                <div class="col-span-2 font-semibold bg-secondary p-3 grid md:grid-cols-3 md:gap-10 gap-5">
                    <div>
                        <h2 class="text-lg text-white/80">Total Payable:</h2>
                        <p class="text-4xl text-white">0.00 </p>
                    </div>
                    <div>
                        <h2 class="text-lg text-white/80">Total Paying:</h2>
                        <p class="text-4xl text-white">0.00 </p>
                    </div>
                    <div>
                        <h2 class="text-lg text-white/80">Change:</h2>
                        <p class="text-4xl text-white">0.00 </p>
                    </div>
                </div>
            </div>
            <div class="popupFooter space-x-2">
                <button class="px-4 py-2 bg-red-600 text-white modelClose">Close</button>
                <button class="px-4 py-2 bg-primary text-white">Submit</button>
            </div>
        </div>
    </div>
    {{-- -------------------- Modals -------------------- --}}
@endsection
@push('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelector("#tbody-id").addEventListener("click", function(event) {

                const target = event.target;
                const row = target.closest("tr");

                if (!row) return;

                let qtyElem = row.querySelector(".qty");
                let priceElem = row.querySelector(".price");
                let subtotalElem = row.querySelector(".subtotal");

                let price = parseFloat(priceElem.innerText.replace("$", ""));
                let qty = parseInt(qtyElem.innerText);

                if (target.classList.contains("increase")) {
                    qty++;
                }

                // Decrease quantity, but not below 1
                if (target.classList.contains("decrease") && qty > 1) {
                    qty--;
                }

                // Update quantity and subtotal
                qtyElem.innerText = qty;
                subtotalElem.innerText = `$${(qty * price).toFixed(2)}`;

                // Remove row when delete button is clicked
                if (target.closest(".delete-row")) {
                    row.remove();
                }
            });

            document.getElementById("fullscreenButton").addEventListener("click", function() {
                let icon = document.getElementById("fullscreenIcon");

                if (!document.fullscreenElement) {
                    document.documentElement.requestFullscreen();
                    icon.src = "{{ asset('media/icons/fullscreen_exit.svg') }}"; // Change to exit icon
                } else {
                    document.exitFullscreen();
                    icon.src =
                        "{{ asset('media/icons/fullscreen.svg') }}"; // Change back to fullscreen icon
                }
            });

            // Ensure the icon updates when exiting full-screen with the ESC key
            document.addEventListener("fullscreenchange", function() {
                let icon = document.getElementById("fullscreenIcon");
                if (!document.fullscreenElement) {
                    icon.src = "{{ asset('media/icons/fullscreen.svg') }}"; // Reset to fullscreen icon
                }
            });
        });
    </script>
@endpush
