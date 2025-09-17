@extends('layouts.app')

@section('title', 'Флора Чечни')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endsection

@section('content')
<div class="container">
    <h1>Разнообразие флоры Чечни</h1>
    
    <div class="category-container">

        <div class="category-card" onclick="window.location.href='{{ route('cards.index', ['kind' => 'plants', 'region' => '']) }}'">
            <img src="{{ asset('images/plants.jpg') }}" alt="Растения" class="category-image">
            <div class="category-content">
                <h2 class="category-title">Растения</h2>
                <p class="category-description">Рановидности видов деревьев, цветов, трав и других растений Чечни.</p>
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