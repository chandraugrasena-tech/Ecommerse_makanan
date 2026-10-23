<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Promo;
use App\Services\OrderService;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // GET /admin/dashboard
    public function index()
    {
        $title = 'Dashboard';

        // pesanan yang dibatalkan tidak dihitung sebagai pendapatan
        $validOrders = Order::where('status', '!=', 'dibatalkan');

        $stats = [
            ['label' => 'Jumlah Pesanan', 'value' => Order::count()],
            ['label' => 'Total Pendapatan', 'value' => 'Rp '.number_format((clone $validOrders)->sum('total'), 0, ',', '.')],
            ['label' => 'Jumlah Menu', 'value' => Product::count()],
            ['label' => 'Pesanan Aktif', 'value' => Order::whereIn('status', ['diterima', 'dimasak', 'diantar'])->count()],
        ];

        // jumlah pesanan per status
        $statusCounts = Order::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        // menu terlaris
        $topProducts = OrderItem::select('product_name', DB::raw('SUM(quantity) as sold'))
            ->whereHas('order', fn ($q) => $q->where('status', '!=', 'dibatalkan'))
            ->groupBy('product_name')
            ->orderByDesc('sold')
            ->limit(5)
            ->get();

        // pendapatan 7 hari terakhir
        $daily = collect(range(6, 0))->map(function ($i) {
            $date = now()->subDays($i)->startOfDay();
            return [
                'label' => $date->format('d/m'),
                'total' => (int) Order::where('status', '!=', 'dibatalkan')
                    ->whereBetween('created_at', [$date, $date->copy()->endOfDay()])
                    ->sum('total'),
            ];
        });

        $recentOrders = Order::with(['items', 'user'])->latest('order_id')->limit(8)->get();

        return view('admin.dashboard', compact('title', 'stats', 'statusCounts', 'topProducts', 'daily', 'recentOrders'));
    }

    // GET /admin/about  -> profil toko Mutiara Jaya
    public function about()
    {
        $title = 'About';

        // informasi toko (sama dengan INFO pada AboutPage.jsx versi React)
        $info = [
            'area' => 'Singaraja',
            'phone' => '0895-6227-69692',
            'whatsapp' => '0895622759692',
            'email' => 'admin@mutiarajaya.id',
            'ongkir' => OrderService::ONGKIR,
        ];

        $stats = [
            ['label' => 'Total Menu', 'value' => Product::count()],
            ['label' => 'Kategori Menu', 'value' => Category::count()],
            ['label' => 'Pesanan Masuk', 'value' => Order::count()],
            ['label' => 'Wilayah Antar', 'value' => $info['area']],
        ];

        $categories = Category::withCount('products')->orderBy('category_id')->get();
        $promos = Promo::where('is_active', true)->orderBy('promo_id')->get();

        return view('admin.about', compact('title', 'info', 'stats', 'categories', 'promos'));
    }
}
