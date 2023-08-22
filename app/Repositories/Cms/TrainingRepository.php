<?php


namespace App\Repositories\Cms;


use App\Models\Cms\Service;
use App\Models\Cms\Training;
use App\Repositories\BaseRepository;
use App\Repositories\System\DocumentResourceRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TrainingRepository extends BaseRepository
{

    const MODEL = Training::class;

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
                'category_id' => $input['category_id'],
                'video_link' => $input['video_link'],
                'contents' => $input['content'],
                'user_id' => Auth::id(),
                'isactive' => 1,
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
                'content' => $input['content'],
                'user_id' => Auth::id(),
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
        if((request()->file('training_image'))){
            $document_resource_repo->saveDocument($service_id,5,'training_image', $input);
        }

        if((request()->file('training_document'))){
            $document_resource_repo->saveDocument($service_id,6,'training_document', $input);
        }

    }






    /*Get all for Datatable Client*/
    public function getAllForDt()
    {
        return $this->query()->where('isactive', 1);
    }


}
