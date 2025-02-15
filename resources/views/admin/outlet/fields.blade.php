
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
                {!! Form::label( 'address', 'Address ' ,array('class' => 'form-label')) !!}
                {!! Form::text('address',null,['class'=>'form-control', 'placeholder'=>'Ex: Enter Address']) !!}

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="mb-3">
                {!! Form::label( 'details', 'Details ' ,array('class' => 'form-label')) !!}
                {{ Form::textarea('details', null, array('class' => 'form-control','rows' => 3,'placeholder' => 'Enter Supplier Details')) }}

            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="mb-3">
                    {!! Form::label('is_active_courier_gateway', 'Courier Gateway Status', ['class' => 'form-label']) !!}
                    <div class="form-check">
                        {{ Form::radio('is_active_courier_gateway', 1, $outlet->is_active_courier_gateway == 1, ['class' => 'form-check-input', 'id' => 'active']) }}
                        {{ Form::label('active', 'Active', ['class' => 'form-check-label']) }}
                    </div>
                    <div class="form-check">
                        {{ Form::radio('is_active_courier_gateway', 0, $outlet->is_active_courier_gateway == 0, ['class' => 'form-check-input', 'id' => 'inactive']) }}
                        {{ Form::label('inactive', 'Inactive', ['class' => 'form-check-label']) }}
                    </div>
                </div>
            </div>
        </div>
        
    </div>

</div>
