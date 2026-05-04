@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="row mb-3">
    <div class="col-sm-6">
        <h3>Báo Cáo Phí Phạt</h3>
    </div>
</div>

<!-- Summary Small Boxes -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-danger">
            <div class="inner">
                <h3>{{ number_format($totalPenalties ?? 0, 0) }}</h3>
                <p>Tổng Phí (đ)</p>
            </div>
            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M10.464 8.746c.422-.429.753-1.084.883-1.85a.75.75 0 00-.925-.75h-.00l-.383.183.13-.694a.75.75 0 00-.747-.825h-.008a.75.75 0 00-.747.825l.13.694-.383-.183h-.003a.75.75 0 00-.925.75c.13.766.46 1.42.883 1.85l1.02 1.04a2.25 2.25 0 003.168 0l1.02-1.04z"></path>
            </svg>
            <a href="#" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                Xem chi tiết <i class="bi bi-chevron-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-success">
            <div class="inner">
                <h3>{{ number_format($paidPenalties ?? 0, 0) }}</h3>
                <p>Đã Thanh Toán (đ)</p>
            </div>
            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path fill-rule="evenodd" d="M8.603 3.799A4.49 4.49 0 0112.582 1c2.291 0 4.436 1.086 5.84 2.887.063.063.11.147.172.234A3.5 3.5 0 0013.94 4H12a3.5 3.5 0 00-2.878 1.456c-.161-.035-.309-.08-.463-.127A4.484 4.484 0 008.603 3.8zM18.89 12a4.5 4.5 0 01-4.786 4.471 3.5 3.5 0 01.947-2.746c1.113 0 2.333-.857 2.333-2.333V12c0-.52.211-.999.567-1.342a4.499 4.499 0 015.942 4.342z" clip-rule="evenodd"></path>
            </svg>
            <a href="#" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                Xem chi tiết <i class="bi bi-chevron-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-warning">
            <div class="inner">
                <h3>{{ number_format($unpaidPenalties ?? 0, 0) }}</h3>
                <p>Chưa Thanh Toán (đ)</p>
            </div>
            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path fill-rule="evenodd" d="M6.75 2.25A.75.75 0 017.5 3v1.745a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75z" clip-rule="evenodd"></path>
            </svg>
            <a href="#" class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover">
                Xem chi tiết <i class="bi bi-chevron-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-info">
            <div class="inner">
                <h3>{{ $totalPenalties > 0 ? round(($paidPenalties / $totalPenalties) * 100, 1) : 0 }}%</h3>
                <p>Tỷ Lệ Thanh Toán</p>
            </div>
            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path fill-rule="evenodd" d="M15.22 6.268a.75.75 0 01.968.432l5.942 17.826H21a.75.75 0 01-.75.75H3.75a.75.75 0 01-.75-.75h-.188l5.941-17.826a.75.75 0 01.432-.968c.59-.195 1.201.29 1.268.868l.307 2.293h2.826l.307-2.293c.068-.578.678-1.063 1.268-.868z" clip-rule="evenodd"></path>
            </svg>
            <a href="#" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                Xem chi tiết <i class="bi bi-chevron-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Penalties by Type Card -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Phí Phạt Theo Loại</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Loại Phí</th>
                                <th class="text-center">Số Lần</th>
                                <th class="text-end">Tổng Tiền (đ)</th>
                                <th class="text-end">Trung Bình (đ)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($penaltiesByType as $penalty)
                                <tr>
                                    <td><strong>{{ ucfirst(str_replace('_', ' ', $penalty->penalty_type)) }}</strong></td>
                                    <td class="text-center"><span class="badge text-bg-warning">{{ $penalty->count }}</span></td>
                                    <td class="text-end">{{ number_format($penalty->total ?? 0, 0) }}</td>
                                    <td class="text-end">{{ number_format(($penalty->total ?? 0) / $penalty->count, 0) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top Penalized Users Card -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Top 10 Người Bị Phạt Nhiều Nhất</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Người Dùng</th>
                                <th class="text-center">Số Lần Phạt</th>
                                <th class="text-end">Tổng Tiền (đ)</th>
                                <th class="text-end">Trung Bình (đ)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topPenalizedUsers as $user)
                                <tr>
                                    <td><strong>{{ $loop->iteration }}</strong></td>
                                    <td>{{ $user->user?->name }}</td>
                                    <td class="text-center">
                                        <span class="badge text-bg-danger">{{ $user->count }}</span>
                                    </td>
                                    <td class="text-end">{{ number_format($user->total ?? 0, 0) }}</td>
                                    <td class="text-end">{{ number_format(($user->total ?? 0) / $user->count, 0) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
