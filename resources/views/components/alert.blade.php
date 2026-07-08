@props(['type' => 'info', 'dismissible' => true, 'title' => null, 'actions' => []])

@php
$baseClasses = 'relative px-4 py-3 rounded-lg border shadow-sm transition-all duration-300 ease-in-out transform';
$typeConfig = [
    'info' => [
        'bg' => 'bg-blue-50',
        'border' => 'border-blue-200',
        'text' => 'text-blue-800',
        'icon' => 'fas fa-info-circle text-blue-500',
        'progress' => 'bg-blue-500'
    ],
    'success' => [
        'bg' => 'bg-green-50',
        'border' => 'border-green-200',
        'text' => 'text-green-800',
        'icon' => 'fas fa-check-circle text-green-500',
        'progress' => 'bg-green-500'
    ],
    'warning' => [
        'bg' => 'bg-yellow-50',
        'border' => 'border-yellow-200',
        'text' => 'text-yellow-800',
        'icon' => 'fas fa-exclamation-triangle text-yellow-500',
        'progress' => 'bg-yellow-500'
    ],
    'error' => [
        'bg' => 'bg-red-50',
        'border' => 'border-red-200',
        'text' => 'text-red-800',
        'icon' => 'fas fa-exclamation-circle text-red-500',
        'progress' => 'bg-red-500'
    ],
];

$config = $typeConfig[$type] ?? $typeConfig['info'];
$classes = "{$baseClasses} {$config['bg']} {$config['border']} {$config['text']}";
@endphp

<div class="{{ $classes }}"
     role="alert"
     x-data="{
        show: true,
        mounted: false,
        progress: 100
     }"
     x-show="show"
     x-init="
        mounted = true;
        if ({{ $dismissible ? 'true' : 'false' }}) {
            let duration = 5000;
            let interval = 50;
            let step = 100 / (duration / interval);

            let timer = setInterval(() => {
                progress -= step;
                if (progress <= 0) {
                    clearInterval(timer);
                    show = false;
                }
            }, interval);
        }
     "
     x-transition:enter="transition ease-out duration-300 transform"
     x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
     x-transition:leave="transition ease-in duration-200 transform"
     x-transition:leave-start="opacity-100 scale-100"
     x-transition:leave-end="opacity-0 scale-95">

    <div class="flex items-start">
        <div class="flex-shrink-0">
            <i class="{{ $config['icon'] }} text-lg"></i>
        </div>

        <div class="ml-3 flex-1 min-w-0">
            @if($title)
                <h4 class="font-semibold text-sm mb-1">{{ $title }}</h4>
            @endif

            <div class="text-sm">
                {{ $slot }}
            </div>

            @if(count($actions) > 0)
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach($actions as $action)
                        <button type="button"
                                class="text-xs px-3 py-1 rounded-full font-medium transition-all duration-200 hover:scale-105 {{ $action['class'] ?? 'bg-white bg-opacity-50 hover:bg-opacity-75' }}"
                                @if(isset($action['onclick'])) onclick="{{ $action['onclick'] }}" @endif>
                            @if(isset($action['icon']))
                                <i class="{{ $action['icon'] }} mr-1"></i>
                            @endif
                            {{ $action['text'] }}
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        @if($dismissible)
            <button type="button"
                    @click="show = false"
                    class="ml-4 inline-flex text-gray-400 hover:text-gray-600 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-{{ $type === 'error' ? 'red' : ($type === 'success' ? 'green' : ($type === 'warning' ? 'yellow' : 'blue')) }}-500 rounded-full p-1">
                <span class="sr-only">Tutup</span>
                <i class="fas fa-times text-sm"></i>
            </button>
        @endif
    </div>

    <!-- Progress bar -->
    @if($dismissible)
        <div class="absolute bottom-0 left-0 right-0 h-1 bg-black bg-opacity-10 rounded-b-lg overflow-hidden">
            <div class="h-full {{ $config['progress'] }} transition-all ease-linear"
                 x-bind:style="`width: ${progress}%`"></div>
        </div>
    @endif
</div>
