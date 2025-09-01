@props(['label', 'value', 'color' => 'blue'])

<div class="bg-gray-50 p-4 rounded-xl flex flex-col items-start shadow-sm">
    <h3 class="text-sm font-medium text-gray-500">{{ $label }}</h3>
    <p class="text-3xl font-bold text-{{ $color }}-600">{{ $value }}</p>
</div>
