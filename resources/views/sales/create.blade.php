@extends('layouts.app')

@section('title', 'Enregistrer une Vente')

@section('content')
<div class="row my-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Enregistrer une Vente</h1>
            <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Retour
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('sales.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="book_id" class="form-label">Livre</label>
                        <select class="form-select @error('book_id') is-invalid @enderror" id="book_id" name="book_id" required>
                            <option value="">Sélectionner un livre</option>
                            @foreach($books as $book)
                                <option value="{{ $book->id }}" data-price="{{ $book->prix }}" data-stock="{{ $book->stock }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                                    {{ $book->titre }} (Stock: {{ $book->stock }}) - {{ number_format($book->prix, 2) }} €
                                </option>
                            @endforeach
                        </select>
                        @error('book_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="quantité" class="form-label">Quantité</label>
                                <input type="number" min="1" class="form-control @error('quantité') is-invalid @enderror" id="quantité" name="quantité" value="{{ old('quantité', 1) }}" required>
                                <div id="stock-warning" class="form-text text-danger d-none">Quantité supérieure au stock disponible!</div>
                                @error('quantité')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="prix_unitaire" class="form-label">Prix unitaire (€)</label>
                                <input type="number" step="0.01" min="0" class="form-control @error('prix_unitaire') is-invalid @enderror" id="prix_unitaire" name="prix_unitaire" value="{{ old('prix_unitaire') }}" required>
                                @error('prix_unitaire')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="date_vente" class="form-label">Date de vente</label>
                        <input type="date" class="form-control @error('date_vente') is-invalid @enderror" id="date_vente" name="date_vente" value="{{ old('date_vente', date('Y-m-d')) }}" required>
                        @error('date_vente')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info">
                        <div class="fw-bold">Total: <span id="total-price">0.00</span> €</div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Récupérer les éléments du formulaire
        const bookSelect = document.getElementById('book_id');
        const quantityInput = document.getElementById('quantité');
        const priceInput = document.getElementById('prix_unitaire');
        const totalPriceSpan = document.getElementById('total-price');
        const stockWarning = document.getElementById('stock-warning');

        // Fonction pour mettre à jour le prix total
        function updateTotalPrice() {
            const quantity = parseFloat(quantityInput.value) || 0;
            const price = parseFloat(priceInput.value) || 0;
            const total = quantity * price;
            totalPriceSpan.textContent = total.toFixed(2);
        }

        // Fonction pour vérifier le stock disponible
        function checkStock() {
            const selectedOption = bookSelect.options[bookSelect.selectedIndex];
            if (selectedOption.value) {
                const stock = parseInt(selectedOption.dataset.stock);
                const quantity = parseInt(quantityInput.value) || 0;
                
                if (quantity > stock) {
                    stockWarning.classList.remove('d-none');
                } else {
                    stockWarning.classList.add('d-none');
                }
            }
        }

        // Mettre à jour le prix unitaire lors de la sélection d'un livre
        bookSelect.addEventListener('change', function() {
            const selectedOption = bookSelect.options[bookSelect.selectedIndex];
            if (selectedOption.value) {
                priceInput.value = selectedOption.dataset.price;
                updateTotalPrice();
                checkStock();
            } else {
                priceInput.value = '';
                updateTotalPrice();
            }
        });

        // Mettre à jour le prix total lorsque la quantité change
        quantityInput.addEventListener('input', function() {
            updateTotalPrice();
            checkStock();
        });

        // Mettre à jour le prix total lorsque le prix unitaire change
        priceInput.addEventListener('input', updateTotalPrice);

        // Initialiser les valeurs
        if (bookSelect.value) {
            const selectedOption = bookSelect.options[bookSelect.selectedIndex];
            if (!priceInput.value && selectedOption.dataset.price) {
                priceInput.value = selectedOption.dataset.price;
            }
            updateTotalPrice();
            checkStock();
        }
    });
</script>
@endsection
@endsection
