<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'items', 'total', 'status'];

    protected $casts = [
        'items' => 'array',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Scope to filter orders by status.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStatus($query, $status)
    {
        return $query->when($status, fn ($q) => $q->where('status', $status));
    }
}
