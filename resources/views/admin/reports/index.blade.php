@extends('layouts.admin')

@section('content')
<div class="px-4 md:px-8 py-6 md:py-10">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
        <div>
            <h1 class="text-3xl font-black text-black uppercase tracking-tighter leading-none mb-2 italic">ANALYTICS ENGINE</h1>
            <p class="text-[10px] font-black text-black uppercase tracking-[0.3em] border-l-4 border-black pl-3">Enterprise Performance Reporting / Live Stream</p>
        </div>
        <div class="flex items-center gap-3">
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="bg-black text-white px-6 py-4 text-[10px] font-black uppercase tracking-widest hover:bg-gold transition-all shadow-[4px_4px_0px_0px_rgba(212,175,55,1)] flex items-center gap-2">
                    <i class="fas fa-file-export"></i> Data Payload
                </button>
                <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-64 bg-white border-2 border-black shadow-2xl z-50">
                    <a href="{{ route('admin.reports.export', ['type' => 'daily_sales']) }}" class="block px-6 py-4 text-[10px] font-black text-black uppercase tracking-widest hover:bg-black hover:text-white border-b-2 border-black transition-all">Sales Vector (CSV)</a>
                    <a href="{{ route('admin.reports.export', ['type' => 'product_sales']) }}" class="block px-6 py-4 text-[10px] font-black text-black uppercase tracking-widest hover:bg-black hover:text-white border-b-2 border-black transition-all">Inventory Audit (CSV)</a>
                    <a href="{{ route('admin.reports.export', ['type' => 'customers']) }}" class="block px-6 py-4 text-[10px] font-black text-black uppercase tracking-widest hover:bg-black hover:text-white transition-all">Registry Log (CSV)</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Master Analysis Grid (Stacked Graphs) -->
    <div class="space-y-12">
        
        <!-- 1. Revenue & Profit Overview (Bitcoin/Currency Style) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="bg-white border-2 border-black p-4 md:p-10 lg:col-span-2 shadow-[12px_12px_0px_0px_rgba(0,0,0,0.05)] overflow-x-auto">
                <div class="min-w-[500px]">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-[16px] font-black uppercase tracking-[0.4em] text-black">Revenue Velocity</h3>
                            <p class="text-[11px] font-black text-gold uppercase mt-1">Metric: Operational Cashflow [Y-Axis: Amount / X-Axis: Intervals]</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 bg-emerald-500 rounded-full inline-block"></span>
                            <span class="text-[9px] font-black uppercase tracking-widest text-gray-500">Live Feed</span>
                        </div>
                    </div>
                    <div class="h-[400px]">
                        <canvas id="orderChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="bg-white border-2 border-black p-8 flex flex-col justify-center relative overflow-hidden shadow-[12px_12px_0px_0px_rgba(212,175,55,0.1)]">
                <h3 class="text-[12px] font-black uppercase tracking-[0.3em] text-black mb-10 border-b-2 border-black pb-4">Financial Payload</h3>
                <div class="space-y-10">
                    <div>
                        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Total Gross Inflow</p>
                        <p class="text-4xl font-black text-black tracking-tighter">Rs. {{ number_format($totalRevenue) }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gold uppercase tracking-widest mb-2">Net Project Margin</p>
                        <p class="text-4xl font-black text-gold tracking-tighter">Rs. {{ number_format($grossProfit) }}</p>
                    </div>
                    <div class="bg-black text-white p-6 border-b-4 border-gold">
                        <p class="text-[10px] font-black uppercase tracking-widest mb-2 text-gold">Efficiency Score</p>
                        <p class="text-3xl font-black tracking-tighter">
                            {{ $totalRevenue > 0 ? round(($grossProfit / $totalRevenue) * 100, 1) : 0 }}%
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Customer Acquisition (Candlestick-inspired Area Chart) -->
        <div class="bg-white border-2 border-black p-4 md:p-10 shadow-[12px_12px_0px_0px_rgba(0,0,0,0.05)] overflow-x-auto">
            <div class="min-w-[600px]">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-[16px] font-black uppercase tracking-[0.4em] text-black">Acquisition Trajectory</h3>
                        <p class="text-[11px] font-black text-black/50 uppercase">Analysis of new user registry nodes across time intervals</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-0.5 bg-emerald-500 inline-block"></span>
                            <span class="text-[8px] font-black uppercase tracking-widest text-gray-400">Growth</span>
                        </div>
                    </div>
                </div>
                <div class="h-[400px]">
                    <canvas id="customerChart"></canvas>
                </div>
            </div>
        </div>

        <!-- 3. VIP List & Inventory -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-black text-white p-10 border-2 border-black shadow-[12px_12px_0px_0px_rgba(0,0,0,0.1)]">
                <h3 class="text-[14px] font-black uppercase tracking-[0.4em] text-gold mb-12 flex items-center gap-3 italic">
                    <i class="fas fa-crown"></i> ELITE REGISTRY
                </h3>
                <div class="space-y-6">
                    @forelse($customerInsights['vip'] as $vip)
                    <div class="flex items-center justify-between border-b border-white/10 pb-4">
                        <div>
                            <p class="text-[13px] font-black uppercase text-white">{{ $vip->name }}</p>
                            <p class="text-[9px] font-black text-gray-500 uppercase">{{ $vip->email }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[15px] font-black text-gold">Rs. {{ number_format($vip->orders_sum_total, 0) }}</p>
                            <span class="text-[8px] font-black bg-gold/20 text-gold px-2 py-0.5 uppercase">Lifetime Node</span>
                        </div>
                    </div>
                    @empty
                    <p class="text-center py-10 opacity-30 text-[10px] font-black">NO ELITE NODES DETECTED</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white border-2 border-black p-10 shadow-[12px_12px_0px_0px_rgba(0,0,0,0.05)]">
                <h3 class="text-[14px] font-black uppercase tracking-[0.4em] text-black mb-12 italic border-l-4 border-black pl-4">INVENTORY ANALYTICS</h3>
                <div class="h-[300px]">
                    <canvas id="stockChart"></canvas>
                </div>
            </div>
        </div>

        <!-- 4. Asset Evaluation -->
        <div class="bg-white border-2 border-black p-4 md:p-10 shadow-[12px_12px_0px_0px_rgba(0,0,0,0.05)] overflow-x-auto">
            <div class="min-w-[600px]">
                <h3 class="text-[16px] font-black uppercase tracking-[0.4em] text-black mb-10">Asset Valuation Matrix</h3>
                <div class="h-[400px]">
                    <canvas id="assetChart"></canvas>
                </div>
            </div>
        </div>

        <!-- 5. Sales Volume (Crypto Ticker Style) -->
        <div class="bg-white border-2 border-black p-4 md:p-10 shadow-[12px_12px_0px_0px_rgba(0,0,0,0.05)] overflow-x-auto">
            <div class="min-w-[600px]">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-[16px] font-black uppercase tracking-[0.4em] text-black">Transactional Throughput</h3>
                        <p class="text-[11px] font-black text-black/50 uppercase">Analysis of completed order items / 30 day history</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-0.5 bg-rose-500 inline-block"></span>
                            <span class="text-[8px] font-black uppercase tracking-widest text-gray-400">Volume</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 bg-rose-500/10 inline-block"></span>
                            <span class="text-[8px] font-black uppercase tracking-widest text-gray-400">Range</span>
                        </div>
                    </div>
                </div>
                <div class="h-[400px]">
                    <canvas id="soldChart"></canvas>
                </div>
            </div>
        </div>

    </div>
</div>

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    /* Ensure charts have enough space on mobile via horizontal scroll */
    @media (max-width: 768px) {
        .overflow-x-auto::-webkit-scrollbar {
            height: 4px;
        }
        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #000;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // ── Crosshair Plugin (Bitcoin/Currency chart feel) ──
        const crosshairPlugin = {
            id: 'crosshair',
            afterDraw(chart) {
                if (chart.tooltip?._active?.length) {
                    const ctx = chart.ctx;
                    const activePoint = chart.tooltip._active[0];
                    const x = activePoint.element.x;
                    const y = activePoint.element.y;
                    const topY = chart.chartArea.top;
                    const bottomY = chart.chartArea.bottom;
                    const leftX = chart.chartArea.left;
                    const rightX = chart.chartArea.right;
                    
                    ctx.save();
                    // Vertical line
                    ctx.beginPath();
                    ctx.setLineDash([3, 3]);
                    ctx.lineWidth = 1;
                    ctx.strokeStyle = 'rgba(0,0,0,0.3)';
                    ctx.moveTo(x, topY);
                    ctx.lineTo(x, bottomY);
                    ctx.stroke();
                    
                    // Horizontal line
                    ctx.beginPath();
                    ctx.moveTo(leftX, y);
                    ctx.lineTo(rightX, y);
                    ctx.stroke();
                    ctx.restore();
                }
            }
        };

        // ── Custom Axis Arrows ──
        const axisArrows = {
            id: 'axisArrows',
            afterDraw(chart) {
                const {ctx, chartArea: {top, bottom, left, right}, scales: {x, y}} = chart;
                ctx.save();
                ctx.lineWidth = 2;
                ctx.strokeStyle = '#000000';
                
                ctx.beginPath();
                ctx.moveTo(x.left, top - 10);
                ctx.lineTo(x.left, bottom);
                ctx.stroke();
                
                ctx.beginPath();
                ctx.moveTo(x.left - 5, top - 2);
                ctx.lineTo(x.left, top - 12);
                ctx.lineTo(x.left + 5, top - 2);
                ctx.fillStyle = '#000000';
                ctx.fill();

                ctx.beginPath();
                ctx.moveTo(x.left, y.bottom);
                ctx.lineTo(right + 10, y.bottom);
                ctx.stroke();

                ctx.beginPath();
                ctx.moveTo(right + 2, y.bottom - 5);
                ctx.lineTo(right + 12, y.bottom);
                ctx.lineTo(right + 2, y.bottom + 5);
                ctx.fill();
                
                ctx.restore();
            }
        };

        // ── Currency/Crypto Chart Options ──
        const cryptoOptions = {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            layout: { padding: { top: 20, right: 20 } },
            plugins: { 
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#000',
                    cornerRadius: 0,
                    borderColor: '#D4AF37',
                    borderWidth: 1,
                    titleFont: { size: 11, weight: '900', family: 'Outfit, monospace' },
                    bodyFont: { size: 12, weight: '700', family: 'Outfit, monospace' },
                    padding: 12,
                    displayColors: false,
                    callbacks: {
                        label: function(ctx) {
                            return 'Rs. ' + ctx.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    grid: { 
                        color: 'rgba(0,0,0,0.04)', 
                        lineWidth: 1,
                        drawBorder: false, 
                    },
                    border: { display: false },
                    ticks: { 
                        font: { size: 10, family: 'Outfit, monospace', weight: '700' }, 
                        color: '#999',
                        padding: 12,
                        callback: function(value) {
                            if (value >= 1000) return 'Rs. ' + (value/1000).toFixed(0) + 'K';
                            return 'Rs. ' + value;
                        }
                    }
                },
                x: {
                    grid: { display: false },
                    border: { display: false },
                    ticks: { 
                        font: { size: 10, family: 'Outfit, monospace', weight: '700' }, 
                        color: '#999',
                        padding: 10
                    }
                }
            }
        };

        const technicalOptions = {
            responsive: true,
            maintainAspectRatio: false,
            layout: { padding: { top: 20, right: 20 } },
            plugins: { 
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#000',
                    cornerRadius: 0,
                    borderColor: '#D4AF37',
                    borderWidth: 1,
                    titleFont: { size: 11, weight: '900', family: 'Outfit, monospace' },
                    bodyFont: { size: 12, weight: '700', family: 'Outfit, monospace' },
                    padding: 12,
                    displayColors: false,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.04)', lineWidth: 1 },
                    border: { display: false },
                    ticks: { 
                        font: { size: 10, family: 'Outfit, monospace', weight: '700' }, 
                        color: '#999',
                        padding: 12
                    }
                },
                x: {
                    grid: { display: false },
                    border: { display: false },
                    ticks: { 
                        font: { size: 10, family: 'Outfit, monospace', weight: '700' }, 
                        color: '#999',
                        padding: 10
                    }
                }
            }
        };

        // ══════════════════════════════════════════════
        // 1. Revenue Chart (Currency/Bitcoin Gradient Style)
        // ══════════════════════════════════════════════
        const orderData = @json($orderStats);
        const revenueCtx = document.getElementById('orderChart').getContext('2d');
        
        // Create gradient fill like crypto charts
        const revenueGrad = revenueCtx.createLinearGradient(0, 0, 0, 400);
        revenueGrad.addColorStop(0, 'rgba(16, 185, 129, 0.25)');
        revenueGrad.addColorStop(0.5, 'rgba(16, 185, 129, 0.08)');
        revenueGrad.addColorStop(1, 'rgba(16, 185, 129, 0)');
        
        new Chart(revenueCtx, {
            type: 'line',
            plugins: [axisArrows, crosshairPlugin],
            data: {
                labels: orderData.map(d => d.month),
                datasets: [{
                    data: orderData.map(d => d.revenue),
                    borderColor: '#10B981',
                    borderWidth: 2.5,
                    pointRadius: 0,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: '#10B981',
                    pointHoverBorderColor: '#fff',
                    pointHoverBorderWidth: 3,
                    fill: true,
                    backgroundColor: revenueGrad,
                    tension: 0.4
                }]
            },
            options: {
                ...cryptoOptions,
                plugins: { 
                    ...cryptoOptions.plugins,
                }
            }
        });

        // ══════════════════════════════════════════════
        // 2. Customer Chart (Gradient Bar - Trading Volume Style)
        // ══════════════════════════════════════════════
        const customerData = @json($customerGrowth);
        const custCtx = document.getElementById('customerChart').getContext('2d');
        
        const custGrad = custCtx.createLinearGradient(0, 0, 0, 400);
        custGrad.addColorStop(0, 'rgba(16, 185, 129, 0.9)');
        custGrad.addColorStop(1, 'rgba(16, 185, 129, 0.3)');
        
        new Chart(custCtx, {
            type: 'bar',
            plugins: [axisArrows, crosshairPlugin],
            data: {
                labels: customerData.map(d => d.month),
                datasets: [{
                    data: customerData.map(d => d.count),
                    backgroundColor: customerData.map((d, i, arr) => {
                        if (i === 0) return 'rgba(16, 185, 129, 0.7)';
                        return d.count >= arr[i-1].count ? 'rgba(16, 185, 129, 0.7)' : 'rgba(239, 68, 68, 0.7)';
                    }),
                    borderColor: customerData.map((d, i, arr) => {
                        if (i === 0) return '#10B981';
                        return d.count >= arr[i-1].count ? '#10B981' : '#EF4444';
                    }),
                    borderWidth: 1,
                    barPercentage: 0.6,
                    borderRadius: 2,
                }]
            },
            options: {
                ...technicalOptions,
                interaction: { mode: 'index', intersect: false },
            }
        });

        // ══════════════════════════════════════════════
        // 3. Stock Chart (Doughnut)
        // ══════════════════════════════════════════════
        new Chart(document.getElementById('stockChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Healthy', 'Low', 'Critical'],
                datasets: [{
                    data: [{{ $stockLevels['in_stock'] }}, {{ $stockLevels['low_stock'] }}, {{ $stockLevels['out_of_stock'] }}],
                    backgroundColor: ['#10B981', '#F59E0B', '#EF4444'],
                    borderWidth: 4,
                    borderColor: '#fff',
                    cutout: '72%',
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { 
                            font: { size: 11, weight: '700', family: 'Outfit' }, 
                            color: '#333',
                            padding: 16,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    }
                }
            }
        });

        // ══════════════════════════════════════════════
        // 4. Asset Chart (Horizontal Bar with Gradient)
        // ══════════════════════════════════════════════
        const assetData = @json($categoryValue);
        const assetCtx = document.getElementById('assetChart').getContext('2d');
        
        const assetGrad = assetCtx.createLinearGradient(0, 0, 600, 0);
        assetGrad.addColorStop(0, 'rgba(212, 175, 55, 0.4)');
        assetGrad.addColorStop(1, 'rgba(212, 175, 55, 0.9)');
        
        new Chart(assetCtx, {
            type: 'bar',
            plugins: [crosshairPlugin],
            data: {
                labels: assetData.map(d => d.name),
                datasets: [{
                    data: assetData.map(d => d.total_value),
                    backgroundColor: assetGrad,
                    borderColor: '#D4AF37',
                    borderWidth: 1,
                    borderRadius: 3,
                }]
            },
            options: { 
                ...technicalOptions, 
                indexAxis: 'y',
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    ...technicalOptions.plugins,
                    tooltip: {
                        ...technicalOptions.plugins.tooltip,
                        callbacks: {
                            label: function(ctx) {
                                return 'Rs. ' + ctx.parsed.x.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        // ══════════════════════════════════════════════
        // 5. Transactional Throughput (Crypto/Currency Jagged Line + Gradient Fill)
        // ══════════════════════════════════════════════
        const soldData = @json($soldTrajectory);
        const soldCtx = document.getElementById('soldChart').getContext('2d');
        
        const soldGrad = soldCtx.createLinearGradient(0, 0, 0, 400);
        soldGrad.addColorStop(0, 'rgba(239, 68, 68, 0.2)');
        soldGrad.addColorStop(0.5, 'rgba(239, 68, 68, 0.05)');
        soldGrad.addColorStop(1, 'rgba(239, 68, 68, 0)');
        
        new Chart(soldCtx, {
            type: 'line',
            plugins: [axisArrows, crosshairPlugin],
            data: {
                labels: soldData.map(d => d.date),
                datasets: [{
                    data: soldData.map(d => d.quantity),
                    borderColor: '#EF4444',
                    borderWidth: 2,
                    pointRadius: 0,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: '#EF4444',
                    pointHoverBorderColor: '#fff',
                    pointHoverBorderWidth: 2,
                    fill: true,
                    backgroundColor: soldGrad,
                    tension: 0.1  // Slightly smoothed for crypto look but still sharp
                }]
            },
            options: {
                ...cryptoOptions,
                scales: {
                    ...cryptoOptions.scales,
                    y: {
                        ...cryptoOptions.scales.y,
                        beginAtZero: true,
                        ticks: {
                            ...cryptoOptions.scales.y.ticks,
                            callback: function(value) { return value; }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
