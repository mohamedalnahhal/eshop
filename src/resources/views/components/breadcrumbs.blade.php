@props(['links'])

<nav class="container flex text-muted text-sm font-medium mb-6" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-2 space-x-reverse">
        
        <li class="inline-flex items-center">
            <a href="{{ route('shop.index') }}" class="inline-flex items-center gap-1 hover:text-primary transition">
                @icon('home', 'w-4 h-4')
                الرئيسية
            </a>
        </li>

        @foreach($links as $label => $url)
            <li>
                <div class="flex items-center">
                    <span class="mx-2 text-muted">/</span>
                    
                    @if($loop->last || !$url)
                        <span class="text-theme font-bold" aria-current="page">
                            {{ $label }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="hover:text-primary transition">
                            {{ $label }}
                        </a>
                    @endif
                </div>
            </li>
        @endforeach
        
    </ol>
</nav>