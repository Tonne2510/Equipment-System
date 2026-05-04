@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard Quản Lý Thiết Bị</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalDevices }}</h3>
                    <p>Tổng số thiết bị</p>
                </div>
                <div class="icon">
                    <i class="fas fa-laptop"></i>
                </div>
                <a href="{{ route('thiet-bi.index') }}" class="small-box-footer">
                    Xem danh sách <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $availableDevices }}</h3>
                    <p>Thiết bị sẵn sàng</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <a href="{{ route('thiet-bi.index') }}" class="small-box-footer">
                    Xem thiết bị <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $currentLoans }}</h3>
                    <p>Thiết bị đang mượn</p>
                </div>
                <div class="icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <a href="{{ route('muon-thiet-bi.index') }}" class="small-box-footer">
                    Xem mượn <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $overdueLoans }}</h3>
                    <p>Đang quá hạn</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <a href="{{ route('muon-thiet-bi.index') }}" class="small-box-footer">
                    Xem mượn <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $activeDevices }}</h3>
                    <p>Thiết bị hoạt động</p>
                </div>
                <div class="icon">
                    <i class="fas fa-tools"></i>
                </div>
                <a href="{{ route('thiet-bi.index') }}" class="small-box-footer">
                    Xem thiết bị <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalEmployees }}</h3>
                    <p>Tổng nhân viên</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('nhan-vien.index') }}" class="small-box-footer">
                    Xem nhân viên <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-secondary">
                <div class="inner">
                    <h3>{{ $activeEmployees }}</h3>
                    <p>Nhân viên đang hoạt động</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <a href="{{ route('nhan-vien.index') }}" class="small-box-footer">
                    Xem nhân viên <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-dark">
                <div class="inner">
                    <h3>{{ $totalLoans }}</h3>
                    <p>Tổng lượt mượn</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <a href="{{ route('muon-thiet-bi.index') }}" class="small-box-footer">
                    Xem mượn <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">Giao dịch mượn mới nhất</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nhân viên</th>
                                    <th>Thiết bị</th>
                                    <th>Ngày mượn</th>
                                    <th>Ngày trả</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentLoans as $loan)
                                    <tr>
                                        <td>{{ $loan->nhanVien->ten_nhan_vien ?? '---' }}</td>
                                        <td>{{ $loan->thietBi->ten_thiet_bi ?? '---' }}</td>
                                        <td>{{ $loan->ngay_muon }}</td>
                                        <td>{{ $loan->ngay_tra ? $loan->ngay_tra : 'Chưa trả' }}</td>
                                        <td>
                                            @if($loan->status == 1)
                                                <span class="badge bg-warning">Đang mượn</span>
                                            @else
                                                <span class="badge bg-success">Đã trả</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">Chưa có giao dịch mượn nào.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
