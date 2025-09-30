@extends('layouts.sidebar')

@section('title', 'Analytics - Manager Dashboard')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard Analitik</h1>
                <p class="text-gray-600">Analisis komprehensif komplain dan metrik kinerja</p>
            </div>
        </div>

        <!-- Key Metrics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Complaints -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Komplain</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $analytics['complaintsByStatus']->sum('total') }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <i class="fas fa-clipboard-list text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Completed Rate -->
            @php
                $totalComplaints = $analytics['complaintsByStatus']->sum('total');
                $completedComplaints = $analytics['complaintsByStatus']->where('status', 'selesai')->first()->total ?? 0;
                $completionRate = $totalComplaints > 0 ? round(($completedComplaints / $totalComplaints) * 100, 1) : 0;
            @endphp
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Tingkat Penyelesaian</p>
                        <p class="text-2xl font-bold text-green-600">{{ $completionRate }}%</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-lg">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Avg Resolution Time -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Rata-rata Waktu Penyelesaian</p>
                        <p class="text-2xl font-bold text-orange-600">
                            {{ $analytics['avgResolutionTime'] ? round($analytics['avgResolutionTime'], 1) . ' jam' : 'Tidak Ada Data' }}
                        </p>
                    </div>
                    <div class="bg-orange-100 p-3 rounded-lg">
                        <i class="fas fa-clock text-orange-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Active Categories -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Kategori Aktif</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $analytics['complaintsByCategory']->count() }}</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-lg">
                        <i class="fas fa-tags text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Status Distribution Chart -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Distribusi Status Komplain</h3>
                <div class="space-y-4">
                    @foreach($analytics['complaintsByStatus'] as $status)
                        @php
                            $percentage = $totalComplaints > 0 ? round(($status->total / $totalComplaints) * 100, 1) : 0;
                            $statusColors = [
                                'baru' => 'bg-blue-500',
                                'diproses' => 'bg-yellow-500', 
                                'selesai' => 'bg-green-500'
                            ];
                            $statusLabels = [
                                'baru' => 'Baru',
                                'diproses' => 'Diproses',
                                'selesai' => 'Selesai'
                            ];
                        @endphp
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-4 h-4 rounded {{ $statusColors[$status->status] ?? 'bg-gray-500' }}"></div>
                                <span class="text-sm font-medium text-gray-700">{{ $statusLabels[$status->status] ?? ucfirst($status->status) }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-600">{{ $status->total }}</span>
                                <span class="text-xs text-gray-500">({{ $percentage }}%)</span>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="h-2 rounded-full {{ $statusColors[$status->status] ?? 'bg-gray-500' }}" style="width: {{ $percentage }}%"></div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Monthly Trends Chart -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Tren Bulanan</h3>
                <div class="space-y-3">
                    @php
                        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                        $maxTotal = collect($analytics['monthlyTrends'])->max('total') ?: 1;
                    @endphp
                    @foreach($analytics['monthlyTrends'] as $trend)
                        @php
                            $monthName = $months[$trend['month'] - 1] ?? 'Unknown';
                            $totalWidth = ($trend['total'] / $maxTotal) * 100;
                            $completedWidth = $trend['total'] > 0 ? ($trend['completed'] / $trend['total']) * $totalWidth : 0;
                        @endphp
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700 w-12">{{ $monthName }}</span>
                            <div class="flex-1 mx-4">
                                <div class="w-full bg-gray-200 rounded-full h-4 relative">
                                    <div class="h-4 bg-blue-300 rounded-full" style="width: {{ $totalWidth }}%"></div>
                                    <div class="h-4 bg-green-500 rounded-full absolute top-0 left-0" style="width: {{ $completedWidth }}%"></div>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-medium text-gray-900">{{ $trend['total'] }}</span>
                                <span class="text-xs text-green-600 block">{{ $trend['completed'] }} selesai</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Bottom Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Category Performance -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Kinerja Kategori</h3>
                <div class="space-y-4">
                    @foreach($analytics['complaintsByCategory'] as $category)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $category->name }}</h4>
                                <p class="text-sm text-gray-600">{{ $category->description }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-lg font-bold text-blue-600">{{ $category->complaints_count }}</span>
                                <p class="text-xs text-gray-500">komplain</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Top Performers -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">CS Terbaik</h3>
                <div class="space-y-4">
                    @foreach($analytics['topPerformers'] as $index => $performer)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-bold text-blue-600">#{{ $index + 1 }}</span>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $performer->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $performer->email }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-lg font-bold text-green-600">{{ $performer->resolved_complaints_count }}</span>
                                <p class="text-xs text-gray-500">diselesaikan</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Add any interactive features here
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-refresh every 5 minutes
        setInterval(function() {
            location.reload();
        }, 300000);
    });
</script>
@endpush
