<?php

namespace Modules\HealthcareBridge\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaterialReceive extends Model
{
    use HasFactory;

    protected $table = 'bridge_material_receives';

    protected $fillable = [
        'external_id',
        'external_request_id',
        'external_mutation_id',
        'receive_type',
        'received_at',
        'received_from',
        'healthcare_from_id',
        'healthcare_to_id',
        'department_from_id',
        'department_to_id',
        'wh_from_id',
        'wh_to_id',
        'approved_by',
        'approved_by_name',
        'approved_at',
        'status',
        'note',
    ];
    
    protected static function newFactory()
    {
        return \Modules\HealthcareBridge\Database\factories\MaterialReceiveFactory::new();
    }

    public function request()
    {
        return $this->belongsTo(MaterialRequest::class, 'external_request_id', 'external_id');
    }

    public function mutation()
    {
        return $this->belongsTo(MaterialMutation::class, 'external_mutation_id', 'external_id');
    }

    public function items()
    {
        return $this->hasMany(MaterialReceiveItem::class, 'external_receive_id', 'external_id');
    }
}
