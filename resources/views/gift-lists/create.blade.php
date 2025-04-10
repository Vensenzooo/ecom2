@extends('layouts.client')

@section('title', 'Créer une liste de cadeaux')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Créer une liste de cadeaux</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('client.gift-lists.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Retour aux listes
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('client.gift-lists.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="titre" class="form-label">Titre <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('titre') is-invalid @enderror" 
                           id="titre" name="titre" value="{{ old('titre') }}" required>
                    @error('titre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="4">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="date_evenement" class="form-label">Date de l'événement</label>
                    <input type="date" class="form-control @error('date_evenement') is-invalid @enderror" 
                           id="date_evenement" name="date_evenement" value="{{ old('date_evenement') }}">
                    @error('date_evenement')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Créer la liste
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
