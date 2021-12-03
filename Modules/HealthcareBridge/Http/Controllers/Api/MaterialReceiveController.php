<?php

namespace Modules\HealthcareBridge\Http\Controllers\Api;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HealthcareBridge\Entities\MaterialRequest;
use Modules\HealthcareBridge\Helper\Helper;

class MaterialReceiveController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('healthcarebridge::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('healthcarebridge::create');
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
        //     'MutationID' => 'required'
        // ]);
        $validated = $request->all();

        $data = [
            'external_id' => $validated['ReceiveID'],
            'external_request_id' => $validated['RequestID'],
            'external_mutation_id' => $validated['MutationID'],
            'received_at' => $validated['Tanggal'],
            'received_from' => $validated['DariNamaRS'],
            'healthcare_from_id' => $validated['DariKodeRS'],
            'healthcare_to_id' => $validated['KodeRS'],
            'department_from_id' => $validated['DariDepartemenID'],
            'department_to_id' => $validated['DepartemenID'],
            'wh_from_id' => $validated['DariGudangID'],
            'wh_to_id' => $validated['GudangID'],
            'receive_type' => $validated['JenisReceiveID'] == '0' ? 'request' : 'donasi',
            'approved_by' => $validated['DisetujuiOleh'],
            'approved_by_name' => $validated['DisetujuiOleh'],
            'approved_at' => $validated['DisetujuiTanggal'],
            'status' => Helper::statusReceiveName($validated['StatusID']),
            'note' => $validated['Keterangan'],
        ];
        $materialMutation = MaterialRequest::updateOrCreate([
            'healthcare_from_id' => $data['healthcare_from_id'],
            'external_id' => $data['external_id'],
        ], $data);

        return $materialMutation;
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
                    'external_id' => $item['Mutasi2ID'] ?? null,
                    'external_mutation_id' => $item['MutasiID'] ?? null,
                    'external_request_item_id' => $item['Request2ID'] ?? null,
                    'external_item_id' => $item['ItemID'] ?? null,
                    'qty' => $item['Jumlah'] ?? null,
                    'unit' => $item['Satuan'] ?? null,
                    'base_unit' => $item['SatuanDasar'] ?? null,
                    'unit_conversion' => $item['KonversiSatuan'] ?? null,
                    'cogs' => $item['COGS'] ?? null,
                    'note' => $item['Keterangan'] ?? null,
                ];

                $itemArray[] = $temp;
            }

            $materialMutation = MaterialMutation::where('external_id', $validated['MutasiID'])
                ->where('healthcare_from_id', $validated['KodeRS'])
                ->first();
            $materialMutation->items()->delete(); // Clear Out Old Items
            $insertItem = $materialMutation->items()->insert($itemArray);

            return $insertItem;
        }

        return null;
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('healthcarebridge::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('healthcarebridge::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
