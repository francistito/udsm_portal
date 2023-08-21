
<div id="accordion">
    <div class="card">
        <div class="card-header" id="headingTitle">
            <h5 class="mb-0">
                <a class="btn btn-link" data-toggle="collapse" data-target="#title" aria-expanded="true" aria-controls="title">
                    Slider title
                </a>
            </h5>
        </div>

        <div id="title" class="collapse show" aria-labelledby="title" data-parent="#title">
            <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            {!! Form::label('title', __("label.title"), ['class' => 'required_asterik']) !!}

                            {!! Form::text('title',null , ['placeholder' => __('label.title')  ,'id'=>'video_link', 'class' => 'form-control','required']) !!}
                            {!! $errors->first('title', '<span class="badge badge-danger">:message</span>') !!}

                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-6">
                            {!! Form::label('size','Size', ['class' => 'required_asterik']) !!}

                            {!! Form::text('size',null , ['placeholder' => __('Size')  ,'id'=>'video_link', 'class' => 'form-control number','number']) !!}
                            <span class="badge ">eg in pixes</span>

                            {!! $errors->first('size', '<span class="badge badge-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-6">
                            {!! Form::label('color','Color', ['class' => 'required_asterik']) !!}

                            {!! Form::text('color',null , ['placeholder' => __('Color')  ,'id'=>'video_link', 'class' => 'form-control','required']) !!}
                            {!! $errors->first('color', '<span class="badge badge-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            {!! Form::label('position', 'Position', ['class' => 'required_asterik']) !!}

                            {!! Form::text('position',null , ['placeholder' => 'Position'  ,'id'=>'video_link', 'class' => 'form-control','required']) !!}
                            <span class="badge ">eg left or right</span>

                            {!! $errors->first('position', '<span class="badge badge-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            {{ Form::label('isactive', __('label.status'), ['class' =>'required_asterik']) }}
                            {!! Form::select('isactive',['0' => 'In active', '1' => 'Active'],1,['class'=>'form-control select2', 'id' => 'isactive','required','placeholder' => '', 'autocomplete' => 'off']) !!}
                            {!!  $errors->first('isactive', '<span class="badge badge-danger">:message</span>')  !!}
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>


<div id="accordion">
    <div class="card">
        <div class="card-header" id="headingDescription">
            <h5 class="mb-0">
                <a class="btn btn-link" data-toggle="collapse" data-target="#description" aria-expanded="true" aria-controls="description">
                   Slider description
                </a>
            </h5>
        </div>

        <div id="description" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
            <div class="card-body">

                    <div class="row">
                        <div class="col-lg-12">
                            {!! Form::label('description', __("label.content"), ['class' => 'required_asterik']) !!}

                            {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => '10', 'autocomplete' => 'off', 'id' => 'editor', 'aria-describedby' => 'contentHelp', 'required']) !!}
                            {!! $errors->first('description', '<span class="badge badge-danger">:message</span>') !!}
                        </div>


                    </div>
                    <div class="row">

                        <div class="col-md-6">
                                {!! Form::label('description_size','Size', ['class' => 'required_asterik']) !!}

                                {!! Form::text('description_size',null , ['placeholder' => __('Size')  ,'id'=>'video_link', 'class' => 'form-control number','number']) !!}
                                <span class="badge ">eg in pixes</span>

                                {!! $errors->first('size', '<span class="badge badge-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-6">
                            {!! Form::label('description_color','Color', ['class' => 'required_asterik']) !!}

                            {!! Form::text('description_color',null , ['placeholder' => __('Color')  ,'id'=>'video_link', 'class' => 'form-control','required']) !!}
                            {!! $errors->first('color', '<span class="badge badge-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            {!! Form::label('position', 'Position', ['class' => 'required_asterik']) !!}

                            {!! Form::text('position',null , ['placeholder' => 'Position'  ,'id'=>'video_link', 'class' => 'form-control','required']) !!}
                            <span class="badge ">eg left or right</span>

                            {!! $errors->first('position', '<span class="badge badge-danger">:message</span>') !!}
                        </div>
                    </div>

            </div>
        </div>
    </div>
</div>




<div id="accordion">
    <div class="card">
        <div class="card-header" id="headingButton">
            <h5 class="mb-0">
                <a class="btn btn-link" data-toggle="collapse" data-target="#button" aria-expanded="true" aria-controls="button">
                    Slider button
                </a>
            </h5>
        </div>

        <div id="button" class="collapse" aria-labelledby="button" data-parent="#button">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        {!! Form::label('button_title', __("Button title"), ['class' => 'required_asterik']) !!}

                        {!! Form::text('button_title',null , ['placeholder' => __('')  ,'id'=>'video_link', 'class' => 'form-control','required']) !!}
                        {!! $errors->first('button_title', '<span class="badge badge-danger">:message</span>') !!}

                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        {!! Form::label('button_link', __("Button link"), ['class' => 'required_asterik']) !!}

                        {!! Form::text('button_link',null , ['placeholder' => __('')  ,'id'=>'video_link', 'class' => 'form-control','required']) !!}
                        {!! $errors->first('button_link', '<span class="badge badge-danger">:message</span>') !!}

                    </div>
                </div>

                <div class="row">

                    <div class="col-md-6">
                        {!! Form::label('button_color','Color', ['class' => 'required_asterik']) !!}

                        {!! Form::text('button_color',null , ['placeholder' => __('')  ,'id'=>'video_link', 'class' => 'form-control','required']) !!}
                        {!! $errors->first('button_color', '<span class="badge badge-danger">:message</span>') !!}
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>

<div id="accordion">
    <div class="card">
        <div class="card-header" id="headingImages">
            <h5 class="mb-0">
                <a class="btn btn-link" data-toggle="collapse" data-target="#image" aria-expanded="true" aria-controls="image">
                   Slider Image
                </a>
            </h5>
        </div>

        <div id="image" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
            <div class="card-body">

                    <div class="row">
                        <div class="col-lg-12">
                            {{ Form::label('slider_image', __('label.image'), ['class' =>'']) }}<br>
                            {{ Form::file('slider_image',[]) }}
                            <br>
                            {!!  $errors->first('slider_image', '<span class="badge badge-danger">:message</span>') !!}
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>



