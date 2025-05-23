<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class MapController extends Controller
{
    public function index(): View
    {
        // Массив регионов для отображения на карте
        $regions = [
            'GRZR' => 'Грозненский район',
            'URUS' => 'Урус-Мартановский район',
            'VEDN' => 'Веденский район',
            'ITUM' => 'Итум-Калинский район',
            'SHAL' => 'Шалинский район',
            'SHAT' => 'Шатойский район',
            'SHAR' => 'Шаройский район',
            'KURC' => 'Курчалоевский район',
            'SHEL' => 'Шелковской район',
            'SERN' => 'Серноводский район',
            'ACHM' => 'Ачхой-Мартановский район',
            'NTRK' => 'Надтеречный район',
            'NAUR' => 'Наурский район',
            'GUMS' => 'Гудермесский район',
            'NOJU' => 'Ножай-Юртовский район',
            'GROZ' => 'г. Грозный',
            'ARGN' => 'г. Аргун'
        ];
        
        return view('map.index', compact('regions'));
    }
}