@extends('admin.layouts.app')

@section('title', 'Dashboard - Admin Panel')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <!-- İstatistik Kartları -->
    <div class="col-md-3 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Toplam Kullanıcı</h6>
                        <h2 class="mb-0">{{ \App\Models\User::count() }}</h2>
                    </div>
                    <div class="text-primary" style="font-size: 40px;">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Abone Kullanıcılar</h6>
                        <h2 class="mb-0">{{ \App\Models\User::where('is_subscribed', true)->count() }}</h2>
                    </div>
                    <div class="text-success" style="font-size: 40px;">
                        <i class="bi bi-person-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Satış İlanları</h6>
                        <h2 class="mb-0">{{ \App\Models\SaleListing::where('status', 'active')->count() }}</h2>
                    </div>
                    <div class="text-info" style="font-size: 40px;">
                        <i class="bi bi-cart"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Tamir İlanları</h6>
                        <h2 class="mb-0">{{ \App\Models\RepairListing::where('status', 'active')->count() }}</h2>
                    </div>
                    <div class="text-warning" style="font-size: 40px;">
                        <i class="bi bi-tools"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hızlı Erişim -->
<div class="row">
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clock-history me-2"></i>Son Kayıt Olan Kullanıcılar
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="px-4 py-3">İsim</th>
                                <th class="py-3">Email</th>
                                <th class="py-3">Durum</th>
                                <th class="py-3">Kayıt Tarihi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(\App\Models\User::latest()->take(5)->get() as $user)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar me-2" style="width: 32px; height: 32px; font-size: 14px;">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            {{ $user->name }}
                                        </div>
                                    </td>
                                    <td class="py-3">{{ $user->email }}</td>
                                    <td class="py-3">
                                        @if($user->is_subscribed)
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>Abone
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">Standart</span>
                                        @endif
                                    </td>
                                    <td class="py-3">{{ $user->created_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        Henüz kullanıcı bulunmuyor
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-lightning me-2"></i>Hızlı İşlemler
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-people me-2"></i>Kullanıcıları Yönet
                    </a>
                    <a href="{{ route('admin.subscriptions.index') }}?subscription_status=1" class="btn btn-outline-success">
                        <i class="bi bi-person-check me-2"></i>Abone Kullanıcılar
                    </a>
                    <a href="{{ route('admin.subscriptions.index') }}?subscription_status=0" class="btn btn-outline-secondary">
                        <i class="bi bi-person-x me-2"></i>Standart Kullanıcılar
                    </a>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <i class="bi bi-graph-up me-2"></i>İstatistikler
            </div>
            <div class="card-body">
                @php
                    $totalUsers = \App\Models\User::count();
                    $subscribedUsers = \App\Models\User::where('is_subscribed', true)->count();
                    $percentage = $totalUsers > 0 ? round(($subscribedUsers / $totalUsers) * 100, 1) : 0;
                @endphp

                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Abonelik Oranı</span>
                        <strong>{{ $percentage }}%</strong>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success"
                             role="progressbar"
                             style="width: {{ $percentage }}%"
                             aria-valuenow="{{ $percentage }}"
                             aria-valuemin="0"
                             aria-valuemax="100">
                        </div>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Bu Ay Kayıtlar</span>
                    <strong>{{ \App\Models\User::whereMonth('created_at', now()->month)->count() }}</strong>
                </div>

                <div class="d-flex justify-content-between">
                    <span class="text-muted">Bugün Kayıtlar</span>
                    <strong>{{ \App\Models\User::whereDate('created_at', today())->count() }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .card-body h2 {
        font-weight: 700;
        color: #333;
    }

    .progress {
        border-radius: 10px;
    }

    .progress-bar {
        border-radius: 10px;
    }

    .table th {
        font-weight: 600;
        color: #6c757d;
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }

    .table tbody tr {
        transition: background-color 0.2s;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>
@endpush
