@props(['avgRating', 'totalReviews', 'ratingCounts'])

<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 mb-6">
    <div class="flex items-start gap-5 flex-wrap">
        <div class="w-full flex flex-col items-start min-w-20">
            <h2 class="w-full text-2xl font-bold text-gray-900 mb-4 pb-4 border-b border-gray-200">التقييمات</h2>
            <x-rating-stars :rating="$avgRating" :reviewsCount="$totalReviews" class="mt-1"/>
            <span class="text-lg text-gray-500">{{ number_format($avgRating, 1) }} من 5.0</span>
        </div>
      
        <div class="flex-1 min-w-45 space-y-1.5">
            @foreach([5,4,3,2,1] as $star)
                @php $pct = $totalReviews ? round(($ratingCounts[$star] ?? 0) / $totalReviews * 100) : 0; @endphp
                <div class="flex items-center gap-2">
                    <span class="text-left shrink-0">{{ $star }} <span class="text-yellow-400">★</span></span>
                    <div class="flex-1 bg-gray-100 rounded-full h-4 overflow-hidden">
                        <div class="h-4 rounded-full bg-yellow-400 transition-all duration-500"
                             style="width: {{ $pct }}%"></div>
                    </div>
                    <span class="w-8 text-end text-gray-500 shrink-0">{{ $pct }}%</span>
                </div>
            @endforeach
        </div>
    </div>
</div>