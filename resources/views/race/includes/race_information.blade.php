
<div class="form-row">
    <div class="form-group col-md-6">
        <label class="required font-weight-bold text-dark text-2">Race type</label>
        {{ Form::select('race_type',['0' => 'Group', '1' => 'individual'],1,['class'=>'form-control select2','required', 'id' => 'status','placeholder' => '', 'autocomplete' => 'off']) }}

    </div>
    <div class="form-group col-md-6">
        <label class="required font-weight-bold text-dark text-2">Tace category</label>
        {{ Form::select('race_category',['0' => '5km', '1' => '10km'],1,['class'=>'form-control select2','required', 'id' => 'status','placeholder' => '', 'autocomplete' => 'off']) }}

        <div class="row">
            <div class="form-group col-md-4">
                <label class="required font-weight-bold text-dark text-2">5KM</label>
                <input type="text" value="" data-msg-required="Please enter your name." maxlength="100"
                       class="form-control" name="name" id="name" required>
            </div>
            <div class="form-group col-md-4">
                <label class="required font-weight-bold text-dark text-2">10KM</label>
                <input type="text" value="" data-msg-required="Please enter your name." maxlength="100"
                       class="form-control" name="name" id="name" required>
            </div> <div class="form-group col-md-4">
                <label class="required font-weight-bold text-dark text-2">21KM</label>
                <input type="text" value="" data-msg-required="Please enter your name." maxlength="100"
                       class="form-control" name="name" id="name" required>
            </div>
        </div>


    </div>
</div>
