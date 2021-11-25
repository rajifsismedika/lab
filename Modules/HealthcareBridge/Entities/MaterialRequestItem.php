<?php

namespace Modules\HealthcareBridge\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaterialRequestItem extends Model
{
    use HasFactory;

    protected $table = 'bridge_material_request_items';

    protected $fillable = [
        'external_id',
        'external_request_id',
        'external_item_id',
        'qty',
        'received_qty',
        'unit',
        'base_unit',
        'unit_conversion',
        'note',
    ];
    
    protected static function newFactory()
    {
        return \Modules\HealthcareBridge\Database\factories\MaterialRequestItemFactory::new();
    }

    public function request()
    {
        return $this->belongsTo(MaterialRequest::class, 'external_request_id', 'external_id');
    }

    public function mutation_items()
    {
        return $this->hasMany(MaterialMutationItem::class, 'external_request_item_id', 'external_id');
    }
}
