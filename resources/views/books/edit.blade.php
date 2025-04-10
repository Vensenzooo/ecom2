@extends('layouts.app')

@section('title', 'Modifier un livre')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Modifier le livre</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('books.update', $book->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="titre" class="form-label">Titre</label>
                                    <input type="text" class="form-control @error('titre') is-invalid @enderror" id="titre" name="titre" value="{{ old('titre', $book->titre) }}" required>
                                    @error('titre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="auteur" class="form-label">Auteur</label>
                                    <input type="text" class="form-control @error('auteur') is-invalid @enderror" id="auteur" name="auteur" value="{{ old('auteur', $book->auteur) }}" required>
                                    @error('auteur')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="categorie_id" class="form-label">Catégorie</label>
                                    <select class="form-select @error('categorie_id') is-invalid @enderror" id="categorie_id" name="categorie_id" required>
                                        <option value="">Sélectionner une catégorie</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('categorie_id', $book->categorie_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('categorie_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="niveau_expertise" class="form-label">Niveau d'expertise</label>
                                    <select class="form-select @error('niveau_expertise') is-invalid @enderror" id="niveau_expertise" name="niveau_expertise" required>
                                        <option value="">Sélectionner un niveau</option>
                                        <option value="débutant" {{ old('niveau_expertise', $book->niveau_expertise) == 'débutant' ? 'selected' : '' }}>Débutant</option>
                                        <option value="amateur" {{ old('niveau_expertise', $book->niveau_expertise) == 'amateur' ? 'selected' : '' }}>Amateur</option>
                                        <option value="chef" {{ old('niveau_expertise', $book->niveau_expertise) == 'chef' ? 'selected' : '' }}>Chef</option>
                                    </select>
                                    @error('niveau_expertise')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="prix" class="form-label">Prix (€)</label>
                                    <input type="number" step="0.01" min="0" class="form-control @error('prix') is-invalid @enderror" id="prix" name="prix" value="{{ old('prix', $book->prix) }}" required>
                                    @error('prix')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="stock" class="form-label">Stock</label>
                                    <input type="number" min="0" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', $book->stock) }}" required>
                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" required>{{ old('description', $book->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image_url" class="form-label">URL de l'image</label>
                            <textarea class="form-control @error('image_url') is-invalid @enderror" id="image_url" name="image_url" rows="3">{{ old('image_url', $book->image_url) }}</textarea>
                            <div class="form-text">Collez l'URL complète de l'image. Laissez vide pour utiliser l'image par défaut.</div>
                            @error('image_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Mettre à jour</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Aperçu de l'image</h5>
                </div>
                <div class="card-body text-center">
                    <div class="img-container" style="min-height: 300px; display: flex; align-items: center; justify-content: center;">
                        <img src="{{ $book->image_url }}" alt="{{ $book->titre }}" class="img-fluid mb-3" 
                            style="max-height: 300px; max-width: 100%;"
                            onerror="this.src='https://placehold.co/600x800?text=Image+Non+Disponible'">
                    </div>
                    <p class="text-muted small">Image actuelle: <a href="{{ $book->image_url }}" target="_blank" class="text-break">{{ $book->image_url }}</a></p>
                    <button type="button" id="test-image-btn" class="btn btn-sm btn-outline-secondary">Tester l'URL</button>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fonction pour mettre à jour l'aperçu de l'image
        function updateImagePreview() {
            const imageUrl = document.getElementById('image_url').value || 'https://placehold.co/600x800?text=Livre+de+Cuisine';
            const imgElement = document.querySelector('.img-container img');
            const linkElement = document.querySelector('.img-container + p a');
            
            // Mettre à jour l'image et le lien
            imgElement.src = imageUrl;
            linkElement.href = imageUrl;
            linkElement.textContent = imageUrl;
            
            console.log('Image URL updated to:', imageUrl);
        }

        // Écouteur pour les changements du champ URL de l'image
        document.getElementById('image_url').addEventListener('input', function() {
            updateImagePreview();
        });
        
        // Écouteur pour le bouton de test d'image
        document.getElementById('test-image-btn').addEventListener('click', function() {
            updateImagePreview();
            this.textContent = 'URL testée';
            setTimeout(() => {
                this.textContent = 'Tester l\'URL';
            }, 2000);
        });
    });
</script>
@endsection
@endsection
