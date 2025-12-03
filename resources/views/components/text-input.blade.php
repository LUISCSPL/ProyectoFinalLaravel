@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary rounded-lg shadow-sm transition ease-in-out duration-150']) }}>