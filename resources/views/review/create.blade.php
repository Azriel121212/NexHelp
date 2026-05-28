@extends('layouts.app')
@section('title', 'Kasih Rating')

@section('content')
<header class="bg-surface text-primary shadow-sm flex items-center px-4 py-3 w-full sticky top-0 z-50">
    <h1 class="font-headline-md text-xl font-bold flex-1 text-center">Beri Ulasan</h1>
</header>

<main class="px-4 py-8 max-w-md mx-auto w-full pb-24">
    <div class="text-center mb-8">
        <img src="{{ $task->helper->avatar_url }}" alt="Helper Avatar" class="w-24 h-24 rounded-full object-cover mx-auto mb-4 border-4 border-primary-fixed">
        <h2 class="text-xl font-bold text-on-surface">{{ $task->helper->name }}</h2>
        <p class="text-sm text-outline">Helper buat: {{ $task->title }}</p>
    </div>

    <form action="{{ route('review.store', $task->id) }}" method="POST" class="bg-surface rounded-3xl p-6 shadow-sm border border-surface-container-high">
        @csrf
        
        <!-- Rating Stars -->
        <div class="mb-6 text-center">
            <label class="block text-sm font-bold text-on-surface mb-3">Gimana kerjanya si Helper?</label>
            <div class="flex justify-center gap-2 flex-row-reverse" id="star-rating">
                <input type="radio" name="rating" id="star5" value="5" class="peer hidden" required>
                <label for="star5" class="material-symbols-outlined text-4xl text-outline-variant cursor-pointer peer-checked:text-[#FFB400] hover:text-[#FFB400] transition-colors" style="font-variation-settings: 'FILL' 1;">star</label>
                
                <input type="radio" name="rating" id="star4" value="4" class="peer hidden">
                <label for="star4" class="material-symbols-outlined text-4xl text-outline-variant cursor-pointer peer-checked:text-[#FFB400] hover:text-[#FFB400] peer-checked:!text-[#FFB400] transition-colors" style="font-variation-settings: 'FILL' 1;">star</label>
                
                <input type="radio" name="rating" id="star3" value="3" class="peer hidden">
                <label for="star3" class="material-symbols-outlined text-4xl text-outline-variant cursor-pointer peer-checked:text-[#FFB400] hover:text-[#FFB400] transition-colors" style="font-variation-settings: 'FILL' 1;">star</label>
                
                <input type="radio" name="rating" id="star2" value="2" class="peer hidden">
                <label for="star2" class="material-symbols-outlined text-4xl text-outline-variant cursor-pointer peer-checked:text-[#FFB400] hover:text-[#FFB400] transition-colors" style="font-variation-settings: 'FILL' 1;">star</label>
                
                <input type="radio" name="rating" id="star1" value="1" class="peer hidden">
                <label for="star1" class="material-symbols-outlined text-4xl text-outline-variant cursor-pointer peer-checked:text-[#FFB400] hover:text-[#FFB400] transition-colors" style="font-variation-settings: 'FILL' 1;">star</label>
            </div>
            @error('rating')
                <p class="text-error text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="comment" class="block text-sm font-bold text-on-surface mb-2">Testimoni (Opsional)</label>
            <textarea name="comment" id="comment" rows="3" placeholder="Tulis pesan buat kerjanya dia..." class="w-full bg-surface-container-lowest border border-outline-variant rounded-xl px-4 py-3 text-sm focus:ring-primary focus:border-primary resize-none"></textarea>
            @error('comment')
                <p class="text-error text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="w-full bg-primary text-on-primary py-3.5 rounded-full font-bold hover:bg-primary-container hover:text-on-primary-container transition-colors shadow-md">
            Kirim Ulasan
        </button>
    </form>
</main>

<style>
    /* CSS Trick for Star Rating Hover Effect */
    #star-rating label:hover,
    #star-rating label:hover ~ label,
    #star-rating input:checked ~ label {
        color: #FFB400 !important;
    }
</style>
@endsection
