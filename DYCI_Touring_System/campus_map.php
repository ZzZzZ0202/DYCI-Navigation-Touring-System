<?php
session_start();
require_once 'includes/functions.php';

// Check if user is logged in as either student or visitor
if (!isset($_SESSION['user_type']) || ($_SESSION['user_type'] !== 'student' && $_SESSION['user_type'] !== 'visitor')) {
    header("Location: index.php");
    exit();
}
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
            margin: 0;
            border-radius: 0;
            overflow: hidden;
            box-shadow: none;
        }

        #map {
            height: 100%;
            width: 100%;
            z-index: 1;
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
            right: 1rem;
            top: 5rem;
            z-index: 1000;
            background: white;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
            max-height: calc(100vh - 200px);
            overflow-y: auto;
        }

        .map-controls.hidden {
            opacity: 0;
            pointer-events: none;
            transform: translateX(100%);
        }

        .search-container.hidden {
            opacity: 0;
            pointer-events: none;
            transform: translateY(-100%);
        }

        .floor-controls.hidden {
            opacity: 0;
            pointer-events: none;
            transform: translateX(-100%);
        }

        .map-type-control.hidden {
            opacity: 0;
            pointer-events: none;
            transform: translateY(-100%);
        }

        .controls-visible .leaflet-control {
            opacity: 1;
            pointer-events: auto;
        }

        .controls-hidden .leaflet-control {
            opacity: 0;
            pointer-events: none;
        }

        @media (max-width: 768px) {
            .map-controls {
                max-height: 50vh;
                width: 250px;
            }

            .search-container {
                width: calc(100% - 2rem);
            }
        }

        .map-controls button {
            margin: 0.2rem 0;
            width: 100%;
        }

        .legend {
            position: absolute;
            bottom: 1rem;
            left: 1rem;
            z-index: 1000;
            background: white;
            padding: 1rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin: 0.5rem 0;
        }

        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            margin-right: 0.5rem;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .leaflet-popup-content {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .building-popup {
            padding: 4px;
        }

        .building-popup h5 {
            color: #1a73e8;
            margin-bottom: 0.5rem;
            font-size: 14px;
        }

        .building-popup p {
            color: #5f6368;
            margin-bottom: 0.5rem;
            font-size: 13px;
        }

        .building-popup .btn-primary {
            background: #1a73e8;
            border: none;
            padding: 4px 12px;
            font-size: 13px;
        }

        .building-label {
            background: white;
            border: none;
            border-radius: 4px;
            padding: 2px 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            font-weight: 500;
            color: #1a73e8;
            font-size: 13px;
        }

        .path-label {
            background: white;
            border: none;
            border-radius: 4px;
            padding: 2px 8px;
            font-size: 12px;
            color: #5f6368;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .custom-zoom-controls {
            position: absolute;
            right: 1rem;
            bottom: 2rem;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            background: white;
            padding: 0.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

        .custom-zoom-controls button {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: none;
            background: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .custom-zoom-controls button:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        }

        .rotate-controls {
            position: absolute;
            right: 1rem;
            bottom: 8rem;
            z-index: 1000;
        }

        .rotate-controls button {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: none;
            background: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            cursor: pointer;
            margin: 5px;
        }

        .leaflet-popup-content-wrapper {
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .search-container {
            position: absolute;
            top: 1rem;
            left: 1rem;
            z-index: 1000;
            width: 300px;
        }

        .search-input {
            width: 100%;
            padding: 10px 15px;
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            font-size: 14px;
        }

        .floor-controls {
            position: absolute;
            left: 1rem;
            bottom: 2rem;
            z-index: 1000;
            background: white;
            padding: 0.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

        .floor-btn {
            padding: 6px 12px;
            margin: 0 4px;
            border: none;
            border-radius: 4px;
            background: #f8f9fa;
            color: #1a73e8;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .floor-btn.active {
            background: #1a73e8;
            color: white;
        }

        .floor-btn:hover {
            background: #e8f0fe;
            color: #1a73e8;
        }

        .floor-btn.active:hover {
            background: #1557b0;
            color: white;
        }

        .building-popup img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .facility-icon {
            display: inline-block;
            margin-right: 8px;
            color: #5f6368;
            font-size: 14px;
        }

        .facilities-list {
            margin: 10px 0;
            padding: 0;
            list-style: none;
        }

        .facilities-list li {
            display: inline-block;
            margin-right: 15px;
            color: #5f6368;
            font-size: 13px;
        }

        .map-type-control {
            position: absolute;
            top: 1rem;
            right: 1rem;
            z-index: 1000;
        }

        .map-type-btn {
            padding: 8px 15px;
            background: white;
            border: none;
            border-radius: 4px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            font-size: 13px;
            color: #1a73e8;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .map-type-btn:hover {
            background: #e8f0fe;
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

        <div class="map-type-control hidden">
            <button class="map-type-btn" onclick="toggleMapType()">
                <i class="fas fa-layer-group mr-2"></i>Toggle Satellite View
            </button>
        </div>

        <div class="floor-controls hidden">
            <button class="floor-btn active" onclick="changeFloor('G')">G</button>
            <button class="floor-btn" onclick="changeFloor('2F')">2F</button>
            <button class="floor-btn" onclick="changeFloor('3F')">3F</button>
            <button class="floor-btn" onclick="changeFloor('4F')">4F</button>
        </div>

        <div id="map"></div>
        
        <div class="map-controls hidden">
            <button class="btn btn-sm btn-primary mb-2" onclick="zoomToBuilding('frontEntrance')">
                <i class="fas fa-door-open mr-2"></i>Front Entrance
            </button>
            <button class="btn btn-sm btn-primary mb-2" onclick="zoomToBuilding('main')">
                <i class="fas fa-building mr-2"></i>Building (A)
            </button>
            <button class="btn btn-sm btn-primary mb-2" onclick="zoomToBuilding('buildingA')">
                <i class="fas fa-building mr-2"></i>Building (B)
            </button>
            <button class="btn btn-sm btn-primary mb-2" onclick="zoomToBuilding('court')">
                <i class="fas fa-volleyball-ball mr-2"></i>Elida Court
            </button>
            <button class="btn btn-sm btn-primary" onclick="resetView()">
                <i class="fas fa-sync-alt mr-2"></i>Reset View
            </button>
        </div>

        <div class="custom-zoom-controls">
            <button onclick="zoomIn()"><i class="fas fa-plus"></i></button>
            <button onclick="zoomOut()"><i class="fas fa-minus"></i></button>
            <button onclick="tiltView()"><i class="fas fa-compass"></i></button>
        </div>

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
            // Initialize the map with better 3D perspective
            const map = L.map('map', {
                preferCanvas: true,
                zoomControl: false,
                minZoom: 17,
                maxZoom: 22
            }).setView([14.7986301472909, 120.92793150908635], 19);

            // Add custom tiles with more detail
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 22,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Define building coordinates and paths
            const buildings = {
                mainBuilding: {
                    coords: [14.798830, 120.928031],
                    name: 'Building (A)',
                    description: 'Academic building with classrooms and faculty offices',
                    color: '#8B4513',
                    roofColor: '#A0522D',
                    polygon: [
                        [14.798930, 120.927980],
                        [14.798930, 120.928080],
                        [14.798730, 120.928080],
                        [14.798730, 120.927980]
                    ]
                },
                secondBuilding: {
                    coords: [14.799030, 120.928031],
                    name: 'Building (B)',
                    description: 'Academic building with laboratories and lecture halls',
                    color: '#8B4513',
                    roofColor: '#A0522D',
                    polygon: [
                        [14.799130, 120.927980],
                        [14.799130, 120.928080],
                        [14.798930, 120.928080],
                        [14.798930, 120.927980]
                    ]
                },
                greenSpace: {
                    coords: [14.798930, 120.928031],
                    name: 'Green Space',
                    description: 'Landscaped area between buildings',
                    color: '#228B22',
                    polygon: [
                        [14.798930, 120.928000],
                        [14.798930, 120.928060],
                        [14.798930, 120.928060],
                        [14.798930, 120.928000]
                    ]
                },
                elidaCourt: {
                    coords: [14.799217083795876, 120.92793378111632],
                    name: 'Elida Covered Court',
                    description: 'Multi-purpose sports facility featuring a full-size basketball court, volleyball court markings, and spectator areas. Hosts sports events and outdoor activities.',
                    color: '#8B4513',
                    facilities: [
                        { icon: 'fas fa-basketball-ball', name: 'Basketball Court' },
                        { icon: 'fas fa-volleyball-ball', name: 'Volleyball Court' },
                        { icon: 'fas fa-people-arrows', name: 'Spectator Areas' },
                        { icon: 'fas fa-restroom', name: 'Restrooms' }
                    ],
                    floors: {
                        'G': ['Basketball Court', 'Volleyball Court'],
                        '2F': ['Spectator Areas', 'Restrooms'],
                        '3F': ['Records Section', 'Archive Room'],
                        '4F': ['Meeting Rooms', 'Conference Hall']
                    },
                    polygon: [
                        [14.799317083795876, 120.92789378111632],
                        [14.799317083795876, 120.92797378111632],
                        [14.799117083795876, 120.92797378111632],
                        [14.799117083795876, 120.92789378111632]
                    ]
                },
                frontEntrance: {
                    coords: [14.798601805480369, 120.92794602090093],
                    name: 'Front Entrance',
                    description: 'Main entrance building with reception area and security post',
                    color: '#8B4513',
                    roofColor: '#A0522D',
                    polygon: [
                        [14.798621805480369, 120.92791502090093],
                        [14.798621805480369, 120.92797702090093],
                        [14.798581805480369, 120.92797702090093],
                        [14.798581805480369, 120.92791502090093]
                    ],
                    facilities: [
                        { icon: 'fas fa-door-open', name: 'Reception Area' },
                        { icon: 'fas fa-shield-alt', name: 'Security Post' },
                        { icon: 'fas fa-wheelchair', name: 'Accessible Entry' },
                        { icon: 'fas fa-directions', name: 'Information Desk' }
                    ]
                }
            };

            // Update paths to be minimal
            const paths = [
                {
                    coords: [
                        [14.798830, 120.928020],
                        [14.798930, 120.928020]
                    ],
                    name: 'Main Path'
                }
            ];

            // Add building polygons with roof effect
            for (let id in buildings) {
                const building = buildings[id];
                
                if (building.polygon) {
                    // Main building polygon
                    L.polygon(building.polygon, {
                        color: building.color,
                        fillColor: building.color,
                        fillOpacity: 0.8,
                        weight: 1,
                        smoothFactor: 1.5
                    }).addTo(map);

                    // Add roof effect for buildings (not for green space)
                    if (id !== 'greenSpace' && building.roofColor) {
                        // Create roof highlight
                        const roofPolygon = [
                            [building.polygon[0][0], building.polygon[0][1]], // NW
                            [building.polygon[1][0], building.polygon[1][1]], // NE
                            [(building.polygon[1][0] + building.polygon[2][0])/2, building.polygon[1][1]], // Middle E
                            [(building.polygon[0][0] + building.polygon[3][0])/2, building.polygon[0][1]]  // Middle W
                        ];
                        
                        L.polygon(roofPolygon, {
                            color: building.roofColor,
                            fillColor: building.roofColor,
                            fillOpacity: 0.6,
                            weight: 1,
                            smoothFactor: 1.5
                        }).addTo(map);
                    }
                }

                // Add markers only for buildings
                if (id !== 'greenSpace') {
                    const icon = L.divIcon({
                        className: 'custom-div-icon',
                        html: `<div style="background-color: ${building.color}; width: 6px; height: 6px; border: 1px solid #fff; border-radius: 50%;"></div>`,
                        iconSize: [6, 6],
                        iconAnchor: [3, 3]
                    });

                    const marker = L.marker(building.coords, { icon: icon })
                        .bindPopup(`
                            <div class="building-popup">
                                <h5>${building.name}</h5>
                                <p>${building.description}</p>
                                ${building.rooms ? `
                                    <div class="rooms-list">
                                        <h6>Rooms:</h6>
                                        ${building.rooms.map(room => `
                                            <a href="room_schedule.php?room=${room}" class="btn btn-sm btn-outline-primary mb-1">
                                                Room ${room}
                                            </a>
                                        `).join(' ')}
                                    </div>
                                ` : ''}
                            </div>
                        `)
                        .addTo(map);

                    // Add building labels
                    L.marker(building.coords, {
                        icon: L.divIcon({
                            className: 'building-label',
                            html: `<div style="font-size: 8px; color: #5D4037;">${building.name}</div>`,
                            iconSize: [40, 12],
                            iconAnchor: [20, 0]
                        })
                    }).addTo(map);
                }
            }

            // Update paths with thinner lines
            paths.forEach(path => {
                L.polyline(path.coords, {
                    color: '#666',
                    weight: 1,
                    opacity: 0.4,
                    dashArray: '2, 4',
                    smoothFactor: 1.5,
                    lineCap: 'round'
                }).addTo(map);
            });

            // Update styles for better appearance
            const additionalStyles = `
                <style>
                    .building-label {
                        background: none !important;
                        border: none !important;
                        box-shadow: none !important;
                        font-weight: 600;
                        text-shadow: 0px 0px 2px rgba(255,255,255,0.8);
                    }

                    .custom-div-icon {
                        background: none !important;
                        border: none !important;
                    }

                    .leaflet-popup-content {
                        margin: 8px;
                    }

                    .building-popup h5 {
                        margin: 0 0 5px 0;
                        font-size: 12px;
                        color: #5D4037;
                        font-weight: 600;
                    }

                    .building-popup p {
                        margin: 0;
                        font-size: 10px;
                        color: #795548;
                    }
                </style>
            `;
            document.head.insertAdjacentHTML('beforeend', additionalStyles);

            // Store map instance globally
            window.campusMap = map;
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

        function zoomIn() {
            window.campusMap.zoomIn(1, { animate: true });
        }

        function zoomOut() {
            window.campusMap.zoomOut(1, { animate: true });
        }

        let currentBearing = 0;
        function tiltView() {
            currentBearing = (currentBearing + 45) % 360;
            window.campusMap.setBearing(currentBearing);
        }

        // Search functionality
        function searchLocations(query) {
            query = query.toLowerCase();
            for (let id in buildings) {
                const building = buildings[id];
                if (building.name.toLowerCase().includes(query) ||
                    building.description.toLowerCase().includes(query) ||
                    Object.values(building.floors).flat().some(f => f.toLowerCase().includes(query))) {
                    map.flyTo(building.coords, 21, {
                        duration: 1.5,
                        easeLinearity: 0.25
                    });
                    break;
                }
            }
        }

        // Floor change functionality
        function changeFloor(floor) {
            document.querySelectorAll('.floor-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelector(`.floor-btn[onclick="changeFloor('${floor}')"]`).classList.add('active');
            
            // Update building information based on floor
            for (let id in buildings) {
                const building = buildings[id];
                if (building.floors && building.floors[floor]) {
                    const marker = map.getMarker(id); // You'll need to implement marker tracking
                    marker.setPopupContent(`
                        <div class="building-popup">
                            <h5>${building.name} - ${floor}</h5>
                            <p>Facilities on this floor:</p>
                            <ul>
                                ${building.floors[floor].map(f => `<li>${f}</li>`).join('')}
                            </ul>
                        </div>
                    `);
                }
            }
        }

        // Map type toggle
        let isSatellite = false;
        function toggleMapType() {
            isSatellite = !isSatellite;
            const tileLayer = isSatellite ?
                'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}' :
                'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
            
            map.eachLayer((layer) => {
                if (layer instanceof L.TileLayer) {
                    map.removeLayer(layer);
                }
            });

            L.tileLayer(tileLayer, {
                maxZoom: 22,
                attribution: isSatellite ? '© Esri' : '© OpenStreetMap contributors'
            }).addTo(map);

            document.querySelector('.map-type-btn').innerHTML = 
                `<i class="fas fa-layer-group mr-2"></i>Toggle ${isSatellite ? 'Street' : 'Satellite'} View`;
        }

        // Add control visibility state
        let controlsVisible = false;

        // Function to toggle controls visibility
        function toggleControls() {
            controlsVisible = !controlsVisible;
            const controls = [
                '.map-controls',
                '.search-container',
                '.floor-controls',
                '.map-type-control'
            ];

            controls.forEach(control => {
                document.querySelector(control).classList.toggle('hidden');
            });

            // Update toggle button icon
            const toggleBtn = document.querySelector('.controls-toggle i');
            toggleBtn.className = controlsVisible ? 'fas fa-times' : 'fas fa-cog';

            // Update map container class for leaflet controls
            document.querySelector('.map-container').classList.toggle('controls-hidden');
            document.querySelector('.map-container').classList.toggle('controls-visible');
        }

        // Show controls when hovering near edges
        let hoverTimeout;
        document.addEventListener('mousemove', (e) => {
            clearTimeout(hoverTimeout);
            
            const threshold = 50; // pixels from edge
            if (e.clientX > window.innerWidth - threshold || 
                e.clientX < threshold || 
                e.clientY < threshold || 
                e.clientY > window.innerHeight - threshold) {
                
                if (!controlsVisible) {
                    hoverTimeout = setTimeout(() => {
                        toggleControls();
                    }, 500);
                }
            } else if (controlsVisible) {
                hoverTimeout = setTimeout(() => {
                    toggleControls();
                }, 2000);
            }
        });

        // Hide controls when map is clicked
        document.getElementById('map').addEventListener('click', () => {
            if (controlsVisible) {
                toggleControls();
            }
        });

        // Initialize with hidden controls
        document.addEventListener('DOMContentLoaded', () => {
            // Existing initialization code...

            // Show controls initially, then hide after 3 seconds
            toggleControls();
            setTimeout(() => {
                if (controlsVisible) {
                    toggleControls();
                }
            }, 3000);
        });
    </script>
</body>
</html> 
            toggleControls();
            setTimeout(() => {
                if (controlsVisible) {
                    toggleControls();
                }
            }, 3000);
        });
    </script>
</body>
</html> 