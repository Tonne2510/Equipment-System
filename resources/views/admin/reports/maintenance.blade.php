@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="row mb-3">
    <div class="col-sm-6">
        <h3>Báo Cáo Bảo Trì</h3>
    </div>
</div>

<!-- Statistics Small Boxes -->
<div class="row">
    <div class="col-lg-4 col-6">
        <div class="small-box text-bg-primary">
            <div class="inner">
                <h3>{{ number_format($totalCost ?? 0, 0) }}</h3>
                <p>Tổng Chi Phí (đ)</p>
            </div>
            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M10.464 8.746c.422-.429.753-1.084.883-1.85a.75.75 0 00-.925-.75h-.00l-.383.183.13-.694a.75.75 0 00-.747-.825h-.008a.75.75 0 00-.747.825l.13.694-.383-.183h-.003a.75.75 0 00-.925.75c.13.766.46 1.42.883 1.85l1.02 1.04a2.25 2.25 0 003.168 0l1.02-1.04z"></path>
            </svg>
            <a href="#" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                Xem chi tiết <i class="bi bi-chevron-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box text-bg-success">
            <div class="inner">
                <h3>{{ $maintenanceRecords->total() }}</h3>
                <p>Số Lần Bảo Trì</p>
            </div>
            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path fill-rule="evenodd" d="M11.078 2.25c-.929 0-1.734.709-1.734 1.585v12.582c0 .876.805 1.585 1.734 1.585h1.844c.929 0 1.734-.709 1.734-1.585V3.835c0-.876-.805-1.585-1.734-1.585h-1.844zm-7.171 12.324c-.929 0-1.734.709-1.734 1.585v2.328c0 .876.805 1.585 1.734 1.585h1.844c.929 0 1.734-.709 1.734-1.585v-2.328c0-.876-.805-1.585-1.734-1.585h-1.844zm14.346 0c-.929 0-1.734.709-1.734 1.585v2.328c0 .876.805 1.585 1.734 1.585h1.844c.929 0 1.734-.709 1.734-1.585v-2.328c0-.876-.805-1.585-1.734-1.585h-1.844z" clip-rule="evenodd"></path>
            </svg>
            <a href="#" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                Xem chi tiết <i class="bi bi-chevron-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box text-bg-info">
            <div class="inner">
                <h3>{{ $maintenanceRecords->total() > 0 ? number_format(($totalCost ?? 0) / $maintenanceRecords->total(), 0) : 0 }}</h3>
                <p>Chi Phí Trung Bình (đ)</p>
            </div>
            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M12 2.25a.75.75 0 01.75.75v2.25H12V3a.75.75 0 01-.75-.75z"></path>
            </svg>
            <a href="#" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                Xem chi tiết <i class="bi bi-chevron-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Cost by Type Card -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Chi Phí Bảo Trì Theo Loại</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="maintenance-cost-table">
                        <thead class="table-light">
                            <tr>
                                <th>Loại Bảo Trì</th>
                                <th class="text-center">Số Lần</th>
                                <th class="text-end">Tổng Chi Phí (đ)</th>
                                <th class="text-end">Trung Bình (đ)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($costByType as $cost)
                                <tr>
                                    <td><strong>{{ ucfirst(str_replace('_', ' ', $cost->maintenance_type)) }}</strong></td>
                                    <td class="text-center"><span class="badge text-bg-info">{{ $cost->count }}</span></td>
                                    <td class="text-end">{{ number_format($cost->total ?? 0, 0) }}</td>
                                    <td class="text-end">{{ number_format(($cost->total ?? 0) / $cost->count, 0) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Maintenance Records Card -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Danh Sách Bảo Trì <span class="badge text-bg-secondary">{{ $maintenanceRecords->total() }}</span></h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="maintenance-records-table">
                        <thead class="table-light">
                            <tr>
                                <th>Thiết Bị</th>
                                <th>Loại Bảo Trì</th>
                                <th>Ngày Bắt Đầu</th>
                                <th>Ngày Kết Thúc</th>
                                <th class="text-end">Chi Phí (đ)</th>
                                <th>Trạng Thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($maintenanceRecords as $record)
                                <tr>
                                    <td><strong>{{ $record->equipment?->model?->name }}</strong></td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $record->maintenance_type)) }}</td>
                                    <td>{{ $record->start_date->format('d/m/Y') }}</td>
                                    <td>{{ $record->end_date?->format('d/m/Y') ?? '-' }}</td>
                                    <td class="text-end">{{ number_format($record->cost ?? 0, 0) }}</td>
                                    <td>
                                        @if($record->status === 'completed')
                                            <span class="badge text-bg-success">Hoàn Thành</span>
                                        @elseif($record->status === 'in-progress')
                                            <span class="badge text-bg-warning">Đang Thực Hiện</span>
                                        @else
                                            <span class="badge text-bg-secondary">{{ ucfirst($record->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Không có bảo trì nào</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($maintenanceRecords->count() > 0)
                <div class="card-footer d-flex justify-content-center">
                    {{ $maintenanceRecords->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@section('scripts')
<script>
// Initialize table managers for maintenance report tables
document.addEventListener('DOMContentLoaded', function() {
    new TableManager('maintenance-cost-table');
    new TableManager('maintenance-records-table');
});
</script>
@endsection
@endsection
