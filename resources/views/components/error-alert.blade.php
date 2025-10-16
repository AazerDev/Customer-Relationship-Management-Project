@if(session('success'))
<div class="p-4 mb-4 text-sm text-blue text-blue-800 bg-sky-100" role="alert"> {{ session('success') }} </div>
@elseif (session('error'))
<div class="p-4 mb-4 text-sm text-danger text-blue-800 bg-red-100" role="alert"> {{ session('error') }} </div>
@endif