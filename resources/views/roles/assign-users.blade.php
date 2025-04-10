@extends('layouts.app')

@section('title', 'Assigner des utilisateurs au rôle')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--multiple {
        border-color: #ced4da;
    }
    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    .role-badge {
        font-size: 0.9rem;
        padding: 0.4rem 0.8rem;
        margin-left: 1rem;
    }
    .system-role-warning {
        background-color: #fff3cd;
        border-color: #ffecb5;
        color: #664d03;
        padding: 1rem;
        border-radius: 0.25rem;
        margin-bottom: 1.5rem;
    }
    .action-buttons {
        display: flex;
        justify-content: space-between;
        margin-top: 2rem;
    }
    .tab-content {
        padding-top: 1.5rem;
    }
    .user-list-table {
        margin-top: 1rem;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h1>Assigner des utilisateurs</h1>
            <p class="text-muted">
                Rôle : {{ $role->nom }} 
                @if($isSystemRole)
                    <span class="badge bg-warning role-badge">Rôle Système</span>
                @endif
            </p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('roles.show', $role) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Retour au rôle
            </a>
        </div>
    </div>

    @if($isSystemRole)
    <div class="system-role-warning">
        <div class="d-flex">
            <div class="me-3">
                <i class="fas fa-exclamation-triangle fa-2x"></i>
            </div>
            <div>
                <h5>Rôle système</h5>
                <p class="mb-0">
                    Ce rôle est un rôle système intégré à l'application. Vous pouvez assigner des utilisateurs à ce rôle, 
                    mais ses permissions et autres propriétés ne peuvent pas être modifiées.
                </p>
            </div>
        </div>
    </div>
    @endif

    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="add-users-tab" data-bs-toggle="tab" data-bs-target="#add-users" type="button" role="tab" aria-controls="add-users" aria-selected="true">
                        <i class="fas fa-user-plus me-1"></i> Ajouter des utilisateurs
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="current-users-tab" data-bs-toggle="tab" data-bs-target="#current-users" type="button" role="tab" aria-controls="current-users" aria-selected="false">
                        <i class="fas fa-user-minus me-1"></i> Gérer les utilisateurs actuels
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="myTabContent">
                <!-- Onglet pour ajouter des utilisateurs -->
                <div class="tab-pane fade show active" id="add-users" role="tabpanel" aria-labelledby="add-users-tab">
                    <form action="{{ route('roles.update-users', $role) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="action" value="add">
                        
                        <div class="mb-3">
                            <label for="users_to_add" class="form-label">Sélectionner des utilisateurs à ajouter</label>
                            <select class="form-control select2-multiple @error('users_to_add') is-invalid @enderror" 
                                   id="users_to_add" name="users_to_add[]" multiple>
                                @foreach($users as $user)
                                    @if(!in_array($user->id, $roleUsers))
                                    <option value="{{ $user->id }}">
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                    @endif
                                @endforeach
                            </select>
                            
                            @error('users_to_add')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            <div class="form-text">
                                <i class="fas fa-info-circle"></i> 
                                Vous pouvez sélectionner plusieurs utilisateurs. Utilisez la barre de recherche pour filtrer.
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-user-plus me-1"></i> Ajouter les utilisateurs sélectionnés
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Onglet pour supprimer des utilisateurs -->
                <div class="tab-pane fade" id="current-users" role="tabpanel" aria-labelledby="current-users-tab">
                    @if(count($roleUsers) > 0)
                        <form action="{{ route('roles.update-users', $role) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="action" value="remove">
                            
                            <div class="table-responsive user-list-table">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;"><input type="checkbox" id="select-all"></th>
                                            <th>Nom</th>
                                            <th>Email</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $user)
                                            @if(in_array($user->id, $roleUsers))
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="users_to_remove[]" value="{{ $user->id }}" class="user-checkbox">
                                                </td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    <span class="badge bg-primary">{{ count($roleUsers) }}</span> utilisateur(s) avec ce rôle
                                </div>
                                <button type="submit" class="btn btn-danger" id="remove-btn" disabled>
                                    <i class="fas fa-user-minus me-1"></i> Supprimer les utilisateurs sélectionnés
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> Aucun utilisateur n'a ce rôle actuellement.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialiser Select2 avec fonctionnalité de recherche
        $('.select2-multiple').select2({
            placeholder: "Sélectionner des utilisateurs...",
            allowClear: true,
            width: '100%'
        });
        
        // Gestion de la case à cocher "Tout sélectionner"
        $('#select-all').change(function() {
            $('.user-checkbox').prop('checked', $(this).prop('checked'));
            updateRemoveButton();
        });
        
        // Activer/désactiver le bouton de suppression en fonction des cases cochées
        $('.user-checkbox').change(function() {
            updateRemoveButton();
        });
        
        function updateRemoveButton() {
            var anyChecked = $('.user-checkbox:checked').length > 0;
            $('#remove-btn').prop('disabled', !anyChecked);
        }
    });
</script>
@endpush
