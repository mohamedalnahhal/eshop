<div class="flex items-center gap-2 group">
    <img src="{{ asset('images/logo.svg') }}" alt="eShop" class="h-8 w-auto">
    @guest
    <span class="text-xl font-semibold text-slate-900">eShop</span>
    @else
    <span class="text-xl font-semibold text-slate-900">eShop Dashboard</span>
    <span class="inline-flex items-center gap-1.5 ms-2 px-2 py-1 rounded-lg text-sm font-semibold bg-slate-200 text-slate-600">System Admin</span>
    @endguest
</div>