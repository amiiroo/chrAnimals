@extends('layouts.app')

@section('title', 'Админ-панель')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="admin-header">
        <h1>Админ-панель - Управление карточками</h1>
    </div>
    
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    
    <div class="admin-controls">
        <div class="search-box">
            <form action="{{ route('admin.index') }}" method="GET">
                <input type="text" name="search" placeholder="Поиск по названию..." value="{{ request('search') }}">
                <span class="search-icon">🔍</span>
            </form>
        </div>
        
        <div class="filter-controls">
            <select name="kind" onchange="window.location.href='{{ route('admin.index') }}?kind='+this.value">
                <option value="">Все типы</option>
                <option value="animal" {{ request('kind') == 'animal' ? 'selected' : '' }}>Животные</option>
                <option value="plants" {{ request('kind') == 'plants' ? 'selected' : '' }}>Растения</option>
                <option value="bug" {{ request('kind') == 'bug' ? 'selected' : '' }}>Насекомые</option>
                <option value="fungus" {{ request('kind') == 'fungus' ? 'selected' : '' }}>Грибы</option>
            </select>
            
            <select name="region" onchange="window.location.href='{{ route('admin.index') }}?region='+this.value">
                <option value="">Все регионы</option>
                <option value="GRZR" {{ request('region') == 'GRZR' ? 'selected' : '' }}>Грозненский район</option>
                <option value="URUS" {{ request('region') == 'URUS' ? 'selected' : '' }}>Урус-Мартановский район</option>
                <option value="VEDN" {{ request('region') == 'VEDN' ? 'selected' : '' }}>Веденский район</option>
                <option value="ITUM" {{ request('region') == 'ITUM' ? 'selected' : '' }}>Итум-Калинский район</option>
                <option value="SHAL" {{ request('region') == 'SHAL' ? 'selected' : '' }}>Шалинский район</option>
                <option value="SHAT" {{ request('region') == 'SHAT' ? 'selected' : '' }}>Шатойский район</option>
                <option value="SHAR" {{ request('region') == 'SHAR' ? 'selected' : '' }}>Шаройский район</option>
                <option value="KURC" {{ request('region') == 'KURC' ? 'selected' : '' }}>Курчалоевский район</option>
                <option value="SHEL" {{ request('region') == 'SHEL' ? 'selected' : '' }}>Шелковской район</option>
                <option value="SERN" {{ request('region') == 'SERN' ? 'selected' : '' }}>Серноводский район</option>
                <option value="ACHM" {{ request('region') == 'ACHM' ? 'selected' : '' }}>Ачхой-Мартановский район</option>
                <option value="NTRK" {{ request('region') == 'NTRK' ? 'selected' : '' }}>Надтеречный район</option>
                <option value="NAUR" {{ request('region') == 'NAUR' ? 'selected' : '' }}>Наурский район</option>
                <option value="GUMS" {{ request('region') == 'GUMS' ? 'selected' : '' }}>Гудермесский район</option>
                <option value="NOJU" {{ request('region') == 'NOJU' ? 'selected' : '' }}>Ножай-Юртовский район</option>
                <option value="GROZ" {{ request('region') == 'GROZ' ? 'selected' : '' }}>г. Грозный</option>
                <option value="ARGN" {{ request('region') == 'ARGN' ? 'selected' : '' }}>г. Аргун</option>
            </select>
            
            <a href="{{ route('admin.create') }}" class="btn">Добавить карточку</a>
        </div>
    </div>
    
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Изображение</th>
                <th>Название</th>
                <th>Тип</th>
                <th>Регион</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cards as $card)
            <tr>
                <td>{{ $card->id }}</td>
                <td>
                    @if($card->image)
                    <img src="{{ asset('storage/' . $card->image) }}" alt="{{ $card->title }}" class="thumbnail">
                    @else
                    <div class="thumbnail no-image">Нет фото</div>
                    @endif
                </td>
                <td>{{ $card->title }}</td>
                <td>
                    @switch($card->kind)
                        @case('animal')
                            Животное
                            @break
                        @case('plants')
                            Растение
                            @break
                        @case('bug')
                            Насекомое
                            @break
                        @case('fungus')
                            Гриб
                            @break
                        @default
                            {{ $card->kind }}
                    @endswitch
                </td>
                <td>
                    @switch($card->region)
                        @case('GRZR')
                            Грозненский район
                            @break
                        @case('URUS')
                            Урус-Мартановский район
                            @break
                        {{-- Другие регионы --}}
                        @default
                            {{ $card->region }}
                    @endswitch
                </td>
                <td class="actions-cell">
                    <a href="{{ route('admin.edit', $card->id) }}" class="btn btn-secondary btn-sm">Изменить</a>
                    
                    <form action="{{ route('admin.destroy', $card->id) }}" method="POST" onsubmit="return confirm('Вы уверены?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Удалить</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Карточки не найдены</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="pagination">
        {{ $cards->links() }}
    </div>
</div>
@endsection