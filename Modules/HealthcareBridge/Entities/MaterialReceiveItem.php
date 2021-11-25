<?php

namespace Modules\HealthcareBridge\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaterialReceiveItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'external_id',
        'external_receive_id',
        'external_mutation_item_id',
        'external_request_item_id',
        'external_item_id',
        'received_qty',
        'unit',
        'base_unit',
        'unit_conversion',
        'cogs',
        'note',
    ];
    
    protected static function newFactory()
    {
        return \Modules\HealthcareBridge\Database\factories\MaterialReceiveItemFactory::new();
    }

    public function receive()
    {
        return $this->belongsTo(MaterialReceive::class, 'external_receive_id', 'external_id');
    }

    public function mutation_item()
    {
        return $this->belongsTo(MaterialMutationItem::class, 'external_mutation_item_id', 'external_id');
    }

    public function request_item()
    {
        return $this->belongsTo(MaterialRequestItem::class, 'external_request_item_id', 'external_id');
    }
}
