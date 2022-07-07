<?php

namespace Modules\FrontendCMS\Services;

use Illuminate\Support\Facades\Validator;
use \Modules\FrontendCMS\Repositories\RepairRepository;

class RepairService{

    protected $repairRepository;

    public function __construct(RepairRepository  $repairRepository)
    {
        $this->repairRepository = $repairRepository;
    }

    public function save($data)
    {
        return $this->repairRepository->save($data);
    }

    public function update($data,$id)
    {
        return $this->repairRepository->update($data, $id);
    }

    public function getAll()
    {
        return $this->repairRepository->getAll();
    }
    public function getActiveAll(){
        return $this->repairRepository->getActiveAll();
    }

    public function deleteById($id)
    {
        return $this->repairRepository->delete($id);
    }

    public function showById($id)
    {
        return $this->repairRepository->show($id);
    }

    public function editById($id){
        return $this->repairRepository->edit($id);
    }

}
