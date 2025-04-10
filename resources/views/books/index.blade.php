@extends('layouts.app')

@section('title', 'Gestion des Livres')

@section('content')
<div class="row my-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h1>Liste des Livres</h1>
        <a href="{{ route('books.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-1"></i> Ajouter un livre
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Auteur</th>
                        <th>Catégorie</th>
                        <th>Prix</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($books as $book)
                    <tr>
                        <td>{{ $book->id }}</td>
                        <td>{{ $book->titre }}</td>
                        <td>{{ $book->auteur }}</td>
                        <td>{{ $book->category->nom ?? 'N/A' }}</td>
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
                            <a href="{{ route('books.edit', $book) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('books.destroy', $book) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce livre?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $books->links() }}
        </div>
    </div>
</div>
@endsection
