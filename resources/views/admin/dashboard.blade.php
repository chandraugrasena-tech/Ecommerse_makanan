<x-admin-layout :title="$title">
    <div class="space-y-6">

        {{-- Kartu statistik --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            @foreach ($stats as $s)
                <div class="bg-white p-5 rounded shadow flex items-center gap-4">
                    <div class="min-w-0">
                        <p class="text-xs text-gray-500">{{ $s['label'] }}</p>
                        <p class="text-xl font-extrabold text-gray-800 truncate">{{ $s['value'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Pendapatan 7 hari --}}
            <div class="bg-white p-5 rounded shadow lg:col-span-2">
                <h3 class="font-bold mb-4">Pendapatan 7 hari terakhir</h3>
                @php $max = max(1, $daily->max('total')); @endphp
                <div class="flex items-end gap-2 h-40">
                    @foreach ($daily as $d)
                        <div class="flex-1 flex flex-col items-center justify-end h-full">
                            <span class="text-[10px] text-gray-500 mb-1">{{ $d['total'] > 0 ? number_format($d['total'] / 1000, 0, ',', '.').'rb' : '' }}</span>
                            <div class="w-full bg-[#165823] rounded-t" style="height: {{ max(2, round($d['total'] / $max * 100)) }}%"></div>
                            <span class="text-[10px] text-gray-500 mt-1">{{ $d['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Status & menu terlaris --}}
            <div class="space-y-6">
                <div class="bg-white p-5 rounded shadow">
                    <h3 class="font-bold mb-3">Status pesanan</h3>
                    <div class="space-y-1.5 text-sm">
                        @foreach (\App\Models\Order::STATUS_LABELS as $key => $label)
                            <div class="flex justify-between"><span>{{ $label }}</span><span class="font-bold">{{ $statusCounts[$key] ?? 0 }}</span></div>
                        @endforeach
                    </div>
                </div>
                <div class="bg-white p-5 rounded shadow">
                    <h3 class="font-bold mb-3">Menu terlaris</h3>
                    @forelse ($topProducts as $tp)
                        <div class="flex justify-between text-sm py-1 border-b last:border-0"><span class="truncate pr-2">{{ $tp->product_name }}</span><span class="font-bold shrink-0">{{ $tp->sold }}x</span></div>
                    @empty
                        <p class="text-sm text-gray-500">Belum ada data.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Pesanan terbaru --}}
        <div class="bg-white p-5 rounded shadow">
            <div class="flex justify-between items-center mb-3">
                <h3 class="font-bold">Daftar Pesanan Masuk</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-[#165823] hover:underline">Lihat semua</a>
            </div>

            @if ($recentOrders->isEmpty())
                <div class="text-sm text-gray-500 py-6 text-center">
                    Belum ada pesanan. Buat pesanan dari halaman toko, nanti muncul di sini.
                    <div class="mt-3"><a href="{{ route('home') }}" class="font-bold text-[#165823] underline">Ke halaman toko</a></div>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="border-b text-gray-600">
                                <th class="py-2 pr-4">ID</th><th class="py-2 pr-4">Waktu</th><th class="py-2 pr-4">Pelanggan</th>
                                <th class="py-2 pr-4">Item</th><th class="py-2 pr-4">Pembayaran</th><th class="py-2 pr-4">Total</th><th class="py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentOrders as $o)
                                <tr class="border-b last:border-0">
                                    <td class="py-2 pr-4 font-mono font-bold"><a href="{{ route('admin.orders.show', $o) }}" class="text-[#165823] hover:underline">{{ $o->order_code }}</a></td>
                                    <td class="py-2 pr-4 whitespace-nowrap">{{ $o->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="py-2 pr-4">{{ $o->user?->name }}</td>
                                    <td class="py-2 pr-4">{{ $o->items->sum('quantity') }} porsi</td>
                                    <td class="py-2 pr-4">{{ $o->payment_method }}</td>
                                    <td class="py-2 pr-4 font-bold">{{ rp($o->total) }}</td>
                                    <td class="py-2">@include('admin.orders._badge', ['status' => $o->status])</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
