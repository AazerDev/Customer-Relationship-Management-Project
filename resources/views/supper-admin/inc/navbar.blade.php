<div class="sidebar fixed left-0 top-[99px] h-full w-20 bg-black px-3.5 py-2 transition-all duration-500 ease-in-out shadow-[5px_-1px_10px_0_rgba(0,0,0,0.2)]">
    <div class="logo-details h-10 flex items-center relative border-b mb-3 border-b-gray-700">
        <div class="font-bold text-white uppercase logo_name">WELCOME !</div>
        <div class=""><img id="btn" width="24" src="{{ asset('media/icons/menu.svg') }}" alt="Menu Button">
        </div>
    </div>
    <ul class="nav-list">
        <li class="relative mb-[15px]">
            <a href="{{ route('dashboard') }}"
                class="tab flex items-center gap-3.5 pl-3.5 h-[45px] w-full no-underline transition-all duration-400 ease-in-out {{ request()->is('dashboard') ? 'bg-primary' : 'hover:bg-primary_hover' }}">
                <img class="icons" width="24" src="{{ asset('media/icons/dashboard.svg') }}" alt="dashboard">
                <span
                    class="links_name text-white text-[15px] font-bold whitespace-nowrap opacity-0 pointer-events-none transition-opacity duration-400">Dashboard</span>
            </a>
            <span class="tooltip">Dashboard</span>
        </li>
        <li class="relative my-[15px]">
            <a href="#"
                class="flex justify-between items-center mb-1  h-[45px] w-full no-underline transition-all duration-400 ease-in-out {{ request()->is('users/*') ? 'bg-primary' : 'hover:bg-primary_hover' }} dropdown-toggle"
                data-target="invertoryDropdown">
                <div class="tab flex items-center gap-3.5 pl-3.5">
                    <img class="icons" width="24" sizes=""
                        src="{{ asset('media/icons/users.svg') }}" alt="inventory">
                    <span
                        class="links_name text-white text-[15px] font-bold whitespace-nowrap opacity-0 pointer-events-none transition-opacity duration-400">Users</span>
                </div>
                <div class="down-arrow">
                    <img class="" src="{{ asset('media/icons/down.svg') }}" alt="">
                </div>
            </a>
            <span class="tooltip">Users</span>
            <div class="w-full space-y-1 dropdown" id="invertoryDropdown">
                <a href="{{ route('user.list') }}" class="dropdown-item block text-sm {{ request()->is('/users/list/') ? 'bg-[#3387cca9] font-black' : 'hover:bg-primary_hover' }}">
                    - Users list
                </a>
            </div>
        </li>

        <li class="relative my-[15px]">
            <a href="/pos" class="tab flex items-center gap-3.5 pl-3.5 h-[45px] w-full no-underline transition-all duration-400 ease-in-out {{ request()->is('/pos') ? 'bg-primary' : 'hover:bg-primary_hover' }}">
                <img class="icons" width="24" src="{{ asset('media/icons/pos-dark.svg') }}" alt="pos">
                <span class="links_name text-white text-[15px] font-bold whitespace-nowrap opacity-0 pointer-events-none transition-opacity duration-400">POS</span>
            </a>
            <span class="tooltip">POS</span>
        </li>

    </ul>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
    integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $(document).ready(function() {
        const $sidebar = $(".sidebar");
        const $closeBtn = $("#btn");
        const $navList = $(".nav-list");
        const $tab = $(".tab");

        $(".dropdown").css("max-height", "0").css("opacity", "0").removeClass("open");

        $closeBtn.on("click", function() {
            console.log("clicked")
            $sidebar.toggleClass("open");
            $navList.toggleClass("scroll");
            menuBtnChange();

            if (!$sidebar.hasClass("open")) {
                $(".dropdown").removeClass("open").css("max-height", "0").css("opacity", "0");
                $(".down-arrow").removeClass("rotate");
            }
        });

        $navList.on("click", ".dropdown-toggle", function(e) {
            e.preventDefault();
            const $toggle = $(this);
            const dropdownId = $toggle.data("target");
            const $dropdown = $("#" + dropdownId);
            const $downArrow = $toggle.find(".down-arrow");

            if (!$sidebar.hasClass("open")) {
                $sidebar.addClass("open");
                $navList.addClass("scroll");
                menuBtnChange();

                setTimeout(() => {
                    toggleDropdown($dropdown, $downArrow);
                }, 300);
            } else {
                $(".dropdown").not($dropdown).each(function() {
                    $(this).removeClass("open").css("max-height", "0").css("opacity", "0");
                    $(this).siblings(".dropdown-toggle").find(".down-arrow").removeClass(
                        "rotate");
                });
                toggleDropdown($dropdown, $downArrow);
            }
        });

        function toggleDropdown($dropdown, $downArrow) {
            $dropdown.toggleClass("open");

            if ($dropdown.hasClass("open")) {
                $dropdown.css({
                    "max-height": $dropdown.prop("scrollHeight") + "px",
                    "opacity": "1"
                });
                $downArrow.addClass("rotate");
            } else {
                $dropdown.css({
                    "max-height": "0",
                    "opacity": "0"
                });
                $downArrow.removeClass("rotate");
            }
        }

        function menuBtnChange() {
            if ($sidebar.hasClass("open")) {
                $closeBtn.attr("src", "{{ asset('media/icons/menu-close.svg') }}");
                $sidebar.removeClass("px-3.5").addClass("px-[0.875rem] ")
                $tab.removeClass("pl-3.5").addClass("pl-3.5")
            } else {
                $closeBtn.attr("src", "{{ asset('media/icons/menu.svg') }}");
                $sidebar.removeClass("px-[0.875rem]").addClass("px-3.5 close")
                $tab.removeClass("pl-3.5").addClass("pl-3.5")
            }
        }
    });
</script>
