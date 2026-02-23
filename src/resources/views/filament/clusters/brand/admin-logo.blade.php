<div class="flex flex-row items-center justify-center gap-2 text-primary-900">
    <img class="h-8" src="{{ asset('images/logo.svg') }}" alt="eShop">
    <span>eShop</span>
    @guest
    @else
    <span class="py-0.5 px-2 font-semibold text-sm bg-primary-200 rounded-lg">System Admin</span>
    @endguest
</div>