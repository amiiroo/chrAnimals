@extends('layouts.app')

@section('title', 'Редактирование карточки')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="admin-header">
        <h1>Редактирование карточки</h1>
    </div>
    
    <div class="admin-form">
        <form action="{{ route('admin.update', $card->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-row">
                <div class="form-col">
                    <div class="form-group">
                        <label for="title">Название</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $card->title) }}" required>
                        @error('title')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-col">
                    <div class="form-group">
                        <label for="image">Изображение</label>
                        <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/jpg">
                        @if($card->image)
                            <img src="{{ asset('storage/' . $card->image) }}" alt="{{ $card->title }}" class="preview-image">
                        @endif
                        @error('image')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-col">
                    <div class="form-group">
                        <label for="kind">Тип</label>
                        <select id="kind" name="kind" required>
                            <option value="plants" selected>Растение</option>
                        </select>
                        @error('kind')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-col">
                    <div class="form-group">
                        <label for="region">Регион</label>
                        <select id="region" name="region" required>
                            <option value="GRZR" {{ old('region', $card->region) == 'GRZR' ? 'selected' : '' }}>Грозненский район</option>
                            <option value="URUS" {{ old('region', $card->region) == 'URUS' ? 'selected' : '' }}>Урус-Мартановский район</option>
                            <option value="VEDN" {{ old('region', $card->region) == 'VEDN' ? 'selected' : '' }}>Веденский район</option>
                            <option value="ITUM" {{ old('region', $card->region) == 'ITUM' ? 'selected' : '' }}>Итум-Калинский район</option>
                            <option value="SHAL" {{ old('region', $card->region) == 'SHAL' ? 'selected' : '' }}>Шалинский район</option>
                            <option value="SHAT" {{ old('region', $card->region) == 'SHAT' ? 'selected' : '' }}>Шатойский район</option>
                            <option value="SHAR" {{ old('region', $card->region) == 'SHAR' ? 'selected' : '' }}>Шаройский район</option>
                            <option value="KURC" {{ old('region', $card->region) == 'KURC' ? 'selected' : '' }}>Курчалоевский район</option>
                            <option value="SHEL" {{ old('region', $card->region) == 'SHEL' ? 'selected' : '' }}>Шелковской район</option>
                            <option value="SERN" {{ old('region', $card->region) == 'SERN' ? 'selected' : '' }}>Серноводский район</option>
                            <option value="ACHM" {{ old('region', $card->region) == 'ACHM' ? 'selected' : '' }}>Ачхой-Мартановский район</option>
                            <option value="NTRK" {{ old('region', $card->region) == 'NTRK' ? 'selected' : '' }}>Надтеречный район</option>
                            <option value="NAUR" {{ old('region', $card->region) == 'NAUR' ? 'selected' : '' }}>Наурский район</option>
                            <option value="GUMS" {{ old('region', $card->region) == 'GUMS' ? 'selected' : '' }}>Гудермесский район</option>
                            <option value="NOJU" {{ old('region', $card->region) == 'NOJU' ? 'selected' : '' }}>Ножай-Юртовский район</option>
                            <option value="GROZ" {{ old('region', $card->region) == 'GROZ' ? 'selected' : '' }}>г. Грозный</option>
                            <option value="ARGN" {{ old('region', $card->region) == 'ARGN' ? 'selected' : '' }}>г. Аргун</option>
                        </select>
                        @error('region')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="description">Описание</label>
                <textarea id="description" name="description" rows="4" required>{{ old('description', $card->description) }}</textarea>
                @error('description')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-row">
                <div class="form-col">
                    <div class="form-group">
                        <label for="status">Статус</label>
                        <input type="text" id="status" name="status" value="{{ old('status', $card->status) }}">
                    </div>
                </div>
                
                <div class="form-col">
                    <div class="form-group">
                        <label for="population">Популяция</label>
                        <input type="text" id="population" name="population" value="{{ old('population', $card->population) }}">
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="habitat">Ареал обитания</label>
                <input type="text" id="habitat" name="habitat" value="{{ old('habitat', $card->habitat) }}">
            </div>
            
            <div class="form-group">
                <label for="threats">Основные угрозы</label>
                <textarea id="threats" name="threats" rows="3">{{ old('threats', $card->threats) }}</textarea>
            </div>
            <div class="form-group">
                <label for="dop_info">Дополнительная информация</label>
                <textarea id="dop_info" name="dop_info" rows="3">{{ old('dop_info') }}</textarea>
                @error('dop_info')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="conservation">Меры по сохранению</label>
                <textarea id="conservation" name="conservation" rows="3">{{ old('conservation', $card->conservation) }}</textarea>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn">Сохранить изменения</button>
                <a href="{{ route('admin.index') }}" class="btn btn-secondary">Отмена</a>
            </div>
        </form>
    </div>
</div>
@endsection