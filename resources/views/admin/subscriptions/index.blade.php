@extends('admin.layouts.app')

@section('title', 'Kullanıcı Yönetimi - Admin Panel')
@section('page-title', 'Kullanıcılar & Abonelikler')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="bi bi-people me-2"></i>Kullanıcı Listesi
                    <span class="badge bg-primary ms-2">{{ $users->total() }} Kullanıcı</span>
                </div>
            </div>

            <div class="card-body">
                <!-- Filtreleme Formu -->
                <form method="GET" action="{{ route('admin.subscriptions.index') }}" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text"
                                       name="email"
                                       class="form-control"
                                       placeholder="Email ile ara..."
                                       value="{{ request('email') }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <select name="subscription_status" class="form-select">
                                <option value="">Tüm Kullanıcılar</option>
                                <option value="1" {{ request('subscription_status') === '1' ? 'selected' : '' }}>
                                    Sadece Aboneler
                                </option>
                                <option value="0" {{ request('subscription_status') === '0' ? 'selected' : '' }}>
                                    Standart Kullanıcılar
                                </option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <div class="btn-group w-100" role="group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-funnel me-1"></i>Filtrele
                                </button>
                                <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Kullanıcı Tablosu -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th style="width: 8%;">ID</th>
                                <th style="width: 30%;">Kullanıcı</th>
                                <th style="width: 35%;">Email</th>
                                <th style="width: 20%;">Abonelik Durumu</th>
                                <th style="width: 7%;" class="text-center">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td class="fw-bold">#{{ $user->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar me-2" style="width: 36px; height: 36px; font-size: 14px;">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $user->name }}</div>
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar3 me-1"></i>
                                                    {{ $user->created_at->format('d.m.Y') }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <i class="bi bi-envelope me-1 text-muted"></i>
                                        {{ $user->email }}
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input subscription-toggle"
                                                   type="checkbox"
                                                   role="switch"
                                                   data-user-id="{{ $user->id }}"
                                                   data-user-name="{{ $user->name }}"
                                                   {{ $user->is_subscribed ? 'checked' : '' }}
                                                   style="cursor: pointer; width: 48px; height: 24px;">
                                            <label class="form-check-label ms-2">
                                                @if($user->is_subscribed)
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle me-1"></i>Abone
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">Standart</span>
                                                @endif
                                            </label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-info"
                                                data-bs-toggle="tooltip"
                                                title="Detaylar">
                                            <i class="bi bi-info-circle"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <i class="bi bi-inbox" style="font-size: 48px; color: #ccc;"></i>
                                        <p class="text-muted mt-3 mb-0">Kullanıcı bulunamadı</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($users->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            {{ $users->firstItem() }}-{{ $users->lastItem() }} arası gösteriliyor (Toplam: {{ $users->total() }})
                        </div>
                        <div>
                            {{ $users->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Onay Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-question-circle text-warning me-2"></i>
                    Abonelik Durumunu Değiştir
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="confirmMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <button type="button" class="btn btn-primary" id="confirmButton">Onayla</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .table th {
        font-weight: 600;
        color: #6c757d;
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table tbody tr {
        transition: all 0.2s;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
        transform: scale(1.01);
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .form-switch .form-check-input {
        cursor: pointer;
    }

    .form-switch .form-check-input:checked {
        background-color: #28a745;
        border-color: #28a745;
    }

    .input-group-text {
        background-color: #f8f9fa;
        border-right: none;
    }

    .input-group .form-control {
        border-left: none;
    }

    .input-group .form-control:focus {
        border-color: #ced4da;
        box-shadow: none;
    }

    .badge {
        padding: 0.4em 0.65em;
        font-weight: 500;
    }

    .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    }

    .modal-header {
        border-bottom: 1px solid #e9ecef;
        padding: 20px;
    }

    .modal-body {
        padding: 25px;
    }

    .modal-footer {
        border-top: 1px solid #e9ecef;
        padding: 15px 20px;
    }

    .user-avatar {
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
    }
</style>
@endpush

@push('scripts')
<script>
    // CSRF Token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    // Tooltip'leri aktifleştir
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Modal
    const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
    let currentUserId = null;
    let currentStatus = null;
    let currentToggle = null;

    // Toggle'lara event listener ekle
    document.querySelectorAll('.subscription-toggle').forEach(toggle => {
        toggle.addEventListener('change', function(e) {
            e.preventDefault();

            currentUserId = this.dataset.userId;
            currentStatus = this.checked;
            currentToggle = this;

            const userName = this.dataset.userName;
            const message = currentStatus
                ? `<strong>${userName}</strong> kullanıcısını <span class="text-success fw-bold">ABONE</span> yapmak istediğinize emin misiniz?`
                : `<strong>${userName}</strong> kullanıcısının aboneliğini <span class="text-danger fw-bold">KALDIRMAK</span> istediğinize emin misiniz?`;

            document.getElementById('confirmMessage').innerHTML = message;
            confirmModal.show();
        });
    });

    // Onay butonuna tıklandığında
    document.getElementById('confirmButton').addEventListener('click', async function() {
        this.disabled = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>İşleniyor...';

        try {
            const response = await fetch(`/admin/subscriptions/users/${currentUserId}/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    is_subscribed: currentStatus
                })
            });

            const data = await response.json();

            if (response.ok && data.success) {
                // Başarılı
                confirmModal.hide();

                // Başarı mesajı göster
                showSuccessMessage(data.message);

                // Sayfayı yenile
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                // Hata
                showErrorMessage(data.message || 'Bir hata oluştu!');
                currentToggle.checked = !currentStatus;
            }
        } catch (error) {
            console.error('Error:', error);
            showErrorMessage('Bir hata oluştu!');
            currentToggle.checked = !currentStatus;
        } finally {
            this.disabled = false;
            this.innerHTML = 'Onayla';
        }
    });

    // Başarı mesajı göster
    function showSuccessMessage(message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-success alert-dismissible fade show';
        alertDiv.innerHTML = `
            <i class="bi bi-check-circle me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.querySelector('.content-area').insertBefore(alertDiv, document.querySelector('.content-area').firstChild);
    }

    // Hata mesajı göster
    function showErrorMessage(message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-danger alert-dismissible fade show';
        alertDiv.innerHTML = `
            <i class="bi bi-exclamation-triangle me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.querySelector('.content-area').insertBefore(alertDiv, document.querySelector('.content-area').firstChild);
    }

    // Modal kapandığında toggle'ı eski haline getir
    document.getElementById('confirmModal').addEventListener('hidden.bs.modal', function() {
        if (currentToggle) {
            currentToggle.checked = !currentStatus;
        }
    });
</script>
@endpush
