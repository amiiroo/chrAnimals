<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CardController extends Controller
{
    private $regions = [
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
    public function index(Request $request): View
    {
        $kind = $request->kind;
        $region = $request->region;
        
        $cards = Card::where('kind', $kind)
                    ->when($region, function($query) use ($region) {
                        return $query->where('region', $region);
                    })
                    ->whereNull('deleted_at')
                    ->get();
                    
        $kindTitle = $this->getKindTitle($kind);
        
        $regionName = $region ? ($this->regions[$region] ?? 'Чеченская Республика') : 'Чеченская Республика';
        
        return view('cards.index', compact('cards', 'kindTitle', 'kind', 'region', 'regionName'));
    }
    
    public function show(Card $card): View
    {
        return view('cards.show', compact('card'));
    }
    
    private function getKindTitle(string $kind): string
    {
        $titles = [
            'plant' => 'растений',
        ];
        
        return $titles[$kind] ?? '';
    }
}