@extends('layouts.client')

@section('title', $giftList->titre)

@push('styles')
<style>
    .gift-card {
        transition: all 0.3s ease;
        height: 100%;
    }
    .gift-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .gift-img {
        height: 180px;
        object-fit: cover;
        width: 100%;
    }
    .reserved-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        color: white;
        font-size: 1.5rem;
        font-weight: bold;
    }
    .reserved-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        transform: rotate(15deg);
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h1>{{ $giftList->titre }}</h1>
                    <p class="text-muted">
                        Liste de cadeaux de <strong>{{ $giftList->user->name }}</strong>
                    </p>
                    
                    @if($giftList->description)
                        <p>{{ $giftList->description }}</p>
                    @endif
                    
                    @if($giftList->date_evenement)
                        <p>
                            <i class="fas fa-calendar-alt me-2"></i> 
                            Date: {{ $giftList->date_evenement instanceof \Carbon\Carbon ? $giftList->date_evenement->format('d/m/Y') : $giftList->date_evenement }}
                        </p>
                    @endif
                </div>
                <div class="col-md-4 text-end">
                    <!-- Message sur le statut de la liste -->
                    @if(!$giftList->active)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Cette liste n'est plus active.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Affichage du progrès de la liste -->
    <div class="row align-items-center mb-4">
        <div class="col-md-3">
            <h2>Liste des souhaits</h2>
        </div>
        <div class="col-md-9">
            @php
                $totalItems = $giftList->items->count();
                $reservedItems = $giftList->items->where('reserve', true)->count();
                $progressPercentage = $totalItems > 0 ? ($reservedItems / $totalItems) * 100 : 0;
            @endphp
            <div class="progress" style="height: 25px;">
                <div class="progress-bar bg-success" role="progressbar" 
                    
                    aria-valuenow="{{ $progressPercentage }}"
                    aria-valuemin="0" 
                    aria-valuemax="100">
                    {{ round($progressPercentage) }}% réservés
                </div>
            </div>
            <div class="d-flex justify-content-between mt-1">
                <small class="text-muted">{{ $reservedItems }} articles réservés</small>
                <small class="text-muted">{{ $totalItems }} articles au total</small>
            </div>
        </div>
    </div>
    
    <!-- Affichage des livres/articles -->
    @if($giftList->items->count() > 0)
        <div class="row">
            @foreach($giftList->items as $item)
                <div class="col-md-4 mb-4">
                    <div class="card gift-card position-relative">
                        @if($item->reserve)
                            <div class="reserved-badge">
                                <span class="badge bg-danger p-2">
                                    <i class="fas fa-check-circle me-1"></i> Réservé
                                </span>
                            </div>
                        @endif
                        
                        @if($item->book->image_url)
                            <img src="{{ $item->book->image_url }}" alt="{{ $item->book->titre }}" class="gift-img">
                        @else
                            <div class="gift-img d-flex align-items-center justify-content-center bg-light">
                                <i class="fas fa-book fa-3x text-muted"></i>
                            </div>
                        @endif
                        
                        <div class="card-body">
                            <h5 class="card-title">{{ $item->book->titre }}</h5>
                            <p class="card-text">
                                <small class="text-muted">{{ $item->book->auteur }}</small>
                            </p>
                            <p class="card-text">
                                {{ Str::limit($item->book->description, 100) }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold">{{ number_format($item->book->prix, 2) }} €</span>
                                <span class="badge bg-info">Quantité: {{ $item->quantite }}</span>
                            </div>
                        </div>
                        
                        <div class="card-footer">
                            @if(!$item->reserve && $giftList->active)
                                <form action="{{ route('gift-lists.reserve-item', [$giftList->code_partage, $item->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100" 
                                            onclick="return confirm('Voulez-vous vraiment réserver cet article?')">
                                        <i class="fas fa-gift me-1"></i> Réserver
                                    </button>
                                </form>
                            @elseif($item->reserve)
                                <button class="btn btn-outline-secondary w-100" disabled>
                                    <i class="fas fa-check-circle me-1"></i> Déjà réservé
                                </button>
                            @else
                                <button class="btn btn-outline-secondary w-100" disabled>
                                    <i class="fas fa-times-circle me-1"></i> Non disponible
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-info-circle fa-2x mb-3"></i>
            <h3>Cette liste ne contient aucun article</h3>
            <p>Aucun article n'a encore été ajouté à cette liste de cadeaux.</p>
        </div>
    @endif
    
    <!-- Section d'information -->
    <div class="card mt-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">À propos des réservations</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-lock text-primary fa-2x"></i>
                        </div>
                        <div>
                            <h5>Confidentialité</h5>
                            <p class="text-muted">Les réservations ne sont pas visibles par le créateur de la liste</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-share-alt text-success fa-2x"></i>
                        </div>
                        <div>
                            <h5>Partagez</h5>
                            <p class="text-muted">Partagez ce lien avec d'autres personnes</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-undo text-warning fa-2x"></i>
                        </div>
                        <div>
                            <h5>Annulation</h5>
                            <p class="text-muted">Contactez-nous si vous souhaitez annuler une réservation</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set progress bar width using JavaScript
        const progressBar = document.querySelector('.progress-bar');
        if (progressBar) {
            const width = progressBar.getAttribute('data-width');
            progressBar.style.width = width + '%';
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set progress bar width using JavaScript
        document.querySelectorAll('.progress-bar').forEach(function(bar) {
            const percentage = bar.getAttribute('data-percentage');
            if (percentage) {
                bar.style.width = percentage + '%';
            }
        });
    });
</script>
@endpush
