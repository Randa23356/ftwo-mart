@props(['order', 'variant' => 'button'])

@if($order->tracking_number && $order->tracking_url)
    @if($variant === 'link')
        <a href="{{ $order->tracking_url }}"
           target="_blank"
           rel="noopener noreferrer"
           class="inline-flex items-center text-sm font-bold text-blue-600 hover:underline">
            <i class="fas fa-search-location mr-1.5"></i>
            {{ $order->tracking_button_label }}
            <i class="fas fa-external-link-alt ml-1.5 text-xs"></i>
        </a>
    @else
        <a href="{{ $order->tracking_url }}"
           target="_blank"
           rel="noopener noreferrer"
           {{ $attributes->merge(['class' => 'inline-flex items-center justify-center mt-4 px-4 py-2.5 bg-teal-600 text-white text-sm font-bold rounded-lg hover:bg-teal-700 shadow-md transition-all']) }}>
            <i class="fas fa-search-location mr-2"></i>
            {{ $order->tracking_button_label }}
            <i class="fas fa-external-link-alt ml-2 text-xs opacity-80"></i>
        </a>
    @endif
@endif
