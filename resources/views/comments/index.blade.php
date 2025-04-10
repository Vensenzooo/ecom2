@extends('layouts.app')

@push('styles')
<style>
    .comment-card {
        transition: all 0.2s ease;
        border-left: 4px solid #e9ecef;
    }
    .comment-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .comment-card.pending {
        border-left-color: #ffc107;
    }
    .comment-card.approved {
        border-left-color: #28a745;
    }
    .comment-card.rejected {
        border-left-color: #dc3545;
    }
    .comment-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    .comment-meta {
        font-size: 0.85rem;
        color: #6c757d;
    }
    .comment-actions {
        display: flex;
        gap: 10px;
    }
    .badge-status {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
        font-weight: normal;
    }
    .filter-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 30px;
    }
    
    /* Styles pour uniformiser la pagination */
    .pagination {
        margin-top: 2rem;
    }
    
    .pagination .page-link {
        font-size: 0.9rem;
        padding: 0.375rem 0.75rem;
        line-height: 1.5;
        border-radius: 0.25rem;
        position: relative;
        display: block;
    }
    
    .pagination .page-item:first-child .page-link,
    .pagination .page-item:last-child .page-link {
        font-size: 0.9rem; /* Même taille que les chiffres */
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.375rem 0.75rem;
    }
    
    /* Astuce pour réduire la taille des flèches */
    .pagination .page-item:first-child .page-link span,
    .pagination .page-item:last-child .page-link span {
        transform: scale(0.9);
        display: inline-block;
    }
</style>
@endpush

@section('title', 'Gestion des commentaires')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h1 class="mb-0">Gestion des commentaires</h1>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('comments.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i>Nouveau commentaire
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="filter-section mb-4">
        <form action="{{ route('comments.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="status" class="form-label">Statut</label>
                <select name="status" id="status" class="form-select">
                    <option value="">Tous les statuts</option>
                    <option value="en attente" {{ request('status') == 'en attente' ? 'selected' : '' }}>En attente</option>
                    <option value="approuvé" {{ request('status') == 'approuvé' ? 'selected' : '' }}>Approuvé</option>
                    <option value="rejeté" {{ request('status') == 'rejeté' ? 'selected' : '' }}>Rejeté</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="book_id" class="form-label">Livre</label>
                <select name="book_id" id="book_id" class="form-select">
                    <option value="">Tous les livres</option>
                    @foreach($books ?? [] as $book)
                        <option value="{{ $book->id }}" {{ request('book_id') == $book->id ? 'selected' : '' }}>{{ $book->titre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="category_id" class="form-label">Catégorie</label>
                <select name="category_id" id="category_id" class="form-select">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories ?? [] as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="fas fa-filter me-2"></i>Filtrer
                    </button>
                    <a href="{{ route('comments.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="row">
        <div class="col-12">
            <!-- Statistiques des commentaires -->
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="card bg-light h-100">
                        <div class="card-body text-center">
                            <h5 class="text-warning mb-2"><i class="fas fa-clock me-2"></i>En attente</h5>
                            <h2 class="mb-0">{{ $pendingCount ?? $comments->where('statut', 'en attente')->count() }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card bg-light h-100">
                        <div class="card-body text-center">
                            <h5 class="text-success mb-2"><i class="fas fa-check me-2"></i>Approuvés</h5>
                            <h2 class="mb-0">{{ $approvedCount ?? $comments->where('statut', 'approuvé')->count() }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card bg-light h-100">
                        <div class="card-body text-center">
                            <h5 class="text-danger mb-2"><i class="fas fa-times me-2"></i>Rejetés</h5>
                            <h2 class="mb-0">{{ $rejectedCount ?? $comments->where('statut', 'rejeté')->count() }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des commentaires -->
            @if($comments->count() > 0)
                <div class="row">
                    @foreach($comments as $comment)
                        <div class="col-lg-6 mb-4">
                            <div class="card comment-card {{ $comment->statut == 'en attente' ? 'pending' : ($comment->statut == 'approuvé' ? 'approved' : 'rejected') }}">
                                <div class="card-body">
                                    <div class="comment-header">
                                        <h5 class="card-title mb-0">{{ $comment->user ? $comment->user->name : 'Utilisateur supprimé' }}</h5>
                                        <span class="badge {{ $comment->statut == 'en attente' ? 'bg-warning' : ($comment->statut == 'approuvé' ? 'bg-success' : 'bg-danger') }} badge-status">
                                            {{ ucfirst($comment->statut) }}
                                        </span>
                                    </div>
                                    
                                    <div class="comment-meta mb-3">
                                        <span class="me-3"><i class="fas fa-book me-1"></i> {{ $comment->book ? $comment->book->titre : 'Livre indisponible' }}</span>
                                        <span class="me-3"><i class="fas fa-folder me-1"></i> {{ $comment->book && $comment->book->category ? $comment->book->category->nom : 'Non catégorisé' }}</span>
                                        <span><i class="fas fa-calendar-alt me-1"></i> {{ $comment->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    
                                    <p class="card-text">{{ Str::limit($comment->contenu, 150) }}</p>
                                    
                                    <div class="comment-actions">
                                        <a href="{{ route('comments.show', $comment) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i> Voir
                                        </a>
                                        
                                        <a href="{{ route('comments.edit', $comment) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-edit me-1"></i> Éditer
                                        </a>
                                        
                                        @if($comment->statut == 'en attente')
                                            <form action="{{ route('comments.update', $comment) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="contenu" value="{{ $comment->contenu }}">
                                                <input type="hidden" name="book_id" value="{{ $comment->book_id }}">
                                                <input type="hidden" name="statut" value="approuvé">
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check me-1"></i> Approuver
                                                </button>
                                            </form>
                                            
                                            <form action="{{ route('comments.update', $comment) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="contenu" value="{{ $comment->contenu }}">
                                                <input type="hidden" name="book_id" value="{{ $comment->book_id }}">
                                                <input type="hidden" name="statut" value="rejeté">
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-times me-1"></i> Rejeter
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?')">
                                                <i class="fas fa-trash me-1"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $comments->links() }}
                </div>
            @else
                <div class="alert alert-info text-center p-5">
                    <i class="fas fa-comments fa-3x mb-3"></i>
                    <h4>Aucun commentaire trouvé</h4>
                    @if(isset($filtered) && $filtered)
                        <p>Aucun commentaire ne correspond à vos critères de recherche.</p>
                        <div class="mt-3">
                            @if(isset($selectedBook))
                                <span class="badge bg-primary fs-6 me-2">Livre: {{ $selectedBook->titre }}</span>
                            @endif
                            @if(isset($selectedCategory))
                                <span class="badge bg-info fs-6 me-2">Catégorie: {{ $selectedCategory->nom }}</span>
                            @endif
                            @if(request('status'))
                                <span class="badge bg-secondary fs-6">Statut: {{ request('status') }}</span>
                            @endif
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('comments.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-times me-2"></i>Effacer les filtres
                            </a>
                        </div>
                    @else
                        <p>Il n'y a pas de commentaires dans la base de données.</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation des cartes de commentaires
        document.querySelectorAll('.comment-card').forEach(function(card, index) {
            setTimeout(function() {
                card.style.opacity = '0';
                setTimeout(function() {
                    card.style.transition = 'all 0.3s ease';
                    card.style.opacity = '1';
                }, 50);
            }, index * 50);
        });
    });
</script>
@endpush
