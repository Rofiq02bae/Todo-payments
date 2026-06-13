<x-mail::message>
# 🎉 Todo Berhasil Dibuat

Halo,

Todo baru berhasil ditambahkan.

**Judul Todo:**

> {{ $todo->title }}

<x-mail::button :url="url('/todos')">
Lihat Daftar Todo
</x-mail::button>

Terima kasih telah menggunakan Todo App.

Semoga produktif! 🚀
</x-mail::message>