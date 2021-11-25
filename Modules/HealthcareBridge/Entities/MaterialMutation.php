<?php

namespace Modules\HealthcareBridge\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaterialMutation extends Model
{
    use HasFactory;

    protected $fillable = [
        'external_id',
        'external_request_id',
        'send_at',
        'received_at',
        'healthcare_from_id',
        'healthcare_to_id',
        'department_from_id',
        'department_to_id',
        'wh_from_id',
        'wh_to_id',
        'group',
        'mutation_type',
        'approved_by',
        'approved_by_name',
        'approved_at',
        'status',
        'note',
    ];
    
    protected static function newFactory()
    {
        return \Modules\HealthcareBridge\Database\factories\MaterialMutationFactory::new();
    }

    public function items()
    {
        return $this->hasMany(MaterialMutationItem::class, 'external_mutation_id', 'external_id');
    }

    public function request()
    {
        return $this->belongsTo(MaterialRequest::class, 'external_request_id', 'external_id');
    }
}
