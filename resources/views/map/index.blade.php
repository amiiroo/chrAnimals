@extends('layouts.app')

@section('title', 'Карта регионов Чечни')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/map.css') }}">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    <div class="map-container">
        <div class="map-header">
            <h1>Регионы Чеченской Республики</h1>
            <p>Исследуйте биоразнообразие региона через интерактивную карту</p>
        </div>
        <div id="map"></div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" 
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Инициализация карты
    const map = L.map('map', {
        minZoom: 7,
        maxZoom: 12,
        zoomControl: true,
        zoomAnimation: true,
        fadeAnimation: true,
        markerZoomAnimation: true,
        scrollWheelZoom: true,
        touchZoom: true,
        boxZoom: true,
        doubleClickZoom: true,
        tap: true
    }).setView([43.3, 45.7], 8);

    // Современные тайлы
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
        subdomains: 'abcd',
        maxZoom: 20
    }).addTo(map);

    // Цветовая схема
    const colors = {
        'region': '#54A0FF',
        'city': '#9C88FF',
        'highlight': '#FF6B6B',
        'republic': '#1DD1A1'
    };

    // Стили для регионов
    function getRegionStyle(code, isHighlighted = false) {
        const isCity = (code === 'GROZ' || code === 'ARGN');
        return {
            fillColor: isHighlighted ? colors.highlight : (isCity ? colors.city : colors.region),
            weight: isHighlighted ? 3 : 2,
            opacity: 1,
            color: 'white',
            dashArray: '',
            fillOpacity: isHighlighted ? 0.7 : 0.6
        };
    }

    // Загрузка границы Чечни
    fetch('{{ asset('geojson/chechnya.geojson') }}')
        .then(response => response.json())
        .then(data => {
            L.geoJSON(data, {
                style: {
                    fillColor: colors.republic,
                    fillOpacity: 0.2,
                    color: '#2F3640',
                    weight: 3,
                    dashArray: '5, 5'
                }
            }).addTo(map);
            loadAllRegions();
        })
        .catch(error => {
            console.error('Ошибка загрузки границы:', error);
            loadAllRegions();
        });

    // Загрузка всех регионов
    function loadAllRegions() {
        const regionsGroup = L.layerGroup().addTo(map);
        
        @foreach($regions as $code => $name)
            @if($code !== 'CHECH')
                loadRegion('{{ $code }}', '{{ $name }}', regionsGroup);
            @endif
        @endforeach
        
        addLegend();
    }
    
    // Загрузка одного региона
    function loadRegion(code, name, layerGroup) {
        const filename = code.toLowerCase();
        const regionUrl = `{{ asset('geojson') }}/${filename}.geojson`;
        
        fetch(regionUrl)
            .then(response => response.json())
            .then(data => {
                const regionLayer = L.geoJSON(data, {
                    style: getRegionStyle(code),
                    onEachFeature: function(feature, layer) {
                        const popupContent = `
                            <div class="region-popup">
                                <div class="region-name">${name}</div>
                                <div class="region-info">
                                    Исследуйте исчезающие виды этого региона
                                </div>
                                <div class="popup-links">
                                    <a href="{{ url('/cards') }}?kind=animal&region=${code}" class="region-link">
                                        <i class="fas fa-paw"></i> Животные
                                    </a>
                                    <a href="{{ url('/cards') }}?kind=plants&region=${code}" class="region-link">
                                        <i class="fas fa-leaf"></i> Растения
                                    </a>
                                    <a href="{{ url('/cards') }}?kind=bug&region=${code}" class="region-link">
                                        <i class="fas fa-bug"></i> Насекомые
                                    </a>
                                    <a href="{{ url('/cards') }}?kind=fungus&region=${code}" class="region-link">
                                        <i class="fas fa-mushroom"></i> Грибы
                                    </a>
                                </div>
                            </div>
                        `;
                        
                        layer.bindPopup(popupContent, {
                            maxWidth: 300,
                            className: 'region-popup-container'
                        });
                        
                        layer.on({
                            mouseover: function() {
                                this.setStyle(getRegionStyle(code, true));
                                this.bringToFront();
                            },
                            mouseout: function() {
                                this.setStyle(getRegionStyle(code));
                            },
                            click: function() {
                                map.fitBounds(this.getBounds(), {
                                    padding: [50, 50],
                                    animate: true,
                                    duration: 0.5
                                });
                            }
                        });
                    }
                }).addTo(layerGroup);
                
                // Добавление метки
                const bounds = regionLayer.getBounds();
                const center = bounds.getCenter();
                
                L.marker(center, {
                    icon: L.divIcon({
                        className: 'region-label',
                        html: name,
                        iconSize: [120, 30],
                        iconAnchor: [60, 15]
                    })
                }).addTo(layerGroup);
            })
            .catch(error => console.error(`Ошибка загрузки региона ${code}:`, error));
    }
    
    // Добавление легенды
    function addLegend() {
        const legend = L.control({position: 'bottomright'});
        legend.onAdd = function(map) {
            const div = L.DomUtil.create('div', 'map-legend');
            div.innerHTML = `
                <h4>Типы территорий</h4>
                <div class="legend-item">
                    <span class="legend-color" style="background-color:${colors.region}"></span>
                    <span>Район</span>
                </div>
                <div class="legend-item">
                    <span class="legend-color" style="background-color:${colors.city}"></span>
                    <span>Город</span>
                </div>
                <div class="legend-item">
                    <span class="legend-color" style="background-color:${colors.republic}"></span>
                    <span>Республика</span>
                </div>
            `;
            return div;
        };
        legend.addTo(map);
    }
});
</script>
@endsection