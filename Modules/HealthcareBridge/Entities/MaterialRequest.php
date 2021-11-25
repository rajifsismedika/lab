<?php

namespace Modules\HealthcareBridge\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaterialRequest extends Model
{
    use HasFactory;

    protected $table = 'bridge_material_requests';

    protected $fillable = [
        'external_id',
        'requested_at',
        'received_at',
        'healthcare_from_id',
        'healthcare_to_id',
        'department_from_id',
        'department_to_id',
        'wh_from_id',
        'wh_to_id',
        'group',
        'is_direct_expense',
        'approved_by',
        'approved_by_name',
        'approved_at',
        'status',
        'note'
    ];
    
    protected static function newFactory()
    {
        return \Modules\HealthcareBridge\Database\factories\MaterialRequestFactory::new();
    }

    public function items()
    {
        return $this->hasMany(MaterialRequestItem::class, 'external_request_id', 'external_id');
    }

    public function mutations()
    {
        return $this->hasMany(MaterialMutation::class, 'external_request_id', 'external_id');
    }

    public function receives()
    {
        return $this->hasMany(MaterialReceive::class, 'external_request_id', 'external_id');
    }
}
