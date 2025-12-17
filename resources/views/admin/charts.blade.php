@extends('layouts.admin')

@section('content')
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-semibold text-gray-800 mb-10">Grafik & Statistik</h1>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- 4 Canvas Grafik seperti sebelumnya -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Penjualan Per Hari (Desember 2025)</h2>
                <canvas id="dailySalesChart" height="300"></canvas>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Top 10 Produk Terlaris</h2>
                <canvas id="topProductsChart" height="300"></canvas>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Stok Rendah per Kategori (< 20 unit)</h2>
                <canvas id="lowStockChart" height="300"></canvas>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Status Pesanan</h2>
                <canvas id="orderStatusChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Chart.js CDN - Pindah ke akhir body -->
    @endsection

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Cek kalau Chart sudah load
                if (typeof Chart === 'undefined') {
                    console.error('Chart.js gagal load!');
                    return;
                }

              // 1. Penjualan Per Hari (Desember 2025 - Data Realistis)
                        const dailyCtx = document.getElementById('dailySalesChart').getContext('2d');
                        new Chart(dailyCtx, {
                            type: 'line',
                            data: {
                                labels: @json($labels),
                                datasets: [{
                                    label: 'Total Penjualan (Rp)',
                                    data: @json($data),
                                    borderColor: '#4f46e5',
                                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                                    tension: 0.4,
                                    fill: true,
                                    pointBackgroundColor: '#4f46e5',
                                    pointRadius: 5,
                                    pointHoverRadius: 8
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    title: {
                                        display: true,
                                        text: 'Penjualan Harian (1 Des - ' + new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) + ')',
                                        font: { size: 18 }
                                    },
                                    legend: { position: 'top' },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            callback: function(value) {
                                                return 'Rp ' + value.toLocaleString('id-ID');
                                            }
                                        }
                                    }
                                }
                            }
                        });

                // 2. Top Produk
                const topCtx = document.getElementById('topProductsChart').getContext('2d');
                new Chart(topCtx, {
                    type: 'bar',
                    data: {
                        labels: @json($topProducts->pluck('name')->take(10)),
                        datasets: [{
                            label: 'Terjual',
                            data: @json($topProducts->pluck('sold')->take(10)),
                            backgroundColor: '#10b981'
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { position: 'top' } },
                        scales: { y: { beginAtZero: true } }
                    }
                });

                // 3. Stok Rendah Kategori
                const lowCtx = document.getElementById('lowStockChart').getContext('2d');
                new Chart(lowCtx, {
                    type: 'doughnut',
                    data: {
                        labels: @json($lowStockCategories->pluck('name')),
                        datasets: [{
                            data: @json($lowStockCategories->pluck('low_count')),
                            backgroundColor: ['#ef4444', '#f97316', '#eab308', '#84cc16', '#22c55e']
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { position: 'right' } }
                    }
                });

            
                // 4. Status Pesanan
                const statusCtx = document.getElementById('orderStatusChart').getContext('2d');
                new Chart(statusCtx, {
                    type: 'pie',
                    data: {
                        labels: ['Paid', 'Shipped', 'Completed'],
                        datasets: [{
                            data: [
                                {{ $orderStats['paid'] ?? 0 }},
                                {{ $orderStats['shipped'] ?? 0 }},
                                {{ $orderStats['completed'] ?? 0 }}
                            ],
                            backgroundColor: ['#10b981', '#3b82f6', '#8b5cf6']
                        }]
                    },
                    options: { responsive: true }
                });
            });
        </script>
    @endpush