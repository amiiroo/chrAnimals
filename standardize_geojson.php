<?php
// standardize_geojson.php

$dir = 'public/geojson';
$files = glob("$dir/*.geojson");

foreach ($files as $file) {
    echo "Обработка файла: " . basename($file) . "...\n";
    
    // Чтение файла
    $content = file_get_contents($file);
    $geojson = json_decode($content, true);
    
    // Проверка формата
    if (!isset($geojson['type']) || $geojson['type'] !== 'FeatureCollection') {
        // Преобразование в FeatureCollection, если это одиночный Feature
        if (isset($geojson['type']) && $geojson['type'] === 'Feature') {
            $geojson = [
                'type' => 'FeatureCollection',
                'features' => [$geojson]
            ];
        }
        // Если это просто геометрия
        elseif (isset($geojson['type']) && in_array($geojson['type'], ['Polygon', 'MultiPolygon'])) {
            $geojson = [
                'type' => 'FeatureCollection',
                'features' => [
                    [
                        'type' => 'Feature',
                        'properties' => [
                            'name' => pathinfo(basename($file), PATHINFO_FILENAME)
                        ],
                        'geometry' => $geojson
                    ]
                ]
            ];
        }
        
        // Сохранение изменённого файла
        file_put_contents($file, json_encode($geojson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        echo "Файл " . basename($file) . " стандартизирован!\n";
    } else {
        echo "Файл " . basename($file) . " уже в правильном формате.\n";
    }
}

echo "Обработка завершена!\n";