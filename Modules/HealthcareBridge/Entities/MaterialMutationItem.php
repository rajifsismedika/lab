<?php

namespace Modules\HealthcareBridge\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaterialMutationItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'external_id',
        'external_mutation_id',
        'external_request_item_id',
        'external_item_id',
        'qty',
        'received_qty',
        'unit',
        'base_unit',
        'unit_conversion',
        'cogs',
    ];
    
    protected static function newFactory()
    {
        return \Modules\HealthcareBridge\Database\factories\MaterialMutationItemFactory::new();
    }

    public function mutation()
    {
        return $this->belongsTo(MaterialMutation::class, 'external_mutation_id', 'external_id');
    }

    public function request_item()
    {
        return $this->belongsTo(MaterialRequestItem::class, 'external_request_item_id', 'external_id');
    }

    public function receive_items()
    {
        return $this->hasMany(MaterialReceiveitem::class, 'external_mutation_item_id', 'external_id');
    }
}
