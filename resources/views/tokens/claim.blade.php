@extends('layouts.client')

@section('title', 'Réclamer un Code Cadeau')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Réclamer un Code Cadeau</h5>
                </div>
                
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif
                    
                    @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif
                    
                    <p class="mb-4">
                        Saisissez le code cadeau que vous avez reçu pour bénéficier d'une réduction sur votre prochain achat.
                    </p>
                    
                    <form action="{{ route('client.tokens.claim.process') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="code" class="form-label">Code Cadeau</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-gift"></i>
                                </span>
                                <input type="text" id="code" name="code" class="form-control form-control-lg text-center @error('code') is-invalid @enderror" 
                                       placeholder="GIFT1A2B3C4D50" value="{{ old('code') }}" required>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">
                                Les codes cadeaux vous sont généralement envoyés par email ou offerts par d'autres utilisateurs.
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-check-circle me-1"></i> Réclamer ma réduction
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="text-center mt-3">
                <a href="{{ route('client.tokens.index') }}" class="btn btn-link">
                    <i class="fas fa-arrow-left me-1"></i> Retour aux Étoiles
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
