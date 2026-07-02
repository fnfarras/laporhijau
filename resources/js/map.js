document.addEventListener('DOMContentLoaded', () => {
    // ── Elements & State ──────────────────────────────────────────────
    const sidebarList = document.getElementById('sidebar-list-container');
    const searchInput = document.getElementById('sidebar-search');
    const reportCountIndicator = document.getElementById('report-count-indicator');
    
    // Desktop Filters
    const filterKategori = document.getElementById('filter-kategori');
    const btnResetFilter = document.getElementById('btn-reset-filter');
    const statusCheckboxes = document.querySelectorAll('.status-filter-checkbox');
    
    // Mobile Filters
    const filterKategoriMobile = document.getElementById('filter-kategori-mobile');
    const btnResetFilterMobile = document.getElementById('btn-reset-filter-mobile');
    const statusCheckboxesMobile = document.querySelectorAll('.status-filter-checkbox-mobile');
    
    const btnLokasiSaya = document.getElementById('btn-lokasi-saya');

    let map = null;
    let markerCluster = null;
    let allReports = [];
    let activeMarkers = {}; // map report.id -> marker instance

    // ── Initialize Map ────────────────────────────────────────────────
    function initMap() {
        // Center of Pekanbaru, Riau
        const defaultCenter = [0.5070, 101.4478];
        const defaultZoom = 12;

        map = L.map('interactive-map', {
            zoomControl: true,
            maxZoom: 18,
            minZoom: 6
        }).setView(defaultCenter, defaultZoom);

        // CartoDB Voyager Tile Layer (lebih bersih & profesional dari OSM default)
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="https://carto.com/">CARTO</a>',
            subdomains: 'abcd',
            maxZoom: 20
        }).addTo(map);


        markerCluster = L.markerClusterGroup({
            showCoverageOnHover: false,
            zoomToBoundsOnClick: true,
            maxClusterRadius: 40
        });
        map.addLayer(markerCluster);
    }

    // ── Custom SVG Marker Creator ─────────────────────────────────────
    function getMarkerColor(status) {
        switch (status) {
            case 'pending': return '#f59e0b';
            case 'verified': return '#0ea5e9';
            case 'in_progress': return '#f97316';
            case 'resolved': return '#16a34a';
            case 'rejected': return '#ef4444';
            default: return '#94a3b8';
        }
    }

    function createCustomMarkerIcon(status) {
        const color = getMarkerColor(status);
        return L.divIcon({
            className: 'custom-div-icon',
            html: `<div class="marker-pin" style="background-color: ${color};"></div>`,
            iconSize: [30, 42],
            iconAnchor: [15, 42],
            popupAnchor: [0, -36]
        });
    }

    // ── Get Status Badge Config ───────────────────────────────────────
    function getStatusBadge(status) {
        switch (status) {
            case 'pending': return { label: '⏳ Pending', class: 'bg-amber-50 text-amber-700 border-amber-200' };
            case 'verified': return { label: '✅ Verified', class: 'bg-sky-50 text-sky-700 border-sky-200' };
            case 'in_progress': return { label: '🔧 In Progress', class: 'bg-orange-50 text-orange-700 border-orange-200' };
            case 'resolved': return { label: '🎉 Selesai', class: 'bg-green-50 text-green-700 border-green-200' };
            case 'rejected': return { label: '❌ Ditolak', class: 'bg-red-50 text-red-700 border-red-200' };
            default: return { label: status, class: 'bg-gray-50 text-gray-700 border-gray-200' };
        }
    }

    // ── Generate Popup HTML ───────────────────────────────────────────
    function generatePopupHtml(report) {
        const badge = getStatusBadge(report.status);
        const imgHtml = report.photo_url 
            ? `<div class="w-full h-28 mb-3 rounded-lg overflow-hidden bg-gray-100">
                 <img src="${report.photo_url}" class="w-full h-full object-cover" alt="${report.title}">
               </div>`
            : '';

        return `
            <div class="p-1 font-sans max-w-[240px]">
                ${imgHtml}
                <div class="flex items-center gap-1.5 mb-1 flex-wrap">
                    <span class="text-[10px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full font-semibold">
                        ${report.category.icon} ${report.category.name}
                    </span>
                    <span class="text-[10px] px-2 py-0.5 rounded-full border font-bold ${badge.class}">
                        ${badge.label}
                    </span>
                </div>
                <h4 class="font-bold text-gray-900 text-sm leading-snug mb-1.5">${report.title}</h4>
                <p class="text-xs text-gray-500 mb-1 leading-snug">📍 ${report.address}</p>
                <p class="text-[10px] text-gray-400 mb-3">👤 ${report.reporter_name} · ${report.created_at}</p>
                <a href="/laporan/${report.id}" class="block text-center w-full py-1.5 bg-green-600 hover:bg-green-700 active:bg-green-800 text-white font-bold text-xs rounded-lg transition-colors" style="text-decoration:none;">
                    Lihat Detail →
                </a>
            </div>
        `;
    }

    // ── Fetch & Load Data ─────────────────────────────────────────────
    async function fetchReports() {
        try {
            const response = await fetch(window.MAP_DATA_URL);
            if (!response.ok) throw new Error('Gagal mengambil data peta');
            
            allReports = await response.json();
            renderReports();
        } catch (error) {
            console.error(error);
            sidebarList.innerHTML = `
                <div class="p-6 text-center text-red-500 text-xs font-semibold">
                    ⚠️ ${error.message}
                </div>
            `;
            reportCountIndicator.textContent = 'Gagal memuat data';
        }
    }

    // ── Get Active Filters ────────────────────────────────────────────
    function getFilters() {
        // Sync desktop/mobile category
        const isMobile = window.innerWidth < 768;
        const categoryVal = isMobile 
            ? filterKategoriMobile.value 
            : filterKategori.value;
            
        // Sync status checkboxes
        const checkboxes = isMobile ? statusCheckboxesMobile : statusCheckboxes;
        const activeStatuses = Array.from(checkboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);

        const searchKeyword = searchInput.value.toLowerCase().trim();

        return {
            category: categoryVal,
            statuses: activeStatuses,
            keyword: searchKeyword
        };
    }

    // ── Sync Desktop & Mobile Controls ────────────────────────────────
    function syncFilters(changedFrom) {
        if (changedFrom === 'desktop') {
            filterKategoriMobile.value = filterKategori.value;
            statusCheckboxesMobile.forEach((cb, idx) => {
                cb.checked = statusCheckboxes[idx].checked;
            });
        } else if (changedFrom === 'mobile') {
            filterKategori.value = filterKategoriMobile.value;
            statusCheckboxes.forEach((cb, idx) => {
                cb.checked = statusCheckboxesMobile[idx].checked;
            });
        }
    }

    // ── Render Markers & Sidebar List ────────────────────────────────
    function renderReports() {
        // Clear existing markers
        markerCluster.clearLayers();
        activeMarkers = {};
        sidebarList.innerHTML = '';

        const filters = getFilters();
        const filtered = allReports.filter(report => {
            // Category Filter
            if (filters.category && report.category.id != filters.category) return false;
            // Status Filter
            if (!filters.statuses.includes(report.status)) return false;
            // Search keyword
            if (filters.keyword) {
                const titleMatch = report.title.toLowerCase().includes(filters.keyword);
                const addrMatch = report.address.toLowerCase().includes(filters.keyword);
                if (!titleMatch && !addrMatch) return false;
            }
            return true;
        });

        // Update count indicator
        reportCountIndicator.textContent = `${filtered.length} laporan terpantau`;

        if (filtered.length === 0) {
            sidebarList.innerHTML = `
                <div class="p-8 text-center text-gray-400 text-xs">
                     🔍 Tidak ada laporan cocok
                </div>
            `;
            return;
        }

        const bounds = L.latLngBounds();

        filtered.forEach(report => {
            // 1. Render Map Marker
            const marker = L.marker([report.latitude, report.longitude], {
                icon: createCustomMarkerIcon(report.status)
            });

            marker.bindPopup(generatePopupHtml(report));
            markerCluster.addLayer(marker);
            activeMarkers[report.id] = marker;

            bounds.extend([report.latitude, report.longitude]);

            // 2. Render Sidebar Card Item
            const badge = getStatusBadge(report.status);
            const card = document.createElement('div');
            card.className = 'p-3 border border-gray-100 rounded-xl hover:border-green-300 hover:shadow-sm cursor-pointer transition-all flex gap-3 bg-white';
            
            const thumbHtml = report.photo_url
                ? `<img src="${report.photo_url}" class="w-12 h-12 rounded-lg object-cover flex-shrink-0 bg-gray-50" alt="${report.title}">`
                : `<div class="w-12 h-12 rounded-lg bg-slate-50 flex items-center justify-center text-lg flex-shrink-0">📷</div>`;

            card.innerHTML = `
                ${thumbHtml}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-0.5">
                        <span class="text-[9px] text-gray-400 font-semibold truncate max-w-[70px]">${report.category.name}</span>
                        <span class="text-[9px] font-bold ${badge.class} border px-1 rounded-sm">${badge.label}</span>
                    </div>
                    <h5 class="text-xs font-bold text-gray-800 line-clamp-1">${report.title}</h5>
                    <p class="text-[10px] text-gray-400 truncate mt-0.5">📍 ${report.address}</p>
                </div>
            `;

            // Click sidebar card -> focus map & open popup
            card.addEventListener('click', () => {
                map.setView([report.latitude, report.longitude], 16);
                // Open popup after a slight delay to allow rendering
                setTimeout(() => {
                    marker.openPopup();
                }, 100);
            });

            sidebarList.appendChild(card);
        });

        // Autofit map to show all markers if any exist and filter was not empty
        if (filtered.length > 0 && (filters.category || filters.keyword || filters.statuses.length < 5)) {
            map.fitBounds(bounds, { padding: [50, 50] });
        }
    }

    // ── Setup Listeners ───────────────────────────────────────────────
    function setupListeners() {
        // Desktop Filter Listeners
        filterKategori.addEventListener('change', () => {
            syncFilters('desktop');
            renderReports();
        });
        statusCheckboxes.forEach(cb => {
            cb.addEventListener('change', () => {
                syncFilters('desktop');
                renderReports();
            });
        });

        // Mobile Filter Listeners
        filterKategoriMobile.addEventListener('change', () => {
            syncFilters('mobile');
            renderReports();
        });
        statusCheckboxesMobile.forEach(cb => {
            cb.addEventListener('change', () => {
                syncFilters('mobile');
                renderReports();
            });
        });

        // Search Input
        searchInput.addEventListener('input', renderReports);

        // Reset Filters (Desktop)
        btnResetFilter.addEventListener('click', () => {
            filterKategori.value = '';
            statusCheckboxes.forEach(cb => cb.checked = true);
            syncFilters('desktop');
            searchInput.value = '';
            renderReports();
        });

        // Reset Filters (Mobile)
        btnResetFilterMobile.addEventListener('click', () => {
            filterKategoriMobile.value = '';
            statusCheckboxesMobile.forEach(cb => cb.checked = true);
            syncFilters('mobile');
            searchInput.value = '';
            renderReports();
        });

        // Geolocation "Lokasi Saya"
        btnLokasiSaya.addEventListener('click', () => {
            if (!navigator.geolocation) {
                alert('Geolocation tidak didukung oleh browser Anda');
                return;
            }

            btnLokasiSaya.disabled = true;
            btnLokasiSaya.innerHTML = '⏳ Mencari lokasi...';

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    
                    // Add temporary user location marker
                    L.circle([lat, lng], {
                        color: '#16a34a',
                        fillColor: '#16a34a',
                        fillOpacity: 0.15,
                        radius: 200
                    }).addTo(map);

                    L.marker([lat, lng], {
                        icon: L.divIcon({
                            className: 'custom-div-icon',
                            html: '<div class="w-4 h-4 bg-green-600 rounded-full border-2 border-white shadow animate-ping"></div>'
                        })
                    }).addTo(map);

                    map.setView([lat, lng], 14);

                    btnLokasiSaya.disabled = false;
                    btnLokasiSaya.innerHTML = '📍 Lokasi Saya';
                },
                (error) => {
                    alert('Gagal mendapatkan lokasi: ' + error.message);
                    btnLokasiSaya.disabled = false;
                    btnLokasiSaya.innerHTML = '📍 Lokasi Saya';
                }
            );
        });
    }

    // ── Launch ────────────────────────────────────────────────────────
    initMap();
    setupListeners();
    fetchReports();
});
