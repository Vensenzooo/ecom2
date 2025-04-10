@extends('layouts.app')

@section('title', 'Envoyer une alerte')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Envoyer une alerte à {{ $user->name }}</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('alerts.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        
                        <div class="mb-3">
                            <label for="type" class="form-label">Type d'alerte</label>
                            <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                                <option value="info">Information</option>
                                <option value="warning">Avertissement</option>
                                <option value="danger">Sévère</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea name="message" id="message" rows="5" class="form-control @error('message') is-invalid @enderror" required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('users.show', $user) }}" class="btn btn-outline-secondary">Annuler</a>
                            <button type="submit" class="btn btn-primary">Envoyer l'alerte</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Informations sur l'utilisateur</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=7F9CF5&background=EBF4FF" class="rounded-circle me-3" width="50" height="50" alt="Avatar">
                        <div>
                            <h5 class="mb-0">{{ $user->name }}</h5>
                            <p class="text-muted mb-0">{{ $user->email }}</p>
                        </div>
                    </div>
                    
                    <div class="mb-2">
                        <strong>Rôles:</strong>
                        @foreach($user->roles as $role)
                            <span class="badge bg-secondary me-1">{{ $role->nom }}</span>
                        @endforeach
                    </div>
                    
                    <div class="mb-2">
                        <strong>Compte créé le:</strong> {{ $user->created_at->format('d/m/Y') }}
                    </div>
                    
                    @if($user->is_restricted)
                        <div class="alert alert-danger mt-3 mb-0">
                            <i class="fas fa-ban me-2"></i>Ce compte est actuellement restreint.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
