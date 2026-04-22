<x-cms-dashboard::layouts.admin title="Dashboard">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');
        
        .dashboard-container {
            font-family: 'Outfit', sans-serif;
            background: #f8fafc;
            color: #1e293b;
        }

        .stat-card {
            background: white;
            border-radius: 1.5rem;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid #f1f5f9;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .icon-box {
            width: 48px;
            height: 48px;
            border-radius: 0.75rem;
            display: flex;
            items-center: center;
            justify-content: center;
            background: #f1f5f9;
            margin-bottom: 1rem;
        }

        .chart-container {
            background: white;
            border-radius: 1.5rem;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            border: 1px solid #f1f5f9;
        }

        .badge-success {
            background: #f0fdf4;
            color: #16a34a;
            padding: 0.25rem 0.5rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .badge-danger {
            background: #fef2f2;
            color: #dc2626;
            padding: 0.25rem 0.5rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .tab-btn {
            padding: 0.5rem 1.25rem;
            font-size: 0.813rem;
            font-weight: 600;
            border-radius: 0.625rem;
            transition: all 0.2s;
        }

        .tab-btn.active {
            background: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            color: #1e293b;
        }

        .tab-btn:not(.active) {
            color: #64748b;
        }
    </style>

    <div class="dashboard-container min-h-screen p-4 md:p-8">
        <!-- Top Row: Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Client Rating -->
            <div class="stat-card">
                <div class="icon-box">
                    <svg class="w-6 h-6 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                </div>
                <div class="flex justify-between items-end">
                    <div>
                        <p class="text-[13px] font-medium text-slate-500 mb-1">{{ $stats['client_rating']['label'] }}</p>
                        <h3 class="text-2xl font-bold">{{ $stats['client_rating']['score'] }}</h3>
                    </div>
                    <div class="flex flex-col items-end">
                        <span class="badge-success">{{ $stats['client_rating']['change'] }}</span>
                        <span class="text-[11px] text-slate-400 mt-1">Vs last month</span>
                    </div>
                </div>
            </div>

            <!-- Instagram Followers -->
            <div class="stat-card">
                <div class="icon-box">
                    <svg class="w-6 h-6 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div class="flex justify-between items-end">
                    <div>
                        <p class="text-[13px] font-medium text-slate-500 mb-1">{{ $stats['instagram_followers']['label'] }}</p>
                        <h3 class="text-2xl font-bold">{{ $stats['instagram_followers']['count'] }}</h3>
                    </div>
                    <div class="flex flex-col items-end">
                        <span class="badge-danger">{{ $stats['instagram_followers']['change'] }}</span>
                        <span class="text-[11px] text-slate-400 mt-1">Vs last month</span>
                    </div>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="stat-card">
                <div class="icon-box">
                    <svg class="w-6 h-6 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="flex justify-between items-end">
                    <div>
                        <p class="text-[13px] font-medium text-slate-500 mb-1">{{ $stats['total_revenue']['label'] }}</p>
                        <h3 class="text-2xl font-bold">{{ $stats['total_revenue']['amount'] }}</h3>
                    </div>
                    <div class="flex flex-col items-end">
                        <span class="badge-success">{{ $stats['total_revenue']['change'] }}</span>
                        <span class="text-[11px] text-slate-400 mt-1">Vs last month</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Middle Row: Charts & Traffic -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-8">
            <!-- Impression & Data Traffic -->
            <div class="lg:col-span-8 chart-container">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-[18px] font-bold">Impression & Data Traffic</h3>
                        <p class="text-[12px] text-slate-400">Jun 1, 2024 - Dec 1, 2025</p>
                    </div>
                    <div class="text-right">
                        <div class="flex items-center gap-2">
                           <h3 class="text-[18px] font-bold">{{ $stats['total_revenue']['amount'] }}.00</h3>
                           <span class="badge-success text-[10px]">+7.96%</span>
                        </div>
                        <p class="text-[11px] text-slate-400">Total Revenue</p>
                    </div>
                </div>
                <div class="h-[300px]">
                    <canvas id="impressionChart"></canvas>
                </div>
            </div>

            <!-- Traffic Stats -->
            <div class="lg:col-span-4 chart-container flex flex-col">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-[18px] font-bold">Traffic Stats</h3>
                    <button class="text-slate-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg></button>
                </div>
                
                <div class="bg-slate-100 p-1 rounded-xl flex mb-8">
                    <button class="flex-1 tab-btn active">Today</button>
                    <button class="flex-1 tab-btn">Week</button>
                    <button class="flex-1 tab-btn">Month</button>
                </div>

                <div class="space-y-8 flex-grow">
                    <!-- New Subscribers -->
                    <div class="flex justify-between items-center">
                        <div class="flex-grow">
                            <p class="text-[12px] text-slate-400 font-medium mb-1">New Subscribers</p>
                            <h4 class="text-[18px] font-bold">{{ $stats['traffic_stats']['new_subscribers']['value'] }}</h4>
                            <span class="text-[11px] text-green-500 font-bold">{{ $stats['traffic_stats']['new_subscribers']['change'] }} <span class="text-slate-400 font-medium">then last Week</span></span>
                        </div>
                        <div class="w-24 h-12">
                            <canvas id="subscribersSparkline"></canvas>
                        </div>
                    </div>

                    <!-- Conversion Rate -->
                    <div class="flex justify-between items-center">
                        <div class="flex-grow">
                            <p class="text-[12px] text-slate-400 font-medium mb-1">Conversion Rate</p>
                            <h4 class="text-[18px] font-bold">{{ $stats['traffic_stats']['conversion_rate']['value'] }}</h4>
                            <span class="text-[11px] text-red-500 font-bold">{{ $stats['traffic_stats']['conversion_rate']['change'] }} <span class="text-slate-400 font-medium">then last Week</span></span>
                        </div>
                        <div class="w-24 h-12">
                            <canvas id="conversionSparkline"></canvas>
                        </div>
                    </div>

                    <!-- Page Bounce Rate -->
                    <div class="flex justify-between items-center">
                        <div class="flex-grow">
                            <p class="text-[12px] text-slate-400 font-medium mb-1">Page Bounce Rate</p>
                            <h4 class="text-[18px] font-bold">{{ $stats['traffic_stats']['bounce_rate']['value'] }}</h4>
                            <span class="text-[11px] text-green-500 font-bold">{{ $stats['traffic_stats']['bounce_rate']['change'] }} <span class="text-slate-400 font-medium">then last Week</span></span>
                        </div>
                        <div class="w-24 h-12">
                            <canvas id="bounceSparkline"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Row: Featured Campaigns -->
        <div class="grid grid-cols-1 gap-8 mb-8">
            <div class="chart-container">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-[18px] font-bold">Featured Campaigns</h3>
                    <button class="text-slate-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg></button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[12px] text-slate-400 border-b">
                                <th class="pb-4 font-semibold uppercase">Campaign Name</th>
                                <th class="pb-4 font-semibold uppercase">Status</th>
                                <th class="pb-4 font-semibold uppercase text-right">Performance</th>
                            </tr>
                        </thead>
                        <tbody class="text-[13px]">
                            <tr class="border-b">
                                <td class="py-4 font-bold text-slate-700">Summer Sale 2024</td>
                                <td class="py-4"><span class="px-2 py-1 bg-green-50 text-green-600 rounded-lg font-bold">Active</span></td>
                                <td class="py-4 text-right font-bold text-indigo-600">+45.2%</td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-4 font-bold text-slate-700">Christmas Special</td>
                                <td class="py-4"><span class="px-2 py-1 bg-blue-50 text-blue-600 rounded-lg font-bold">Planned</span></td>
                                <td class="py-4 text-right font-bold text-slate-400">0.0%</td>
                            </tr>
                            <tr>
                                <td class="py-4 font-bold text-slate-700">New User Promotion</td>
                                <td class="py-4"><span class="px-2 py-1 bg-slate-50 text-slate-400 rounded-lg font-bold">Finished</span></td>
                                <td class="py-4 text-right font-bold text-red-500">-2.4%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Main Impression Chart
            const ctx = document.getElementById('impressionChart').getContext('2d');
            const gradient1 = ctx.createLinearGradient(0, 0, 0, 400);
            gradient1.addColorStop(0, 'rgba(99, 102, 241, 0.2)');
            gradient1.addColorStop(1, 'rgba(99, 102, 241, 0)');

            const gradient2 = ctx.createLinearGradient(0, 0, 0, 400);
            gradient2.addColorStop(0, 'rgba(56, 189, 248, 0.1)');
            gradient2.addColorStop(1, 'rgba(56, 189, 248, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($stats['main_chart']['labels']) !!},
                    datasets: [
                        {
                            label: 'Impression',
                            data: {!! json_encode($stats['main_chart']['data1']) !!},
                            borderColor: '#6366f1',
                            backgroundColor: gradient1,
                            fill: true,
                            tension: 0.4,
                            borderWidth: 3,
                            pointRadius: 0
                        },
                        {
                            label: 'Traffic',
                            data: {!! json_encode($stats['main_chart']['data2']) !!},
                            borderColor: '#38bdf8',
                            backgroundColor: gradient2,
                            fill: true,
                            tension: 0.4,
                            borderWidth: 2,
                            pointRadius: 0
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { 
                            beginAtZero: true,
                            grid: { display: true, color: '#f1f5f9' },
                            ticks: { color: '#94a3b8', font: { size: 10 } }
                        },
                        x: { 
                            grid: { display: false },
                            ticks: { color: '#94a3b8', font: { size: 10 } }
                        }
                    }
                }
            });

            // Sparklines function
            function createSparkline(elementId, data, color) {
                new Chart(document.getElementById(elementId).getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: ['', '', '', '', '', '', ''],
                        datasets: [{
                            data: data,
                            borderColor: color,
                            borderWidth: 2,
                            fill: false,
                            tension: 0.4,
                            pointRadius: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false }, tooltip: { enabled: false } },
                        scales: { x: { display: false }, y: { display: false } }
                    }
                });
            }

            createSparkline('subscribersSparkline', {!! json_encode($stats['traffic_stats']['new_subscribers']['data']) !!}, '#22c55e');
            createSparkline('conversionSparkline', {!! json_encode($stats['traffic_stats']['conversion_rate']['data']) !!}, '#ef4444');
            createSparkline('bounceSparkline', {!! json_encode($stats['traffic_stats']['bounce_rate']['data']) !!}, '#22c55e');
        });
    </script>
</x-cms-dashboard::layouts.admin>
