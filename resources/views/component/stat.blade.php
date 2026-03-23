@props([
    'title' => '',
    'value' => '',
    'subtitle' => '',
    'color' => 'text-white'
])

<div class="bg-[#0F172A] border border-slate-800 rounded-xl p-5
            transition-all duration-300
            hover:-translate-y-1 hover:shadow-xl hover:shadow-cyan-500/10">

    <p class="text-sm text-slate-400">
        {{ $title }}
    </p>

    <h3 class="text-3xl font-bold mt-2 {{ $color }}">
        {{ $value }}
    </h3>

    @if($subtitle)
        <p class="text-xs text-slate-500 mt-1">
            {{ $subtitle }}
        </p>
    @endif
</div>
