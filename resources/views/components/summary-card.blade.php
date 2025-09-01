@props(['label', 'value', 'color' => 'green'])

<div class="bg-{{ $color }}-50 p-4 rounded-xl flex items-center shadow-sm">
    <div class="flex-shrink-0">
        <div class="w-3 h-3 bg-{{ $color }}-600 rounded-full"></div>
    </div>
    <div class="ml-3">
        <p class="text-sm font-medium text-{{ $color }}-900">{{ $label }}</p>
        <p class="text-2xl font-bold text-{{ $color }}-600">{{ $value }}</p>
    </div>
</div>
