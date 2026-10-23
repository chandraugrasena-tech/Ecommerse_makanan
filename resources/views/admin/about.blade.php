<x-admin-layout :title="$title">
    <div class="max-w-5xl space-y-6">

        {{-- Banner --}}
        <div class="bg-gradient-to-r from-[#165823] to-[#218335] text-white rounded-2xl p-6 flex items-center gap-4">
            <div>
                <h1 class="text-2xl font-extrabold">Mutiara Jaya</h1>
                <p class="text-sm text-green-100">Pesan Antar Cepat &amp; Hangat. Melayani area {{ $info['area'] }}</p>
            </div>
        </div>

        {{-- Ringkasan toko --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach ($stats as $s)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex items-center gap-3">
                    <div class="min-w-0">
                        <p class="text-[11px] text-gray-500">{{ $s['label'] }}</p>
                        <p class="text-lg font-extrabold text-gray-800 truncate">{{ $s['value'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">

                {{-- Profil --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="font-bold text-lg text-gray-800 mb-2">Profil Singkat</h2>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Mutiara Jaya adalah layanan pesan antar makanan yang menyajikan menu rumahan seperti ayam geprek,
                        nasi campur, sate, aneka minuman, dan camilan. Pelanggan dapat memesan lewat website, memilih
                        alamat pengantaran, memakai promo, lalu memantau pesanan sampai tiba.
                    </p>
                </div>

                {{-- Layanan --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="font-bold text-lg text-gray-800 mb-3">Layanan Pengantaran &amp; Pembayaran</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
                        <div class="p-3 rounded-xl bg-[#fff2f2]">
                            <p class="text-[11px] text-gray-500">Wilayah antar</p>
                            <p class="font-bold text-gray-800">{{ $info['area'] }}</p>
                        </div>
                        <div class="p-3 rounded-xl bg-[#fff2f2]">
                            <p class="text-[11px] text-gray-500">Ongkos kirim</p>
                            <p class="font-bold text-gray-800">{{ rp($info['ongkir']) }}</p>
                        </div>
                        <div class="p-3 rounded-xl bg-[#fff2f2]">
                            <p class="text-[11px] text-gray-500">Pembayaran</p>
                            <p class="font-bold text-gray-800">CASH &amp; QRIS</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-3">Pembayaran CASH dilakukan di tempat saat pesanan tiba. Pembayaran QRIS dilakukan lewat scan kode setelah pesanan dibuat.</p>
                </div>

                {{-- Alur pemesanan --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="font-bold text-lg text-gray-800 mb-3">Alur Pemesanan</h2>
                    <ol class="space-y-3 text-sm">
                        @foreach ([
                            ['Pilih menu', 'Pelanggan memilih menu dan memasukkannya ke keranjang.'],
                            ['Pilih alamat &amp; promo', 'Alamat "Anter ke" dipilih, kode promo bisa dipakai bila memenuhi minimal belanja.'],
                            ['Pesan &amp; bayar', 'Pelanggan memilih CASH atau QRIS lalu menekan "Pesan sekarang".'],
                            ['Pesanan diproses', 'Admin memperbarui status: Diterima, Dimasak, Diantar, lalu Selesai.'],
                        ] as $i => [$judul, $isi])
                            <li class="flex items-start gap-3">
                                <span class="w-7 h-7 shrink-0 rounded-full bg-[#165823] text-white text-xs font-bold flex items-center justify-center">{{ $i + 1 }}</span>
                                <div>
                                    <p class="font-bold text-gray-800">{!! $judul !!}</p>
                                    <p class="text-xs text-gray-500">{!! $isi !!}</p>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </div>

                {{-- Menu per kategori --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="font-bold text-lg text-gray-800 mb-3">Menu Kami</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        @foreach ($categories as $c)
                            <a href="{{ route('home', ['category' => $c->category_id]) }}" class="p-3 rounded-xl border border-gray-200 hover:bg-gray-50">
                                <p class="font-bold text-sm text-gray-800">{{ $c->category }}</p>
                                <p class="text-xs text-gray-500">{{ $c->products_count }} menu</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                {{-- Promo --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-3">
                    <h2 class="font-bold text-lg text-gray-800">Promo Berlaku</h2>
                    @forelse ($promos as $p)
                        <div class="p-3 rounded-xl border border-gray-200">
                            <p class="text-sm font-bold text-[#165823]">{{ $p->title }}</p>
                            <p class="text-[11px] text-gray-500">Min. belanja {{ rp($p->min_order) }}</p>
                            <span class="inline-block mt-1 bg-gray-100 border px-2 py-0.5 rounded-lg text-[11px] font-mono font-bold">{{ $p->code }}</span>
                        </div>
                    @empty
                        <p class="text-xs text-gray-500">Belum ada promo aktif.</p>
                    @endforelse
                </div>

                {{-- Kontak --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-3">
                    <h2 class="font-bold text-lg text-gray-800">Kontak Toko</h2>
                    <a href="tel:{{ str_replace('-', '', $info['phone']) }}" class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 hover:bg-gray-50 text-sm">
                        <div><p class="text-[11px] text-gray-500">Nomor Admin</p><p class="font-bold text-gray-800">{{ $info['phone'] }}</p></div>
                    </a>
                    <a href="https://wa.me/{{ $info['whatsapp'] }}" target="_blank" rel="noreferrer" class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 hover:bg-gray-50 text-sm">
                        <div><p class="text-[11px] text-gray-500">WhatsApp</p><p class="font-bold text-gray-800">Chat sekarang</p></div>
                    </a>
                    <a href="mailto:{{ $info['email'] }}" class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 hover:bg-gray-50 text-sm">
                        <div class="min-w-0"><p class="text-[11px] text-gray-500">Email</p><p class="font-bold text-gray-800 truncate">{{ $info['email'] }}</p></div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
