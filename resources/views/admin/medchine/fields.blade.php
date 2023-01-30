

	    <div class="edit-profile">
	        <div class="row">
                <div class="col-xl-6 card">

	                    <div class="card-header pb-0">
	                        <h4 class="card-title mb-3">Add Medicine</h4>
	                        <div class="card-options">
	                            <a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a>
	                        </div>
	                    </div>
	                    <div class="card-body card">
	                        <div class="row">
	                            <div class="col-sm-12 col-md-12">
	                                <div class="mb-3">
                                        {!! Form::label('name', 'Medicine Name *', array('class' => 'form-label')) !!}
                                        {!! Form::text('medicine_name',null,['class'=>'form-control', 'id' => 'name','placeholder'=>'Enter Medicine Name' ]) !!}

	                                </div>
	                            </div>
	                            <div class="col-sm-12 col-md-12">
	                                <div class="mb-3">
                                        {!! Form::label('age', 'Strength', array('class' => 'form-label')) !!}
                                        {!! Form::text('strength',null,['class'=>'form-control', 'id' => 'age','placeholder'=>'Strength' ]) !!}

	                                </div>
	                            </div>

	                            <div class="col-sm-12 col-md-12">
	                                <div class="mb-3">
                                        @if(Request::route()->named('medicine.edit'))
                                        @php
                                        $datas =  App\Models\Unit::find($medicine->unit_id);
                                        $data = $datas->id;
                                         $name = $datas->unit_name;
                                         @endphp
	                               {{ Form::label('unit_id', 'Unit *') }}
                                  {{ Form::select('unit_id',[$data=>$name] , null, ['class' => 'form-control', 'placeholder' => '--please select--', 'required', 'id' => 'sel_emp2']) }}
                                  @else
                                  {{ Form::label('unit_id', 'Unit *') }}
                                  {{ Form::select('unit_id',[''] , null, ['class' => 'form-control', 'placeholder' => '--please select--', 'required', 'id' => 'sel_emp2']) }}
                                  @endif
	                                </div>
	                            </div>
	                            <div class="col-sm-12 col-md-12">
	                                <div class="mb-3">
                                         @if(Request::route()->named('medicine.edit'))
                                        @php
                                        $datas =  App\Models\Category::find($medicine->category_id);
                                        $data = $datas->id;
                                         $name = $datas->category_name;
                                         @endphp
                                         {{ Form::label('category_id', 'Category *') }}
                                        {{ Form::select('category_id',[$data=>$name] , null, ['class' => 'form-control', 'placeholder' => '--please select--', 'required', 'id' => 'sel_emp4']) }}

                                      @else
                                      {{ Form::label('category_id', 'Category *') }}
                                      {{ Form::select('category_id',[''] , null, ['class' => 'form-control', 'placeholder' => '--please select--', 'required', 'id' => 'sel_emp4']) }}
                                    @endif
	                            </div>
                                </div>
                                <div class="col-sm-12 col-md-12">
	                                <div class="mb-3">
                                        {!! Form::label('price', 'Price *', array('class' => 'form-label')) !!}
                                        {!! Form::number('price',null,['class'=>'form-control', 'id' => 'name','placeholder'=>'Enter Guardian Name','step' => '0.1' ]) !!}
	                                </div>
	                            </div>

	                            <div class="col-sm-12 col-md-12">
	                                <div class="mb-3">
                                        @if(Request::route()->named('medicine.edit'))
                                        @php
                                        $datas =  App\Models\Manufacturer::find($medicine->manufacturer_id);
                                        $data = $datas->id;
                                         $name = $datas->manufacturer_name;
                                         @endphp
                                        {{ Form::label('manufacturer_id', 'Manufacturer Name *') }}
                                        {{ Form::select('manufacturer_id', [$data=>$name], null,['class' => 'form-control', 'placeholder' => '--please select--', 'required' , 'id' => 'sel_emp1']) }}
                                        @else
                                        {{ Form::label('manufacturer_id', 'Manufacturer Name *') }}
                                        {{ Form::select('manufacturer_id', [''], null,['class' => 'form-control', 'placeholder' => '--please select--', 'required' , 'id' => 'sel_emp1']) }}
                                     @endif
	                                </div>
	                            </div>




	                        </div>
	                    </div>


	            </div>
                <div class="col-xl-6 card">

                    <div class="card-body card">
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="mb-3">
                                    {!! Form::label('tax', 'Vat ', array('class' => 'form-label')) !!}
                                    {!! Form::number('tax',null,['class'=>'form-control', 'id' => 'name','placeholder'=>'0.00%','step' => '0.1' ]) !!}
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12">
                                <div class="mb-3">
                                    {!! Form::label('generic_name', 'Generic Name *', array('class' => 'form-label')) !!}
                                    {!! Form::text('generic_name',null,['class'=>'form-control', 'id' => 'name','placeholder'=>'Enter Generic Name' ]) !!}

                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12">
                                <div class="mb-3">
                                    {!! Form::label('box_size', 'Box Size', array('class' => 'form-label')) !!}
                                    {!! Form::number('box_size',null,['class'=>'form-control', 'id' => 'age','placeholder'=>'Enter Box Size' ]) !!}

                                </div>
                            </div>

                            <div class="col-sm-12 col-md-12">
                                <div class="mb-3">
                                    {!! Form::label('medicine_details', 'Medicine Details', array('class' => 'form-label')) !!}
                                    {!! Form::text('medicine_details',null,['class'=>'form-control', 'id' => 'name','placeholder'=>'Enter Medicine Details','step' => '0.1' ]) !!}
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12">
                                <div class="mb-3">
                                    @if(Request::route()->named('medicine.edit'))
                                        @php
                                        $datas =  App\Models\Type::find($medicine->type_id);

                                        @endphp
      @if (!is_null($datas))
      @php
         $data = $datas->id;
         $name = $datas->type_name;
      @endphp
      @endif
                                    {{ Form::label('type_id', 'Medicine Type *') }}
                                    {{ Form::select('type_id', [$data=>$name], null,['class' => 'form-control', 'placeholder' => '--please select--', 'id' => 'sel_emp3']) }}


                                    @else
                                    {{ Form::label('type_id', 'Medicine Type *') }}
                                    {{ Form::select('type_id', [''], null,['class' => 'form-control', 'placeholder' => '--please select--', 'id' => 'sel_emp3']) }}
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12">

                                    @if(Request::route()->named('medicine.edit'))
                                    @if (file_exists(('uploads/'.$medicine->image)) && !is_null($medicine->image))
                                    <div class="mb-3 ">
                                        {!! Form::label('image', 'Existing Image', array('class' => 'col-sm-3 col-form-label')) !!}
										{{-- <label class="col-sm-3 col-form-label">Existing Logo</label> --}}
										<div class="col-sm-9">
                                            <div>
                                                <img src="{{ asset('uploads/'.$medicine->image) }}" alt="" class="w_300" width="200" height="200" >
                                            </div>
										</div>
									</div>
                                    @endif
                                    <div class="mb-3">
                                    {!! Form::label('image', 'Image', array('class' => 'form-label')) !!}


                                    {!! Form::file('image',['class'=>'form-control' ]) !!}
                                </div>
                                @else
                                <div class="mb-3">
                                {!! Form::label('image', 'Image', array('class' => 'form-label')) !!}


                                {!! Form::file('image',['class'=>'form-control' ]) !!}
                                </div>
                                @endif


                            </div>
                            <div class="col-sm-12 col-md-12">
                                <div class="mb-3">
                                    {!! Form::label('manufacturer_price', 'Manufacturer Price', array('class' => 'form-label')) !!}
                                    {!! Form::number('manufacturer_price',null,['class'=>'form-control', 'id' => 'age','placeholder'=>'0.00' ,'step' => '0.1']) !!}

                                </div>
                            </div>

                        </div>
                    </div>


            </div>
            </div>
        </div>
 