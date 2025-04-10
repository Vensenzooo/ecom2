@extends('layouts.client')

@section('title', 'Mon profil')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Mon profil</h1>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <div class="row">
        <!-- Informations de profil -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Informations personnelles</h5>
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
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse e-mail</label>
                            <input type="email" class="form-control" id="email" value="{{ $user->email }}" readonly disabled>
                            <div class="form-text">L'adresse e-mail ne peut pas être modifiée.</div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Mettre à jour</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Changement de mot de passe -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Changer de mot de passe</h5>
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
                            <label for="password_confirmation" class="form-label">Confirmer le nouveau mot de passe</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Changer le mot de passe</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Préférences -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Préférences d'affichage</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.theme') }}" method="POST" id="themeForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">Thème</label>
                            <div class="d-flex">
                                <div class="form-check me-3">
                                    <input class="form-check-input" type="radio" name="theme" id="themeLight" value="light" {{ session('theme', 'light') === 'light' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="themeLight">Clair</label>
                                </div>
                                <div class="form-check me-3">
                                    <input class="form-check-input" type="radio" name="theme" id="themeDark" value="dark" {{ session('theme') === 'dark' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="themeDark">Sombre</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="theme" id="themeAuto" value="auto" {{ session('theme') === 'auto' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="themeAuto">Automatique</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Enregistrer les préférences</button>
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
    /* Styles pour la page de profil */
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #eaecef;
        font-weight: 500;
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto-submit the form when theme selection changes
    document.addEventListener('DOMContentLoaded', function() {
        const themeForm = document.getElementById('themeForm');
        const themeRadios = document.querySelectorAll('input[name="theme"]');
        
        themeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                themeForm.submit();
            });
        });
    });
</script>
@endpush
