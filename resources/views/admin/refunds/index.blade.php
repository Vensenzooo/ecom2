@extends('layouts.app')

@section('title', 'Gestion des remboursements')

@push('styles')
<style>
    .refund-card {
        transition: all 0.3s ease;
    }
    .refund-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15);
    }
    .status-badge {
        font-size: 0.85rem;
        padding: 5px 10px;
        border-radius: 20px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="mb-0">Gestion des remboursements</h1>
            <p class="text-muted">Gérez les demandes de remboursement des clients</p>
        </div>
    </div>
    
    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h5 class="text-warning mb-2"><i class="fas fa-clock me-2"></i>En attente</h5>
                    <h2 class="mb-0">{{ $pendingRefunds->total() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h5 class="text-success mb-2"><i class="fas fa-check me-2"></i>Remboursés</h5>
                    <h2 class="mb-0">{{ $completedRefunds->total() }}</h2>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Onglets pour organiser les remboursements -->
    <ul class="nav nav-tabs mb-4" id="refundTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="pending-tab" data-bs-toggle="tab" href="#pending" role="tab">
                <i class="fas fa-clock me-2"></i>Demandes en attente
                @if($pendingRefunds->total() > 0)
                    <span class="badge bg-danger ms-1">{{ $pendingRefunds->total() }}</span>
                @endif
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="completed-tab" data-bs-toggle="tab" href="#completed" role="tab">
                <i class="fas fa-check-circle me-2"></i>Remboursements traités
            </a>
        </li>
    </ul>
    
    <div class="tab-content" id="refundTabsContent">
        <!-- Demandes en attente -->
        <div class="tab-pane fade show active" id="pending" role="tabpanel">
            @if($pendingRefunds->count() > 0)
                <div class="row">
                    @foreach($pendingRefunds as $order)
                        <div class="col-md-6 mb-4" data-order-id="{{ $order->id }}">
                            <div class="card refund-card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Commande #{{ $order->id }}</h5>
                                    <span class="badge bg-warning status-badge">Remboursement demandé</span>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <strong>Client:</strong> {{ $order->user->name }}
                                    </div>
                                    <div class="mb-3">
                                        <strong>Date de demande:</strong> {{ $order->refund_requested_at->format('d/m/Y H:i') }}
                                    </div>
                                    <div class="mb-3">
                                        <strong>Montant:</strong> {{ number_format($order->montant_total, 2) }} €
                                    </div>
                                    <div class="mb-3">
                                        <strong>Moyen de paiement:</strong>
                                        <span class="badge {{ $order->mode_paiement === 'paypal' ? 'bg-primary' : 'bg-info' }}">
                                            {{ $order->mode_paiement === 'paypal' ? 'PayPal' : 'Tokens' }}
                                        </span>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Raison:</strong>
                                        <p>{{ Str::limit($order->refund_reason, 100) }}</p>
                                    </div>
                                    
                                    <div class="d-flex gap-2 mt-3">
                                        <a href="{{ route('admin.refunds.show', $order) }}" class="btn btn-primary">
                                            <i class="fas fa-eye me-1"></i> Détails
                                        </a>
                                        <form action="{{ route('admin.refunds.approve', $order) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success" onclick="return confirm('Êtes-vous sûr de vouloir approuver ce remboursement?')">
                                                <i class="fas fa-check me-1"></i> Approuver
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $order->id }}">
                                            <i class="fas fa-times me-1"></i> Rejeter
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Modal de rejet -->
                            <div class="modal fade" id="rejectModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Refuser la demande de remboursement</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('admin.refunds.reject', $order) }}" method="POST" id="rejectForm{{ $order->id }}">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="rejection_reason" class="form-label">Raison du refus</label>
                                                    <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" required></textarea>
                                                    <div class="form-text">Cette raison sera communiquée au client.</div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                <button type="submit" class="btn btn-danger">Refuser le remboursement</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $pendingRefunds->links() }}
                </div>
            @else
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                    <h4>Aucune demande de remboursement en attente</h4>
                    <p>Toutes les demandes ont été traitées.</p>
                </div>
            @endif
        </div>
        
        <!-- Remboursements traités -->
        <div class="tab-pane fade" id="completed" role="tabpanel">
            @if($completedRefunds->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Client</th>
                                <th>Date demande</th>
                                <th>Date traitement</th>
                                <th>Montant</th>
                                <th>Paiement</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($completedRefunds as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->user->name }}</td>
                                    <td>{{ $order->refund_requested_at ? $order->refund_requested_at->format('d/m/Y') : 'Non spécifiée' }}</td>
                                    <td>{{ $order->refunded_at ? $order->refunded_at->format('d/m/Y') : 'En attente' }}</td>
                                    <td>{{ number_format($order->montant_total, 2) }} €</td>
                                    <td>
                                        <span class="badge {{ $order->mode_paiement === 'paypal' ? 'bg-primary' : 'bg-info' }}">
                                            {{ $order->mode_paiement === 'paypal' ? 'PayPal' : 'Tokens' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.refunds.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $completedRefunds->links() }}
                </div>
            @else
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                    <h4>Aucun remboursement traité</h4>
                    <p>L'historique des remboursements sera affiché ici.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle refund rejection with AJAX to remove the item from view
        document.querySelectorAll('[id^="rejectForm"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(form);
                const orderId = form.id.replace('rejectForm', '');
                
                // Send AJAX request
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close modal
                        bootstrap.Modal.getInstance(document.getElementById('rejectModal' + orderId)).hide();
                        
                        // Remove card from view with animation
                        const card = document.querySelector(`.col-md-6[data-order-id="${orderId}"]`);
                        if (card) {
                            card.style.transition = 'all 0.5s ease';
                            card.style.opacity = '0';
                            card.style.transform = 'translateY(-20px)';
                            setTimeout(() => {
                                card.remove();
                                // Update counter
                                const counter = document.querySelector('#pending-tab .badge');
                                if (counter) {
                                    let count = parseInt(counter.textContent) - 1;
                                    counter.textContent = count;
                                    if (count === 0) {
                                        // Show empty message if no more pending refunds
                                        document.querySelector('#pending').innerHTML = `
                                            <div class="alert alert-info text-center">
                                                <i class="fas fa-info-circle fa-2x mb-3"></i>
                                                <h4>Aucune demande de remboursement en attente</h4>
                                                <p>Toutes les demandes ont été traitées.</p>
                                            </div>
                                        `;
                                    }
                                }
                            }, 500);
                        }
                        
                        // Show success toast
                        showToast('Succès', 'La demande de remboursement a été rejetée avec succès', 'success');
                    } else {
                        showToast('Erreur', data.message || 'Une erreur est survenue', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Erreur', 'Une erreur est survenue lors du traitement de la demande', 'error');
                });
            });
        });
        
        // Toast notification function
        function showToast(title, message, type) {
            const toastContainer = document.getElementById('toast-container') || createToastContainer();
            const toast = document.createElement('div');
            toast.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0`;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        <strong>${title}</strong>: ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            `;
            
            toastContainer.appendChild(toast);
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
            
            // Remove toast after it's hidden
            toast.addEventListener('hidden.bs.toast', function () {
                toast.remove();
            });
        }
        
        // Create toast container if it doesn't exist
        function createToastContainer() {
            const container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'position-fixed bottom-0 end-0 p-3';
            container.style.zIndex = '1050';
            document.body.appendChild(container);
            return container;
        }
    });
</script>
@endpush
