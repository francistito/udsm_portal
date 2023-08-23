<div class="form-row">
    <div class="form-group col-md-6">
        <label class="required font-weight-bold text-dark text-2">First name</label>
        <input type="text" value="" data-msg-required="Please enter your name." maxlength="100"
               class="form-control" name="first_name" id="first_name" required>
        {!! $errors->first('first_name', '<span class="badge badge-danger">:message</span>') !!}

    </div>
    <div class="form-group col-md-6">
        <label class="required font-weight-bold text-dark text-2">Last name</label>
        <input type="text" value="" data-msg-required="Please enter your lastname."
               data-msg-email="Please enter a valid email last name." maxlength="100"
               class="form-control" name="last_name" id="last_name" required>
        {!! $errors->first('last_name', '<span class="badge badge-danger">:message</span>') !!}

    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-6">
        <label class="required font-weight-bold text-dark text-2">Date of birth</label>
        <input type="date" value="" data-msg-required="Please enter your name." maxlength="100"
               class="form-control" name="date_of_birth" id="date_of_birth" required>
        {!! $errors->first('date_of_birth', '<span class="badge badge-danger">:message</span>') !!}

    </div>
    <div class="form-group col-md-6">
        <label class="required font-weight-bold text-dark text-2">Gender</label>
        {{ Form::select('gender_cv_id',code_value()->getCodeValuesForSelectWithNoLang(3),null,['class'=>'form-control select2','required', 'id' => 'gender','placeholder' => '', 'autocomplete' => 'off']) }}
        {!! $errors->first('gender_cv_id', '<span class="badge badge-danger">:message</span>') !!}


    </div>
</div>
<div class="form-row">
    <div class="form-group col-md-6">
        <label class="required font-weight-bold text-dark text-2">Email</label>
        <input type="text" value="" data-msg-required="Please enter your email." maxlength="100"
               class="form-control" name="email" id="email" required>
        {!! $errors->first('email', '<span class="badge badge-danger">:message</span>') !!}

    </div>
    <div class="form-group col-md-6">
        <label class="required font-weight-bold text-dark text-2">Phone number</label>
        <input type="text" value="" data-msg-required="Please enter your phone number."
               data-msg-email="Please enter a valid email address." maxlength="100"
               class="form-control" name="phone_number" id="phone_number" required>
        {!! $errors->first('phone_number', '<span class="badge badge-danger">:message</span>') !!}

    </div>
</div>
<div class="form-row">
    <div class="form-group col-md-6">
        <label class=" font-weight-bold text-dark text-2">Nationality</label>
        <input type="text" value="" data-msg-required="Please enter your Nationality." maxlength="100"
               class="form-control" name="nationality" id="nationality" >
        {!! $errors->first('nationality', '<span class="badge badge-danger">:message</span>') !!}

    </div>
    <div class="form-group col-md-6">
        <label class=" font-weight-bold text-dark text-2">My Address/City/Town</label>
        <input type="text" value="" data-msg-required="Please enter your phone number."
               data-msg-email="Please enter a valid email address." maxlength="100"
               class="form-control" name="address" id="address" >
        {!! $errors->first('address', '<span class="badge badge-danger">:message</span>') !!}

    </div>
</div>
