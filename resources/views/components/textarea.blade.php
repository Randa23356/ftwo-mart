@props([
    'label' => null,
    'error' => null,
    'helpText' => null,
    'rows' => 3
])

<div>
    @if($label)
        <label for="{{ $attributes->get('id') }}" class="block text-sm font-medium text-gray-700 mb-2">
            {{ $label }}
            @if($attributes->has('required'))
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <textarea 
        rows="{{ $rows }}"
        {{ $attributes->merge([
            'class' => 'w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors resize-none ' . 
                       ($error ? 'border-red-300 focus:ring-red-500' : 'border-gray-300')
        ]) }}
    >{{ $slot }}</textarea>

    @if($error)
        <p class="mt-1 text-sm text-red-600">
            <i class="fas fa-exclamation-circle mr-1"></i>
            {{ $error }}
        </p>
    @endif

    @if($helpText)
        <p class="mt-1 text-sm text-gray-500">
            {{ $helpText }}
        </p>
    @endif
</div>
