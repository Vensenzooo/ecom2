@extends('layouts.app')

@section('title', 'Détails de l\'utilisateur')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>{{ $user->name }}</h1>
        </div>
        <div class="col-md-6 text-end">
            @can('is-admin')
                <div class="btn-group">
                    <a href="{{ route('users.profile.edit', $user) }}" class="btn btn-primary">
                        <i class="fas fa-user-edit me-2"></i>Modifier le profil
                    </a>
                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="visually-hidden">Menu</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a href="{{ route('alerts.create', ['user_id' => $user->id]) }}" class="dropdown-item">
                                <i class="fas fa-bell me-2"></i>Envoyer une alerte
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        @if($user->is_restricted)
                            <li>
                                <form action="{{ route('users.unrestrict', $user) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-success">
                                        <i class="fas fa-unlock me-2"></i>Lever la restriction
                                    </button>
                                </form>
                            </li>
                        @else
                            <li>
                                <a href="{{ route('users.restrict.show', $user) }}" class="dropdown-item text-danger">
                                    <i class="fas fa-ban me-2"></i>Restreindre le compte
                                </a>
                            </li>
                        @endif
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a href="{{ route('users.edit', $user) }}" class="dropdown-item">
                                <i class="fas fa-cog me-2"></i>Gérer les rôles
                            </a>
                        </li>
                    </ul>
                </div>
            @endcan
            
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary ms-2">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
        </div>
    </div>

    @if($user->is_restricted)
        <div class="alert alert-danger d-flex align-items-center mb-4">
            <i class="fas fa-ban fa-2x me-3"></i>
            <div>
                <h5 class="alert-heading mb-1">Ce compte est actuellement restreint</h5>
                <p class="mb-0">Motif: {{ $user->restriction_reason }}</p>
                <p class="mb-0 small">Restreint le: {{ $user->restricted_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Informations personnelles</h5>
                </div>
                <div class="card-body text-center">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=7F9CF5&background=EBF4FF&size=150" 
                        class="rounded-circle img-fluid mb-3" width="150" height="150" alt="Avatar">
                    <h4>{{ $user->name }}</h4>
                    <p class="text-muted">{{ $user->email }}</p>
                    
                    <div class="d-flex justify-content-center mt-3">
                        @foreach($user->roles as $role)
                            <span class="badge bg-primary me-1">{{ $role->nom }}</span>
                        @endforeach
                    </div>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Membre depuis</span>
                        <span>{{ $user->created_at->format('d/m/Y') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Commentaires</span>
                        <span class="badge bg-primary rounded-pill">{{ $user->comments->count() }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Activité récente</h5>
                </div>
                <div class="card-body">
                    @if($user->comments->isEmpty())
                        <p class="text-muted text-center">Aucun commentaire trouvé.</p>
                    @else
                        <ul class="list-group">
                            @foreach($user->comments->take(5) as $comment)
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <a href="{{ route('books.show', $comment->book) }}" class="fw-bold">{{ $comment->book->titre }}</a>
                                        <span class="badge {{ $comment->statut == 'approuvé' ? 'bg-success' : ($comment->statut == 'en attente' ? 'bg-warning' : 'bg-danger') }}">
                                            {{ $comment->statut }}
                                        </span>
                                    </div>
                                    <p class="mb-1">{{ Str::limit($comment->contenu, 120) }}</p>
                                    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                </li>
                            @endforeach
                        </ul>
                        
                        @if($user->comments->count() > 5)
                            <div class="text-center mt-3">
                                <a href="{{ route('comments.index', ['user_id' => $user->id]) }}" class="btn btn-sm btn-outline-primary">
                                    Voir tous les commentaires ({{ $user->comments->count() }})
                                </a>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
            
            @can('is-admin')
                <!-- Section des alertes envoyées -->
                <div class="card mb-4">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Alertes envoyées</h5>
                        <a href="{{ route('alerts.create', ['user_id' => $user->id]) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-plus me-1"></i>Nouvelle alerte
                        </a>
                    </div>
                    <div class="card-body">
                        @if($user->alerts->isEmpty())
                            <p class="text-muted text-center">Aucune alerte n'a été envoyée à cet utilisateur.</p>
                        @else
                            <ul class="list-group">
                                @foreach($user->alerts->sortByDesc('created_at')->take(5) as $alert)
                                    <li class="list-group-item list-group-item-{{ $alert->type }}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <p class="mb-1">{{ $alert->message }}</p>
                                                <small class="text-muted">
                                                    Envoyée par {{ $alert->creator->name }} le {{ $alert->created_at->format('d/m/Y H:i') }}
                                                </small>
                                            </div>
                                            <span class="badge {{ is_null($alert->read_at) ? 'bg-secondary' : 'bg-light text-dark' }}">
                                                {{ is_null($alert->read_at) ? 'Non lue' : 'Lue le ' . $alert->read_at->format('d/m/Y') }}
                                            </span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            @endcan
        </div>
    </div>
</div>
@endsection
