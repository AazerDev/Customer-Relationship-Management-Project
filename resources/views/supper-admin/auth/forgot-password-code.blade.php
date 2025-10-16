@extends('supper-admin')
@php
    $page = 'Enter Code';
    $meta_title = 'Enter Code - BRJ';
    $meta_description = 'Enter Code - BRJ';
@endphp


@push('style')
@endpush

@section('content')

    <body class="bg-white">
        <div class="flex h-screen bg-white">
            <div class="m-auto lg:container">
                <div class="relative grid gap-6 px-5 md:grid-cols-2 lg:gap-10 lg:px-10">
                    <div class="relative hidden md:flex">
                        <div class="absolute bg-[#63FFEF] top-5 left-2 w-[60%] h-[230px] rounded-br-[150%]"></div>
                        <div class="px-10 pt-5 border border-white backdrop-blur-lg bg-white/40">
                            <h6 class="text-base font-bold text-mid-night-blue lg:text-lg lg:mb-10">Usage Tip</h6>
                            <h4 class="mb-3 text-5xl font-light leading-snug text-mid-night-blue lg:mb-10">SBS - Auth Ready
                                KIT </h4>
                            <p class="text-[#2B6CEC]">lorem ipsum dummy text — <br> <span> anytime,
                                    anywhere!</span></p>
                            <img src="{{ asset('media/images/login.png') }}" width="100%" alt="">
                        </div>

                    </div>
                    <div class="xl:w-[60%] lg:w-fit-content w-[] flex flex-col justify-between items-center m-auto h-full">
                        <div class="flex flex-col w-full pt-20 space-y-10">
                            <img class="w-[250px]" src="{{ asset('media/icons/logo-b.svg') }}" alt="">

                            <div>
                                <h2 class="text-2xl text-secondary">Verify Email!</h2>
                                <p class="py-3 text-sm text-gray-500">Please enter the code we send on your registered <br>
                                    email s******@gmail.com</p>
                            </div>

                            <form class="">
                                <div class="flex space-x-7">
                                    <input type="text" maxlength="1"
                                        class="xl:w-full xl:h-[60px] lg:w-8 lg:h-8 md:w-6 md:h-6 text-4xl bg-[#CFE9FF] text-center focus:outline-dashed outline-sky-600">
                                    <input type="text" maxlength="1" disabled
                                        class="xl:w-full xl:h-[60px] lg:w-8 lg:h-8 md:w-6 md:h-6 text-4xl bg-[#CFE9FF] text-center focus:outline-dashed">
                                    <input type="text" maxlength="1" disabled
                                        class="xl:w-full xl:h-[60px] lg:w-8 lg:h-8 md:w-6 md:h-6 text-4xl bg-[#CFE9FF] text-center focus:outline-dashed">
                                    <input type="text" maxlength="1" disabled
                                        class="xl:w-full xl:h-[60px] lg:w-8 lg:h-8 md:w-6 md:h-6 text-4xl bg-[#CFE9FF] text-center focus:outline-dashed">
                                    <input type="text" maxlength="1" disabled
                                        class="xl:w-full xl:h-[60px] lg:w-8 lg:h-8 md:w-6 md:h-6 text-4xl bg-[#CFE9FF] text-center focus:outline-dashed">
                                    <input type="text" maxlength="1" disabled
                                        class="xl:w-full xl:h-[60px] lg:w-8 lg:h-8 md:w-6 md:h-6 text-4xl bg-[#CFE9FF] text-center focus:outline-dashed">
                                </div>
                                <a href="{{ route('forgotpasswordSuccess') }}"
                                    class="flex justify-center items-center bg-primary w-full h-[50px] text-white text-lg mt-5">
                                    {{-- <div class="w-5 h-5 border-2 border-white rounded-full animate-spin border-l-transparent"></div> --}}
                                    Confirm
                                </a>
                            </form>
                        </div>
                        <div class="text-gray-500 text-[12px] font-light md:mt-0 mt-4">
                            {{-- <div class="flex items-center justify-between mb-4 space-x-7">
                            <p class="cursor-pointer hover:underline">Contact Support</p>
                            <p class="cursor-pointer hover:underline">Term of Use</p>
                            <p class="cursor-pointer hover:underline">Privacy Policy</p>
                        </div> --}}
                            <p class="italic">2025 BRJ. ALL RIGHTS RESERVED</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
@endsection

@push('script')
    <script>
        const inputs = document.querySelectorAll("input"),
            button = document.querySelector("button");

        // Function to distribute OTP digits when pasted
        inputs[0].addEventListener("paste", (e) => {
            const pasteData = e.clipboardData.getData("text");

            // Prevent default paste behavior
            e.preventDefault();

            // Ensure the pasted data length matches the number of input fields
            if (pasteData.length <= inputs.length) {
                // Distribute the pasted value to each input
                inputs.forEach((input, index) => {
                    input.value = pasteData[index] ||
                        ""; // If pasteData has no value for a field, keep it empty
                    input.removeAttribute("disabled");
                });

                // Focus the last input
                inputs[inputs.length - 1].focus();

                // Enable the button if all inputs are filled
                if (inputs[inputs.length - 1].value !== "") {
                    button.classList.remove("cursor-not-allowed");
                    button.classList.add("cursor-pointer", "bg-blue-600");
                    button.removeAttribute("disabled");
                }
            }
        });

        // Iterate over all inputs
        inputs.forEach((input, index1) => {
            input.addEventListener("keyup", (e) => {
                const currentInput = input,
                    nextInput = input.nextElementSibling,
                    prevInput = input.previousElementSibling;

                if (currentInput.value.length > 1) {
                    currentInput.value = "";
                    return;
                }

                // Enable next input if it's disabled and the current input has a value
                if (nextInput && nextInput.hasAttribute("disabled") && currentInput.value !== "") {
                    nextInput.removeAttribute("disabled");
                    nextInput.focus();
                }

                // Handle Backspace to reset and go back to previous input
                if (e.key === "Backspace") {
                    inputs.forEach((input, index2) => {
                        if (index1 <= index2 && prevInput) {
                            input.setAttribute("disabled", true);
                            input.value = "";
                            prevInput.focus();
                        }
                    });
                }

                // Enable the button when all inputs are filled
                if (!inputs[3].disabled && inputs[3].value !== "") {
                    button.classList.remove("cursor-not-allowed");
                    button.classList.add("cursor-pointer", "bg-blue-600");
                    button.removeAttribute("disabled");
                    return;
                }
                button.classList.add("cursor-not-allowed");
                button.classList.remove("cursor-pointer", "bg-blue-600");
                button.setAttribute("disabled", true);
            });
        });

        // Focus the first input on window load
        window.addEventListener("load", () => inputs[0].focus());
    </script>
@endpush
