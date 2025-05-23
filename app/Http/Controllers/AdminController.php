<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index(Request $request): View
    {
        $query = Card::query();
        
        // Поиск по названию
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        
        // Фильтр по типу
        if ($request->has('kind') && $request->kind) {
            $query->where('kind', $request->kind);
        }
        
        // Фильтр по региону
        if ($request->has('region') && $request->region) {
            $query->where('region', $request->region);
        }
        
        $cards = $query->paginate(10);
        
        return view('admin.index', compact('cards'));
    }
    
    public function create(): View
    {
        return view('admin.create');
    }
    
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'kind' => 'required|string|in:animal,plants,bug,fungus',
            'region' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'nullable|string',
            'population' => 'nullable|string',
            'habitat' => 'nullable|string',
            'threats' => 'nullable|string',
            'conservation' => 'nullable|string',
        ]);
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('cards', 'public');
            $validated['image'] = $imagePath;
        }
        
        Card::create($validated);
        
        return redirect()->route('admin.index')->with('success', 'Карточка успешно создана');
    }
    
    public function edit(Card $card): View
    {
        return view('admin.edit', compact('card'));
    }
    
    public function update(Request $request, Card $card): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'kind' => 'required|string|in:animal,plants,bug,fungus',
            'region' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'nullable|string',
            'population' => 'nullable|string',
            'habitat' => 'nullable|string',
            'threats' => 'nullable|string',
            'conservation' => 'nullable|string',
        ]);
        
        if ($request->hasFile('image')) {
            // Удаляем старое изображение
            if ($card->image) {
                Storage::disk('public')->delete($card->image);
            }
            
            $imagePath = $request->file('image')->store('cards', 'public');
            $validated['image'] = $imagePath;
        }
        
        $card->update($validated);
        
        return redirect()->route('admin.index')->with('success', 'Карточка успешно обновлена');
    }
    
    public function destroy(Card $card): RedirectResponse
    {
        $card->delete();
        return redirect()->route('admin.index')->with('success', 'Карточка удалена');
    }
}