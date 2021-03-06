<?php

namespace Modules\HealthcareBridge\Http\Controllers\Api;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HealthcareBridge\Entities\MaterialMutation;
use Modules\HealthcareBridge\Helper\Helper;

class MaterialMutationController extends Controller
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
            'external_id' => $validated['MutasiID'],
            'external_request_id' => $validated['RequestID'],
            'send_at' => $validated['Tanggal'],
            'healthcare_from_id' => $validated['KodeRS'],
            'healthcare_to_id' => $validated['KeKodeRS'],
            'department_from_id' => $validated['DepartemenID'],
            'department_to_id' => $validated['KeDepartemenID'],
            'wh_from_id' => $validated['GudangID'],
            'wh_to_id' => $validated['KeGudangID'],
            'group' => $validated['GroupItem'],
            'mutation_type' => $validated['JenisMutasiID'] == '0' ? 'reguler' : 'kadaluarsa',
            'is_direct_expense' => $validated['DirectExpense'] == 'N' ? false : true,
            'approved_by' => $validated['DisetujuiOleh'],
            'approved_by_name' => $validated['DisetujuiOleh'],
            'approved_at' => $validated['DisetujuiTanggal'],
            'status' => Helper::statusMutasiName($validated['StatusID']),
            'note' => $validated['Keterangan'],
        ];
        $materialMutation = MaterialMutation::updateOrCreate([
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
                $expiration_date = empty($item['TanggalED']) && $item['TanggalED'] != '00-0000' ? implode('-', array_reverse(explode('-', $item['TanggalED']))).'-01' : null;
                
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
                    'expiration_date' => $expiration_date,
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
    public function show(Request $request, $kode_rs, $external_id)
    {
        $materialMutation = MaterialMutation::where('healthcare_to_id', $kode_rs)
            ->where('external_id', $external_id)
            ->with('items')
            ->firstOrFail();
        
        // TODO USE COLLECTION MAP
        $hisMaterialMutation = $materialMutation->his_format;
        foreach ($materialMutation->items as $item) {
            $hisMaterialMutation['items'][] = $item->his_format;
        }

        return response($hisMaterialMutation);
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

    public function toRs(Request $request, $kode_rs)
    {
        $materialMutations = MaterialMutation::with('items')
            ->where('healthcare_to_id', $kode_rs)
            ->whereDoesntHave('receives');
        if ($request->has('KeDepartemenID')) {
            $materialMutations->where('department_to_id', $request->KeDepartemenID);
        }
        $materialMutations = $materialMutations->get();

        // TODO USE COLLECTION MAP
        $data = [
            'page' => 1,
            'total' => 20,
            'rows' => [],
        ];
        foreach ($materialMutations as $mutation) {
            $hisItems = $mutation->items->map(function($item) {
                return $item->his_format;
            });

            $flexigridFormat = [
                'id' => $mutation->external_id,
                'cell' => $mutation->his_format,
                'items' => $hisItems
            ];

            $data['rows'][] = $flexigridFormat;
        }

        return response($data);
    }
}
