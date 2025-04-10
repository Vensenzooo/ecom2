@extends('layouts.client', ['hideFooter' => true])

@section('title', 'Mon panier')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Mon panier</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('client.catalog') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-1"></i> Continuer mes achats
            </a>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
            @if($cartItems->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Livre</th>
                                <th class="text-center">Prix unitaire</th>
                                <th class="text-center">Quantité</th>
                                <th class="text-end">Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartItems as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <h6 class="mb-0">{{ $item->book->titre }}</h6>
                                                <small class="text-muted">{{ $item->book->auteur }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ number_format($item->book->prix, 2) }} €</td>
                                    <td class="text-center">
                                        <form action="{{ route('client.cart.update', $item) }}" method="POST" class="d-flex align-items-center justify-content-center">
                                            @csrf
                                            @method('PUT')
                                            <select name="quantity" class="form-select form-select-sm w-auto mx-2" onchange="this.form.submit()">
                                                @for($i = 1; $i <= min(10, $item->book->stock); $i++)
                                                    <option value="{{ $i }}" {{ $item->quantity == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </form>
                                    </td>
                                    <td class="text-end">{{ number_format($item->book->prix * $item->quantity, 2) }} €</td>
                                    <td class="text-end">
                                        <form action="{{ route('client.cart.remove', $item) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Total:</td>
                                <td class="text-end fw-bold">{{ number_format($total, 2) }} €</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Code de réduction</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('client.cart.apply-discount') }}" method="POST" class="row g-3 align-items-center">
                            @csrf
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text" name="discount_code" id="discount_code" class="form-control @error('discount_code') is-invalid @enderror" 
                                           placeholder="Entrez votre code de réduction" value="{{ session('discount_code') ?? old('discount_code') }}">
                                    <button class="btn btn-primary" type="submit">Appliquer</button>
                                </div>
                                @error('discount_code')
                                    <div class="invalid-feedback d-block">{{ $errors->first('discount_code') }}</div>
                                @enderror
                            </div>
                            @if(session('discount_code'))
                                <div class="col-md-4">
                                    <div class="bg-light p-2 text-center rounded">
                                        <p class="mb-0"><strong>Code appliqué:</strong> {{ session('discount_code') }}</p>
                                        <p class="mb-0 text-success"><strong>Réduction:</strong> {{ session('discount_percentage') }}%</p>
                                        <a href="{{ route('client.cart.remove-discount') }}" class="btn btn-sm btn-outline-danger mt-1">
                                            <i class="fas fa-times"></i> Supprimer
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </form>
                        
                        @if(!session('discount_code') && Auth::user()->tokens >= 1000)
                            <div class="mt-3">
                                <p class="text-muted">Vous avez {{ Auth::user()->tokens }} étoiles. <a href="{{ route('client.tokens.index') }}">Utilisez-les pour obtenir une réduction!</a></p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <h5>Sous-total:</h5>
                            <h5>{{ number_format($subtotal ?? $total, 2) }} €</h5>
                        </div>
                        
                        @if(session('discount_percentage'))
                            <div class="d-flex justify-content-between text-success mb-2">
                                <h5>Réduction ({{ session('discount_percentage') }}%):</h5>
                                <h5>-{{ number_format(($subtotal ?? $total) * session('discount_percentage') / 100, 2) }} €</h5>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <h4>Total:</h4>
                                <h4>{{ number_format(($subtotal ?? $total) * (1 - session('discount_percentage') / 100), 2) }} €</h4>
                            </div>
                        @else
                            <hr>
                            <div class="d-flex justify-content-between">
                                <h4>Total:</h4>
                                <h4>{{ number_format($total, 2) }} €</h4>
                            </div>
                        @endif
                        
                        <div class="d-grid gap-2 mt-3">
                            <a href="{{ route('client.cart.checkout') }}" class="btn btn-success btn-lg">
                                <i class="fas fa-shopping-cart me-2"></i> Passer la commande
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <form action="{{ route('client.cart.clear') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="fas fa-trash me-1"></i> Vider le panier
                        </button>
                    </form>
                    
                    <a href="{{ route('client.cart.checkout') }}" class="btn btn-primary">
                        <i class="fas fa-shopping-cart me-1"></i> Passer à la caisse
                    </a>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart fa-3x mb-3 text-muted"></i>
                    <h4>Votre panier est vide</h4>
                    <p class="text-muted">Découvrez notre catalogue de livres délicieux.</p>
                    <a href="{{ route('client.catalog') }}" class="btn btn-primary mt-3">Parcourir le catalogue</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
