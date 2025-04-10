@extends('layouts.app')

@section('title', 'Restreindre le compte')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Restreindre le compte de {{ $user->name }}</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Attention - Action irréversible</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        L'utilisateur sera immédiatement déconnecté et n'aura plus accès à son compte jusqu'à ce que vous leviez la restriction.
                    </div>
                    
                    <form action="{{ route('users.restrict', $user) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="restriction_reason" class="form-label">Motif de la restriction</label>
                            <textarea name="restriction_reason" id="restriction_reason" rows="3" class="form-control @error('restriction_reason') is-invalid @enderror" required>{{ old('restriction_reason') }}</textarea>
                            <div class="form-text">Ce motif sera affiché à l'utilisateur.</div>
                            @error('restriction_reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('users.show', $user) }}" class="btn btn-outline-secondary">Annuler</a>
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir restreindre ce compte ?')">
                                <i class="fas fa-ban me-2"></i>Restreindre ce compte
                            </button>
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
