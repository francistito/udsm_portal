<?php

namespace App\Models\Access\Relationship;



use App\Models\Access\Role;
use App\Models\Access\User;
use App\Models\Hr\Employee\Employee;
use App\Models\Operation\Project\Project;
use App\Models\Operation\Sales\Sale;
use App\Models\Operation\Station\Station;
use App\Models\System\CodeValue;
use App\Models\System\Country;
use App\Models\System\Document;
use App\Models\System\Region;
use App\Models\System\Report;
use App\Models\Workflow\WfDefinition;
use App\Models\Workflow\WfTrack;


trait UserRelationship
{
    /**
     * Many-to-Many relations with Role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * @return mixed
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class)->withTimestamps();
    }

    public function userAccounts()
    {
        return $this->belongsToMany(CodeValue::class, "user_accounts", "user_id", "user_account_cv_id");
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }


    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * @return mixed
     */
    public function wfTracks()
    {
        //return $this->hasMany(WfTrack::class);
        return $this->morphMany(WfTrack::class, 'user');
    }



    /**
     * @return mixed
     */
    public function wfTracksUser()
    {
        //return $this->hasMany(WfTrack::class);
        return $this->hasMany(WfTrack::class);
    }

    public function wfDefinition()
    {
        return $this->belongsToMany(WfDefinition::class,'user_wf_definition');
    }



    public function logs()
    {
        return $this->hasMany('user_logs','user_id','id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function station()
    {
        return $this->belongsTo(Station::class);
    }


    public function favouriteReports()
    {
        return $this->belongsToMany(Report::class, 'report_user_favourite')->withTimestamps();
    }


    public function projects()
    {
        return $this->belongsToMany(Project::class,'project_team','user_id','project_id')->withPivot('ispm', 'can_manage_project', 'rate_per_hr', 'isactive');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

}
