@extends('layouts.app')

@section('title', 'Modifier le Rôle')

@section('content')
<div class="row my-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Modifier le Rôle</h1>
            <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Retour
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('roles.update', $role) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom du rôle</label>
                        <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom" name="nom" value="{{ old('nom', $role->nom) }}" required>
                        <div class="form-text">Exemple: admin, gestionnaire, editeur, etc.</div>
                        @error('nom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Permissions section -->
                    <div class="form-group mb-3">
                        <label class="form-label">Permissions spécifiques</label>
                        <div class="mt-2">
                            <div class="form-check mb-2">
                                <input type="hidden" name="can_manage_books" value="0">
                                <input class="form-check-input" type="checkbox" name="can_manage_books" id="can_manage_books" value="1" {{ old('can_manage_books', $role->can_manage_books) ? 'checked' : '' }}>
                                <label class="form-check-label" for="can_manage_books">
                                    Peut gérer les livres
                                </label>
                            </div>
                            
                            <div class="form-check mb-2">
                                <input type="hidden" name="can_manage_categories" value="0">
                                <input class="form-check-input" type="checkbox" name="can_manage_categories" id="can_manage_categories" value="1" {{ old('can_manage_categories', $role->can_manage_categories) ? 'checked' : '' }}>
                                <label class="form-check-label" for="can_manage_categories">
                                    Peut gérer les catégories
                                </label>
                            </div>
                            
                            <div class="form-check mb-2">
                                <input type="hidden" name="can_manage_comments" value="0">
                                <input class="form-check-input" type="checkbox" name="can_manage_comments" id="can_manage_comments" value="1" {{ old('can_manage_comments', $role->can_manage_comments) ? 'checked' : '' }}>
                                <label class="form-check-label" for="can_manage_comments">
                                    Peut gérer les commentaires
                                </label>
                            </div>
                            
                            <div class="form-check mb-2">
                                <input type="hidden" name="can_manage_sales" value="0">
                                <input class="form-check-input" type="checkbox" name="can_manage_sales" id="can_manage_sales" value="1" {{ old('can_manage_sales', $role->can_manage_sales) ? 'checked' : '' }}>
                                <label class="form-check-label" for="can_manage_sales">
                                    Peut gérer les ventes
                                </label>
                            </div>
                            
                            <div class="form-check mb-2">
                                <input type="hidden" name="can_view_dashboard" value="0">
                                <input class="form-check-input" type="checkbox" name="can_view_dashboard" id="can_view_dashboard" value="1" {{ old('can_view_dashboard', $role->can_view_dashboard) ? 'checked' : '' }}>
                                <label class="form-check-label" for="can_view_dashboard">
                                    Peut voir le tableau de bord
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
