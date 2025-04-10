@extends('layouts.app')

@section('title', 'Gestion des alertes')

@push('styles')
<style>
    .alert-card {
        transition: all 0.3s ease;
        border-left: 4px solid;
    }
    .alert-card.unread {
        border-left-color: #4e73df;
        background-color: rgba(78, 115, 223, 0.05);
    }
    .border-left-info { border-left-color: #36b9cc !important; }
    .border-left-warning { border-left-color: #f6c23e !important; }
    .border-left-danger { border-left-color: #e74a3b !important; }
    .border-left-primary { border-left-color: #4e73df !important; }
    .border-left-success { border-left-color: #1cc88a !important; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h1 class="mb-0">Gestion des alertes</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Toutes vos alertes</h6>
                    @if($unreadAlerts->count() > 0)
                        <form action="{{ route('alerts.readAll') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-check-double me-1"></i>Tout marquer comme lu
                            </button>
                        </form>
                    @endif
                </div>
                <div class="card-body">
                    <!-- Alertes non lues -->
                    @if($unreadAlerts->count() > 0)
                        <h5 class="mb-3">Messages non lus</h5>
                        @foreach($unreadAlerts as $alert)
                            <div class="card mb-3 alert-card unread border-left-{{ $alert->type }}">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="card-title fw-bold">{{ $alert->message }}</h6>
                                        <form action="{{ route('alerts.read', $alert) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-check"></i> Marquer comme lu
                                            </button>
                                        </form>
                                    </div>
                                    <p class="card-text text-muted small">{{ $alert->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                        <hr class="my-4">
                    @endif
                    
                    <!-- Historique des alertes -->
                    <h5 class="mb-3">Historique des messages</h5>
                    @if($alerts->count() > 0)
                        @foreach($alerts as $alert)
                            <div class="card mb-3 alert-card border-left-{{ $alert->type }}">
                                <div class="card-body">
                                    <p class="card-text">{{ $alert->message }}</p>
                                    <p class="card-text text-muted small">{{ $alert->created_at->diffForHumans() }}</p>
                                    @if($alert->read_at)
                                        <p class="card-text text-muted small">Lu le {{ $alert->read_at->format('d/m/Y à H:i') }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $alerts->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>Vous n'avez pas d'alertes.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
