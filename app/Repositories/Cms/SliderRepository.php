<?php


namespace App\Repositories\Cms;


use App\Models\Cms\Slider;
use App\Repositories\BaseRepository;
use App\Repositories\System\DocumentResourceRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SliderRepository extends BaseRepository
{

    const MODEL = Slider::class;

    public function __construct()
    {


    }




    /*Insert/create PaylollCompliance*/
    public function store(array $input)
    {
        return DB::transaction(function() use($input){
            $slider = $this->query()->firstOrCreate([
                'title' => $input['title'],
                'description' => $input['description'],
                'color' => $input['color'],
                'position' => $input['position'],
                'size' => $input['size'],
                'description_color' => $input['description_color'],
                'description_size' => $input['description_size'],
                'isactive' => $input['isactive'],
                'button_title' => $input['button_title'],
                'button_link' => $input['button_link'],
                'button_color' => $input['button_color'],
            ]);

            //serve image
            $this->saveDocuments($slider->id,$input);
            return $slider;
        });
    }

    /*Insert/create PaylollCompliance*/
    public function update(array $input,$slider)
    {
        return DB::transaction(function() use($input,$slider){
          $slider->update([
              'title' => $input['title'],
              'description' => $input['description'],
              'color' => $input['color'],
              'position' => $input['position'],
              'size' => $input['size'],
              'description_color' => $input['description_color'],
              'description_size' => $input['description_size'],
              'isactive' => $input['isactive'],
              'button_title' => $input['button_title'],
              'button_link' => $input['button_link'],
              'button_color' => $input['button_color'],
            ]);
            //slider image
            $this->saveDocuments($slider->id,$input);
            return $slider;
        });
    }



    /*Save document(s) attached on the form*/
    public function saveDocuments($slider_id, array $input)
    {
        $document_resource_repo = new DocumentResourceRepository();
        if((request()->file('slider_image'))){
            $document_resource_repo->saveDocument($slider_id,4,'slider_image', $input);
        }

    }


    /**
     * @param $slider
     * delete slider
     */
    public function delete($slider)
    {
        $slider->delete();
    }



    /*Get all for Datatable Client*/
    public function getAllForDt()
    {
        return $this->query();
    }


}
