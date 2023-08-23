
<div class="form-row">
    <div class="form-group col-md-4">
        <label class="required font-weight-bold text-dark text-2"> Category</label><br>

        {{ Form::select('race_category_cv_id',code_value()->getCodeValuesForSelectWithNoLang(7),null,['class'=>'form-control select2 individual_input','required', 'id' => 'status','placeholder' => '', 'autocomplete' => 'off']) }}
        {!! $errors->first('race_category_cv_id', '<span class="badge badge-danger">:message</span>') !!}

    </div>
    <div class="form-group col-md-4">
        <label class="required font-weight-bold text-dark text-2">Shirt type</label>
        {{ Form::select('tshirt_type_cv_id',code_value()->getCodeValuesForSelectWithNoLang(5),null,['class'=>'form-control select2 individual_input','required', 'id' => 'status','placeholder' => '', 'autocomplete' => 'off']) }}
        {!! $errors->first('tshirt_type_cv_id', '<span class="badge badge-danger">:message</span>') !!}


    </div>
    <div class="form-group col-md-4">
        <label class="required font-weight-bold text-dark text-2">Shirt size</label>
        {{ Form::select('tshirt_size_cv_id',code_value()->getCodeValuesForSelectWithNoLang(6),null,['class'=>'form-control select2 individual_input','required', 'id' => 'status','placeholder' => '', 'autocomplete' => 'off']) }}
        {!! $errors->first('tshirt_size_cv_id', '<span class="badge badge-danger">:message</span>') !!}

    </div>
</div>

