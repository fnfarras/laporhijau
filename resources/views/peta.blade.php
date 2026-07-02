<x-app-layout>
    @section('title', 'Peta Interaktif Laporan')

    @push('styles')
        <!-- Leaflet.js CSS -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
        <!-- Leaflet.markercluster CSS -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
        
        <style>
            #interactive-map {
                height: 75vh;
                border-radius: 16px;
                z-index: 10;
            }
            /* Custom Scrollbar for Sidebar List */
            .sidebar-scrollbar::-webkit-scrollbar {
                width: 6px;
            }
            .sidebar-scrollbar::-webkit-scrollbar-track {
                background: #f1f5f9;
                border-radius: 8px;
            }
            .sidebar-scrollbar::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 8px;
            }
            .sidebar-scrollbar::-webkit-scrollbar-thumb:hover {
                background: #94a3b8;
            }
            /* Custom Marker Icons using SVG */
            .custom-div-icon {
                background: transparent;
                border: none;
            }
            .marker-pin {
                width: 32px;
                height: 32px;
                border-radius: 50% 50% 50% 0;
                position: absolute;
                transform: rotate(-45deg);
                left: 50%;
                top: 50%;
                margin: -16px 0 0 -16px;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
                border: 2px solid white;
            }
            .marker-pin::after {
                content: '';
                width: 12px;
                height: 12px;
                margin: 0;
                background: white;
                border-radius: 50%;
            }
            /* Legend & Filter Control Overlays */
            .map-overlay-card {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(8px);
                border: 1px solid rgba(226, 232, 240, 0.8);
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                border-radius: 12px;
            }
        </style>
    @endpush

    <div class="py-6" style="font-family: 'Plus Jakarta Sans', sans-serif;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                        <span>🗺️</span> Peta Pantauan Laporan Lingkungan
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">Pantau seluruh laporan masalah lingkungan di Riau secara real-time</p>
                </div>
                <div class="flex items-center gap-3">
                    <button id="btn-lokasi-saya" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 active:bg-gray-100 text-gray-700 text-sm font-semibold rounded-xl transition-colors shadow-sm">
                        📍 Gunakan Lokasi Saya
                    </button>
                </div>
            </div>

            {{-- Main Map & Sidebar Layout --}}
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                
                {{-- Sidebar Laporan List (Panel Kiri) --}}
                <div class="lg:col-span-1 bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex flex-col h-[75vh]">
                    <div class="mb-4">
                        <h2 class="font-bold text-gray-800 text-base">Daftar Laporan</h2>
                        <p class="text-xs text-gray-400 mt-0.5" id="report-count-indicator">Sedang memuat data...</p>
                        
                        <div class="mt-3 relative">
                            <input type="text" id="sidebar-search" placeholder="Cari laporan..." class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-green-400 bg-gray-50/50">
                            <span class="absolute left-3.5 top-2.5 text-gray-400 text-xs">🔍</span>
                        </div>
                    </div>

                    {{-- Loading Skeleton / List --}}
                    <div id="sidebar-list-container" class="flex-1 overflow-y-auto sidebar-scrollbar space-y-2 pr-1">
                        {{-- Loading Skeleton items --}}
                        <div class="skeleton-loader space-y-2">
                            @for ($i = 0; $i < 4; $i++)
                                <div class="p-3 border border-gray-100 rounded-xl animate-pulse flex gap-3">
                                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex-shrink-0"></div>
                                    <div class="flex-1 space-y-2 py-1">
                                        <div class="h-2.5 bg-gray-200 rounded w-3/4"></div>
                                        <div class="h-2 bg-gray-100 rounded w-1/2"></div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>

                {{-- Map Container (Panel Kanan) --}}
                <div class="lg:col-span-3 relative">
                    <div id="interactive-map" class="shadow-sm border border-gray-100"></div>

                    {{-- Panel Filter Pojok Kanan Atas --}}
                    <div class="absolute top-4 right-4 z-[400] w-64 md:w-72 map-overlay-card p-4 hidden md:block">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-2 mb-3">
                            <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wide">Filter Laporan</h3>
                            <button id="btn-reset-filter" class="text-[10px] text-green-600 hover:text-green-700 font-bold uppercase">Reset</button>
                        </div>
                        
                        <div class="space-y-3">
                            {{-- Kategori Dropdown --}}
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Kategori</label>
                                <select id="filter-kategori" class="w-full px-3 py-1.5 border border-gray-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-green-400 bg-white">
                                    <option value="">Semua Kategori</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Status Checkbox --}}
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1.5">Status</label>
                                <div class="space-y-1.5 text-xs text-gray-600">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" value="pending" class="status-filter-checkbox rounded text-amber-500 focus:ring-amber-400 w-3.5 h-3.5" checked>
                                        <span>⏳ Pending</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" value="verified" class="status-filter-checkbox rounded text-sky-500 focus:ring-sky-400 w-3.5 h-3.5" checked>
                                        <span>✅ Terverifikasi</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" value="in_progress" class="status-filter-checkbox rounded text-orange-500 focus:ring-orange-400 w-3.5 h-3.5" checked>
                                        <span>🔧 Sedang Ditangani</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" value="resolved" class="status-filter-checkbox rounded text-green-500 focus:ring-green-400 w-3.5 h-3.5" checked>
                                        <span>🎉 Selesai</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" value="rejected" class="status-filter-checkbox rounded text-red-500 focus:ring-red-400 w-3.5 h-3.5" checked>
                                        <span>❌ Ditolak</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Legend warna status di pojok kiri bawah --}}
                    <div class="absolute bottom-4 left-4 z-[400] map-overlay-card p-3 text-xs hidden sm:block">
                        <h4 class="font-bold text-gray-700 mb-2 text-[10px] uppercase tracking-wider">Legend Status</h4>
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-amber-500 border border-white shadow-sm inline-block"></span>
                                <span>Pending</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-sky-500 border border-white shadow-sm inline-block"></span>
                                <span>Verified</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-orange-500 border border-white shadow-sm inline-block"></span>
                                <span>In Progress</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-green-500 border border-white shadow-sm inline-block"></span>
                                <span>Resolved</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-red-500 border border-white shadow-sm inline-block"></span>
                                <span>Rejected</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Responsive Filter for Mobile (Bottom collapsible panel or simple container below map) --}}
            <div class="mt-4 bg-white rounded-2xl border border-gray-100 shadow-sm p-4 md:hidden">
                <h3 class="font-bold text-sm text-gray-800 mb-3 flex justify-between items-center">
                    <span>⚙️ Filter Laporan</span>
                    <button id="btn-reset-filter-mobile" class="text-xs text-green-600 font-bold">RESET</button>
                </h3>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Kategori</label>
                        <select id="filter-kategori-mobile" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-green-400 bg-white">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Status</label>
                        <div class="flex flex-wrap gap-2 text-xs">
                            <label class="flex items-center gap-1.5 px-3 py-1.5 border border-gray-200 rounded-full cursor-pointer bg-gray-50/50" id="lbl-status-pending">
                                <input type="checkbox" value="pending" class="status-filter-checkbox-mobile rounded text-amber-500 focus:ring-amber-400 w-3 h-3" checked>
                                <span>⏳ Pending</span>
                            </label>
                            <label class="flex items-center gap-1.5 px-3 py-1.5 border border-gray-200 rounded-full cursor-pointer bg-gray-50/50" id="lbl-status-verified">
                                <input type="checkbox" value="verified" class="status-filter-checkbox-mobile rounded text-sky-500 focus:ring-sky-400 w-3 h-3" checked>
                                <span>✅ Verified</span>
                            </label>
                            <label class="flex items-center gap-1.5 px-3 py-1.5 border border-gray-200 rounded-full cursor-pointer bg-gray-50/50" id="lbl-status-inprogress">
                                <input type="checkbox" value="in_progress" class="status-filter-checkbox-mobile rounded text-orange-500 focus:ring-orange-400 w-3 h-3" checked>
                                <span>🔧 In Progress</span>
                            </label>
                            <label class="flex items-center gap-1.5 px-3 py-1.5 border border-gray-200 rounded-full cursor-pointer bg-gray-50/50" id="lbl-status-resolved">
                                <input type="checkbox" value="resolved" class="status-filter-checkbox-mobile rounded text-green-500 focus:ring-green-400 w-3 h-3" checked>
                                <span>🎉 Selesai</span>
                            </label>
                            <label class="flex items-center gap-1.5 px-3 py-1.5 border border-gray-200 rounded-full cursor-pointer bg-gray-50/50" id="lbl-status-rejected">
                                <input type="checkbox" value="rejected" class="status-filter-checkbox-mobile rounded text-red-500 focus:ring-red-400 w-3 h-3" checked>
                                <span>❌ Rejected</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <!-- Leaflet.js script -->
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <!-- Leaflet.markercluster script -->
        <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>

        <!-- API endpoints config object -->
        <script>
            window.MAP_DATA_URL = "{{ route('api.map-data') }}";
        </script>

        <!-- Load map logic via Vite -->
        @vite(['resources/js/map.js'])
    @endpush
</x-app-layout>
