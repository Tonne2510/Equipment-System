@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="row mb-3">
    <div class="col-sm-6">
        <h3>Báo Cáo Vi Phạm</h3>
    </div>
</div>

<!-- Statistics Small Boxes -->
<div class="row">
    <div class="col-lg-4 col-6">
        <div class="small-box text-bg-danger">
            <div class="inner">
                <h3>{{ $violations->total() }}</h3>
                <p>Tổng Vi Phạm</p>
            </div>
            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path fill-rule="evenodd" d="M2.25 12c0-6.215 5.061-11.25 11.25-11.25 6.215 0 11.25 5.035 11.25 11.25 0 6.214-5.035 11.25-11.25 11.25-6.189 0-11.25-5.036-11.25-11.25zm4.5 0a.75.75 0 100 1.5h10.5a.75.75 0 000-1.5H6.75z" clip-rule="evenodd"></path>
            </svg>
            <a href="#" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                Xem chi tiết <i class="bi bi-chevron-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box text-bg-warning">
            <div class="inner">
                <h3>{{ $violationStats->count() }}</h3>
                <p>Loại Vi Phạm</p>
            </div>
            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M12 2.25a.75.75 0 01.75.75v2.25H12V3a.75.75 0 01-.75-.75z"></path>
            </svg>
            <a href="#" class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover">
                Xem chi tiết <i class="bi bi-chevron-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box text-bg-info">
            <div class="inner">
                <h3>{{ $topViolators->count() }}</h3>
                <p>Người Vi Phạm</p>
            </div>
            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122z"></path>
            </svg>
            <a href="#" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                Xem chi tiết <i class="bi bi-chevron-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Violation Types Card -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Thống Kê Loại Vi Phạm</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Loại Vi Phạm</th>
                                <th class="text-center">Số Lần</th>
                                <th>Tỷ Lệ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($violationStats as $stat)
                                <tr>
                                    <td><strong>{{ ucfirst(str_replace('_', ' ', $stat->violation_type)) }}</strong></td>
                                    <td class="text-center"><span class="badge text-bg-warning">{{ $stat->count }}</span></td>
                                    <td>
                                        <div class="progress" style="height: 25px;">
                                            <div class="progress-bar bg-warning" style="width: {{ ($stat->count / $violations->total() * 100) }}%">
                                                {{ round($stat->count / $violations->total() * 100, 1) }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top Violators Card -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Top 10 Người Vi Phạm Nhiều Nhất</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Người Dùng</th>
                                <th class="text-end">Số Vi Phạm</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topViolators as $violator)
                                <tr>
                                    <td><strong>{{ $loop->iteration }}</strong></td>
                                    <td>{{ $violator->user?->name }}</td>
                                    <td class="text-end">
                                        <span class="badge text-bg-danger">{{ $violator->violation_count }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- All Violations Card -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Danh Sách Vi Phạm <span class="badge text-bg-danger">{{ $violations->total() }}</span></h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Người Dùng</th>
                                <th>Loại Vi Phạm</th>
                                <th>Ngày Vi Phạm</th>
                                <th>Ghi Chú</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($violations as $violation)
                                <tr>
                                    <td><strong>{{ $violation->user?->name }}</strong></td>
                                    <td>
                                        <span class="badge text-bg-warning">
                                            {{ ucfirst(str_replace('_', ' ', $violation->violation_type)) }}
                                        </span>
                                    </td>
                                    <td>{{ $violation->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $violation->description ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Không có vi phạm nào</td>
                                </tr>
                            @endforelse
                        </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $violations->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
