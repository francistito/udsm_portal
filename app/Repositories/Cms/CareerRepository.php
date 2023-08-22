<?php


namespace App\Repositories\Cms;


use App\Models\Cms\Career;
use App\Models\Cms\Service;
use App\Repositories\BaseRepository;
use App\Repositories\System\DocumentResourceRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CareerRepository extends BaseRepository
{

    const MODEL = Career::class;

    public function __construct()
    {


    }


    /**
     * @param $service_type_id
     * @return mixed
     */
    public function getServiceByServiceDirectory($service_type_id)
    {
        $services = $this->query()->where('service_type_cv_id',$service_type_id)->get();
        return $services;
    }

    /*Insert/create PaylollCompliance*/
    public function store(array $input)
    {
        return DB::transaction(function() use($input){
            $service = $this->query()->firstOrCreate([
                'title' => $input['title'],
                'service_type_cv_id' => $input['service_type_cv_id'],
                'description' => $input['content'],
//                'user_id' => Auth::id(),
                'isactive' =>1,
            ]);

            //serve image
            $this->saveDocuments($service->id,$input);
            return $service;
        });
    }

    /*Insert/create PaylollCompliance*/
    public function update(array $input,$service)
    {
        return DB::transaction(function() use($input,$service){
          $service->update([
                'title' => $input['title'],
                'service_type_cv_id' => $input['service_type_cv_id'],
                'description' => $input['content'],
//                'user_id' => Auth::id(),
              'isactive' =>$input['isactive'],


          ]);

            //serve image
            $this->saveDocuments($service->id,$input);
            return $service;
        });
    }



    /*Save document(s) attached on the form*/
    public function saveDocuments($service_id, array $input)
    {
        $document_resource_repo = new DocumentResourceRepository();
        if((request()->file('service_image'))){
            $document_resource_repo->saveDocument($service_id,8,'service_image', $input);
        }

    }






    /*Get all for Datatable Client*/
    public function getAllForDt()
    {
        return $this->query()->get();
    }


}
