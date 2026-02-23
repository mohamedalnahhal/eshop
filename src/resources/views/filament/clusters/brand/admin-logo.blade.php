@guest
    <div class="flex flex-col items-center justify-center gap-2">
        <div class="flex flex-row gap-2 items-center justify-center">
            <img src="{{ asset('images/logo.svg') }}" alt="Login Logo" class="h-8">
            <span class="text-primary-900 dark:text-primary-400">eShop</span>
            <!-- <span class="py-0.5 px-2 font-semibold text-sm text-primary-900 dark:text-primary-200 bg-primary-200 dark:bg-primary-900 rounded-lg">System Admin</span> -->
        </div>
        <span class="py-0.5 px-2 font-semibold text-sm text-primary-900 dark:text-primary-200 bg-primary-200 dark:bg-primary-900 rounded-lg">Super Admin Portal</span>
    </div>

@else
    <div class="flex flex-row items-center justify-center gap-2 text-primary-900">
        <img class="h-8" src="{{ asset('images/logo.svg') }}" alt="eShop">
        <span class="dark:text-primary-400">eShop</span>
        <span class="py-0.5 px-2 font-semibold text-sm dark:text-primary-200 bg-primary-200 dark:bg-primary-900 rounded-lg">System Admin</span>
    </div>
@endguest