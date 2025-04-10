@extends('layouts.app')

@push('styles')
<style>
    /* Styles personnalisés pour le dashboard */
    .card-dashboard {
        transition: all 0.3s;
        border: none;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }
    .card-dashboard:hover {
        transform: translateY(-4px);
        box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.3);
    }
    .bg-gradient-primary {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    }
    .bg-gradient-success {
        background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
    }
    .bg-gradient-info {
        background: linear-gradient(135deg, #36b9cc 0%, #258391 100%);
    }
    .bg-gradient-warning {
        background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);
    }
    .bg-gradient-danger {
        background: linear-gradient(135deg, #e74a3b 0%, #be2617 100%);
    }
    .text-xs {
        font-size: 0.7rem;
    }
    .border-left-primary, .border-left-success, .border-left-info, .border-left-warning, .border-left-danger {
        border-left: 0.25rem solid;
    }
    .border-left-primary {
        border-left-color: #4e73df !important;
    }
    .border-left-success {
        border-left-color: #1cc88a !important;
    }
    .border-left-info {
        border-left-color: #36b9cc !important;
    }
    .border-left-warning {
        border-left-color: #f6c23e !important;
    }
    .border-left-danger {
        border-left-color: #e74a3b !important;
    }
    .card-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
    }
    .action-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: white;
        font-size: 1.5rem;
        margin-bottom: 10px;
    }
    .action-card {
        text-align: center;
        margin-bottom: 1rem;
    }
    .action-card .card-body {
        padding: 1.25rem;
    }
    .chart-area, .chart-pie {
        position: relative;
        height: 20rem;
        width: 100%;
    }
</style>
@endpush

@section('title', 'Tableau de bord')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-8">
            <h1>Tableau de bord</h1>
        </div>
        <div class="col-4 text-end">
            <span class="badge bg-primary">{{ now()->format('d F Y') }}</span>
        </div>
    </div>

    <!-- Statistiques principales communes à tous les rôles -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-dashboard border-left-primary h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Livres en catalogue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalBooks }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-dashboard border-left-warning h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Stock faible</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $lowStockBooks }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-dashboard border-left-success h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Ventes du mois</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $monthlySales }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-euro-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-dashboard border-left-info h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Commentaires en attente</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingComments }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-dashboard border-left-danger h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Remboursements en attente</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingRefunds }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-undo-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides spécifiques au rôle -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-dashboard">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Actions rapides</h6>
                </div>
                <div class="card-body">
                    <div class="row justify-content-center">
                        @can('is-admin')
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="action-card">
                                <div class="action-icon bg-gradient-primary mx-auto">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h5>Utilisateurs</h5>
                                <a href="{{ route('users.index') }}" class="btn btn-sm btn-primary">Gérer</a>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="action-card">
                                <div class="action-icon bg-gradient-danger mx-auto">
                                    <i class="fas fa-user-tag"></i>
                                </div>
                                <h5>Rôles</h5>
                                <a href="{{ route('roles.index') }}" class="btn btn-sm btn-danger">Gérer</a>
                            </div>
                        </div>
                        @endcan
                        
                        @can('is-manager')
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="action-card">
                                <div class="action-icon bg-gradient-success mx-auto">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                                <h5>Ventes</h5>
                                <a href="{{ route('sales.index') }}" class="btn btn-sm btn-success">Voir</a>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="action-card">
                                <div class="action-icon bg-gradient-warning mx-auto">
                                    <i class="fas fa-plus-circle"></i>
                                </div>
                                <h5>Ajouter livre</h5>
                                <a href="{{ route('books.create') }}" class="btn btn-sm btn-warning">Créer</a>
                            </div>
                        </div>
                        @endcan
                        
                        @can('is-editor')
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="action-card">
                                <div class="action-icon bg-gradient-primary mx-auto">
                                    <i class="fas fa-book"></i>
                                </div>
                                <h5>Livres</h5>
                                <a href="{{ route('books.index') }}" class="btn btn-sm btn-primary">Gérer</a>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="action-card">
                                <div class="action-icon bg-gradient-info mx-auto">
                                    <i class="fas fa-folder"></i>
                                </div>
                                <h5>Catégories</h5>
                                <a href="{{ route('categories.index') }}" class="btn btn-sm btn-info">Gérer</a>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="action-card">
                                <div class="action-icon bg-gradient-warning mx-auto">
                                    <i class="fas fa-comments"></i>
                                </div>
                                <h5>Commentaires</h5>
                                <a href="{{ route('comments.index') }}" class="btn btn-sm btn-warning">Gérer</a>
                            </div>
                        </div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- GRAPHIQUES POUR LES ÉDITEURS -->
    @if($isEditor)
    <div class="row">
        <!-- Graphique des commentaires par statut -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card card-dashboard">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Commentaires par statut</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie">
                        <canvas id="commentsByStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphique des livres par catégorie -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card card-dashboard">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Livres par catégorie</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie">
                        <canvas id="booksByCategoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Graphique des niveaux d'expertise -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card card-dashboard">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Répartition par niveau d'expertise</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie">
                        <canvas id="expertiseLevelChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tableau des livres avec stock faible -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card card-dashboard">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Livres en stock faible</h6>
                    <a href="{{ route('books.index') }}" class="btn btn-sm btn-danger">
                        <i class="fas fa-exclamation-triangle me-1"></i>Gérer
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Livre</th>
                                    <th>Stock actuel</th>
                                    <th>Prix</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($stockAlertData) && $stockAlertData->count() > 0)
                                    @foreach($stockAlertData as $book)
                                    <tr>
                                        <td>{{ $book->titre }}</td>
                                        <td>
                                            <span class="badge {{ $book->stock < 5 ? 'bg-danger' : 'bg-warning' }}">
                                                {{ $book->stock }}
                                            </span>
                                        </td>
                                        <td>{{ number_format($book->prix, 2) }} €</td>
                                        <td>
                                            <a href="{{ route('books.edit', $book) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i> Éditer
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center">Aucun livre en stock faible</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- GRAPHIQUES POUR LES GESTIONNAIRES -->
    @if($isManager)
    <div class="row">
        <!-- Graphique des ventes mensuelles -->
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card card-dashboard">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Ventes mensuelles</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="monthlySalesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphique des livres les plus vendus -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card card-dashboard">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Top 5 des livres</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie">
                        <canvas id="topBooksChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Graphique des ventes par catégorie -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card card-dashboard">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Ventes par catégorie</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie">
                        <canvas id="salesByCategoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tableau des dernières ventes -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card card-dashboard">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Dernières ventes</h6>
                    <a href="{{ route('sales.index') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-list me-1"></i>Toutes les ventes
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Livre</th>
                                    <th>Quantité</th>
                                    <th>Total</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($recentSales) && $recentSales->count() > 0)
                                    @foreach($recentSales as $sale)
                                    <tr>
                                        <td>{{ $sale->book->titre }}</td>
                                        <td>{{ $sale->quantité }}</td>
                                        <td>{{ number_format($sale->prix_unitaire * $sale->quantité, 2) }} €</td>
                                        <td>
                                            @if($sale->date_vente instanceof \Carbon\Carbon)
                                                {{ $sale->date_vente->format('d/m/Y H:i') }}
                                            @else
                                                {{ $sale->date_vente }}
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center">Aucune vente récente</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des remboursements récents -->
    <div class="row">
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card card-dashboard">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Remboursements récents</h6>
                    @can('is-admin')
                    <a href="{{ route('admin.refunds.index') }}" class="btn btn-sm btn-danger">
                        <i class="fas fa-list me-1"></i>Gérer les remboursements
                    </a>
                    @endcan
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Client</th>
                                    <th>Montant</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($recentRefunds) && $recentRefunds->count() > 0)
                                    @foreach($recentRefunds as $refund)
                                    <tr>
                                        <td>{{ $refund->user->name }}</td>
                                        <td>
                                            @if($refund->mode_paiement === 'tokens')
                                                @php
                                                    $details = json_decode($refund->details_paiement, true);
                                                    $tokens = $details['tokens_used'] ?? 0;
                                                @endphp
                                                {{ number_format($tokens) }} tokens
                                            @else
                                                {{ number_format($refund->montant_total, 2) }} €
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $refund->statut === 'refund_requested' ? 'bg-warning' : 'bg-info' }}">
                                                {{ $refund->statut === 'refund_requested' ? 'En attente' : 'Remboursé' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($refund->refund_requested_at)
                                                {{ \Carbon\Carbon::parse($refund->refund_requested_at)->format('d/m/Y') }}
                                            @else
                                                {{ \Carbon\Carbon::parse($refund->created_at)->format('d/m/Y') }}
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center">Aucun remboursement récent</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphique comparatif remboursements/ventes -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card card-dashboard">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Remboursements mensuels</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="refundsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- GRAPHIQUES POUR LES ADMINISTRATEURS -->
    @if($isAdmin)
    <div class="row">
        <!-- Graphique des types d'utilisateurs -->
        <div class="col-xl-4 col-lg-4 mb-4">
            <div class="card card-dashboard">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Types d'utilisateurs</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie">
                        <canvas id="userTypesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Graphique des ventes mensuelles -->
        <div class="col-xl-8 col-lg-8 mb-4">
            <div class="card card-dashboard">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Ventes mensuelles</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="adminMonthlySalesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Commentaires par statut et niveau d'expertise -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card card-dashboard">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Commentaires et expertise</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="chart-pie" style="height: 200px;">
                                <canvas id="commentStatusChart"></canvas>
                            </div>
                            <p class="text-center mt-2">Commentaires par statut</p>
                        </div>
                        <div class="col-md-6">
                            <div class="chart-pie" style="height: 200px;">
                                <canvas id="expertisePieChart"></canvas>
                            </div>
                            <p class="text-center mt-2">Niveaux d'expertise</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tableau des activités récentes -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card card-dashboard">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Activités récentes</h6>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="activityTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="comments-tab" data-bs-toggle="tab" href="#comments" role="tab">
                                <i class="fas fa-comments me-1"></i>Commentaires
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="sales-tab" data-bs-toggle="tab" href="#sales" role="tab">
                                <i class="fas fa-shopping-cart me-1"></i>Ventes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="users-tab" data-bs-toggle="tab" href="#users" role="tab">
                                <i class="fas fa-user-plus me-1"></i>Nouveaux utilisateurs
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content" id="activityTabContent">
                        <div class="tab-pane fade show active" id="comments" role="tabpanel">
                            <div class="table-responsive mt-3">
                                <table class="table">
                                    @if(isset($recentActivitiesData) && count($recentActivitiesData['comments']) > 0)
                                        @foreach($recentActivitiesData['comments'] as $comment)
                                        <tr>
                                            <td>{{ $comment->user->name }}</td>
                                            <td>{{ Str::limit($comment->contenu, 30) }}</td>
                                            <td>
                                                <span class="badge {{ $comment->statut === 'approuvé' ? 'bg-success' : ($comment->statut === 'en attente' ? 'bg-warning' : 'bg-danger') }}">
                                                    {{ $comment->statut }}
                                                </span>
                                            </td>
                                            <td>{{ $comment->created_at->diffForHumans() }}</td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center">Aucun commentaire récent</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="sales" role="tabpanel">
                            <div class="table-responsive mt-3">
                                <table class="table">
                                    @if(isset($recentActivitiesData) && count($recentActivitiesData['sales']) > 0)
                                        @foreach($recentActivitiesData['sales'] as $sale)
                                        <tr>
                                            <td>{{ $sale->book->titre }}</td>
                                            <td>{{ $sale->quantité }}</td>
                                            <td>{{ number_format($sale->prix_unitaire * $sale->quantité, 2) }} €</td>
                                            <td>{{ $sale->date_vente->diffForHumans() }}</td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center">Aucune vente récente</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="users" role="tabpanel">
                            <div class="table-responsive mt-3">
                                <table class="table">
                                    @if(isset($recentActivitiesData) && count($recentActivitiesData['users']) > 0)
                                        @foreach($recentActivitiesData['users'] as $newUser)
                                        <tr>
                                            <td>{{ $newUser->name }}</td>
                                            <td>{{ $newUser->email }}</td>
                                            <td>{{ $newUser->created_at->diffForHumans() }}</td>
                                            <td>
                                                <a href="{{ route('users.show', $newUser) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center">Aucun nouvel utilisateur</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation des cartes statistiques
        document.querySelectorAll('.card-dashboard').forEach(function(card, index) {
            setTimeout(function() {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(function() {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 50);
            }, index * 100);
        });

        // Configuration des couleurs
        const backgroundColors = [
            'rgba(78, 115, 223, 0.7)',
            'rgba(28, 200, 138, 0.7)',
            'rgba(54, 185, 204, 0.7)',
            'rgba(246, 194, 62, 0.7)',
            'rgba(231, 74, 59, 0.7)',
            'rgba(133, 135, 150, 0.7)'
        ];
        
        // Données pour les graphiques
        let monthlySalesData, topBooksData, expertiseLevelData, userTypesData, 
            commentsByStatusData, booksByCategoryData, salesByCategoryData;
        
        try {
            // Debug pour voir ce qui est réellement dans les données
            console.log("Contenu données mensuelles:", document.getElementById('monthly-sales-data')?.textContent || '{}');
            
            monthlySalesData = JSON.parse(document.getElementById('monthly-sales-data')?.textContent || '{}');
            topBooksData = JSON.parse(document.getElementById('top-books-data')?.textContent || '{}');
            expertiseLevelData = JSON.parse(document.getElementById('expertise-level-data')?.textContent || '{}');
            userTypesData = JSON.parse(document.getElementById('user-types-data')?.textContent || '{}');
            commentsByStatusData = JSON.parse(document.getElementById('comments-by-status-data')?.textContent || '{}');
            booksByCategoryData = JSON.parse(document.getElementById('books-by-category-data')?.textContent || '{}');
            salesByCategoryData = JSON.parse(document.getElementById('sales-by-category-data')?.textContent || '{}');
            
            // Debug des données après parsing
            console.log("Données ventes mensuelles après parsing:", monthlySalesData);
            
            // Vérifier la structure des données et fournir des valeurs par défaut si nécessaire
            if (!monthlySalesData.labels || !Array.isArray(monthlySalesData.labels) || monthlySalesData.labels.length === 0 ||
                !monthlySalesData.values || !Array.isArray(monthlySalesData.values) || monthlySalesData.values.length === 0) {
                console.warn("Structure de données incorrecte pour monthlySalesData, utilisation de valeurs par défaut");
                monthlySalesData = {
                    labels: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin'],
                    values: [15, 25, 40, 30, 50, 60]
                };
            }
            
        } catch (e) {
            console.error("Erreur lors de l'initialisation des données:", e);
            // Fournir des données par défaut en cas d'erreur
            monthlySalesData = {
                labels: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin'],
                values: [10, 20, 30, 25, 40, 35]
            };
        }
        
        // Initialisation du graphique des ventes mensuelles - Amélioration de la méthode
        if (document.getElementById('monthlySalesChart')) {
            console.log("Création du graphique des ventes mensuelles avec les données:", monthlySalesData);
            
            const ctx = document.getElementById('monthlySalesChart').getContext('2d');
            
            // Créer le graphique avec une animation différée pour s'assurer que le canvas est bien rendu
            setTimeout(() => {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: monthlySalesData.labels,
                        datasets: [{
                            label: 'Ventes',
                            data: monthlySalesData.values,
                            backgroundColor: 'rgba(78, 115, 223, 0.05)',
                            borderColor: 'rgba(78, 115, 223, 1)',
                            pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                            pointBorderColor: '#fff',
                            pointHoverBackgroundColor: '#fff',
                            pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                            borderWidth: 2,
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                enabled: true
                            }
                        },
                        animation: {
                            duration: 2000,
                            easing: 'easeOutQuart'
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return value + ' €';
                                    }
                                }
                            }
                        }
                    }
                });
                console.log("Graphique des ventes mensuelles initialisé avec succès");
            }, 200);
        } else {
            console.warn("Élément monthlySalesChart non trouvé dans le DOM");
        }
        
        // Graphiques pour les ÉDITEURS
        // Graphique des commentaires par statut
        if (document.getElementById('commentsByStatusChart') && commentsByStatusData?.labels) {
            const ctx = document.getElementById('commentsByStatusChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: commentsByStatusData.labels,
                    datasets: [{
                        data: commentsByStatusData.values,
                        backgroundColor: commentsByStatusData.colors || backgroundColors
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }
        
        // Graphique des livres par catégorie
        if (document.getElementById('booksByCategoryChart') && booksByCategoryData?.labels) {
            const ctx = document.getElementById('booksByCategoryChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: booksByCategoryData.labels,
                    datasets: [{
                        label: 'Nombre de livres',
                        data: booksByCategoryData.values,
                        backgroundColor: backgroundColors[2]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
        
        // Graphique des niveaux d'expertise
        if (document.getElementById('expertiseLevelChart') && expertiseLevelData?.labels) {
            const ctx = document.getElementById('expertiseLevelChart').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: expertiseLevelData.labels,
                    datasets: [{
                        data: expertiseLevelData.values,
                        backgroundColor: backgroundColors.slice(0, 3)
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }
        
        // Graphiques pour les GESTIONNAIRES
        // Graphique des livres les plus vendus
        if (document.getElementById('topBooksChart') && topBooksData?.labels) {
            const ctx = document.getElementById('topBooksChart').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: topBooksData.labels,
                    datasets: [{
                        data: topBooksData.values,
                        backgroundColor: backgroundColors
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }
        
        // Graphique des ventes par catégorie
        if (document.getElementById('salesByCategoryChart') && salesByCategoryData?.labels) {
            const ctx = document.getElementById('salesByCategoryChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: salesByCategoryData.labels,
                    datasets: [{
                        label: 'Nombre de ventes',
                        data: salesByCategoryData.values,
                        backgroundColor: backgroundColors[1]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
        
        // Graphiques pour les ADMINISTRATEURS
        // Graphique des types d'utilisateurs
        if (document.getElementById('userTypesChart') && userTypesData?.labels) {
            const ctx = document.getElementById('userTypesChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: userTypesData.labels,
                    datasets: [{
                        data: userTypesData.values,
                        backgroundColor: backgroundColors.slice(0, 4)
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }
        
        // Graphiques spécifiques pour l'administrateur
        // Graphique des commentaires par statut (ID spécifique pour l'admin)
        if (document.getElementById('commentStatusChart') && commentsByStatusData?.labels) {
            const ctx = document.getElementById('commentStatusChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: commentsByStatusData.labels,
                    datasets: [{
                        data: commentsByStatusData.values,
                        backgroundColor: commentsByStatusData.colors || backgroundColors
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12
                            }
                        }
                    }
                }
            });
        }
        
        // Graphique des niveaux d'expertise (ID spécifique pour l'admin)
        if (document.getElementById('expertisePieChart') && expertiseLevelData?.labels) {
            const ctx = document.getElementById('expertisePieChart').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: expertiseLevelData.labels,
                    datasets: [{
                        data: expertiseLevelData.values,
                        backgroundColor: backgroundColors.slice(0, 3)
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12
                            }
                        }
                    }
                }
            });
        }

        // Vérifier uniquement pour l'administrateur
        if (document.getElementById('adminMonthlySalesChart')) {
            console.log("Initialisation du graphique des ventes mensuelles pour admin");
            const adminCtx = document.getElementById('adminMonthlySalesChart').getContext('2d');
            
            // Utiliser des données de test si les données réelles ne sont pas disponibles
            const adminChartData = monthlySalesData && monthlySalesData.labels ? monthlySalesData : {
                labels: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin'],
                values: [20, 35, 45, 30, 55, 65]
            };
            
            new Chart(adminCtx, {
                type: 'line',
                data: {
                    labels: adminChartData.labels,
                    datasets: [{
                        label: 'Ventes (€)',
                        data: adminChartData.values,
                        backgroundColor: 'rgba(78, 115, 223, 0.05)',
                        borderColor: 'rgba(78, 115, 223, 1)',
                        pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                        borderWidth: 2,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value + ' €';
                                }
                            }
                        }
                    }
                }
            });
            console.log("Graphique admin initialisé avec succès");
        }

        // Graphique des remboursements mensuels
        if (document.getElementById('refundsChart')) {
            try {
                const refundsData = JSON.parse(document.getElementById('refunds-data')?.textContent || '{}');
                
                if (refundsData.labels && refundsData.values) {
                    const ctx = document.getElementById('refundsChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: refundsData.labels,
                            datasets: [{
                                label: 'Remboursements (€)',
                                data: refundsData.values,
                                backgroundColor: 'rgba(231, 74, 59, 0.05)',
                                borderColor: 'rgba(231, 74, 59, 1)',
                                pointBackgroundColor: 'rgba(231, 74, 59, 1)',
                                pointBorderColor: '#fff',
                                borderWidth: 2,
                                tension: 0.3
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return value + ' €';
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            } catch (e) {
                console.error("Erreur lors de l'initialisation du graphique des remboursements:", e);
            }
        }
    });
</script>

<!-- Éléments de données cachés pour les graphiques -->
<div style="display: none">
    @if(isset($monthlySalesData) && is_array($monthlySalesData) && !empty($monthlySalesData))
    <div id="monthly-sales-data">{{ json_encode($monthlySalesData) }}</div>
    @else
    <div id="monthly-sales-data">{{ json_encode(['labels' => ['Janv', 'Févr', 'Mars', 'Avr', 'Mai', 'Juin'], 'values' => [15, 25, 40, 30, 50, 60]]) }}</div>
    @endif
    
    @if(isset($topBooksData))
    <div id="top-books-data">{{ json_encode($topBooksData) }}</div>
    @endif
    
    @if(isset($expertiseLevelData))
    <div id="expertise-level-data">{{ json_encode($expertiseLevelData) }}</div>
    @endif
    
    @if(isset($userTypesData))
    <div id="user-types-data">{{ json_encode($userTypesData) }}</div>
    @endif
    
    @if(isset($commentsByStatusData))
    <div id="comments-by-status-data">{{ json_encode($commentsByStatusData) }}</div>
    @endif
    
    @if(isset($booksByCategoryData))
    <div id="books-by-category-data">{{ json_encode($booksByCategoryData) }}</div>
    @endif
    
    @if(isset($salesByCategoryData))
    <div id="sales-by-category-data">{{ json_encode($salesByCategoryData) }}</div>
    @endif

    @if(isset($refundsData))
    <div id="refunds-data">{{ json_encode($refundsData) }}</div>
    @endif
</div>
@endsection
