<?php

namespace Modules\HealthcareBridge\Http\Controllers\Api;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HealthcareBridge\Entities\MaterialRequest;
use Modules\HealthcareBridge\Entities\MaterialRequestItem;
use Modules\HealthcareBridge\Helper\Helper;

class MaterialRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'KodeRS' => 'required'
        ]);

        return response($validated);

        $materialRequest = MaterialRequest::where('healthcare_to_id', $validated['KodeRS'])->with('items')->firstOrFail();

        $hisMaterialRequest = [
            'RequestID' => $materialRequest->external_id,
            'Tanggal' => $materialRequest->requested_at,
            'TanggalTerima' => $materialRequest->received_at,
            'KodeRS' => $materialRequest->healthcare_from_id,
            'KeKodeRS' => $materialRequest->healthcare_to_id,
            'DepartemenID' => $materialRequest->department_from_id,
            'KeDepartemenID' => $materialRequest->department_to_id,
            'GudangDariDept' => $materialRequest->wh_from_id,
            'GudangKeDept' => $materialRequest->wh_to_id,
            'GroupItem' => $materialRequest->group,
            'DirectExpense' => $materialRequest->is_direct_expense ? 'Y' : 'N',
            'DisetujuiOleh' => $materialRequest->approved_by,
            'DisetujuiOleh' => $materialRequest->approved_by_name,
            'DisetujuiTanggal' => $materialRequest->approved_at,
            'StatusID' => Helper::statusId($materialRequest->status),
            'Keterangan' => $materialRequest->note,
        ];

        foreach ($materialRequest->items as $item) {
            $hisMaterialRequest['items'][] = [
                'Request2ID' => $item->external_id,
                'RequestID' => $item->external_request_id,
                'ItemID' => $item->external_item_id,
                'Jumlah' => $item->qty,
                'JumlahDiterima' => $item->received_qty,
                'Satuan' => $item->unit,
                'SatuanDasar' => $item->base_unit,
                'KonversiSatuan' => $item->unit_conversion,
                'Catatan' => $item->note,
            ];
        }

        return response($hisMaterialRequest);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        // TODO Validation
        // $validated = $request->validate([
        //     'RequestID' => 'required'
        // ]);
        $validated = $request->all();

        $data = [
            'external_id' => $validated['RequestID'],
            'requested_at' => $validated['Tanggal'],
            'received_at' => $validated['TanggalTerima'],
            'healthcare_from_id' => $validated['KodeRS'],
            'healthcare_to_id' => $validated['KeKodeRS'],
            'department_from_id' => $validated['DepartemenID'],
            'department_to_id' => $validated['KeDepartemenID'],
            'wh_from_id' => $validated['GudangDariDept'],
            'wh_to_id' => $validated['GudangKeDept'],
            'group' => $validated['GroupItem'],
            'is_direct_expense' => $validated['DirectExpense'] == 'N' ? false : true,
            'approved_by' => $validated['DisetujuiOleh'],
            'approved_by_name' => $validated['DisetujuiOleh'],
            'approved_at' => $validated['DisetujuiTanggal'],
            'status' => Helper::statusName($validated['StatusID']),
            'note' => $validated['Keterangan'],
        ];
        $materialRequest = MaterialRequest::updateOrCreate([
            'healthcare_from_id' => $data['healthcare_from_id'],
            'external_id' => $data['external_id'],
        ], $data);

        // foreach ($request->items as $item) {
        //     $data_item = [
        //         'external_id' => $validated['Request2ID,
        //         'external_request_id' => $validated['RequestID,
        //         'external_item_id' => $validated['ItemID,
        //         'qty' => $validated['Jumlah,
        //         'received_qty' => $validated['JumlahDiterima,
        //         'unit' => $validated['Satuan,
        //         'base_unit' => $validated['SatuanDasar,
        //         'unit_conversion' => $validated['KonversiSatuan,
        //         'note' => $validated['Catatan,
        //     ];

        //     $materialRequest->items()->create($data_item); // Insert One By One
        // }

        return $materialRequest;
    }

    public function storeItem(Request $request)
    {
        // TODO Validation
        // $validated = $request->validate([
        //     'RequestID' => 'required'
        // ]);
        $validated = $request->all();

        $data = [
            'external_id' => $validated['Request2ID'],
            'external_request_id' => $validated['RequestID'],
            'external_item_id' => $validated['ItemID'],
            'qty' => $validated['Jumlah'],
            'received_qty' => $validated['JumlahDiterima'],
            'unit' => $validated['Satuan'],
            'base_unit' => $validated['SatuanDasar'],
            'unit_conversion' => $validated['KonversiSatuan'],
            'note' => $validated['Catatan'],
        ];
        $materialRequestItem = MaterialRequestItem::updateOrCreate([
            'external_request_id' => $data['external_request_id'],
            'external_item_id' => $data['external_item_id']
        ], $data);

        return $materialRequestItem;
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($external_id)
    {
        $materialRequest = MaterialRequest::where('external_id', $external_id)->with('items')->first();

        $hisMaterialRequest = [
            'RequestID' => $materialRequest->external_id,
            'Tanggal' => $materialRequest->requested_at,
            'TanggalTerima' => $materialRequest->received_at,
            'KodeRS' => $materialRequest->healthcare_from_id,
            'KeKodeRS' => $materialRequest->healthcare_to_id,
            'DepartemenID' => $materialRequest->department_from_id,
            'KeDepartemenID' => $materialRequest->department_to_id,
            'GudangDariDept' => $materialRequest->wh_from_id,
            'GudangKeDept' => $materialRequest->wh_to_id,
            'GroupItem' => $materialRequest->group,
            'DirectExpense' => $materialRequest->is_direct_expense ? 'Y' : 'N',
            'DisetujuiOleh' => $materialRequest->approved_by,
            'DisetujuiOleh' => $materialRequest->approved_by_name,
            'DisetujuiTanggal' => $materialRequest->approved_at,
            'StatusID' => Helper::statusId($materialRequest->status),
            'Keterangan' => $materialRequest->note,
        ];

        foreach ($materialRequest->items as $item) {
            $hisMaterialRequest['items'][] = [
                'Request2ID' => $item->external_id,
                'RequestID' => $item->external_request_id,
                'ItemID' => $item->external_item_id,
                'Jumlah' => $item->qty,
                'JumlahDiterima' => $item->received_qty,
                'Satuan' => $item->unit,
                'SatuanDasar' => $item->base_unit,
                'KonversiSatuan' => $item->unit_conversion,
                'Catatan' => $item->note,
            ];
        }

        return response($hisMaterialRequest);
    }

    public function toRs($kode_rs)
    {
        $materialRequests = MaterialRequest::where('healthcare_to_id', $kode_rs)->with('items')->get();
        $data = [];

        foreach ($materialRequests as $materialRequest) {
            $hisMaterialRequest = [
                'RequestID' => $materialRequest->external_id,
                'Tanggal' => $materialRequest->requested_at,
                'TanggalTerima' => $materialRequest->received_at,
                'KodeRS' => $materialRequest->healthcare_from_id,
                'KeKodeRS' => $materialRequest->healthcare_to_id,
                'DepartemenID' => $materialRequest->department_from_id,
                'KeDepartemenID' => $materialRequest->department_to_id,
                'GudangDariDept' => $materialRequest->wh_from_id,
                'GudangKeDept' => $materialRequest->wh_to_id,
                'GroupItem' => $materialRequest->group,
                'DirectExpense' => $materialRequest->is_direct_expense ? 'Y' : 'N',
                'DisetujuiOleh' => $materialRequest->approved_by,
                'DisetujuiOleh' => $materialRequest->approved_by_name,
                'DisetujuiTanggal' => $materialRequest->approved_at,
                'StatusID' => Helper::statusId($materialRequest->status),
                'Keterangan' => $materialRequest->note,
            ];
    
            foreach ($materialRequest->items as $item) {
                $hisMaterialRequest['items'][] = [
                    'Request2ID' => $item->external_id,
                    'RequestID' => $item->external_request_id,
                    'ItemID' => $item->external_item_id,
                    'Jumlah' => $item->qty,
                    'JumlahDiterima' => $item->received_qty,
                    'Satuan' => $item->unit,
                    'SatuanDasar' => $item->base_unit,
                    'KonversiSatuan' => $item->unit_conversion,
                    'Catatan' => $item->note,
                ];
            }

            $data[] = $hisMaterialRequest;
        }

        return response($data);
    }
}
