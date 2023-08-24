<?php


namespace App\Repositories\Race;


use App\Models\Race\RaceRegistration;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RaceRegistrationRepository extends BaseRepository
{
    const MODEL = RaceRegistration::class;
    public function __construct()
    {

    }

    public function store(array $input)
    {

      return   DB::transaction(function ()use($input){
            $registration = $this->query()->create([
                'first_name' =>$input['first_name'],
                'last_name' =>$input['last_name'],
                'date_of_birth' => $input['date_of_birth'],
                'gender_cv_id' => $input['gender_cv_id'],
                'email' =>$input['email'],
                'phone_number' => $input['phone_number'],
                'nationality' => $input['nationality'],
                'address' =>$input['address'],
                'race_type_cv_id' => $input['race_type_cv_id'],
                'team_name' => $input['team_name'],
                'race_cate' =>1,
                'race_category_cv_id' =>isset($input['race_category_cv_id'])  ? ($input['race_category_cv_id'] ) : null,
                'tshirt_type_cv_id' =>isset($input['tshirt_type_cv_id'])  ? ($input['tshirt_type_cv_id'] ) : null,
                'tshirt_size_cv_id' =>  isset($input['tshirt_size_cv_id'])  ? ($input['tshirt_size_cv_id'] ) : null,
                'terms' => ($input['terms'] == 'on')  ? 1 : 0,
                'five_km' =>   isset($input['5km'])  ? ($input['5km']) : null,
                'ten_km' =>  isset($input['10km'])  ? ($input['10km']) : null,
                'twenty_one_km' => isset($input['21km'])  ? ($input['21km']) : null,
                'xxl' => isset($input['xxl'] )  ? ($input['xxl'] ) : null,
                'xl' =>  isset($input['xl'])  ? ($input['xl'] ) : null,
                'l' => isset($input['l'])  ? ($input['l'] ) : null,
                'm' =>  isset($input['m'])  ? ($input['m'] ) : null,
                's' =>  isset($input['s'])  ? ($input['s'] ) : null,
                'xs' =>  isset($input['xs'])  ? ($input['xs'] ) : null,
            ]);
          return $registration;
        });
    }


    public function update(array $input,$registration)
    {
        return DB::transaction(function ()use($input,$registration){
            $registration->update([
                'title' =>$input['title'],
                'content' =>$input['content'],
                'isactive' => $input['isactive'],
                'hastour' => $input['hastour'],
                'user_id' => Auth::id(),
            ]);

            return $registration;
        });

    }


    //delete blog
    public function delete($registration)
    {
        $registration->delete();
    }



    /*Get all for Datatable Client*/
    public function getAllForDt()
    {
        $query = $this->getAll();
        return $query;
    }

    public function getAllRegistrationByRaceType($race_type)
    {
        $query = $this->query();
        switch ($race_type)
        {
            case 8 :
                 $query->where('race_type_cv_id',8)->get();
                break;
            case 9:
                 $query->where('race_type_cv_id',9)->get();
                break;
        }

        return $query;
    }

}
