<?php

namespace Modules\FrontendCMS\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FrontendCMS\Http\Requests\CreateRepairRequest;
use Modules\FrontendCMS\Http\Requests\UpdateRepairRequest;
use \Modules\FrontendCMS\Services\RepairService;
use Modules\UserActivityLog\Traits\LogActivity;

class RepairController extends Controller
{
    protected $repairService;

    public function __construct(RepairService $repairService)
    {
        $this->middleware('maintenance_mode');
        $this->repairService = $repairService;
    }

    public function index()
    {
        try {
            $data['RepairList'] = $this->repairService->getAll();
        } catch (Exception $e) {
            LogActivity::errorLog($e->getMessage());
            Toastr::error(__('common.operation_failed'));
            return back();
        }
        return view('frontendcms::repair.index', $data);
    }

    public function list()
    {
        $RepairList = $this->repairService->getAll();
        return view('frontendcms::repair.componant.list', compact('RepairList'));
    }

    public function store(CreateRepairRequest $request)
    {
        try {
            $this->repairService->save($request->only('title', 'slug', 'icon', 'status'));

            LogActivity::successLog('Repair added.');

            return $this->loadTableData();
        } catch (Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return response()->json([
                'status' => false,
                'error' => $e
            ]);
        }

    }

    public function show($id)
    {
        try {
            $repair = $this->repairService->showById($id);
            return response()->json([
                'status' => true,
                'TableData' =>  (string)view('frontendcms::repair.components.list', compact('RepairList'))
            ]);
        } catch (Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return response()->json([
                'status' => false,
                'error' => $e
            ]);
        }
        return view('frontendcms::show');
    }

    public function edit($id){

        $repair = $this->repairService->showById($id);
        return view('frontendcms::repair.components.edit',compact('repair'));
    }

    public function update(UpdateRepairRequest $request)
    {
        try {
            $result = $this->repairService->update($request->only('title', 'slug', 'icon', 'status'), $request->id);
            LogActivity::successLog('Repair Updated.');
        } catch (Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return response()->json([
                'status' => false,
                'error' => $e
            ]);
        }
        return  $this->loadTableData();
    }

    public function delete(Request $request)
    {
        try {
            $this->repairService->deleteById($request['id']);
            LogActivity::successLog('Repair Deleted.');
        } catch (Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return response()->json([
                'status' => false,
                'error' => $e,
            ]);
        }

        return  $this->loadTableData();
    }

    private function loadTableData()
    {
        try {
            $RepairList = $this->repairService->getAll();
            return response()->json([
                'status' => true,
                'TableData' =>  (string)view('frontendcms::repair.components.list', compact('RepairList')),
                'createForm' =>  (string)view('frontendcms::repair.components.create')
            ]);
        } catch (\Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return response()->json([
                'status' => false,
                'error' => $e
            ]);
        }
    }
}
