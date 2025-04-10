@extends('layouts.app')

@section('title', 'Détails du Commentaire')

@section('content')
<div class="row my-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Détails du Commentaire</h1>
            <div>
                <a href="{{ route('comments.edit', $comment) }}" class="btn btn-warning me-2">
                    <i class="fas fa-edit me-1"></i> Modifier
                </a>
                <a href="{{ route('comments.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Retour
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Informations</h5>
                        <hr>
                        <p><strong>Livre :</strong> {{ $comment->book->titre ?? 'N/A' }}</p>
                        <p><strong>Auteur :</strong> {{ $comment->user->name ?? 'Anonyme' }}</p>
                        <p><strong>Date :</strong> {{ $comment->created_at->format('d/m/Y H:i') }}</p>
                        <p>
                            <strong>Statut :</strong>
                            <span class="badge bg-{{ $comment->statut == 'approuvé' ? 'success' : ($comment->statut == 'en attente' ? 'warning' : 'danger') }}">
                                {{ $comment->statut }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h5>Contenu du commentaire</h5>
                        <hr>
                        <div class="border p-3 rounded">
                            {{ $comment->contenu }}
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end">
                    <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce commentaire?')">
                            <i class="fas fa-trash me-1"></i> Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
