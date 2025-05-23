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
            <p>Интерактивная карта, показывающая регионы Чечни с информацией об исчезающих видах флоры и фауны</p>
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
    // Инициализация карты с более современными параметрами
    const map = L.map('map', {
        minZoom: 7,
        maxZoom: 12,
        zoomControl: true,
        zoomAnimation: true,
        markerZoomAnimation: true,
        scrollWheelZoom: true,
    }).setView([43.3, 45.7], 8);

    // Добавление современного стиля тайлов карты
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
        subdomains: 'abcd',
        maxZoom: 20
    }).addTo(map);

    // Определение регионов и их названий
    const regions = {
        @foreach($regions as $code => $name)
            '{{ $code }}': '{{ $name }}',
        @endforeach
    };

    // Современная цветовая схема
    const colors = {
        'region': '#54A0FF',       // Синий для районов
        'city': '#9C88FF',         // Фиолетовый для городов
        'highlight': '#FF6B6B',    // Красный для выделения
        'republic': '#1DD1A1'      // Зеленый для республики в целом
    };
    
    // Функция получения стиля для региона
    function getRegionStyle(code, isHighlighted = false) {
        const isCity = (code === 'GROZ' || code === 'ARGN');
        return {
            fillColor: isHighlighted ? colors.highlight : (isCity ? colors.city : colors.region),
            weight: 2,
            opacity: 1,
            color: 'white',
            dashArray: '',
            fillOpacity: isHighlighted ? 0.7 : 0.6
        };
    }

    // Словарь для хранения слоев регионов
    const regionLayers = {};

    // Загрузка общей границы Чечни
    fetch('{{ asset('geojson/chechnya.geojson') }}')
        .then(response => response.json())
        .then(data => {
            const chechnyaLayer = L.geoJSON(data, {
                style: {
                    fillColor: colors.republic,
                    fillOpacity: 0.2,
                    color: '#2F3640',
                    weight: 3,
                    dashArray: '5, 5'
                }
            }).addTo(map);
            
            // После успешной загрузки общей границы, загружаем регионы
            loadAllRegions();
        })
        .catch(error => {
            console.error('Ошибка при загрузке границы Чечни:', error);
            // Продолжаем даже при ошибке
            loadAllRegions();
        });

    // Функция для загрузки всех регионов
    function loadAllRegions() {
        // Создаем групповой слой для регионов
        const regionsGroup = L.layerGroup().addTo(map);
        
        // Загружаем каждый регион
        Object.keys(regions).forEach(code => {
            if (code === 'CHECH') return; // Пропускаем общую границу Чечни
            loadRegion(code, regions[code], regionsGroup);
        });
        
        // Добавляем легенду
        addLegend();
    }
    
    // Загрузка отдельного региона
    function loadRegion(code, name, layerGroup) {
        // Преобразуем код в нижний регистр для имени файла
        const filename = code.toLowerCase();
        const regionUrl = `{{ asset('geojson') }}/${filename}.geojson`;
        
        fetch(regionUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                const regionLayer = L.geoJSON(data, {
                    style: getRegionStyle(code),
                    onEachFeature: function(feature, layer) {
                        // Создаем улучшенное всплывающее окно
                        const popupContent = `
                            <div class="region-popup">
                                <div class="region-name">${name}</div>
                                <div class="region-info">
                                    <p>Выберите категорию исчезающих видов этого региона:</p>
                                </div>
                                <div class="popup-links">
                                    <a href="{{ url('/cards') }}?kind=animal&region=${code}" class="region-link">Животные</a>
                                    <a href="{{ url('/cards') }}?kind=plants&region=${code}" class="region-link">Растения</a>
                                    <a href="{{ url('/cards') }}?kind=bug&region=${code}" class="region-link">Насекомые</a>
                                    <a href="{{ url('/cards') }}?kind=fungus&region=${code}" class="region-link">Грибы</a>
                                </div>
                            </div>
                        `;
                        
                        // Настройка всплывающего окна
                        layer.bindPopup(popupContent, {
                            maxWidth: 300,
                            className: 'region-popup-container'
                        });
                        
                        // События взаимодействия
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
                
                // Сохраняем слой региона для доступа к нему позже
                regionLayers[code] = regionLayer;
                
                // Получаем центр региона для метки
                const bounds = regionLayer.getBounds();
                const center = bounds.getCenter();
                
                // Добавляем метку с названием региона
                L.marker(center, {
                    icon: L.divIcon({
                        className: 'region-label',
                        html: name,
                        iconSize: [120, 30],
                        iconAnchor: [60, 15]
                    })
                }).addTo(layerGroup);
            })
            .catch(error => {
                console.error(`Ошибка при загрузке региона ${code}:`, error);
            });
    }
    
    // Добавление современной легенды
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