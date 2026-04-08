@props([
    'boundMin'  => 0,
    'boundMax'  => 1000,
    'step'      => 1,
    'fromModel' => null,
    'toModel'   => null,
    'unit'      => '',
    'fromValue' => null,
    'toValue'   => null,
])

<div
    x-data="{
        min: {{ $fromValue ?? $boundMin }},
        max: {{ $toValue ?? $boundMax }},
        boundMin: {{ $boundMin }},
        boundMax: {{ $boundMax }},
        step: {{ $step }},
        dragging: null,

        get minPercent() {
            return ((this.min - this.boundMin) / (this.boundMax - this.boundMin)) * 100;
        },
        get maxPercent() {
            return ((this.max - this.boundMin) / (this.boundMax - this.boundMin)) * 100;
        },

        snap(val) {
            return Math.round(val / this.step) * this.step;
        },

        startDrag(thumb, e) {
            this.dragging = thumb;
            e.preventDefault();
        },

        onDrag(e) {
            if (!this.dragging) return;
            const track = this.$refs.track;
            const rect  = track.getBoundingClientRect();
            const clientX = e.touches ? e.touches[0].clientX : e.clientX;

            // LTR: left = min, right = max
            let ratio = (clientX - rect.left) / rect.width;
            ratio = Math.max(0, Math.min(1, ratio));
            let val = this.snap(this.boundMin + ratio * (this.boundMax - this.boundMin));

            if (this.dragging === 'min') {
                val = Math.min(val, this.max - this.step);
                val = Math.max(val, this.boundMin);
                this.min = val;
            } else {
                val = Math.max(val, this.min + this.step);
                val = Math.min(val, this.boundMax);
                this.max = val;
            }
        },

        stopDrag() {
            if (!this.dragging) return;
            @if($fromModel) $wire.set('{{ $fromModel }}', this.min); @endif
            @if($toModel)   $wire.set('{{ $toModel }}',   this.max); @endif
            this.dragging = null;
        },

        setMin(val) {
            val = this.snap(Math.max(this.boundMin, Math.min(Number(val), this.max - this.step)));
            this.min = val;
            @if($fromModel) $wire.set('{{ $fromModel }}', val); @endif
        },
        setMax(val) {
            val = this.snap(Math.min(this.boundMax, Math.max(Number(val), this.min + this.step)));
            this.max = val;
            @if($toModel) $wire.set('{{ $toModel }}', val); @endif
        },
    }"
    @mousemove.window="onDrag($event)"
    @mouseup.window="stopDrag()"
    @touchend.window="stopDrag()"
    x-init="
        $el._touchMove = (e) => { if (dragging) { e.preventDefault(); onDrag(e); } };
        window.addEventListener('touchmove', $el._touchMove, { passive: false });
        $el._cleanup = () => window.removeEventListener('touchmove', $el._touchMove);
    "
    @destroy="$el._cleanup()"
    class="space-y-4 select-none"
    dir="ltr"
>
    <div class="flex items-center justify-between text-theme-sm font-semibold text-muted" dir="rtl">
        <span x-text="max + ' {{ $unit }}'"></span>
        <span x-text="min + ' {{ $unit }}'"></span>
    </div>

    <div class="relative flex items-center h-5" x-ref="track">

        <div class="absolute inset-x-0 h-2 rounded-full bg-surface-200"></div>

        <div
            class="absolute h-2 rounded-full bg-primary pointer-events-none"
            :style="`left: ${minPercent}%; right: ${100 - maxPercent}%`"
        ></div>

        <div
            class="absolute w-5 h-5 bg-bg border-2 border-primary rounded-full shadow-input cursor-grab active:cursor-grabbing transition-transform hover:scale-110 active:scale-125"
            :style="`left: calc(${minPercent}% - 10px)`"
            :class="dragging === 'min' ? 'scale-125 border-primary/70' : ''"
            @mousedown="startDrag('min', $event)"
            @touchstart.prevent="startDrag('min', $event)"
        ></div>

        <div
            class="absolute w-5 h-5 bg-bg border-2 border-primary rounded-full shadow-input cursor-grab active:cursor-grabbing transition-transform hover:scale-110 active:scale-125"
            :style="`left: calc(${maxPercent}% - 10px)`"
            :class="dragging === 'max' ? 'scale-125 border-primary/70' : ''"
            @mousedown="startDrag('max', $event)"
            @touchstart.prevent="startDrag('max', $event)"
        ></div>
    </div>
</div>