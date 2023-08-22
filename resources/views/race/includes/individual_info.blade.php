
<div class="form-row">
    <div class="form-group col-md-4">
        <label class="required font-weight-bold text-dark text-2"> Category</label><br>

        {{ Form::select('race_category',code_value()->getCodeValuesForSelectWithNoLang(7),null,['class'=>'form-control select2','required', 'id' => 'status','placeholder' => '', 'autocomplete' => 'off']) }}
    </div>
    <div class="form-group col-md-4">
        <label class="required font-weight-bold text-dark text-2">Shirt type</label>
        {{ Form::select('shirt_type',code_value()->getCodeValuesForSelectWithNoLang(5),null,['class'=>'form-control select2','required', 'id' => 'status','placeholder' => '', 'autocomplete' => 'off']) }}

    </div>
    <div class="form-group col-md-4">
        <label class="required font-weight-bold text-dark text-2">Shirt size</label>
        {{ Form::select('shirt_size',code_value()->getCodeValuesForSelectWithNoLang(6),null,['class'=>'form-control select2','required', 'id' => 'status','placeholder' => '', 'autocomplete' => 'off']) }}

    </div>
</div>

