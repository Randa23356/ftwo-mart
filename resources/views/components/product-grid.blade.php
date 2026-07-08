@props([
    'products',
    'gap' => 'gap-4 sm:gap-6'
])

<div class="grid grid-cols-2 sm:grid-cols-[repeat(auto-fit,minmax(220px,1fr))] {{ $gap }}">
    @php
        $total = count($products);
        $isOddOnMobile = $total % 2 !== 0;
    @endphp

    @foreach($products as $index => $product)
        @php
            $isLastItem = $index === $total - 1;
            // Apply stretch class only on mobile if the total count is odd and it's the last item
            $stretchClass = ($isOddOnMobile && $isLastItem) ? 'col-span-2 sm:col-span-1' : '';
        @endphp
        <div class="{{ $stretchClass }}">
            <x-product-card :product="$product" />
        </div>
    @endforeach
</div>

@php
    $isPaginator = method_exists($products, 'hasPages');
@endphp

@if($isPaginator && $products->hasPages())
    <div class="mt-8">
        {{ $products->links() }}
    </div>
@endif

