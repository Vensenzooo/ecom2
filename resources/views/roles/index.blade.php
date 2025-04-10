@extends('layouts.app')

@section('title', 'Gestion des rôles')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Gestion des rôles</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('roles.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i>Créer un rôle
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-1"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle me-1"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Utilisateurs</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $role)
                            <tr>
                                <td>
                                    <span class="fw-bold">{{ $role->nom }}</span>
                                    @if(in_array($role->nom, ['admin', 'gestionnaire', 'editeur', 'client']))
                                        <span class="badge bg-info ms-2">Système</span>
                                    @endif
                                </td>
                                <td>{{ $role->description }}</td>
                                <td>{{ $role->users_count }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('roles.show', $role) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @if(!in_array($role->nom, ['admin', 'gestionnaire', 'editeur', 'client']))
                                            <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce rôle ?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Aucun rôle trouvé</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination - S'assurer que nous utilisons la pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $roles->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
