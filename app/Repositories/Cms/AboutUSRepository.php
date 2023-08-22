<?php

namespace App\Repositories\Cms;

use App\Models\Cms\AboutUs;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AboutUSRepository extends BaseRepository
{
    const MODEL = AboutUs::class;


    public function __construct()
    {

    }

    /**
     * update FAQ
     * @return mixed
     */
    public function getAll(){
    $query = $this->query()
        ->orderBy('id', 'desc')
        ->paginate(sysdef()->name('pagination_low'));
    return $query;
}

    /**
     * Get FAQ for admin datatable
     * @return mixed
     */
    public function getForAdminDataTable(){
        return $this->query();
    }

    /**
     * Store FAQ
     * @param array $input
     * @return mixed
     */
    public  function store(array $input){
        return DB::transaction(function () use($input){
            $about_us = $this->query()->create([
                'vision'=>$input['vision'],
                'mission'=>$input['mission'],
                'our_goal'=>$input['our_value'],
                'about_us_intro'=>$input['about_us_intro'],
                'about_us'=>$input['about_us'],
                'phone_number'=>$input['phone_number'],
                'email'=>$input['email'],
                'address'=>$input['address'],
                'instagram_link'=>$input['instagram_link'],
                'facebook_link'=>$input['facebook_link'],
                'twitter_link'=>$input['twitter_link'],
                'linkedin'=>$input['linkedin_link'],
            ]);
            return $about_us;
        });
    }

    /**
     * @param FAQs
     * @param array $input
     * Update existing FAQ information
     */
    public function update(AboutUs $about_us, array $input)
    {
        return  DB :: transaction(function() use ($input, $about_us){
            $about_us->update([
                'vision'=>$input['vision'],
                'mission'=>$input['mission'],
                'our_goal'=>$input['our_value'],
                'about_us_intro'=>$input['about_us_intro'],
                'about_us'=>$input['about_us'],
                'phone_number'=>$input['phone_number'],
                'email'=>$input['email'],
                'address'=>$input['address'],
                'instagram_link'=>$input['instagram_link'],
                'facebook_link'=>$input['facebook_link'],
                'twitter_link'=>$input['twitter_link'],
                'linkedin'=>$input['linkedin_link'],
            ]);
            return $about_us;
        });
    }



    /**
     * @param FAQs
     * @param array $input
     * delete existing FAQ information
     */
    public function delete(AboutUs $about_us)
    {
        $about_us->delete();
    }

    /**
     * Get all FAQs by service
     * @param $logistic_service_cv_id
     * @return mixed
     */
    public function getAllByRank(){
        $query = $this->query()
            ->orderBy('rank', 'asc')
            ->paginate(20);
        return $query;
    }



    /**
     * Get all FAQs by sub service / category
     * @param $logistic_service_cv_id
     * @return mixed
     */
    public function getAllBySubService($logistic_service_sub_cv_id){
        $query = $this->query()
            ->where('logistic_service_sub_cv_id', $logistic_service_sub_cv_id)
            ->orderBy('rank', 'asc')
            ->paginate(20);
        return $query;
    }


    /**
     * Get all FAQs which are general
     * @param
     * @return mixed
     */
    public function getGeneralFaqs(){
        $query = $this->query()
            ->where('issystem', 1)
            ->orderBy('rank', 'asc')
            ->paginate(20);
        return $query;
    }


}
