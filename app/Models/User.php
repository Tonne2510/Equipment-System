<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role_id', 'employee_id', 'status', 'phone', 'avatar'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relationships
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function borrowRequests()
    {
        return $this->hasMany(BorrowRequest::class);
    }

    public function approvedRequests()
    {
        return $this->hasMany(BorrowRequest::class, 'approved_by');
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    public function incidentReports()
    {
        return $this->hasMany(IncidentReport::class, 'reported_by');
    }

    public function assignedIncidents()
    {
        return $this->hasMany(IncidentReport::class, 'assigned_to');
    }

    public function violationRecords()
    {
        return $this->hasMany(ViolationRecord::class);
    }

    public function penalties()
    {
        return $this->hasMany(Penalty::class);
    }

    /**
     * Authorization helpers
     */
    public function hasRole($role)
    {
        return $this->role && ($this->role->name === $role || $this->role->id === $role);
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function isManager()
    {
        return $this->hasRole('manager');
    }

    public function isEmployee()
    {
        return $this->hasRole('employee');
    }
}
