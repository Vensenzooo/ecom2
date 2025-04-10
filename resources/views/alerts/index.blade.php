@extends('layouts.app')

@section('title', 'Mes alertes')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Mes alertes</h1>
        </div>
        <div class="col-md-4 text-end">
            @if(Auth::user()->unreadAlertsCount() > 0)
                <form action="{{ route('alerts.readAll') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-check-double me-2"></i>Tout marquer comme lu
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @if($alerts->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>Vous n'avez aucune alerte.
                </div>
            @else
                <div class="list-group">
                    @foreach($alerts as $alert)
                        <div class="list-group-item list-group-item-{{ $alert->type }} d-flex justify-content-between align-items-center {{ is_null($alert->read_at) ? 'fw-bold' : '' }}">
                            <div>
                                @if($alert->type == 'danger')
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                @elseif($alert->type == 'warning')
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                @else
                                    <i class="fas fa-info-circle me-2"></i>
                                @endif
                                {{ $alert->message }}
                                <div class="text-muted small mt-1">
                                    <i class="fas fa-clock me-1"></i>{{ $alert->created_at->diffForHumans() }}
                                    <i class="fas fa-user me-1 ms-2"></i>{{ $alert->creator->name }}
                                </div>
                            </div>
                            @if(is_null($alert->read_at))
                                <form action="{{ route('alerts.read', $alert) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            @else
                                <span class="badge bg-secondary">Lu le {{ $alert->read_at->format('d/m/Y H:i') }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $alerts->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
