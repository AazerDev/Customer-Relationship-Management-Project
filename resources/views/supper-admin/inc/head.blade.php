<div class="text-white bg-black sm:px-6 px-2 w-full right-0 top-0 mt-0  fixed shadow-lg z-50">
    <div class="flex items-center justify-between p-4 h-full">
        <div class="flex items-center gap-20">
            <img src="{{ asset('media/icons/Snow_Berry_Logos_white.svg') }}" width="150" alt="logo" />
        </div>
        <div class="relative flex gap-3 items-center p-3.5">
            <button id="dropdownToggle" class=""><img class="h-10"
                    src="{{ asset('media/icons/profile_icon.svg') }}" /></button>
            <div class="absolute w-32 right-8 z-50 bg-gray-700 shadow-2xl dropdown-content top-14"
                id="dropdownContent" style="display: none">
                    <a href="/profile"
                        class="block p-3 bg-gray-700 hover:bg-[#3362CC] hover:text-white text-[15px]">View Profile</a>
                <a href="{{ route('logout') }}"
                    class="block p-3 bg-gray-700 hover:bg-[#3362CC] hover:text-white text-[15px]">Logout</a>
            </div>
        </div>
    </div>
</div>

@include('supper-admin.inc.navbar')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var dropdownToggle = document.getElementById('dropdownToggle');
        var dropdownContent = document.getElementById('dropdownContent');
        dropdownToggle.addEventListener('click', function(event) {
            event.preventDefault();
            dropdownContent.style.display = (dropdownContent.style.display === 'block') ? 'none' :
                'block';
        });
        document.addEventListener('click', function(event) {
            if (!dropdownToggle.contains(event.target) && !dropdownContent.contains(event.target)) {
                dropdownContent.style.display = 'none';
            }
        });
    });
</script>
