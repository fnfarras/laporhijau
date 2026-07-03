document.addEventListener("DOMContentLoaded",()=>{const b=document.getElementById("sidebar-list-container"),f=document.getElementById("sidebar-search"),p=document.getElementById("report-count-indicator"),d=document.getElementById("filter-kategori"),w=document.getElementById("btn-reset-filter"),u=document.querySelectorAll(".status-filter-checkbox"),g=document.getElementById("filter-kategori-mobile"),E=document.getElementById("btn-reset-filter-mobile"),m=document.querySelectorAll(".status-filter-checkbox-mobile"),c=document.getElementById("btn-lokasi-saya");let s=null,h=null,v=[],y={};function $(){const e=[.507,101.4478];s=L.map("interactive-map",{zoomControl:!0,maxZoom:18,minZoom:6}).setView(e,12),L.tileLayer("https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png",{attribution:'&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="https://carto.com/">CARTO</a>',subdomains:"abcd",maxZoom:20}).addTo(s),h=L.markerClusterGroup({showCoverageOnHover:!1,zoomToBoundsOnClick:!0,maxClusterRadius:40}),s.addLayer(h)}function C(e){switch(e){case"pending":return"#f59e0b";case"verified":return"#0ea5e9";case"in_progress":return"#f97316";case"resolved":return"#16a34a";case"rejected":return"#ef4444";default:return"#94a3b8"}}function M(e){const a=C(e);return L.divIcon({className:"custom-div-icon",html:`<div class="marker-pin" style="background-color: ${a};"></div>`,iconSize:[30,42],iconAnchor:[15,42],popupAnchor:[0,-36]})}function k(e){switch(e){case"pending":return{label:"⏳ Pending",class:"bg-amber-50 text-amber-700 border-amber-200"};case"verified":return{label:"✅ Verified",class:"bg-sky-50 text-sky-700 border-sky-200"};case"in_progress":return{label:"🔧 In Progress",class:"bg-orange-50 text-orange-700 border-orange-200"};case"resolved":return{label:"🎉 Selesai",class:"bg-green-50 text-green-700 border-green-200"};case"rejected":return{label:"❌ Ditolak",class:"bg-red-50 text-red-700 border-red-200"};default:return{label:e,class:"bg-gray-50 text-gray-700 border-gray-200"}}}function T(e){const a=k(e.status);return`
            <div class="p-1 font-sans max-w-[240px]">
                ${e.photo_url?`<div class="w-full h-28 mb-3 rounded-lg overflow-hidden bg-gray-100">
                 <img src="${e.photo_url}" class="w-full h-full object-cover" alt="${e.title}">
               </div>`:""}
                <div class="flex items-center gap-1.5 mb-1 flex-wrap">
                    <span class="text-[10px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full font-semibold">
                        ${e.category.icon} ${e.category.name}
                    </span>
                    <span class="text-[10px] px-2 py-0.5 rounded-full border font-bold ${a.class}">
                        ${a.label}
                    </span>
                </div>
                <h4 class="font-bold text-gray-900 text-sm leading-snug mb-1.5">${e.title}</h4>
                <p class="text-xs text-gray-500 mb-1 leading-snug">📍 ${e.address}</p>
                <p class="text-[10px] text-gray-400 mb-3">👤 ${e.reporter_name} · ${e.created_at}</p>
                <a href="/laporan/${e.id}" class="block text-center w-full py-1.5 bg-green-600 hover:bg-green-700 active:bg-green-800 text-white font-bold text-xs rounded-lg transition-colors" style="text-decoration:none;">
                    Lihat Detail →
                </a>
            </div>
        `}async function I(){try{const e=await fetch(window.MAP_DATA_URL);if(!e.ok)throw new Error("Gagal mengambil data peta");v=await e.json(),o()}catch(e){console.error(e),b.innerHTML=`
                <div class="p-6 text-center text-red-500 text-xs font-semibold">
                    ⚠️ ${e.message}
                </div>
            `,p.textContent="Gagal memuat data"}}function B(){const e=window.innerWidth<768,a=e?g.value:d.value,t=Array.from(e?m:u).filter(l=>l.checked).map(l=>l.value),r=f.value.toLowerCase().trim();return{category:a,statuses:t,keyword:r}}function i(e){e==="desktop"?(g.value=d.value,m.forEach((a,n)=>{a.checked=u[n].checked})):e==="mobile"&&(d.value=g.value,u.forEach((a,n)=>{a.checked=m[n].checked}))}function o(){h.clearLayers(),y={},b.innerHTML="";const e=B(),a=v.filter(t=>{if(e.category&&t.category.id!=e.category||!e.statuses.includes(t.status))return!1;if(e.keyword){const r=t.title.toLowerCase().includes(e.keyword),l=t.address.toLowerCase().includes(e.keyword);if(!r&&!l)return!1}return!0});if(p.textContent=`${a.length} laporan terpantau`,a.length===0){b.innerHTML=`
                <div class="p-8 text-center text-gray-400 text-xs">
                     🔍 Tidak ada laporan cocok
                </div>
            `;return}const n=L.latLngBounds();a.forEach(t=>{const r=L.marker([t.latitude,t.longitude],{icon:M(t.status)});r.bindPopup(T(t)),h.addLayer(r),y[t.id]=r,n.extend([t.latitude,t.longitude]);const l=k(t.status),x=document.createElement("div");x.className="p-3 border border-gray-100 rounded-xl hover:border-green-300 hover:shadow-sm cursor-pointer transition-all flex gap-3 bg-white";const A=t.photo_url?`<img src="${t.photo_url}" class="w-12 h-12 rounded-lg object-cover flex-shrink-0 bg-gray-50" alt="${t.title}">`:'<div class="w-12 h-12 rounded-lg bg-slate-50 flex items-center justify-center text-lg flex-shrink-0">📷</div>';x.innerHTML=`
                ${A}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-0.5">
                        <span class="text-[9px] text-gray-400 font-semibold truncate max-w-[70px]">${t.category.name}</span>
                        <span class="text-[9px] font-bold ${l.class} border px-1 rounded-sm">${l.label}</span>
                    </div>
                    <h5 class="text-xs font-bold text-gray-800 line-clamp-1">${t.title}</h5>
                    <p class="text-[10px] text-gray-400 truncate mt-0.5">📍 ${t.address}</p>
                </div>
            `,x.addEventListener("click",()=>{s.setView([t.latitude,t.longitude],16),setTimeout(()=>{r.openPopup()},100)}),b.appendChild(x)}),a.length>0&&(e.category||e.keyword||e.statuses.length<5)&&s.fitBounds(n,{padding:[50,50]})}function H(){d.addEventListener("change",()=>{i("desktop"),o()}),u.forEach(e=>{e.addEventListener("change",()=>{i("desktop"),o()})}),g.addEventListener("change",()=>{i("mobile"),o()}),m.forEach(e=>{e.addEventListener("change",()=>{i("mobile"),o()})}),f.addEventListener("input",o),w.addEventListener("click",()=>{d.value="",u.forEach(e=>e.checked=!0),i("desktop"),f.value="",o()}),E.addEventListener("click",()=>{g.value="",m.forEach(e=>e.checked=!0),i("mobile"),f.value="",o()}),c.addEventListener("click",()=>{if(!navigator.geolocation){alert("Geolocation tidak didukung oleh browser Anda");return}c.disabled=!0,c.innerHTML="⏳ Mencari lokasi...",navigator.geolocation.getCurrentPosition(e=>{const a=e.coords.latitude,n=e.coords.longitude;L.circle([a,n],{color:"#16a34a",fillColor:"#16a34a",fillOpacity:.15,radius:200}).addTo(s),L.marker([a,n],{icon:L.divIcon({className:"custom-div-icon",html:'<div class="w-4 h-4 bg-green-600 rounded-full border-2 border-white shadow animate-ping"></div>'})}).addTo(s),s.setView([a,n],14),c.disabled=!1,c.innerHTML="📍 Lokasi Saya"},e=>{alert("Gagal mendapatkan lokasi: "+e.message),c.disabled=!1,c.innerHTML="📍 Lokasi Saya"})})}$(),H(),I()});
