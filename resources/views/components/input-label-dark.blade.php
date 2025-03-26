@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm tei-text']) }}>
    {{ $value ?? $slot }}
</label>
