@extends('layouts.client')

@section('title', 'Mes listes de cadeaux')

@push('styles')
<style>
    /* Utiliser des classes CSS statiques pour les pourcentages courants */
    .progress-value-0 { width: 0%; }
    .progress-value-10 { width: 10%; }
    .progress-value-20 { width: 20%; }
    .progress-value-25 { width: 25%; }
    .progress-value-30 { width: 30%; }
    .progress-value-40 { width: 40%; }
    .progress-value-50 { width: 50%; }
    .progress-value-60 { width: 60%; }
    .progress-value-70 { width: 70%; }
    .progress-value-75 { width: 75%; }
    .progress-value-80 { width: 80%; }
    .progress-value-90 { width: 90%; }
    .progress-value-100 { width: 100%; }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Mes listes de cadeaux</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('client.gift-lists.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-1"></i> Créer une liste
            </a>
        </div>
    </div>
    
    @if($giftLists->count() > 0)
        <div class="row">
            @foreach($giftLists as $list)
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $list->titre }}</h5>
                        <span class="badge {{ $list->active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $list->active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="card-body">
                        <p>{{ Str::limit($list->description, 100) }}</p>
                        
                        @if($list->date_evenement)
                        <p class="mb-3">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Date : {{ $list->date_evenement instanceof \Carbon\Carbon ? $list->date_evenement->format('d/m/Y') : $list->date_evenement }}
                        </p>
                        @endif
                        
                        <p class="mb-3">
                            <i class="fas fa-gift me-2"></i>
                            Articles : {{ $list->items_count ?? 0 }}
                        </p>
                        
                        @php
                            $progressValue = $list->getReservationProgressAttribute();
                            // Arrondir à la dizaine la plus proche
                            $roundedProgress = round($progressValue / 10) * 10;
                            // Gérer les cas particuliers
                            if ($progressValue > 0 && $roundedProgress == 0) $roundedProgress = 10;
                            if ($progressValue < 100 && $roundedProgress == 100) $roundedProgress = 90;
                        @endphp
                        <div class="progress mb-3">
                            <div class="progress-bar bg-success progress-value-{{ $roundedProgress }}" 
                                role="progressbar" 
                                aria-valuenow="{{ $progressValue }}"
                                aria-valuemin="0" 
                                aria-valuemax="100">
                                {{ $progressValue }}%
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('client.gift-lists.show', $list) }}" class="btn btn-outline-primary">
                                <i class="fas fa-eye me-1"></i> Voir
                            </a>
                            <div class="input-group" style="max-width: 300px;">
                                <input type="text" class="form-control" value="{{ route('gift-lists.shared', $list->code_partage) }}" readonly>
                                <button class="btn btn-outline-secondary copy-btn" type="button" data-clipboard-text="{{ route('gift-lists.shared', $list->code_partage) }}">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="d-flex justify-content-center mt-4">
            {{ $giftLists->links() }}
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-gift fa-3x mb-3 text-muted"></i>
                <h3>Vous n'avez pas encore de liste de cadeaux</h3>
                <p class="text-muted">Créez votre première liste et partagez-la avec vos amis.</p>
                <a href="{{ route('client.gift-lists.create') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-plus-circle me-1"></i> Créer une liste
                </a>
            </div>
        </div>
    @endif
</div>

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var clipboard = new ClipboardJS('.copy-btn');
        
        clipboard.on('success', function(e) {
            e.trigger.innerHTML = '<i class="fas fa-check"></i>';
            setTimeout(function() {
                e.trigger.innerHTML = '<i class="fas fa-copy"></i>';
            }, 2000);
            e.clearSelection();
        });
    });
</script>
@endsection
@endsection
