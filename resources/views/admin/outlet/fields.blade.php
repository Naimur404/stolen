
<div class="card-body">
    <div class="row">

        <div class="col">
            <div class="mb-3">
                {!! Form::label('outlet_name', 'Outlet Name *', array('class' => 'form-label')) !!}
                {!! Form::text('outlet_name',null,['class'=>'form-control', 'id' => 'name','placeholder'=>'Outlet Name' ,'required' ]) !!}
                @error('outlet_name')
                <div class="invalid-feedback2"> {{ $message }}</div>
            @enderror

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="mb-3">
                {!! Form::label('mobile', 'Phone *', array('class' => 'form-label')) !!}
                {!! Form::number('mobile',null,['class'=>'form-control', 'placeholder'=>'Ex: 01712345678','required' ]) !!}
                @error('mobile')
                <div class="invalid-feedback2"> {{ $message }}</div>
            @enderror
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="mb-3">
                {!! Form::label( 'address', 'Address *' ,array('class' => 'form-label')) !!}
                {!! Form::text('address',null,['class'=>'form-control', 'placeholder'=>'Ex: Enter Address']) !!}

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="mb-3">
                {!! Form::label( 'details', 'Details *' ,array('class' => 'form-label')) !!}
                {{ Form::textarea('details', null, array('class' => 'form-control','rows' => 3,'placeholder' => 'Enter Supplier Details')) }}

            </div>
        </div>
    </div>

</div>
