<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CardController extends Controller
{
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
        
        return view('cards.index', compact('cards', 'kindTitle', 'kind', 'region'));
    }
    
    public function show(Card $card): View
    {
        return view('cards.show', compact('card'));
    }
    
    private function getKindTitle(string $kind): string
    {
        $titles = [
            'animal' => 'животных',
            'plants' => 'растений',
            'bug' => 'насекомых',
            'fungus' => 'грибов'
        ];
        
        return $titles[$kind] ?? '';
    }
}