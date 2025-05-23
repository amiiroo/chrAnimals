<?php

$directories = [
    'public/geojson',
    'public/images',
];

// Создаем директории
foreach ($directories as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
        echo "Создана директория: $dir\n";
    }
}

// Регионы Чечни
$regions = [
    'chechnya' => 'Чеченская Республика',
    'grzr' => 'Грозненский район',
    'urus' => 'Урус-Мартановский район',
    'vedn' => 'Веденский район',
    'itum' => 'Итум-Калинский район',
    'shal' => 'Шалинский район',
    'shat' => 'Шатойский район',
    'shar' => 'Шаройский район',
    'kurc' => 'Курчалоевский район',
    'shel' => 'Шелковской район',
    'sern' => 'Серноводский район',
    'achm' => 'Ачхой-Мартановский район',
    'ntrk' => 'Надтеречный район',
    'naur' => 'Наурский район',
    'gums' => 'Гудермесский район',
    'noju' => 'Ножай-Юртовский район',
    'groz' => 'г. Грозный',
    'argn' => 'г. Аргун',
];

// Создаем GeoJSON файлы
foreach ($regions as $code => $name) {
    $filename = "public/geojson/{$code}.geojson";
    $content = generateGeoJson($name, $code);
    file_put_contents($filename, $content);
    echo "Создан файл: $filename\n";
}

// Категории
$categories = [
    'animals' => 'Животные',
    'plants' => 'Растения',
    'insects' => 'Насекомые',
    'fungi' => 'Грибы',
    'no-image' => 'Нет изображения',
];

// Заглушки для изображений - выводим инструкции
echo "\nНеобходимо создать следующие изображения-заглушки:\n";
foreach ($categories as $code => $name) {
    echo "- public/images/{$code}.jpg - {$name}\n";
    echo "  Можно использовать: https://via.placeholder.com/800x500/70a1ff/ffffff?text=" . urlencode($name) . "\n";
}

// Функция для генерации GeoJSON
function generateGeoJson($name, $code) {
    // Генерируем случайные координаты для разных регионов
    $baseLatitude = 43.0;
    $baseLongitude = 45.7;
    
    // Немного смещаем координаты в зависимости от кода региона
    $offset = crc32($code) % 100 / 100;
    $latitude = $baseLatitude + $offset * 0.5;
    $longitude = $baseLongitude + $offset * 0.5;
    
    return '{
  "type": "FeatureCollection",
  "features": [
    {
      "type": "Feature",
      "properties": {
        "name": "' . $name . '"
      },
      "geometry": {
        "type": "Polygon",
        "coordinates": [
          [
            [' . ($longitude - 0.1) . ', ' . ($latitude + 0.1) . '],
            [' . ($longitude + 0.1) . ', ' . ($latitude + 0.1) . '],
            [' . ($longitude + 0.1) . ', ' . ($latitude - 0.1) . '],
            [' . ($longitude - 0.1) . ', ' . ($latitude - 0.1) . '],
            [' . ($longitude - 0.1) . ', ' . ($latitude + 0.1) . ']
          ]
        ]
      }
    }
  ]
}';
}