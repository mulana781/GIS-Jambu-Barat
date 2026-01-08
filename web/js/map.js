// Inisialisasi peta terpusat ke perkiraan lokasi Desa Jambu Barat
// (Silakan sesuaikan koordinat jika Anda memiliki data yang lebih akurat)
const INITIAL_COORDS = [-6.5099748,110.6635872,4116]; // Contoh koordinat di Jawa Tengah
const INITIAL_ZOOM = 14;

const map = L.map("map").setView(INITIAL_COORDS, INITIAL_ZOOM);

// ---------- Basemap ----------
const osm = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
  maxZoom: 19,
  attribution:
    '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
});

const esriSat = L.tileLayer(
  "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}",
  {
    maxZoom: 19,
    attribution:
      "Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, " +
      "Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community",
  }
);

// Set default basemap
osm.addTo(map);

// ---------- Batas Desa (GeoJSON) ----------
let batasDesaLayer;
const batasDesaCheckbox = document.querySelector("#toggle-batas-desa");

fetch("../batas desa.geojson")
  .then((response) => response.json())
  .then((data) => {
    batasDesaLayer = L.geoJSON(data, {
      style: {
        color: "#f97316", // oranye
        weight: 2,
        fillColor: "#fed7aa",
        fillOpacity: 0.25,
      },
      onEachFeature: (feature, layer) => {
        // Popup info desa saat area batas diklik
        const namaDesa =
          (feature.properties && (feature.properties.nama || feature.properties.NAMOBJ || feature.properties.desa)) ||
          "Desa Jambu Barat";
        const kecamatan =
          feature.properties && (feature.properties.kecamatan || feature.properties.KEC || feature.properties.WADMKC);
        const kabupaten =
          feature.properties && (feature.properties.kabupaten || feature.properties.KAB || feature.properties.WADMKK);

        let content = `<strong>${namaDesa}</strong>`;
        if (kecamatan || kabupaten) {
          content += "<br/>";
        }
        if (kecamatan) {
          content += `Kec. ${kecamatan}<br/>`;
        }
        if (kabupaten) {
          content += `Kab. ${kabupaten}`;
        }

        layer.bindPopup(content);
      },
    });

    // Tambahkan ke peta hanya jika checkbox aktif (atau belum ada checkbox)
    if (!batasDesaCheckbox || batasDesaCheckbox.checked) {
      batasDesaLayer.addTo(map);
    }

    // Sesuaikan tampilan peta ke batas desa
    try {
      map.fitBounds(batasDesaLayer.getBounds(), { padding: [20, 20] });
    } catch (e) {
      console.warn("Tidak dapat menghitung bounds batas desa:", e);
    }
  })
  .catch((err) => {
    console.error("Gagal memuat GeoJSON batas desa:", err);
  });

// Toggle batas desa via checkbox
if (batasDesaCheckbox) {
  batasDesaCheckbox.addEventListener("change", (e) => {
    if (!batasDesaLayer) return; // belum termuat

    if (e.target.checked) {
      batasDesaLayer.addTo(map);
    } else {
      map.removeLayer(batasDesaLayer);
    }
  });
}

// ---------- Marker Fasilitas Umum ----------
// Data dummy fasilitas di sekitar desa (silakan ganti dengan data sebenarnya)
const fasilitas = {
  education: [
    {
      name: "SD Negeri 2 Jambu Barat",
      coords: [-6.524495089095201, 110.70646086594401],
      description: "Sekolah Dasar Negeri",
    },
    {
      name: "SD Negeri 4 Jambu Barat",
      coords: [-6.527118921596079, 110.70703618878578],
      description: "Sekolah Dasar Negeri",
    },
    {
      name: "SD Negeri 6 Jambu Barat",
      coords: [-6.5230257459623875, 110.69785232709923],
      description: "Sekolah Dasar Negeri",
    },
    {
      name: "SD Negeri 9 Jambu Barat",
      coords: [-6.525994771899146, 110.6919225381879],
      description: "Sekolah Dasar Negeri",
    },
    {
      name: "SD Negeri 11 Jambu",
      coords: [-6.5199947325541485, 110.69839123148131],
      description: "Sekolah Dasar Negeri",
    },    
    {
      name: "TPQ ASY SYUHADA'",
      coords: [-6.523764673660157, 110.70217983619264],
      description: "TPQ",
    },    
    {
      name: "MA Mathalibul Huda",
      coords: [-6.527769237146899, 110.70505768051243],
      description: "Sekolah Menengah Atas",
    },    
    {
      name: "Madrasah Ibtidaiyah Mathalibul Huda",
      coords: [-6.528757884357666, 110.70224748722232],
      description: "Madrasah Ibtidaiyah",
    },    
    {
      name: "TK TA JAMBU 06 dan KB MAWARDI 06 MNU",
      coords: [-6.52379863973222, 110.69782208116895],
      description: "Kelompok Bermain",
    },    
  ],
  health: [
    {
      name: "RSU Ashavin Jepara",
      coords: [-6.529316162715326, 110.70013821955288],
      description: "Rumah Sakit Umum",
    },
    {
      name: "Praktik drg. Rizkia Febri",
      coords: [-6.529696005639818, 110.70031167435083],
      description: "Klinik Gigi",
    },
    {
      name: "Poliklinik Kesehatan Desa",
      coords: [-6.520491561097767, 110.69874540771714],
      description: "Klinik Kesehatan",
    },
  ],
  worship: [
    {
      name: "Musholla AL ISTIQOMAH",
      coords: [-6.5282675299355795, 110.69964464721387],
      description: "Musholla lingkungan",
    },
    {
      name: "Mushala Darussalam",
      coords: [-6.529538674444921, 110.69837893740886],
      description: "Mushola lingkungan",
    },
    {
      name: "Masjid Al Hidayah Assyubakir",
      coords: [-6.525338365234253, 110.69040464473748],
      description: "Masjid lingkungan",
    },
    {
      name: "Mushola Nurul Huda",
      coords: [-6.530419390493025, 110.70121490233375],
      description: "Mushola lingkungan",
    },
    {
      name: "GKMI Mlonggo - Jepara",
      coords: [-6.528969734062818, 110.70112907164697],
      description: "Gereja lingkungan",
    },
    {
      name: "Masjid Besar Baiturrohman Mlonggo",
      coords: [-6.527126813945887, 110.70759020583434],
      description: "Masjid lingkungan",
    },
    {
      name: "Masjid Jami",
      coords: [-6.527249379814488, 110.70818836746987],
      description: "Masjid lingkungan",
    },
    {
      name: "Mushola Baitul Muttaqiin Jambu",
      coords: [-6.526232288463486, 110.71423162551008],
      description: "Mushola lingkungan",
    },
    {
      name: "Masjid Baiturrohman 2",
      coords: [-6.5265740639865175, 110.71289974169099],
      description: "Masjid lingkungan",
    },
    {
      name: "Musholla At Taubah",
      coords: [-6.5223729477506955, 110.70357437627008],
      description: "Mushola lingkungan",
    },
    {
      name: "Musholla Darussalam",
      coords: [-6.521674756928878, 110.69904148559547],
      description: "Mushola lingkungan",
    },
    {
      name: "Masjid BAITUL MUTTAQIN",
      coords: [-6.523444215734321, 110.69781839835179],
      description: "Masjid lingkungan",
    },
    {
      name: "Musolla Darus Surur",
      coords: [-6.52673528595033, 110.68222390587857],
      description: "Mushola lingkungan",
    },
    {
      name: "musollah as-shidiqin",
      coords: [-6.523817293623546, 110.7002162931495],
      description: "Mushola lingkungan",
    },
    {
      name: "Mushola Nurul Hadi",
      coords: [-6.525714656743397, 110.70167541484004],
      description: "Mushola lingkungan",
    },
  ],
  public: [
    {
      name: "Lapangan Jambu",
      coords: [-6.526834719681386, 110.70121994074395],
      description: "Bal bal an",
    },
    {
      name: "SPBU Pertamina Mlonggo",
      coords: [-6.528027833551538, 110.7030363602854],
      description: "Pom bensin/SPBU",
    },
    {
      name: "Balai Desa Jambu",
      coords: [-6.5268768547800775, 110.71146077873364],
      description: "Kantor Pemerintah",
    },
    {
      name: "TPI Mlonggo Tempat Pelelangan Ikan",
      coords: [-6.527204961210329, 110.68239098139922],
      description: "Pasar ikan",
    },
  ],
};

// LayerGroup untuk tiap kategori fasilitas
const overlayLayers = {
  education: L.layerGroup(),
  health: L.layerGroup(),
  worship: L.layerGroup(),
  public: L.layerGroup(),
};

// Helper untuk memilih warna berdasarkan kategori
function getCategoryColor(category) {
  switch (category) {
    case "education":
      return "#2563eb";
    case "health":
      return "#dc2626";
    case "worship":
      return "#059669";
    case "public":
      return "#d97706";
    default:
      return "#374151";
  }
}

// Helper untuk membuat icon khusus per kategori (divIcon dengan emoji)
function getCategoryIcon(category) {
  const color = getCategoryColor(category);

  const emojiMap = {
    education: "🏫",
    health: "🏥",
    worship: "⛪",
    public: "⭐",
  };

  const emoji = emojiMap[category] || "•";

  return L.divIcon({
    className: `facility-marker facility-marker-${category}`,
    html: `<span class="facility-marker-emoji">${emoji}</span>`,
    iconSize: [26, 26],
    iconAnchor: [13, 13],
    popupAnchor: [0, -14],
  });
}

// Buat marker untuk tiap fasilitas
Object.entries(fasilitas).forEach(([category, items]) => {
  const group = overlayLayers[category];

  items.forEach((item) => {
    const marker = L.marker(item.coords, {
      icon: getCategoryIcon(category),
    }).bindPopup(
      `<strong>${item.name}</strong><br/><span>${item.description}</span>`
    );

    marker.addTo(group);
  });
});

// Tambahkan semua overlay ke peta sebagai default
overlayLayers.education.addTo(map);
overlayLayers.health.addTo(map);
overlayLayers.worship.addTo(map);
overlayLayers.public.addTo(map);

// ---------- Interaksi Sidebar ----------
// Ganti basemap via radio button
const basemapRadios = document.querySelectorAll('input[name="basemap"]');

basemapRadios.forEach((radio) => {
  radio.addEventListener("change", (e) => {
    const value = e.target.value;

    // Hapus layer dasar yang aktif
    map.removeLayer(osm);
    map.removeLayer(esriSat);

    if (value === "satellite") {
      esriSat.addTo(map);
    } else {
      osm.addTo(map);
    }
  });
});

// Toggle fasilitas umum via checkbox
const overlayCheckboxes = document.querySelectorAll(".overlay-toggle");

overlayCheckboxes.forEach((checkbox) => {
  checkbox.addEventListener("change", (e) => {
    const category = e.target.value;
    const group = overlayLayers[category];

    if (!group) return;

    if (e.target.checked) {
      group.addTo(map);
    } else {
      map.removeLayer(group);
    }
  });
});

// ---------- Legenda Peta ----------
const legend = L.control({ position: "bottomright" });

legend.onAdd = function () {
  const div = L.DomUtil.create("div", "map-legend");

  const items = [
    { label: "Batas Desa", color: "#f97316", type: "line" },
    { label: "Pendidikan", color: getCategoryColor("education"), emoji: "🏫" },
    { label: "Kesehatan", color: getCategoryColor("health"), emoji: "🏥" },
    { label: "Tempat Ibadah", color: getCategoryColor("worship"), emoji: "⛪" },
    { label: "Fasilitas Umum Lainnya", color: getCategoryColor("public"), emoji: "⭐" },
  ];

  let html = '<div class="map-legend-title">Legenda</div>';

  items.forEach((item) => {
    if (item.type === "line") {
      html += `
        <div class="map-legend-item">
          <span class="map-legend-line" style="border-color: ${item.color}"></span>
          <span class="map-legend-label">${item.label}</span>
        </div>
      `;
    } else {
      html += `
        <div class="map-legend-item">
          <span class="map-legend-color" style="background-color: ${item.color}"></span>
          <span class="map-legend-label">${item.emoji ? item.emoji + " " : ""}${item.label}</span>
        </div>
      `;
    }
  });

  div.innerHTML = html;
  return div;
};

legend.addTo(map);

// ---------- Mode Layar Penuh (Fokus Peta) ----------
const fullscreenButton = document.querySelector("#toggle-fullscreen");
const fullscreenFab = document.querySelector("#map-exit-fullscreen");

function toggleMapFullscreen() {
  document.body.classList.toggle("map-full");
  const isFull = document.body.classList.contains("map-full");

  if (fullscreenButton) {
    fullscreenButton.textContent = isFull ? "Keluar Mode Peta Penuh" : "Mode Layar Penuh";
  }

  setTimeout(() => {
    map.invalidateSize();
  }, 200);
}

if (fullscreenButton) {
  fullscreenButton.addEventListener("click", toggleMapFullscreen);
}

if (fullscreenFab) {
  fullscreenFab.addEventListener("click", toggleMapFullscreen);
}


