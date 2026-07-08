@props([
    'min' => 1,
    'max' => 999,
    'value' => 1,
    'name' => 'quantity',
    'id' => null,
    'class' => ''
])

<div class="flex items-center border border-gray-300 rounded-lg {{ $class }}" x-data="{ quantity: {{ $value }} }">
    <button type="button" 
            @click="quantity = Math.max({{ $min }}, quantity - 1)" 
            class="px-3 py-1 text-gray-600 hover:bg-gray-100 transition-colors">
        <i class="fas fa-minus text-xs"></i>
    </button>
    
    <input type="number" 
           x-model="quantity" 
           min="{{ $min }}" 
           max="{{ $max }}"
           name="{{ $name }}"
           @if($id) id="{{ $id }}" @endif
           class="w-16 text-center border-0 focus:ring-0 text-sm">
    
    <button type="button" 
            @click="quantity = Math.min({{ $max }}, quantity + 1)" 
            class="px-3 py-1 text-gray-600 hover:bg-gray-100 transition-colors">
        <i class="fas fa-plus text-xs"></i>
    </button>
</div>
