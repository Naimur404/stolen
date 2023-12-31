
<div class="card-body">
    <div class="row">
        <div class="col">
            <div class="mb-3">
                {!! Form::label('manufacturer_name', 'Manufacturer Name *', array('class' => 'form-label')) !!}
                @if(Request::route()->named('supplier.edit'))
                <select name="manufacturer_id[]" multiple="multiple" id="manufacturer_id" class="form-control" required>
                    <div class="invalid-feedback">Please Add Manufacturer </div>
                @foreach ($manufacturers as $manufacturer)
                <option value="{{ $manufacturer->id }}"
                    @foreach ($exist_manufacturer as $exists) {{ $exists->manufacturer_id == $manufacturer->id ? 'selected' : '' }} @endforeach>
                    {{ $manufacturer->manufacturer_name }}</option>
            @endforeach
            <select name="manufacturer_id[]" multiple="multiple" id="manufacturer_id" required>
                <div class="invalid-feedback">Please Add Manufacturer</div>
                @error('manufacturer_id')
                <div class="invalid-feedback2"> {{ $message }}</div>

            @enderror
            @else


                {{ Form::select('manufacturer_id[]', [''], null,['class' => 'form-control', 'id' => 'sel_emp2','multiple'=>'multiple', 'required']) }}
                <div class="invalid-feedback">Please Add Manufacturer</div>

          @endif
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="mb-3">
                {!! Form::label('validationCustomUsername', 'Supplier Name *', array('class' => 'form-label')) !!}
                {!! Form::text('supplier_name',null,['class'=>'form-control', 'id' => 'validationCustomUsername' ,'required','placeholder'=>'Supplier Name' ]) !!}
                @error('supplier_name')
                <div class="invalid-feedback2"> {{ $message }}</div>

            @enderror



            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="mb-3">
                {!! Form::label('mobile', 'Phone *', array('class' => 'form-label')) !!}
                {{ Form::number('mobile', null, array('class' => 'form-control','required','placeholder' => 'Enter Supplier Phone Number')) }}

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
                {{ Form::textarea('address', null, array('class' => 'form-control','rows' => 3,'placeholder' => 'Enter Supplier Address')) }}

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="mb-3">
                {!! Form::label( 'address', 'Details ' ,array('class' => 'form-label')) !!}
                {{ Form::textarea('details', null, array('class' => 'form-control','rows' => 3,'placeholder' => 'Enter Supplier Details')) }}

            </div>
        </div>
    </div>

</div>
