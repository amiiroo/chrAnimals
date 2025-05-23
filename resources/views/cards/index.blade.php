@extends('layouts.app')

@section('title', "Исчезающие виды {$kindTitle}")

@section('styles')
<link rel="stylesheet" href="{{ asset('css/cards.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="page-header">
        <h1>Исчезающие виды {{ $kindTitle }}</h1>
        @auth
        <a href="{{ route('admin.index') }}" class="admin-btn">Админ</a>
        @endauth
    </div>
    
    <div class="cards-container">
        @forelse($cards as $card)
        <div class="species-card">
            <img src="{{ $card->image ? asset('storage/' . $card->image) : asset('images/no-image.jpg') }}" 
                 alt="{{ $card->title }}" class="species-image">
            
            <div class="species-content">
                <h3 class="species-title">{{ $card->title }}</h3>
                <p class="species-description">{{ $card->description }}</p>
                
                <button type="button" class="btn species-btn">Подробнее</button>
                
                <div class="additional-info">
                    <h4>Дополнительная информация</h4>
                    <p><strong>Статус:</strong> {{ $card->status ?? 'Находится под угрозой исчезновения' }}</p>
                    <p><strong>Популяция:</strong> {{ $card->population ?? 'Сокращается' }}</p>
                    <p><strong>Ареал обитания:</strong> {{ $card->habitat ?? 'Ограничен и фрагментирован' }}</p>
                    <p><strong>Основные угрозы:</strong> {{ $card->threats ?? 'Потеря среды обитания, браконьерство, изменение климата' }}</p>
                    <p><strong>Меры по сохранению:</strong> {{ $card->conservation ?? 'Создание заповедников, программы разведения, международное сотрудничество' }}</p>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center" style="grid-column: 1 / -1;">
            <p>Нет данных для отображения</p>
        </div>
        @endforelse
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Показ дополнительной информации
    const cards = document.querySelectorAll('.species-card');
    
    cards.forEach(card => {
        const button = card.querySelector('.species-btn');
        const additionalInfo = card.querySelector('.additional-info');
        
        button.addEventListener('click', function() {
            if (additionalInfo.classList.contains('active')) {
                additionalInfo.classList.remove('active');
                button.textContent = 'Подробнее';
            } else {
                additionalInfo.classList.add('active');
                button.textContent = 'Скрыть';
            }
        });
    });
});
</script>
@endsection