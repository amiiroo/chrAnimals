@extends('layouts.app')

@section('title', 'Флора и фауна Чечни')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endsection

@section('content')
<div class="container">
    <h1>Исчезающие виды Чечни</h1>
    
    <div class="category-container">
        <div class="category-card" onclick="window.location.href='{{ route('cards.index', ['kind' => 'animal', 'region' => '']) }}'">
            <img src="{{ asset('images/animals.jpg') }}" alt="Животные" class="category-image">
            <div class="category-content">
                <h2 class="category-title">Животные</h2>
                <p class="category-description">Исчезающие виды млекопитающих, рыб, птиц и других животных Чеченской Республики.</p>
                <div class="category-btn">Подробнее</div>
            </div>
        </div>
        
        <div class="category-card" onclick="window.location.href='{{ route('cards.index', ['kind' => 'plants', 'region' => '']) }}'">
            <img src="{{ asset('images/plants.jpg') }}" alt="Растения" class="category-image">
            <div class="category-content">
                <h2 class="category-title">Растения</h2>
                <p class="category-description">Редкие и исчезающие виды деревьев, цветов, трав и других растений Чечни.</p>
                <div class="category-btn">Подробнее</div>
            </div>
        </div>
        
        <div class="category-card" onclick="window.location.href='{{ route('cards.index', ['kind' => 'bug', 'region' => '']) }}'">
            <img src="{{ asset('images/insects.jpg') }}" alt="Насекомые" class="category-image">
            <div class="category-content">
                <h2 class="category-title">Насекомые</h2>
                <p class="category-description">Исчезающие виды пауков, бабочек, муравьев и других насекомых Чеченской Республики.</p>
                <div class="category-btn">Подробнее</div>
            </div>
        </div>
        
        <div class="category-card" onclick="window.location.href='{{ route('cards.index', ['kind' => 'fungus', 'region' => '']) }}'">
            <img src="{{ asset('images/fungi.jpg') }}" alt="Грибы" class="category-image">
            <div class="category-content">
                <h2 class="category-title">Грибы</h2>
                <p class="category-description">Редкие и исчезающие виды грибов, произрастающих на территории Чеченской Республики.</p>
                <div class="category-btn">Подробнее</div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.category-card');
    
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1 + 0.1}s`;
        
        card.setAttribute('tabindex', '0');
        card.setAttribute('role', 'button');
        
        card.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                this.click();
            }
        });
    });
});
</script>
@endsection