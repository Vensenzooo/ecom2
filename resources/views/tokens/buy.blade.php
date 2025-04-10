@extends('layouts.client')

@section('title', 'Acheter des Étoiles')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Acheter des Étoiles</h1>
            <p class="text-muted">Augmentez votre solde d'étoiles pour obtenir des réductions exclusives</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('client.tokens.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-1"></i> Retour aux Étoiles
            </a>
        </div>
    </div>
    
    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <div class="row">
        @foreach($packages as $package)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header {{ $loop->first ? 'bg-light' : ($loop->last ? 'bg-primary text-white' : 'bg-info text-white') }}">
                    <h5 class="mb-0">{{ $package['name'] }}</h5>
                </div>
                <div class="card-body d-flex flex-column">
                    <div class="mb-3 text-center">
                        <span class="display-4 fw-bold">{{ number_format($package['tokens']) }}</span>
                        <span class="text-muted d-block">étoiles</span>
                    </div>
                    
                    <div class="mb-3">
                        <p>{{ $package['description'] }}</p>
                    </div>
                    
                    <div class="price-tag mb-4 text-center">
                        <span class="h3 fw-bold">{{ number_format($package['price'], 2) }} €</span>
                    </div>
                    
                    <form action="{{ route('client.tokens.purchase') }}" method="POST" class="mt-auto">
                        @csrf
                        <input type="hidden" name="package_id" value="{{ $package['id'] }}">
                        <button type="submit" class="btn {{ $loop->first ? 'btn-outline-primary' : ($loop->last ? 'btn-primary' : 'btn-info') }} w-100">
                            <i class="fas fa-shopping-cart me-1"></i> Acheter maintenant
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Pourquoi acheter des étoiles?</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-gift text-primary fa-2x"></i>
                        </div>
                        <div>
                            <h5>Offrez des réductions</h5>
                            <p class="text-muted">Partagez des codes de réduction avec vos amis</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-3">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-percentage text-success fa-2x"></i>
                        </div>
                        <div>
                            <h5>Économisez sur vos achats</h5>
                            <p class="text-muted">Utilisez des réductions jusqu'à 50%</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-3">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-star text-warning fa-2x"></i>
                        </div>
                        <div>
                            <h5>Statut client privilégié</h5>
                            <p class="text-muted">Débloquez des avantages exclusifs</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
