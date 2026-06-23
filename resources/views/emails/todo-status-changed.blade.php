<x-mail::message>
# Status Todo Diperbarui 🔔

Halo,

Status Todo Anda telah berubah.

**{{ $todo->title }}**

Status baru: **{{ $newStatus === 'completed' ? 'Selesai' : 'Aktif' }}**

@if ($newStatus === 'completed')
Todo telah ditandai selesai. Selamat! 🎉
@else
Todo Anda sekarang aktif. Pembayaran berhasil diverifikasi.
@endif

<x-mail::button :url="url('/todos')">
Lihat Daftar Todo
</x-mail::button>

Terima kasih telah menggunakan Todo App.
</x-mail::message>
