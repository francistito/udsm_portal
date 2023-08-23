
<div class="form-row">
    <div class="form-group col-md-6">
        <label class="required font-weight-bold text-dark text-2">Race type</label>
        {{ Form::select('race_type_cv_id',code_value()->getCodeValuesForSelectWithNoLang(4),null,['class'=>'form-control select2','required', 'id' => 'race_type','placeholder' => 'Choose race type', 'autocomplete' => 'off']) }}
        {!! $errors->first('race_type_cv_id', '<span class="badge badge-danger">:message</span>') !!}

    </div>
    <div class="form-group col-md-6">
        <label class=" font-weight-bold text-dark text-2">Team Name</label>
        <input type="text" value="" data-msg-required="Please enter your Nationality." maxlength="100"
               class="form-control" name="team_name" id="team_name" >
    </div>
</div>
<div class="form-row">
<div class="col-md-12">
    <div class="card " id="individual" style="display: none">
        <div class="card-body">
            @include('race.includes.individual_info')

        </div>
    </div>

    <div class="card" id="group" style="display: none">
        <div class="card-body">
            @include('race.includes.group_info')

        </div>
    </div>
</div>


</div>
