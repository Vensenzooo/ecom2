@extends('layouts.client')

@section('title', $giftList->titre)

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>{{ $giftList->titre }}</h1>
            @if($giftList->date_evenement)
                <p class="text-muted">
                    <i class="fas fa-calendar-alt me-1"></i>
                    Date: {{ $giftList->date_evenement instanceof \Carbon\Carbon ? $giftList->date_evenement->format('d/m/Y') : $giftList->date_evenement }}
                </p>
            @endif
        </div>
        <div class="col-md-4 text-end">
            <div class="btn-group">
                <a href="{{ route('client.gift-lists.edit', $giftList) }}" class="btn btn-warning me-2">
                    <i class="fas fa-edit me-1"></i> Modifier
                </a>
                <a href="{{ route('client.gift-lists.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Retour à mes listes
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Colonne de gauche: Détails et actions -->
        <div class="col-md-4 mb-4">
            <!-- Carte d'information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">À propos de cette liste</h5>
                </div>
                <div class="card-body">
                    @if($giftList->description)
                        <p>{{ $giftList->description }}</p>
                    @else
                        <p class="text-muted">Aucune description fournie.</p>
                    @endif

                    <hr>

                    <!-- État de la liste -->
                    <p>
                        <i class="fas fa-toggle-on me-2"></i>
                        État: 
                        <span class="badge {{ $giftList->active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $giftList->active ? 'Active' : 'Inactive' }}
                        </span>
                    </p>

                    <!-- Lien de partage -->
                    <p class="mb-2"><i class="fas fa-share-alt me-2"></i> Lien de partage:</p>
                    <div class="input-group mb-3">
                        <input type="text" id="shareLink" class="form-control form-control-sm"
                               value="{{ route('gift-lists.shared', $giftList->code_partage) }}" readonly>
                        <button class="btn btn-outline-secondary btn-sm copy-btn" type="button" data-clipboard-target="#shareLink">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>

                    <!-- Statistiques -->
                    <div class="d-flex justify-content-between text-muted small mt-3">
                        <span><i class="fas fa-gift me-1"></i> {{ $giftList->items->count() }} articles</span>
                        <span><i class="fas fa-check-circle me-1"></i> {{ $giftList->items->where('reserve', true)->count() }} réservés</span>
                    </div>
                </div>
            </div>

            <!-- Ajouter un livre -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Ajouter un livre</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('client.gift-lists.add-book', $giftList) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="book_id" class="form-label">Livre</label>
                            <select class="form-select @error('book_id') is-invalid @enderror" id="book_id" name="book_id" required>
                                <option value="">Sélectionner un livre</option>
                                @foreach(App\Models\Book::orderBy('titre')->get() as $book)
                                    <option value="{{ $book->id }}">{{ $book->titre }} ({{ number_format($book->prix, 2) }} €)</option>
                                @endforeach
                            </select>
                            @error('book_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="quantite" class="form-label">Quantité</label>
                            <input type="number" class="form-control @error('quantite') is-invalid @enderror" 
                                   id="quantite" name="quantite" min="1" value="1" required>
                            @error('quantite')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-plus-circle me-1"></i> Ajouter à la liste
                        </button>
                    </form>
                </div>
            </div>

            <!-- Partager la liste -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Inviter des amis</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('client.gift-lists.invite', $giftList) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="emails" class="form-label">Emails (séparés par des virgules)</label>
                            <textarea class="form-control @error('emails') is-invalid @enderror" 
                                      id="emails" name="emails" rows="2" required></textarea>
                            @error('emails')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message (optionnel)</label>
                            <textarea class="form-control @error('message') is-invalid @enderror" 
                                      id="message" name="message" rows="3"></textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-paper-plane me-1"></i> Envoyer les invitations
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Colonne de droite: Livres dans la liste -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Livres dans la liste</h5>
                    @if($giftList->items->count() > 0)
                        <span class="badge bg-primary">{{ $giftList->items->count() }} articles</span>
                    @endif
                </div>

                @if($giftList->items->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($giftList->items as $item)
                            <div class="list-group-item {{ $item->reserve ? 'list-group-item-success' : '' }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        @if($item->reserve)
                                            <div class="me-3">
                                                <span class="badge bg-success px-2 py-2">
                                                    <i class="fas fa-check-circle fa-lg"></i>
                                                </span>
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-1">{{ $item->book->titre }}</h6>
                                            <p class="text-muted mb-0">
                                                <small>{{ $item->quantite }} × {{ number_format($item->book->prix, 2) }} €</small>
                                            </p>
                                        </div>
                                    </div>
                                    <div>
                                        @if($item->reserve)
                                            <span class="badge bg-success me-2">Réservé</span>
                                        @endif
                                        <form action="{{ route('client.gift-lists.remove-book', [$giftList, $item->id]) }}" 
                                              method="POST" class="d-inline" 
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir retirer ce livre?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                @if($item->reserve && $item->reserver)
                                    <p class="text-muted mt-2 mb-0 small">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Réservé par {{ $item->reserver->name }}
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="card-body text-center py-5">
                        <i class="fas fa-gift fa-3x text-muted mb-3"></i>
                        <h5>Aucun livre dans cette liste</h5>
                        <p class="text-muted">Ajoutez des livres en utilisant le formulaire à gauche.</p>
                    </div>
                @endif
            </div>

            <!-- Invitations envoyées -->
            @if($giftList->invitations->count() > 0)
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Invitations envoyées</h5>
                    </div>
                    <ul class="list-group list-group-flush">
                        @foreach($giftList->invitations as $invitation)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-envelope me-2 text-muted"></i>
                                    {{ $invitation->email }}
                                </div>
                                <div>
                                    <small class="text-muted">
                                        Envoyée le {{ $invitation->sent_at ? $invitation->sent_at->format('d/m/Y H:i') : 'N/A' }}
                                    </small>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <!-- Bouton de suppression en bas -->
    <div class="text-end mt-4">
        <form action="{{ route('client.gift-lists.destroy', $giftList) }}" method="POST" 
              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette liste de cadeaux? Cette action est irréversible.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash me-1"></i> Supprimer cette liste
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var clipboard = new ClipboardJS('.copy-btn');
        
        clipboard.on('success', function(e) {
            e.trigger.innerHTML = '<i class="fas fa-check"></i>';
            setTimeout(function() {
                e.trigger.innerHTML = '<i class="fas fa-copy"></i>';
            }, 2000);
            e.clearSelection();
        });
    });
</script>
@endpush
