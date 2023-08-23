
<div class="row">
    <div class="col-md-10 ">
        <div>
            <p class=" text-5" style="color: #080809">ENTER REFERENCE NUMBER AFTER MAKE PAYMENT {{--{{ str_replace("+", "", $user->phone_number) }}--}}</p>
        </div>

        <div class="form-group">
            <div class="input-group mb-2">
                <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" id="phone_number" name="reference_number" placeholder="@lang('label.reference_number')" required>
                @if ($errors->has('phone_number'))
                    <span class="invalid-feedback" role="alert">
                                    <strong style="color: red">{{ $errors->first('phone_number') }}</strong>
                                    </span>
                @endif
            </div>
        </div>

    </div>
</div>
