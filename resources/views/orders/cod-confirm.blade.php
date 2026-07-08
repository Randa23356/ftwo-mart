@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-1">Konfirmasi COD</h1>
        <p class="text-sm text-gray-600 mb-6">Order {{ $order->order_number }}</p>

        @if(session('success'))
            <x-alert type="success" :dismissible="true" title="Konfirmasi Berhasil">
                {{ session('success') }}
            </x-alert>
        @endif
        @if(session('error'))
            <x-alert type="error" :dismissible="true" title="Terjadi Kesalahan">
                {{ session('error') }}
            </x-alert>
        @endif

        <div class="mb-4">
            <div class="flex justify-between text-sm text-gray-600">
                <span>Total</span>
                <span class="font-semibold text-gray-900">{{ $order->formatted_total_amount }}</span>
            </div>
            <div class="flex justify-between text-sm text-gray-600 mt-1">
                <span>Metode</span>
                <span class="font-semibold text-gray-900">COD</span>
            </div>
        </div>

        <form method="POST" action="{{ route('cod.confirm.post', $order) }}?{{ http_build_query(request()->query()) }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700">4 digit terakhir No. HP penerima</label>
                <input type="tel" name="phone_last4" pattern="[0-9]{4}" maxlength="4"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                @error('phone_last4')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white py-2 rounded-md font-semibold">
                Konfirmasi Pembayaran COD
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
async function handleCodConfirmation(event) {
    const confirmed = await confirmDialog.show({
        title: 'Konfirmasi Pembayaran COD',
        message: 'Apakah Anda yakin telah menerima pembayaran Cash on Delivery untuk pesanan ini?',
        confirmText: 'Ya, Sudah Bayar',
        cancelText: 'Belum',
        icon: 'fas fa-money-bill-wave text-green-500'
    });

    if (!confirmed) {
        event.preventDefault();
        return false;
    }

    const button = event.target;
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';
    loadingOverlay.show('Memproses konfirmasi pembayaran...');
}

// Notifications are handled globally by the main layout
</script>
@endpush
