<?php
namespace Modules\FrontendCMS\Repositories;

use \Modules\FrontendCMS\Entities\Repair;

class RepairRepository {

    protected $repair;

    public function __construct(Repair $repair)
    {
        $this->repair = $repair;
    }


    public function getAll()
    {
        return $this->repair->get();
    }
    public function getActiveAll(){
        
        return $this->repair::where('status',1)->get();
    }

    public function save($data)
    {
        return $this->repair::create([
            'title' => $data['title'],
            'slug' => $data['slug'],
            'icon' => $data['icon'],
            'status' => $data['status']
        ]);
    }

    public function update($data, $id)
    {
        $repair = $this->repair::where('id',$id)->first();
        $repair->update([
            'title' => $data['title'],
            'slug' => $data['slug'],
            'icon' => $data['icon'],
            'status' => $data['status']
        ]);

        return $repair->fresh();
    }

    public function delete($id)
    {
        $repair = $this->repair->findOrFail($id);
        $repair->delete();

        return $repair;
    }

    public function show($id)
    {
        $repair = $this->repair->findOrFail($id);
        return $repair;
    }

    public function edit($id){
        $repair = $this->repair->findOrFail($id);
        return $repair;
    }
}
