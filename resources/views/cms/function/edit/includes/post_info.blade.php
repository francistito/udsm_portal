<div class="row">
    <div class="col-12">
        {!! Form::label('title', __("label.title"), ['class' => 'required_asterik']) !!}

        {!! Form::text('title',$function->title , ['placeholder' => __('label.title')  ,'id'=>'title', 'class' => 'form-control','required']) !!}
        {!! $errors->first('title', '<span class="badge badge-danger">:message</span>') !!}                    </div>
</div>


<div class="row">
    <div class="col-12">
        {!! Form::label('service_type_cv_id', __("label.type"), ['class' => 'required_asterik']) !!}

        {!! Form::select('service_type_cv_id',$function_types,$function->service_type_cv_id , ['placeholder' => __('label.title')  ,'id'=>'title', 'class' => 'form-control','required']) !!}
        {!! $errors->first('service_type_cv_id', '<span class="badge badge-danger">:message</span>') !!}

    </div>
</div>

<div class="row form-group ">
    <div class="col-sm-12">
        <div class="row">

            <div class="col-xs-6 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    {{ Form::label('service_image', __('label.logo'), ['class' =>'']) }}<br>
                    {{ Form::file('service_image',[]) }}
                    <br>
                    {!!  $errors->first('service_image', '<span class="badge badge-danger">:message</span>') !!}
                </div>
            </div>


        </div>
    </div>
</div>

<div class="row mt-2" >
    <div class="col-lg-12">
        {!! Form::label('content', __("label.content"), ['class' => 'required_asterik']) !!}

        {!! Form::textarea('content', $function->description, ['class' => 'form-control', 'rows' => '10', 'autocomplete' => 'off', 'id' => 'editor', 'aria-describedby' => 'contentHelp', 'required']) !!}
        {!! $errors->first('content', '<span class="badge badge-danger">:message</span>') !!}

    </div>
</div>
