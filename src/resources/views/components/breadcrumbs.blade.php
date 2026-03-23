@props(['links'])

<nav class="container flex text-gray-500 text-sm font-medium mb-6" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-2 space-x-reverse">
        
        <li class="inline-flex items-center">
            <a href="{{ route('shop.index') }}" class="inline-flex items-center hover:text-blue-600 transition">
                الرئيسية
            </a>
        </li>

        @foreach($links as $label => $url)
            <li>
                <div class="flex items-center">
                    <span class="mx-2 text-gray-400">/</span>
                    
                    @if($loop->last || !$url)
                        <span class="text-gray-900 font-bold" aria-current="page">
                            {{ $label }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="hover:text-blue-600 transition">
                            {{ $label }}
                        </a>
                    @endif
                </div>
            </li>
        @endforeach
        
    </ol>
</nav>