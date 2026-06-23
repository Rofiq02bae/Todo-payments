<x-mail::message>
# Pembayaran Berhasil ✅

Halo,

Pembayaran Anda untuk **{{ $todo->title }}** telah berhasil diproses.

## Detail Invoice

| | |
|---|---|
| **Order ID** | {{ $payment->order_id }} |
| **Jumlah** | Rp {{ number_format($payment->amount, 0, ',', '.') }} |
| **Metode Pembayaran** | {{ $payment->payment_type ?? '-' }} |
| **Transaction ID** | {{ $payment->transaction_id ?? '-' }} |
| **Status** | {{ ucfirst($payment->status) }} |

Todo Anda sekarang **aktif** dan PDF dapat diunduh kapan saja.

<x-mail::button :url="url('/todos')">
Lihat Daftar Todo
</x-mail::button>

Terima kasih telah menggunakan Todo App.

Semoga produktif! 🚀
</x-mail::message>
