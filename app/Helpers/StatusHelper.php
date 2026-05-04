<?php

namespace App\Helpers;

class StatusHelper
{
    /**
     * Translate status to Vietnamese with icon
     */
    public static function getStatusBadge($status, $short = false)
    {
        $statuses = [
            'available' => ['text' => 'Sẵn Sàng', 'icon' => '✓', 'class' => 'success'],
            'borrowed' => ['text' => 'Đang Mượn', 'icon' => '✓', 'class' => 'info'],
            'pending' => ['text' => 'Chờ Duyệt', 'icon' => '⏳', 'class' => 'warning'],
            'approved' => ['text' => 'Đã Duyệt', 'icon' => '✓', 'class' => 'success'],
            'maintenance' => ['text' => 'Bảo Trì', 'icon' => '⚙️', 'class' => 'secondary'],
            'damaged' => ['text' => 'Hỏng', 'icon' => '✗', 'class' => 'danger'],
            'lost' => ['text' => 'Mất', 'icon' => '✗', 'class' => 'danger'],
            'returned' => ['text' => 'Đã Trả', 'icon' => '✓', 'class' => 'success'],
            'rejected' => ['text' => 'Từ Chối', 'icon' => '✗', 'class' => 'danger'],
            'renewal_requested' => ['text' => 'Yêu Cầu Gia Hạn', 'icon' => '↻', 'class' => 'info'],
            'return_requested' => ['text' => 'Yêu Cầu Trả', 'icon' => '↩', 'class' => 'warning'],
            'cancelled' => ['text' => 'Đã Hủy', 'icon' => '⊘', 'class' => 'secondary'],
            'open' => ['text' => 'Chưa Giải Quyết', 'icon' => '⚠️', 'class' => 'warning'],
            'in-progress' => ['text' => 'Đang Xử Lý', 'icon' => '⏳', 'class' => 'warning'],
            'resolved' => ['text' => 'Đã Giải Quyết', 'icon' => '✓', 'class' => 'success'],
            'scheduled' => ['text' => 'Lên Lịch', 'icon' => '📅', 'class' => 'info'],
            'completed' => ['text' => 'Hoàn Thành', 'icon' => '✓', 'class' => 'success'],
        ];

        if (!isset($statuses[$status])) {
            return ucfirst(str_replace('_', ' ', $status));
        }

        $data = $statuses[$status];
        return $short ? $data['text'] : "{$data['icon']} {$data['text']}";
    }

    /**
     * Get status badge color class
     */
    public static function getStatusColor($status)
    {
        $statuses = [
            'available' => 'success',
            'borrowed' => 'info',
            'pending' => 'warning',
            'approved' => 'success',
            'maintenance' => 'secondary',
            'damaged' => 'danger',
            'lost' => 'danger',
            'returned' => 'success',
            'rejected' => 'danger',
            'renewal_requested' => 'info',
            'return_requested' => 'warning',
            'cancelled' => 'secondary',
            'open' => 'warning',
            'in-progress' => 'warning',
            'resolved' => 'success',
            'scheduled' => 'info',
            'completed' => 'success',
        ];

        return $statuses[$status] ?? 'secondary';
    }

    /**
     * Get all available statuses for equipment
     */
    public static function getEquipmentStatuses()
    {
        return [
            'available' => 'Sẵn Sàng',
            'borrowed' => 'Đang Mượn',
            'maintenance' => 'Bảo Trì',
            'damaged' => 'Hỏng',
            'lost' => 'Mất',
        ];
    }

    /**
     * Get all available statuses for borrow requests
     */
    public static function getBorrowStatuses()
    {
        return [
            'pending' => 'Chờ Duyệt',
            'approved' => 'Đã Duyệt',
            'borrowed' => 'Đang Mượn',
            'renewal_requested' => 'Yêu Cầu Gia Hạn',
            'return_requested' => 'Yêu Cầu Trả',
            'returned' => 'Đã Trả',
            'rejected' => 'Từ Chối',
            'cancelled' => 'Đã Hủy',
        ];
    }

    /**
     * Get all available statuses for incidents
     */
    public static function getIncidentStatuses()
    {
        return [
            'open' => 'Chưa Giải Quyết',
            'in-progress' => 'Đang Xử Lý',
            'resolved' => 'Đã Giải Quyết',
        ];
    }

    /**
     * Get all available statuses for maintenance
     */
    public static function getMaintenanceStatuses()
    {
        return [
            'scheduled' => 'Lên Lịch',
            'in-progress' => 'Đang Làm',
            'completed' => 'Hoàn Thành',
        ];
    }

    /**
     * Translate severity level to Vietnamese
     */
    public static function getSeverityBadge($severity, $short = false)
    {
        $severities = [
            'low' => ['text' => 'Thấp', 'icon' => '●', 'class' => 'info'],
            'medium' => ['text' => 'Trung Bình', 'icon' => '●', 'class' => 'warning'],
            'high' => ['text' => 'Cao', 'icon' => '●', 'class' => 'danger'],
        ];

        if (!isset($severities[$severity])) {
            return ucfirst($severity);
        }

        $data = $severities[$severity];
        return $short ? $data['text'] : "{$data['icon']} {$data['text']}";
    }

    /**
     * Get severity badge color
     */
    public static function getSeverityColor($severity)
    {
        $severities = [
            'low' => 'info',
            'medium' => 'warning',
            'high' => 'danger',
        ];

        return $severities[$severity] ?? 'secondary';
    }

    /**
     * Translate incident type to Vietnamese
     */
    public static function getIncidentTypeBadge($type, $short = false)
    {
        $types = [
            'damaged' => ['text' => 'Hư Hại', 'icon' => '🔨'],
            'malfunction' => ['text' => 'Hỏng Hóc', 'icon' => '⚙️'],
            'lost' => ['text' => 'Mất Tích', 'icon' => '🔍'],
            'theft' => ['text' => 'Bị Trộm', 'icon' => '🚨'],
            'other' => ['text' => 'Khác', 'icon' => '❓'],
        ];

        if (!isset($types[$type])) {
            return ucfirst(str_replace('_', ' ', $type));
        }

        $data = $types[$type];
        return $short ? $data['text'] : "{$data['icon']} {$data['text']}";
    }

    /**
     * Get all incident types
     */
    public static function getIncidentTypes()
    {
        return [
            'damaged' => 'Hư Hại',
            'malfunction' => 'Hỏng Hóc',
            'lost' => 'Mất Tích',
            'theft' => 'Bị Trộm',
            'other' => 'Khác',
        ];
    }
}
