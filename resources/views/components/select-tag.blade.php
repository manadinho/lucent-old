<select id="{{ $attributes['name'] }}" name="{{ $attributes['name'] }}" {{ $attributes->merge(['class' => 'form-select mt-1 block w-full']) }}>
    {{ $slot }}
</select>
