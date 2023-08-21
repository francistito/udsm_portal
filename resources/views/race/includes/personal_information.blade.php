<div class="form-row">
    <div class="form-group col-md-6">
        <label class="required font-weight-bold text-dark text-2">First name</label>
        <input type="text" value="" data-msg-required="Please enter your name." maxlength="100"
               class="form-control" name="name" id="name" required>
    </div>
    <div class="form-group col-md-6">
        <label class="required font-weight-bold text-dark text-2">Last name</label>
        <input type="email" value="" data-msg-required="Please enter your email address."
               data-msg-email="Please enter a valid email address." maxlength="100"
               class="form-control" name="email" id="email" required>
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-6">
        <label class="required font-weight-bold text-dark text-2">Date of birth</label>
        <input type="text" value="" data-msg-required="Please enter your name." maxlength="100"
               class="form-control" name="name" id="name" required>
    </div>
    <div class="form-group col-md-6">
        <label class="required font-weight-bold text-dark text-2">Gender</label>
        {{ Form::select('gender',['0' => 'Male', '1' => 'Female'],1,['class'=>'form-control select2','required', 'id' => 'status','placeholder' => '', 'autocomplete' => 'off']) }}

    </div>
</div>
