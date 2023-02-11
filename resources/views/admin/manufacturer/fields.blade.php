
<div class="card-body">
    <div class="row">

        <div class="col">
            <div class="mb-3">
                {!! Form::label('manufacturer_name', 'Manufacturer Name *', array('class' => 'form-label')) !!}
                {!! Form::text('manufacturer_name',null,['class'=>'form-control', 'id' => 'name','placeholder'=>'Manufacturer Name','required' ]) !!}
                @error('manufacturer_name')
                <div class="invalid-feedback2"> {{ $message }}</div>

            @enderror

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="mb-3">
                {!! Form::label('mobile', 'Phone *', array('class' => 'form-label')) !!}
                {!! Form::number('mobile',null,['class'=>'form-control', 'placeholder'=>'Ex: 01712345678' , 'required' ]) !!}
                @error('mobile')
                <div class="invalid-feedback2"> {{ $message }}</div>

            @enderror
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="mb-3">
                {!! Form::label( 'address', 'Address ' ,array('class' => 'form-label')) !!}
                {!! Form::text('address',null,['class'=>'form-control', 'placeholder'=>'Ex: Enter Address']) !!}

            </div>
        </div>
    </div>


</div>
