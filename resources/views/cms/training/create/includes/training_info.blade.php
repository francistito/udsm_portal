<div class="row">
    <div class="col-12">
        {!! Form::label('title', __("label.title"), ['class' => 'required_asterik']) !!}

        {!! Form::text('title',null , ['placeholder' => __('label.title')  ,'id'=>'video_link', 'class' => 'form-control','required']) !!}
        {!! $errors->first('title', '<span class="badge badge-danger">:message</span>') !!}

    </div>
</div>



<div class="row">
    <div class="col-12">
        {!! Form::label('category_id', __("label.type"), ['class' => 'required_asterik']) !!}

        {!! Form::select('category_id',$training_types,null , ['placeholder' => __('label.type')  ,'id'=>'video_link', 'class' => 'form-control','required']) !!}
        {!! $errors->first('category_id', '<span class="badge badge-danger">:message</span>') !!}

    </div>
</div>

<div class="row">
    <div class="col-12">
        {!! Form::label('video_link', __("label.video_link"), ['class' => 'required_asterik']) !!}

        {!! Form::text('video_link',null , ['placeholder' => __('label.video_link')  ,'id'=>'video_link', 'class' => 'form-control','required']) !!}
        {!! $errors->first('video_link', '<span class="badge badge-danger">:message</span>') !!}

    </div>
</div>


<div class="row form-group ">
    <div class="col-sm-12">
        <div class="row">

            <div class="col-xs-6 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    {{ Form::label('training_image', __('label.training_image'), ['class' =>'']) }}<br>
                    {{ Form::file('training_image',[]) }}
                    <br>
                    {!!  $errors->first('training_image', '<span class="badge badge-danger">:message</span>') !!}
                </div>
            </div>


        </div>
    </div>
</div>
<div class="row mt-2" >
    <div class="col-lg-12">
        {!! Form::label('content', __("label.content"), ['class' => 'required_asterik']) !!}

        {!! Form::textarea('content', null, ['class' => 'form-control', 'rows' => '10', 'autocomplete' => 'off', 'id' => 'editor', 'aria-describedby' => 'contentHelp', 'required']) !!}
        {!! $errors->first('content', '<span class="badge badge-danger">:message</span>') !!}
    </div>
</div>

<div class="row form-group ">
    <div class="col-sm-12">
        <div class="row">

            <div class="col-xs-6 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    {{ Form::label('training_document', __('label.training_document'), ['class' =>'']) }}<br>
                    {{ Form::file('training_document',[]) }}
                    <br>
                    {!!  $errors->first('training_document', '<span class="badge badge-danger">:message</span>') !!}
                </div>
            </div>


        </div>
    </div>
</div>

