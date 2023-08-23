<?php

namespace App\Http\Controllers\Race;

use App\Http\Controllers\Controller;
use App\Http\Requests\Race\RaceRegistrationRequest;
use App\Models\Race\RaceRegistration;
use App\Repositories\Race\RaceRegistrationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RaceRegistrationController extends Controller
{

    protected $registration_repo;
    public function __construct(){

        $this->registration_repo = new RaceRegistrationRepository();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        return view('race.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


    public function registration()
    {

        return view('race.registration');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RaceRegistrationRequest $request)
    {
        //
        $input = $request->all();
        $race_registration = $this->registration_repo->store($input);
        return redirect()->route('race.registration_summary', ['race_registration' => $race_registration->uuid]);
    }


    public function registrationSummary( $race_registration)
    {
        $race_registration= $this->registration_repo->getByUuid($race_registration);
        $runners = DB::table('race_registrations')
            ->selectRaw('SUM(five_km + ten_km + twenty_one_km) as total_runners')->where('id',$race_registration->id)
            ->first();
        return view('race.registration_summary')
            ->with('total_runners', (float)$runners->total_runners)
            ->with('race_registration',$race_registration);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Race\RaceRegistration  $registration
     * @return \Illuminate\Http\Response
     */
    public function show(RaceRegistration $registration)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Race\RaceRegistration  $registration
     * @return \Illuminate\Http\Response
     */
    public function edit(RaceRegistration $registration)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Race\RaceRegistration  $registration
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RaceRegistration $registration)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Race\RaceRegistration  $registration
     * @return \Illuminate\Http\Response
     */
    public function destroy(RaceRegistration $registration)
    {
        //
    }

    public function getAllForDt()
    {

    }
}
