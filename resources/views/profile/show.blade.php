@extends('layouts.app')

@section('title', 'Mon profil')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Mon profil</h1>
        </div>
    </div>

    <div class="row g-4">
        <!-- Informations du profil -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Mon compte</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=7F9CF5&background=EBF4FF&size=150" 
                            class="rounded-circle img-fluid mb-3" width="150" height="150" alt="Avatar">
                        <h5>{{ $user->name }}</h5>
                        <p class="text-muted">{{ $user->email }}</p>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span class="fw-bold">Date d'inscription:</span>
                        <span>{{ $user->created_at->format('d/m/Y') }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">Rôles:</span>
                        <span>
                            @foreach($user->roles as $role)
                                <span class="badge bg-secondary me-1">{{ $role->nom }}</span>
                            @endforeach
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulaires de modification -->
        <div class="col-md-8">
            <!-- Modification du nom -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Modifier mon nom d'utilisateur</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom d'utilisateur</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Mettre à jour</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Modification du mot de passe -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Modifier mon mot de passe</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Mot de passe actuel</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Nouveau mot de passe</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Changer le mot de passe</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Préférences de thème -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Préférences d'affichage</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.theme') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">Thème</label>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="form-check theme-card">
                                        <input class="form-check-input" type="radio" name="theme" id="themeLight" value="light" {{ session('theme', 'light') === 'light' ? 'checked' : '' }}>
                                        <label class="form-check-label w-100" for="themeLight">
                                            <div class="card">
                                                <div class="card-body text-center">
                                                    <i class="fas fa-sun fa-2x mb-2"></i>
                                                    <h6>Clair</h6>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check theme-card">
                                        <input class="form-check-input" type="radio" name="theme" id="themeDark" value="dark" {{ session('theme') === 'dark' ? 'checked' : '' }}>
                                        <label class="form-check-label w-100" for="themeDark">
                                            <div class="card">
                                                <div class="card-body text-center">
                                                    <i class="fas fa-moon fa-2x mb-2"></i>
                                                    <h6>Sombre</h6>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check theme-card">
                                        <input class="form-check-input" type="radio" name="theme" id="themeAuto" value="auto" {{ session('theme') === 'auto' ? 'checked' : '' }}>
                                        <label class="form-check-label w-100" for="themeAuto">
                                            <div class="card">
                                                <div class="card-body text-center">
                                                    <i class="fas fa-adjust fa-2x mb-2"></i>
                                                    <h6>Automatique</h6>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Appliquer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .theme-card input[type="radio"] {
        display: none;
    }
    .theme-card input[type="radio"]:checked + label .card {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }
    .theme-card label {
        cursor: pointer;
    }
    .theme-card .card {
        transition: all 0.2s;
    }
    .theme-card .card:hover {
        transform: translateY(-5px);
    }
</style>
@endpush
