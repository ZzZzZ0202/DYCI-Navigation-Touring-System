<?php
session_start();
require_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Map - DYCI Tour System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .map-container {
            position: relative;
            height: calc(100vh - 76px);
            width: 100%;
        }

        #map {
            height: 100%;
            width: 100%;
        }

        .navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            color: #4834d4 !important;
            font-weight: 600;
            font-size: 1.2rem;
        }

        .back-btn {
            background-color: #ffd32a;
            color: #333;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            border: none;
        }

        .back-btn:hover {
            background-color: #ffc800;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            text-decoration: none;
            color: #333;
        }

        .controls-toggle {
            position: absolute;
            right: 1rem;
            top: 1rem;
            z-index: 1000;
            background: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .controls-toggle:hover {
            background: #e8f0fe;
            transform: scale(1.1);
        }

        .map-controls {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1000;
            background: white;
            border-radius: 2px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.3);
        }

        .map-controls.hidden {
            display: none;
        }

        .control-btn {
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .control-btn:hover {
            background: #e8f0fe;
            transform: scale(1.05);
        }

        .search-container {
            position: absolute;
            left: 1rem;
            top: 1rem;
            z-index: 1000;
            width: 300px;
            transition: all 0.3s ease;
        }

        .search-container.hidden {
            display: none;
        }

        .search-input {
            width: 100%;
            padding: 0.75rem;
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
            font-size: 1rem;
        }

        .floor-controls {
            position: absolute;
            left: 1rem;
            bottom: 1rem;
            z-index: 1000;
            background: white;
            border-radius: 8px;
            padding: 0.5rem;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
            display: flex;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .floor-controls.hidden {
            display: none;
        }

        .floor-btn {
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 0.5rem 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .floor-btn:hover {
            background: #e8f0fe;
        }

        .floor-btn.active {
            background: #4834d4;
            color: white;
            border-color: #4834d4;
        }

        .legend {
            position: absolute;
            right: 1rem;
            bottom: 1rem;
            z-index: 1000;
            background: white;
            border-radius: 8px;
            padding: 1rem;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 4px;
        }

        .map-type-control {
            padding: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            font-size: 11px;
            font-family: Roboto, Arial, sans-serif;
            color: #666;
            border-bottom: 1px solid #e6e6e6;
        }

        .map-type-control:last-child {
            border-bottom: none;
        }

        .map-type-control:hover {
            background: #f4f4f4;
        }

        .map-type-control.active {
            color: #000;
            font-weight: 500;
        }

        .zoom-controls {
            position: absolute;
            right: 0;
            bottom: 100px;
            margin: 10px;
            z-index: 1000;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            overflow: hidden;
        }

        .zoom-button {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #666;
            font-size: 20px;
            border-bottom: 1px solid #e6e6e6;
            transition: background-color 0.2s;
        }

        .zoom-button:hover {
            background-color: #f8f9fa;
            color: #1a237e;
        }

        .zoom-button:last-child {
            border-bottom: none;
        }

        .building-label {
            background: #1a237e;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: 500;
            font-size: 12px;
            font-family: Roboto, Arial, sans-serif;
            white-space: nowrap;
            box-shadow: 0 1px 4px rgba(0,0,0,0.2);
            z-index: 1000;
            border: none;
            line-height: 1.2;
            text-align: left;
        }

        /* Building markers */
        .marker-icon {
            background: #4CAF50;
            border: 3px solid white;
            border-radius: 50%;
            box-shadow: 0 2px 6px rgba(0,0,0,0.3);
            transition: all 0.3s ease;
        }

        .marker-line {
            stroke: #1a237e;
            stroke-width: 1.5px;
            stroke-opacity: 0.8;
        }

        /* Google Maps style controls */
        .gm-control {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            margin: 10px;
            overflow: hidden;
        }

        .gm-control-header {
            padding: 8px 12px;
            background: #f8f9fa;
            border-bottom: 1px solid #e6e6e6;
            font-size: 13px;
            color: #1a73e8;
            font-weight: 500;
        }

        /* Map type controls */
        .map-type-wrapper {
            position: absolute;
            top: 0;
            right: 0;
            margin: 10px;
            z-index: 1000;
        }

        .map-type-control {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            overflow: hidden;
        }

        .map-type-button {
            padding: 8px 16px;
            font-size: 13px;
            font-family: 'Google Sans', Roboto, Arial, sans-serif;
            color: #3c4043;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            border-bottom: 1px solid #e6e6e6;
            transition: background-color 0.2s;
        }

        .map-type-button:last-child {
            border-bottom: none;
        }

        .map-type-button:hover {
            background-color: #f8f9fa;
        }

        .map-type-button.active {
            color: #1a73e8;
            background-color: #e8f0fe;
        }

        /* Search box */
        .search-box {
            position: absolute;
            top: 0;
            left: 0;
            margin: 10px;
            z-index: 1000;
        }

        .search-input {
            width: 400px;
            padding: 12px 16px 12px 40px;
            background: #fff;
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            font-size: 14px;
            font-family: 'Google Sans', Roboto, Arial, sans-serif;
            color: #3c4043;
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #5f6368;
        }

        .search-input:focus {
            outline: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        }

        /* Add new styles for the no-data message */
        .map-no-data-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.7);
            display: none;
            z-index: 1000;
            pointer-events: none;
        }

        .map-no-data-message {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: rgba(0, 0, 0, 0.7);
            font-family: Roboto, Arial, sans-serif;
            font-size: 16px;
            font-style: italic;
            white-space: nowrap;
        }

        /* Add styles for tooltip */
        .leaflet-tooltip {
            background: #1a237e;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 6px 12px;
            font-family: Roboto, Arial, sans-serif;
            font-size: 14px;
            font-weight: 500;
            box-shadow: 0 2px 6px rgba(0,0,0,0.3);
            white-space: nowrap;
            transition: all 0.3s ease;
        }

        .leaflet-tooltip-top:before {
            border-top-color: #1a237e;
        }

        .leaflet-popup-content-wrapper {
            background: #1a237e;
            color: white;
            border-radius: 4px;
            padding: 4px 8px;
            font-family: Roboto, Arial, sans-serif;
            font-size: 12px;
            font-weight: 500;
            box-shadow: 0 1px 4px rgba(0,0,0,0.2);
        }

        .leaflet-popup-tip {
            background: #1a237e;
        }

        .leaflet-popup-close-button {
            display: none;
        }

        .settings-menu {
            position: absolute;
            top: 60px;
            right: 10px;
            width: 200px;
            z-index: 1000;
        }

        .settings-content {
            padding: 8px 12px;
        }

        .settings-item {
            padding: 8px 0;
            border-bottom: 1px solid #e6e6e6;
            font-size: 13px;
            color: #3c4043;
        }

        .settings-item:last-child {
            border-bottom: none;
        }

        .settings-item label {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0;
            cursor: pointer;
        }

        .settings-item input[type="checkbox"] {
            margin: 0;
        }

        .map-type-button {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .map-type-button:hover {
            background-color: #f8f9fa;
        }

        .map-type-button.active {
            background-color: #e8f0fe;
            color: #1a73e8;
        }

        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <span class="navbar-brand">
                <i class="fas fa-map-marked-alt mr-2"></i>
                DYCI Campus Map
            </span>
            <a href="dashboard.php" class="back-btn">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
        </div>
    </nav>

    <div class="map-container">
        <button class="controls-toggle" onclick="toggleControls()" title="Toggle Controls">
            <i class="fas fa-cog"></i>
        </button>

        <div class="search-container hidden">
            <input type="text" class="search-input" placeholder="Search buildings, facilities, or rooms..." onkeyup="searchLocations(this.value)">
        </div>

        <div id="map"></div>
        
        <div class="legend">
            <h6 class="mb-2">Legend</h6>
            <div class="legend-item">
                <div class="legend-color" style="background: #8B4513;"></div>
                <span>Buildings</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: #8B4513;"></div>
                <span>Elida Covered Court</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: #666666;"></div>
                <span>Paths</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: #95a5a6;"></div>
                <span>Parking</span>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Define building coordinates and paths
            const buildings = {
                officesBuilding: {
                    coords: [14.800041695367383, 120.92807327918848],
                    name: 'Offices Building'
                },
                elidaCourt: {
                    coords: [14.799741143142992, 120.92780105386683],
                    name: 'Elida Campus Court'
                },
                buildingB: {
                    coords: [14.79938540751434, 120.92793002322396],
                    name: 'Building (B)'
                },
                buildingA: {
                    coords: [14.79903700607531, 120.92798312825337],
                    name: 'Building (A)'
                },
                canteenStore: {
                    coords: [14.79906625938845, 120.92775217781148],
                    name: 'Canteen/Store'
                },
                parkingArea: {
                    coords: [14.798959990944827, 120.9277858810013],
                    name: 'Parking Area'
                },
                entrance: {
                    coords: [14.798736280172603, 120.92793002322396],
                    name: 'Entrance'
                }
            };

            // Initialize map
            const map = L.map('map', {
                preferCanvas: true,
                zoomControl: false,
                minZoom: 17,
                maxZoom: 22
            }).setView([14.799389, 120.927932], 18);

            // Define map layers
            const satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 22,
                attribution: '© Esri'
            });

            const streetLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 22,
                attribution: '© OpenStreetMap contributors'
            });

            // Add satellite layer as default
            satelliteLayer.addTo(map);

            // Create map type control
            const mapTypeControl = L.DomUtil.create('div', 'gm-control map-type-wrapper');
            mapTypeControl.innerHTML = `
                <div class="map-type-control">
                    <div class="map-type-button active" data-type="satellite">
                        <i class="fas fa-satellite"></i>
                        Satellite
                    </div>
                    <div class="map-type-button" data-type="street">
                        <i class="fas fa-map"></i>
                        Map
                    </div>
                </div>
            `;

            // Add settings menu
            const settingsMenu = L.DomUtil.create('div', 'gm-control settings-menu hidden');
            settingsMenu.innerHTML = `
                <div class="gm-control-header">
                    <i class="fas fa-cog mr-2"></i>Settings
                </div>
                <div class="settings-content">
                    <div class="settings-item">
                        <label>
                            <input type="checkbox" id="showLabels" checked>
                            Show building labels
                        </label>
                    </div>
                    <div class="settings-item">
                        <label>
                            <input type="checkbox" id="showTooltips" checked>
                            Show tooltips on hover
                        </label>
                    </div>
                </div>
            `;

            // Add controls to map
            map.getContainer().appendChild(mapTypeControl);
            map.getContainer().appendChild(settingsMenu);

            // Add event listeners for map type buttons
            const mapTypeButtons = mapTypeControl.querySelectorAll('.map-type-button');
            mapTypeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const type = this.dataset.type;
                    mapTypeButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');

                    if (type === 'satellite') {
                        map.removeLayer(streetLayer);
                        map.addLayer(satelliteLayer);
                    } else {
                        map.removeLayer(satelliteLayer);
                        map.addLayer(streetLayer);
                    }
                });
            });

            // Update settings toggle function
            function toggleControls() {
                controlsVisible = !controlsVisible;
                settingsMenu.classList.toggle('hidden');
                
                // Update toggle button icon
                const toggleBtn = document.querySelector('.controls-toggle i');
                toggleBtn.className = controlsVisible ? 'fas fa-times' : 'fas fa-cog';
            }

            // Add settings functionality
            document.getElementById('showLabels')?.addEventListener('change', function() {
                const labels = document.querySelectorAll('.building-label');
                labels.forEach(label => {
                    label.style.display = this.checked ? 'block' : 'none';
                });
            });

            document.getElementById('showTooltips')?.addEventListener('change', function() {
                for (let id in buildings) {
                    if (this.checked) {
                        markers[id].bindTooltip(buildings[id].name, {
                            direction: 'top',
                            offset: getTooltipOffset(getMarkerSize(map.getZoom())),
                            permanent: false,
                            opacity: 1
                        });
                    } else {
                        markers[id].unbindTooltip();
                    }
                }
            });

            // Add new styles
            const styleSheet = document.createElement('style');
            styleSheet.textContent = `
                .settings-menu {
                    position: absolute;
                    top: 60px;
                    right: 10px;
                    width: 200px;
                    z-index: 1000;
                }

                .settings-content {
                    padding: 8px 12px;
                }

                .settings-item {
                    padding: 8px 0;
                    border-bottom: 1px solid #e6e6e6;
                    font-size: 13px;
                    color: #3c4043;
                }

                .settings-item:last-child {
                    border-bottom: none;
                }

                .settings-item label {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    margin: 0;
                    cursor: pointer;
                }

                .settings-item input[type="checkbox"] {
                    margin: 0;
                }

                .map-type-button {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    padding: 8px 16px;
                    cursor: pointer;
                    transition: background-color 0.2s;
                }

                .map-type-button:hover {
                    background-color: #f8f9fa;
                }

                .map-type-button.active {
                    background-color: #e8f0fe;
                    color: #1a73e8;
                }

                .hidden {
                    display: none;
                }
            `;
            document.head.appendChild(styleSheet);

            // Function to get marker size based on zoom level
            function getMarkerSize(zoom) {
                // Base size that scales with zoom
                const baseSize = Math.pow(1.5, zoom - 17) * 16;
                
                // Constrain size between 16 and 40 pixels
                return Math.min(Math.max(baseSize, 16), 40);
            }

            // Function to get tooltip offset based on marker size
            function getTooltipOffset(markerSize) {
                return [0, -(markerSize/2 + 8)];
            }

            // Update markers and labels
            const markers = {};

            for (let id in buildings) {
                const building = buildings[id];
                
                const createMarker = () => {
                    const size = getMarkerSize(map.getZoom());
                    const marker = L.marker(building.coords, {
                        icon: L.divIcon({
                            className: 'marker-icon',
                            iconSize: [size, size],
                            iconAnchor: [size/2, size/2]
                        })
                    });

                    // Add tooltip that shows on hover
                    marker.bindTooltip(building.name, {
                        direction: 'top',
                        offset: getTooltipOffset(size),
                        permanent: false,
                        opacity: 1
                    });

                    // Add popup that shows on click
                    marker.bindPopup(building.name, {
                        closeButton: false,
                        offset: getTooltipOffset(size),
                        autoPan: false
                    });

                    // Add click handler
                    marker.on('click', function(e) {
                        if (marker.isPopupOpen()) {
                            marker.closeTooltip();
                        }
                        const targetZoom = Math.min(map.getZoom() + 1, 19);
                        map.flyTo(building.coords, targetZoom, {
                            duration: 0.5
                        });
                    });

                    return marker;
                };

                markers[id] = createMarker().addTo(map);
            }

            // Update markers on zoom
            map.on('zoomend', function() {
                const zoom = map.getZoom();
                
                // Update all markers with smooth transition
                for (let id in markers) {
                    const size = getMarkerSize(zoom);
                    const icon = L.divIcon({
                        className: 'marker-icon',
                        iconSize: [size, size],
                        iconAnchor: [size/2, size/2]
                    });
                    markers[id].setIcon(icon);

                    // Update tooltip and popup offsets
                    const offset = getTooltipOffset(size);
                    markers[id].getTooltip()?.setOffset(offset);
                    markers[id].getPopup()?.setOffset(offset);
                }
            });

            // Add smooth zoom transitions
            map.on('zoomanim', function(e) {
                const zoom = e.zoom;
                const scale = map.getZoomScale(zoom);
                
                // Scale markers during zoom animation
                for (let id in markers) {
                    const size = getMarkerSize(zoom);
                    const icon = markers[id].getElement();
                    if (icon) {
                        icon.style.transform += ` scale(${scale})`;
                    }
                }
            });

            // Add zoom button functionality
            zoomInButton.addEventListener('click', function() {
                map.zoomIn(1, { animate: true });
            });

            zoomOutButton.addEventListener('click', function() {
                map.zoomOut(1, { animate: true });
            });

            // Prevent map zoom when scrolling over controls
            L.DomEvent.disableScrollPropagation(zoomControls);
        });

        // Enhanced zoom functions with smooth animations
        function zoomToBuilding(buildingId) {
            const buildings = {
                main: [14.798730, 120.927931],
                buildingA: [14.799100, 120.927900],
                buildingB: [14.799350, 120.927900],
                buildingC: [14.799600, 120.927900],
                court: [14.799217083795876, 120.92793378111632],
                frontEntrance: [14.798601805480369, 120.92794602090093]
            };
            
            if (buildings[buildingId]) {
                window.campusMap.flyTo(buildings[buildingId], 21, {
                    duration: 1.5,
                    easeLinearity: 0.25
                });
            }
        }

        function resetView() {
            window.campusMap.flyTo([14.798677468589588, 120.92788361869473], 19, {
                duration: 1.5,
                easeLinearity: 0.25
            });
        }

        // Add control visibility state
        let controlsVisible = false;

        // Search functionality
        function searchLocations(query) {
            query = query.toLowerCase();
            for (let id in buildings) {
                const building = buildings[id];
                if (building.name.toLowerCase().includes(query) ||
                    building.description.toLowerCase().includes(query)) {
                    window.campusMap.flyTo(building.coords, 21, {
                        duration: 1.5,
                        easeLinearity: 0.25
                    });
                    break;
                }
            }
        }
    </script>
</body>
</html> 