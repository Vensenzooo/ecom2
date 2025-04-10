@extends('layouts.app')

@section('title', 'Détails de la Catégorie')

@section('content')
<div class="row my-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>{{ $category->nom }}</h1>
            <div>
                <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning me-2">
                    <i class="fas fa-edit me-1"></i> Modifier
                </a>
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Retour
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Informations</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Nom :</strong> {{ $category->nom }}</p>
                        <p><strong>Description :</strong></p>
                        <p>{{ $category->description }}</p>
                        <p><strong>Nombre de livres :</strong> {{ $category->books->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>Livres dans cette catégorie</h5>
                    </div>
                    <div class="card-body">
                        @if($category->books->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Titre</th>
                                            <th>Auteur</th>
                                            <th>Prix</th>
                                            <th>Stock</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($category->books as $book)
                                        <tr>
                                            <td>{{ $book->id }}</td>
                                            <td>{{ $book->titre }}</td>
                                            <td>{{ $book->auteur }}</td>
                                            <td>{{ number_format($book->prix, 2) }} €</td>
                                            <td>
                                                @if($book->stock < 10)
                                                    <span class="badge bg-danger">{{ $book->stock }}</span>
                                                @else
                                                    <span class="badge bg-success">{{ $book->stock }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('books.show', $book) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">Aucun livre dans cette catégorie.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <form action="{{ route('categories.destroy', $category) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie? Tous les livres associés seront également supprimés.')">
                    <i class="fas fa-trash me-1"></i> Supprimer cette catégorie
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
