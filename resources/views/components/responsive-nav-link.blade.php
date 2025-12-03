@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-primary text-start text-base font-medium text-white bg-secondary/80 focus:outline-none focus:text-white focus:bg-secondary transition duration-150 ease-in-out'
            
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-background hover:text-white hover:bg-primary-dark focus:outline-none focus:text-white focus:bg-primary-dark transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>