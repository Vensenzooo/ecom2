@extends('layouts.app')

@section('title', 'Créer un rôle')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Créer un nouveau rôle</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
        </div>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('roles.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom du rôle</label>
                    <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom" name="nom" value="{{ old('nom') }}" required>
                    @error('nom')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Le nom unique du rôle (ex: "moderateur", "vendeur").</div>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <hr class="my-4">
                <h5 class="mb-3">Fonctionnalités et permissions</h5>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header">
                                <h6 class="mb-0">Gestion des accès</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-check mb-2">
                                    <input type="hidden" name="can_view_dashboard" value="0">
                                    <input class="form-check-input" type="checkbox" name="can_view_dashboard" id="can_view_dashboard" value="1" {{ old('can_view_dashboard') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="can_view_dashboard">
                                        Peut voir le tableau de bord
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input type="hidden" name="can_manage_books" value="0">
                                    <input class="form-check-input" type="checkbox" name="can_manage_books" id="can_manage_books" value="1" {{ old('can_manage_books') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="can_manage_books">
                                        Peut gérer les livres
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input type="hidden" name="can_manage_categories" value="0">
                                    <input class="form-check-input" type="checkbox" name="can_manage_categories" id="can_manage_categories" value="1" {{ old('can_manage_categories') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="can_manage_categories">
                                        Peut gérer les catégories
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input type="hidden" name="can_manage_comments" value="0">
                                    <input class="form-check-input" type="checkbox" name="can_manage_comments" id="can_manage_comments" value="1" {{ old('can_manage_comments') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="can_manage_comments">
                                        Peut gérer les commentaires
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input type="hidden" name="can_manage_sales" value="0">
                                    <input class="form-check-input" type="checkbox" name="can_manage_sales" id="can_manage_sales" value="1" {{ old('can_manage_sales') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="can_manage_sales">
                                        Peut gérer les ventes
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header">
                                <h6 class="mb-0">Limites et quotas</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="max_books_per_day" class="form-label">Limite de livres ajoutés par jour</label>
                                    <input type="number" class="form-control" id="max_books_per_day" name="max_books_per_day" value="{{ old('max_books_per_day') }}" min="0">
                                    <div class="form-text">Laisser vide pour illimité.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="max_comments_per_day" class="form-label">Limite de commentaires par jour</label>
                                    <input type="number" class="form-control" id="max_comments_per_day" name="max_comments_per_day" value="{{ old('max_comments_per_day') }}" min="0">
                                    <div class="form-text">Laisser vide pour illimité.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if(isset($permissions) && $permissions->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Permissions spécifiques</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($permissions as $permission)
                                <div class="col-md-4 mb-2">
                                    <div class="form-check">
                                        <input type="checkbox" 
                                               class="form-check-input" 
                                               id="permission_{{ $permission->id }}" 
                                               name="permissions[]" 
                                               value="{{ $permission->id }}" 
                                               {{ (is_array(old('permissions')) && in_array($permission->id, old('permissions'))) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="permission_{{ $permission->id }}">
                                            {{ $permission->nom }}
                                            <small class="d-block text-muted">{{ $permission->description }}</small>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary me-md-2">Annuler</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Enregistrer le rôle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
