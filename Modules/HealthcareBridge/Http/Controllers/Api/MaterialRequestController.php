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

        $materialRequest = MaterialRequest::where('healthcare_to_id', $validated['KodeRS'])->with('items')->get();
        if ($materialRequest) {
            $hisMaterialRequest = $materialRequest->his_format;
            foreach ($materialRequest->items as $item) {
                $hisMaterialRequest['items'][] = $item->his_format;
            }
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
            'status' => Helper::statusRequestName($validated['StatusID']),
            'note' => $validated['Keterangan'],
        ];
        $materialRequest = MaterialRequest::updateOrCreate([
            'healthcare_from_id' => $data['healthcare_from_id'],
            'external_id' => $data['external_id'],
        ], $data);

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
            'healthcare_from_id' => $data['KodeRS'],
            'external_request_id' => $data['external_request_id'],
            'external_item_id' => $data['external_item_id']
        ], $data);

        return $materialRequestItem;
    }

    public function storeItems(Request $request)
    {
        // TODO Validation
        // $validated = $request->validate([
        //     'RequestID' => 'required',
        //     'KodeRS' => 'required',
        // ]);
        $validated = $request->all();

        if ($validated['items']) {
            $itemArray = [];

            foreach ($validated['items'] as $item) {
                $temp = [
                    'external_id' => $item['Request2ID'] ?? null,
                    'external_request_id' => $item['RequestID'] ?? null,
                    'external_item_id' => $item['ItemID'] ?? null,
                    'qty' => $item['Jumlah'] ?? null,
                    'received_qty' => $item['JumlahDiterima'] ?? null,
                    'unit' => $item['Satuan'] ?? null,
                    'base_unit' => $item['SatuanDasar'] ?? null,
                    'unit_conversion' => $item['KonversiSatuan'] ?? null,
                    'note' => $item['Catatan'] ?? null,
                ];

                $itemArray[] = $temp;
            }

            $materialRequest = MaterialRequest::where('external_id', $validated['RequestID'])
                ->where('healthcare_from_id', $validated['KodeRS'])
                ->first();
            $materialRequest->items()->delete(); // Clear Out Old Items
            $insertItem = $materialRequest->items()->insert($itemArray);

            return $insertItem;
        }

        return null;
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(Request $request, $kode_rs, $external_id)
    {
        $materialRequest = MaterialRequest::where('healthcare_to_id', $kode_rs)
            ->where('external_id', $external_id)
            ->with('items')
            ->first();
        
        // TODO USE COLLECTION MAP
        $hisMaterialRequest = $materialRequest->his_format;
        foreach ($materialRequest->items as $item) {
            $hisMaterialRequest['items'][] = $item->his_format;
        }

        return response($hisMaterialRequest);
    }

    public function toRs(Request $request, $kode_rs)
    {
        $materialRequests = MaterialRequest::where('healthcare_to_id', $kode_rs)
            ->whereDoesntHave('mutations')
            ->with('items')->get();

        // TODO USE COLLECTION MAP
        $data = [
            'page' => 1,
            'total' => 20,
            'rows' => [],
        ];
        foreach ($materialRequests as $materialRequest) {
            $hisItems = $materialRequest->items->map(function($item) {
                return $item->his_format;
            });

            $flexigridFormat = [
                'id' => $materialRequest->external_id,
                'cell' => $materialRequest->his_format,
                'items' => $hisItems
            ];

            $data['rows'][] = $flexigridFormat;
        }

        return response($data);
    }
}
