@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 rounded-full shadow-sm focus:ring-orange-700 max-h-7 focus:border-orange-700']) !!}>
