<?php

namespace Modules\HealthcareBridge\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\HealthcareBridge\Helper\Helper;

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

    public function healthcareFrom()
    {
        return $this->hasOne(HisRs::class, 'KodeRS', 'healthcare_from_id');
    }

    public function healthcareTo()
    {
        return $this->hasOne(HisRs::class, 'KodeRS', 'healthcare_to_id');
    }

    public function getHisFormatAttribute()
    {
        $data = [
            'RequestID' => $this->external_id,
            'Tanggal' => $this->requested_at,
            'TanggalBuat' => $this->requested_at,
            'TanggalTerima' => $this->received_at,
            'KodeRS' => $this->healthcare_from_id,
            'KeKodeRS' => $this->healthcare_to_id,
            'KeNamaRS' => $this->healthcareTo->Nama ?? '',
            'DariNamaRS' => $this->healthcareFrom->Nama ?? '',
            'DepartemenID' => $this->department_from_id,
            'KeDepartemenID' => $this->department_to_id,
            'GudangDariDept' => $this->wh_from_id,
            'GudangKeDept' => $this->wh_to_id,
            'GroupItem' => $this->group,
            'DirectExpense' => $this->is_direct_expense ? 'Y' : 'N',
            'DisetujuiOleh' => $this->approved_by,
            'DisetujuiOlehNama' => $this->approved_by_name,
            'DisetujuiTanggal' => $this->approved_at,
            'StatusID' => Helper::statusRequestId($this->status),
            'Keterangan' => $this->note,
        ];

        return $data; 
    }

}
