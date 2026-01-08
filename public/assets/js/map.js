/* Peta publik SIG Desa */
const map = L.map("map").setView([-6.9, 110.5], 13);

// Basemap OSM
L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
  maxZoom: 19,
  attribution: '&copy; OpenStreetMap contributors',
}).addTo(map);

let boundaryLayer;
const markerLayer = L.layerGroup().addTo(map);

// Ambil batas desa
fetch("/GIS/api/get_boundaries.php")
  .then((r) => r.json())
  .then((geojson) => {
    if (!geojson || !geojson.type) return;
    boundaryLayer = L.geoJSON(geojson, {
      style: {
        color: "#f97316",
        weight: 2,
        fillColor: "#fb923c",
        fillOpacity: 0.18,
      },
    }).addTo(map);
    try {
      map.fitBounds(boundaryLayer.getBounds(), { padding: [24, 24] });
    } catch (e) {
      // ignore
    }
  })
  .catch((err) => console.error("Boundary error", err));

// Ambil marker
fetch("/GIS/api/get_markers.php")
  .then((r) => r.json())
  .then((rows) => {
    if (!Array.isArray(rows)) return;
    markerLayer.clearLayers();
    rows.forEach((item) => {
      if (!item.latitude || !item.longitude) return;
      const m = L.circleMarker([item.latitude, item.longitude], {
        radius: 8,
        fillColor: "#2563eb",
        color: "#ffffff",
        weight: 1,
        opacity: 1,
        fillOpacity: 0.9,
      }).bindPopup(
        `<strong>${item.name || "Fasilitas"}</strong><br/>${item.description || ""}`
      );
      m.addTo(markerLayer);
    });
  })
  .catch((err) => console.error("Marker error", err));

