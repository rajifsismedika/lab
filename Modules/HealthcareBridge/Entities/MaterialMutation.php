<?php

namespace Modules\HealthcareBridge\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\HealthcareBridge\Helper\Helper;

class MaterialMutation extends Model
{
    use HasFactory;

    protected $table = 'bridge_material_mutations';

    protected $fillable = [
        'external_id',
        'external_request_id',
        'send_at',
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

    public function getHisFormatAttribute()
    {
        $data = [
            'MutasiID' => $this->external_id,
            'RequestID' => $this->external_request_id,
            'Tanggal' => $this->send_at,
            'DepartemenID' => $this->department_from_id,
            'KeDepartemenID' => $this->department_to_id,
            'GudangID' => $this->wh_from_id,
            'KeGudangID' => $this->wh_to_id,
            'JenisMutasiID' => $this->mutation_type == 'kadaluarsa' ? '1' : '0',
            'Keterangan' => $this->note,
            'GroupItem' => $this->group,
            'DirectExpense' => $this->external_id,
            'StatusID' => Helper::statusMutasiId($this->status),
            'DisetujuiOleh' => $this->approved_by,
            'DisetujuiTanggal' => $this->approved_at,
        ];

        return $data; 
    }
}
