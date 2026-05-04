@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="row mb-3">
    <div class="col-sm-6">
        <h3>Báo Cáo Phân Tích Mượn</h3>
    </div>
</div>

<!-- Summary Small Boxes -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-primary">
            <div class="inner">
                <h3>{{ $totalBorrows }}</h3>
                <p>Tổng Lần Mượn</p>
            </div>
            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M12 2.25a.75.75 0 01.75.75v2.25H12V3a.75.75 0 01-.75-.75z"></path>
            </svg>
            <a href="{{ route('admin.borrowing.index') }}" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                Xem chi tiết <i class="bi bi-chevron-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-success">
            <div class="inner">
                <h3>{{ round($averageBorrowDuration ?? 0, 1) }}</h3>
                <p>Trung Bình (Ngày)</p>
            </div>
            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M12 2.25a.75.75 0 01.75.75v2.25H12V3a.75.75 0 01-.75-.75z"></path>
            </svg>
            <a href="#" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                Xem chi tiết <i class="bi bi-chevron-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-info">
            <div class="inner">
                <h3>{{ $borrowsByMonth->sum('count') }}</h3>
                <p>Năm Nay (2026)</p>
            </div>
            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M12 2.25a.75.75 0 01.75.75v2.25H12V3a.75.75 0 01-.75-.75z"></path>
            </svg>
            <a href="#" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                Xem chi tiết <i class="bi bi-chevron-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-danger">
            <div class="inner">
                <h3>{{ $overdueBorrows->count() }}</h3>
                <p>Quá Hạn</p>
            </div>
            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path fill-rule="evenodd" d="M2.25 12c0-6.215 5.061-11.25 11.25-11.25 6.215 0 11.25 5.035 11.25 11.25 0 6.214-5.035 11.25-11.25 11.25-6.189 0-11.25-5.036-11.25-11.25zm4.5 0a.75.75 0 100 1.5h10.5a.75.75 0 000-1.5H6.75z" clip-rule="evenodd"></path>
            </svg>
            <a href="#" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                Xem chi tiết <i class="bi bi-chevron-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Monthly Trend Card -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Xu Hướng Mượn Theo Tháng</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tháng</th>
                                @for($i = 1; $i <= 12; $i++)
                                    <th class="text-center">T{{ $i }}</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Số Lần</strong></td>
                                @for($i = 1; $i <= 12; $i++)
                                    <td class="text-center">
                                        <span class="badge text-bg-primary">
                                            {{ $borrowsByMonth->where('month', $i)->first()?->count ?? 0 }}
                                        </span>
                                    </td>
                                @endfor
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Overdue Borrows Card -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    Mượn Quá Hạn 
                    <span class="badge text-bg-danger">{{ $overdueBorrows->count() }}</span>
                </h5>
            </div>
            <div class="card-body p-0">
                @if($overdueBorrows->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Người Mượn</th>
                                    <th>Thiết Bị</th>
                                    <th>Ngày Trả Dự Kiến</th>
                                    <th>Quá Hạn (Ngày)</th>
                                    <th class="text-center">Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($overdueBorrows as $borrow)
                                    <tr>
                                        <td><strong>{{ $borrow->user->name }}</strong></td>
                                        <td>
                                            @foreach($borrow->items as $item)
                                                <span class="badge text-bg-info">{{ $item->equipment_id }}</span>
                                            @endforeach
                                        </td>
                                        <td>{{ $borrow->end_date->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="badge text-bg-danger">
                                                {{ abs(now()->diffInDays($borrow->end_date)) }} ngày
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.borrowing.show', $borrow) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i> Xem
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="card-body">
                        <div class="alert alert-success mb-0">
                            <i class="bi bi-check-circle"></i> Không có mượn nào quá hạn.
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
