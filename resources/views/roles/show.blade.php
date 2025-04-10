@extends('layouts.app')

@section('title', 'Détails du rôle')

@section('content')
<div class="container py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h1>{{ $role->nom }}</h1>
            <p class="text-muted">{{ $role->description }}</p>
            @if(in_array($role->nom, ['admin', 'gestionnaire', 'editeur', 'client']))
                <span class="badge bg-warning">Rôle Système</span>
            @endif
        </div>
        <div class="col-md-6 text-end">
            <div class="btn-group">
                <a href="{{ route('roles.assign-users', $role) }}" class="btn btn-success me-2">
                    <i class="fas fa-user-plus me-1"></i> Assigner des utilisateurs
                </a>
                @if(!in_array($role->nom, ['admin', 'gestionnaire', 'editeur', 'client']))
                    <a href="{{ route('roles.edit', $role) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit me-1"></i> Modifier
                    </a>
                    <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" 
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce rôle?')">
                            <i class="fas fa-trash me-1"></i> Supprimer
                        </button>
                    </form>
                @endif
                <a href="{{ route('roles.index') }}" class="btn btn-secondary ms-2">
                    <i class="fas fa-arrow-left me-1"></i> Retour
                </a>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Permissions -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Permissions</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td><strong>Gérer les livres</strong></td>
                                    <td>
                                        @php
                                            // Définir les permissions par défaut pour les rôles système
                                            $canManageBooks = $role->can_manage_books;
                                            if(in_array($role->nom, ['admin', 'gestionnaire', 'editeur'])) {
                                                $canManageBooks = true;
                                            }
                                        @endphp
                                        <span class="badge {{ $canManageBooks ? 'bg-success' : 'bg-danger' }}">
                                            {{ $canManageBooks ? 'Oui' : 'Non' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Gérer les catégories</strong></td>
                                    <td>
                                        @php
                                            $canManageCategories = $role->can_manage_categories;
                                            if(in_array($role->nom, ['admin', 'gestionnaire', 'editeur'])) {
                                                $canManageCategories = true;
                                            }
                                        @endphp
                                        <span class="badge {{ $canManageCategories ? 'bg-success' : 'bg-danger' }}">
                                            {{ $canManageCategories ? 'Oui' : 'Non' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Gérer les commentaires</strong></td>
                                    <td>
                                        @php
                                            $canManageComments = $role->can_manage_comments;
                                            if(in_array($role->nom, ['admin', 'gestionnaire', 'editeur'])) {
                                                $canManageComments = true;
                                            }
                                        @endphp
                                        <span class="badge {{ $canManageComments ? 'bg-success' : 'bg-danger' }}">
                                            {{ $canManageComments ? 'Oui' : 'Non' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Gérer les ventes</strong></td>
                                    <td>
                                        @php
                                            $canManageSales = $role->can_manage_sales;
                                            if(in_array($role->nom, ['admin', 'gestionnaire'])) {
                                                $canManageSales = true;
                                            }
                                        @endphp
                                        <span class="badge {{ $canManageSales ? 'bg-success' : 'bg-danger' }}">
                                            {{ $canManageSales ? 'Oui' : 'Non' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Accès au tableau de bord</strong></td>
                                    <td>
                                        @php
                                            $canViewDashboard = $role->can_view_dashboard;
                                            if(in_array($role->nom, ['admin', 'gestionnaire', 'editeur'])) {
                                                $canViewDashboard = true;
                                            }
                                        @endphp
                                        <span class="badge {{ $canViewDashboard ? 'bg-success' : 'bg-danger' }}">
                                            {{ $canViewDashboard ? 'Oui' : 'Non' }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Limites -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">Limites</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Livres ajoutés par jour
                            <span class="badge bg-primary">{{ $role->max_books_per_day ?: 'Illimité' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Commentaires par jour
                            <span class="badge bg-primary">{{ $role->max_comments_per_day ?: 'Illimité' }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Liste des utilisateurs -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center bg-success text-white">
            <h5 class="card-title mb-0">Utilisateurs avec ce rôle</h5>
            <a href="{{ route('roles.assign-users', $role) }}" class="btn btn-light btn-sm">
                <i class="fas fa-user-plus me-1"></i> Gérer les utilisateurs
            </a>
        </div>
        <div class="card-body">
            @if($role->users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Inscrit le</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($role->users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted text-center py-3">Aucun utilisateur n'a ce rôle.</p>
            @endif
        </div>
    </div>
</div>
@endsection
