

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
                {{-- <div class="col-xl-12 card">

                    <div class="card-header pb-0">
                        <h4 class="card-title mb-0">Permanent Address</h4>
                        <div class="card-options">
                            <a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a>
                        </div>
                    </div>
                    <div class="card-body card">
                        <div class="row">
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    {!! Form::label('name', 'House *', array('class' => 'form-label')) !!}
                                    {!! Form::text('per_house',null,['class'=>'form-control', 'id' => 'name','placeholder'=>'Ex: Mia Bari' ]) !!}
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    {!! Form::label('name', 'Village/Road *', array('class' => 'form-label')) !!}
                                    {!! Form::text('per_village',null,['class'=>'form-control', 'id' => 'name','placeholder'=>'Ex: Musapur' ]) !!}
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    {!! Form::label('name', 'Ward No *', array('class' => 'form-label')) !!}
                                    {!! Form::text('per_ward',null,['class'=>'form-control', 'id' => 'name','placeholder'=>'Ex: 11' ]) !!}
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    {!! Form::label('name', 'PostOffice/Union *', array('class' => 'form-label')) !!}
                                    {!! Form::text('per_post_office',null,['class'=>'form-control', 'id' => 'name','placeholder'=>'Ex: Ali Miea Bazer' ]) !!}
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    {!! Form::label('name', 'Upozila/Thana *', array('class' => 'form-label')) !!}
                                    {!! Form::text('per_thana',null,['class'=>'form-control', 'id' => 'name','placeholder'=>'Ex: Bugura' ]) !!}
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    {!! Form::label('name', 'Zila *', array('class' => 'form-label')) !!}
                                    {!! Form::text('per_zila',null,['class'=>'form-control', 'id' => 'name','placeholder'=>'Ex: Chittagong' ]) !!}
                                </div>
                            </div>


                        </div>
                    </div>


            </div>
                <div class="col-xl-12 card">

                    <div class="card-header pb-0">
                        <h4 class="card-title mb-0">Present Address</h4>
                        <div class="card-options">
                            <a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a>
                        </div>
                    </div>
                    <div class="card-body card">
                        <div class="row">
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    {!! Form::label('name', 'House *', array('class' => 'form-label')) !!}
                                    {!! Form::text('pre_house',null,['class'=>'form-control', 'id' => 'name','placeholder'=>'Ex: Mia Bari' ]) !!}
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    {!! Form::label('name', 'Village/Road *', array('class' => 'form-label')) !!}
                                    {!! Form::text('pre_village',null,['class'=>'form-control', 'id' => 'name','placeholder'=>'Ex: Musapur' ]) !!}
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    {!! Form::label('name', 'Ward No *', array('class' => 'form-label')) !!}
                                    {!! Form::text('pre_ward',null,['class'=>'form-control', 'id' => 'name','placeholder'=>'Ex: 11' ]) !!}
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    {!! Form::label('name', 'PostOffice/Union *', array('class' => 'form-label')) !!}
                                    {!! Form::text('pre_post_office',null,['class'=>'form-control', 'id' => 'name','placeholder'=>'Ex: Ali Miea Bazer' ]) !!}
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    {!! Form::label('name', 'Upozila/Thana *', array('class' => 'form-label')) !!}
                                    {!! Form::text('pre_thana',null,['class'=>'form-control', 'id' => 'name','placeholder'=>'Ex: Bugura' ]) !!}
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    {!! Form::label('name', 'Zila *', array('class' => 'form-label')) !!}
                                    {!! Form::text('pre_zila',null,['class'=>'form-control', 'id' => 'name','placeholder'=>'Ex: Chittagong' ]) !!}
                                </div>
                            </div>
                               <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    {!! Form::label('name', 'Nid No *', array('class' => 'form-label')) !!}
                                    {!! Form::number('nid_no',null,['class'=>'form-control', 'id' => 'name','placeholder'=>'Ex: 542648713' ]) !!}
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    {!! Form::label('name', 'Phone Number *', array('class' => 'form-label')) !!}
                                    {!! Form::text('phone',null,['class'=>'form-control', 'id' => 'name','placeholder'=>'Ex: 01712345678' ]) !!}
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    {!! Form::label('name', 'Email', array('class' => 'form-label')) !!}
                                    {!! Form::email('email',null,['class'=>'form-control', 'id' => 'name','placeholder'=>'Ex: Test@gmail.com' ]) !!}
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="mb-3">
                                    {!! Form::label('name', 'Whatsapp Number', array('class' => 'form-label')) !!}
                                    {!! Form::text('whatsapp_no',null,['class'=>'form-control', 'id' => 'name','placeholder'=>'Ex: 01712345678' ]) !!}
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="mb-3">
                                    {!! Form::label('name', 'Facebook Id', array('class' => 'form-label')) !!}
                                    {!! Form::text('per_zila',null,['class'=>'form-control', 'id' => 'name','placeholder'=>'Ex: m4health' ]) !!}
                                </div>
                            </div>



                        </div>
                    </div>


            </div>


            <div class="col-xl-12 card">

                <div class="card-header pb-0">
                    <h4 class="card-title mb-0">Informer Information</h4>
                    <div class="card-options">
                        <a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a>
                    </div>
                </div>
                <div class="card-body card">
                    <div class="row">
                        <div class="col-sm-6 col-md-4">
                            <div class="mb-3">
                                {!! Form::label('name', 'Name', array('class' => 'form-label')) !!}
                                {!! Form::text('informer_name',null,['class'=>'form-control', 'id' => 'name','placeholder'=>'Ex: Enter Name' ]) !!}
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="mb-3">
                                {!! Form::label('name', 'Phone Number', array('class' => 'form-label')) !!}
                                {!! Form::text('informer_phone',null,['class'=>'form-control', 'id' => 'name','placeholder'=>'Ex: 01712345678' ]) !!}
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="mb-3">
                                {!! Form::label('name', 'M-4 Member Code', array('class' => 'form-label')) !!}
                                {!! Form::text('informer_code',null,['class'=>'form-control', 'id' => 'name','placeholder'=>'Ex: 11' ]) !!}
                            </div>
                        </div>

                    </div>
                </div>


        </div>

        <div class="col-xl-12 card">

            <div class="card-header pb-0">
                <h4 class="card-title mb-0">Other Family Member Information</h4>
                <div class="card-options">
                    <a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a>
                </div>
            </div>
            <div class="card-body card">
                <div class="row">
                    <table class="table table-bordered" id="dynamicAddRemove">
                        <tr>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Relation</th>
                        <th>Remarks</th>

                        </tr>


                        @if (Request::route()->named('card_holder.edit'))

                        @if(count($family_member) > 0)


                        @foreach ($family_member as $data)

                        <tr>
                        <td>
                            <input type="text" name="moreFields[{{$loop->index }}][name]" placeholder="Enter title" class="form-control"  value="{{ $data->name }}"/>
                        </td>
                        <td>
                            <input type="text" name="moreFields[{{ $loop->index}}][age]" placeholder="Enter title" class="form-control"  value="{{ $data->age }}"/>
                        </td>
                        <td>
                            <input type="text" name="moreFields[{{ $loop->index}}][rlsn]" placeholder="Enter title" class="form-control"  value="{{ $data->rlsn }}"/></td>
                        <td> <input type="text" name="moreFields[{{ $loop->index }}][remarks]" placeholder="Enter title" class="form-control"  value="{{ $data->remarks }}"/></td>
                        <td><a href="{{ route('delete_family',$data->id) }}" name="add" id="" class="btn btn-danger">Delete</a></td>

                        @endforeach



                          <td><button type="button" name="add" id="add-btn" class="btn btn-success">Add More</button></td>
                </tr>
                        @else

                        <tr>
                            <td>
                                {!! Form::text('moreFields[0][name]',null,['class'=>'form-control', 'id' => 'name','placeholder'=>'Ex: Enter Name' ]) !!}</td>
                            <td>
                                {!! Form::number('moreFields[0][age]',null,['class'=>'form-control', 'id' => 'name','placeholder'=>'Ex: Enter Age' ]) !!}
                            </td>
                            <td>
                                {!! Form::text('moreFields[0][rlsn]',null,['class'=>'form-control', 'id' => 'name','placeholder'=>'Ex: Enter Relation' ]) !!}</td>
                            <td> {!! Form::text('moreFields[0][remarks]',null,['class'=>'form-control', 'id' => 'name','placeholder'=>'Ex: Enter Remarks' ]) !!}</td>
                        <td><button type="button" name="add" id="add-btn" class="btn btn-success">Add More</button></td>
                        </tr>
                        @endif

                        @else

                        <tr>
                            <td>
                                {!! Form::text('moreFields[0][name]',null,['class'=>'form-control', 'id' => 'name','placeholder'=>'Ex: Enter Name' ]) !!}</td>
                            <td>
                                {!! Form::number('moreFields[0][age]',null,['class'=>'form-control', 'id' => 'name','placeholder'=>'Ex: Enter Age' ]) !!}
                            </td>
                            <td>
                                {!! Form::text('moreFields[0][rlsn]',null,['class'=>'form-control', 'id' => 'name','placeholder'=>'Ex: Enter Relation' ]) !!}</td>
                            <td> {!! Form::text('moreFields[0][remarks]',null,['class'=>'form-control', 'id' => 'name','placeholder'=>'Ex: Enter Remarks' ]) !!}</td>
                        <td><button type="button" name="add" id="add-btn" class="btn btn-success">Add More</button></td>
                        </tr>
                        @endif

                        </table>

                </div>
            </div>


    </div>

  <div class="col-xl-12 card">

        <div class="card-header pb-0">
            <h4 class="card-title mb-0">Payment's Information</h4>
            <div class="card-options">
                <a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a>
            </div>
        </div>
        <div class="card-body card">
            <div class="row">
                <div class="col-sm-6 col-md-6">
                    <div class="mb-3">
                @if(Request::route()->named('card_holder.edit'))
                {{ Form::label('employee_id', 'Officer In Charge *',array('class' => 'form-label')) }}
                @php
                $datas =  App\Models\EmployeeManagement::find($cardHolder->employee_id);
                $data = $datas->id;
                 $name = $datas->name;

              @endphp

              @if (!is_null($data))

         {{ Form::select('employee_id',[$data=>$name],null,['required', 'id' => 'sel_emp', 'class' => 'form-control'] ) }}

              @endif


          @else
      {{ Form::label('employee_id', 'Employee *',array('class' => 'form-label')) }}


      {{ Form::select('employee_id',[''],null,['placeholder' => '--please select--', 'required', 'id' => 'sel_emp', 'class' => 'form-control'] ) }}

@endif
                    </div>
                </div>
                <div class="col-sm-6 col-md-6">
                    <div class="mb-3">
                {!! Form::label( 'follow_up','Date *', array('class' => 'form-label')) !!}
                {!! Form::date('date',\carbon\carbon::now(),['class'=>'form-control digits','data-position' => 'top left','data-language' =>'en','data-multiple-dates-separator' => ', ' ,'data-bs-original-title' => '','placeholder'=>'Ex: 01/01/2023']) !!}
            </div>
        </div>

        <div class="col-sm-6 col-md-4">
            <div class="mb-3">
        {!! Form::label( 'pakage','Package *', array('class' => 'form-label')) !!}
        <select name="package_id" id="package_id" class="form-control">
            @if(Request::route()->named('card_holder.edit'))
        <option value="" > -- Select One --</option>
        @foreach ($package as $data1)
        @if ($cardHolder->package_id == $data1->id)
        <option value="{{ $data1->id }}"  selected>{{ $data1->name }}</option>
        @else
        <option value="{{ $data1->id }}"  >{{ $data1->name }}</option>
        @endif
        @endforeach
        @else
        <option value="" > -- Select One --</option>
        @foreach ($package as $data1)

        <option value="{{ $data1->id }}"  >{{ $data1->name }}</option>

        @endforeach
        @endif
        </select>
    </div>
</div>
@if(Request::route()->named('card_holder.edit'))
@php
$data =  App\Models\HealthCardPackage::find($cardHolder->package_id)
@endphp
<div class="col-sm-6 col-md-4">
    <div class="mb-3">
{!! Form::label( 'follow_up','Price *', array('class' => 'form-label')) !!}
{!! Form::number('price',$data->price,['class'=>'form-control ', 'placeholder'=>'Ex: value' ,'id' =>'price','disabled' => 'disabled']) !!}
</div>
</div>

   <div class="col-sm-6 col-md-4">
                    <div class="mb-3">
                {!! Form::label( 'follow_up','Validity *', array('class' => 'form-label')) !!}
                {!! Form::number('validity',$data->validity,['class'=>'form-control', 'placeholder'=>'Ex: 365', 'id'=>'validity','disabled' => 'disabled']) !!}
            </div>
        </div>
        @else
        <div class="col-sm-6 col-md-4">
            <div class="mb-3">
        {!! Form::label( 'follow_up','Price *', array('class' => 'form-label')) !!}
        {!! Form::number('price','',['class'=>'form-control ', 'placeholder'=>'Ex: value' ,'id' =>'price','disabled' => 'disabled']) !!}
        </div>
        </div>

           <div class="col-sm-6 col-md-4">
                            <div class="mb-3">
                        {!! Form::label( 'follow_up','Validity *', array('class' => 'form-label')) !!}
                        {!! Form::number('validity','',['class'=>'form-control', 'placeholder'=>'Ex: 365', 'id'=>'validity','disabled' => 'disabled']) !!}
                    </div>
                </div>
                @endif

        <div class="col-sm-6 col-md-6">
            <div class="mb-3">
        {!! Form::label( 'pakage','Payment Method *', array('class' => 'form-label')) !!}
        <select name="payment_id" id="payment_id" class="form-control">
            @if(Request::route()->named('card_holder.edit'))
           @php
             $data =  App\Models\Transaction::find($cardHolder->transaction_id)
           @endphp
        <option value="" > -- Select One --</option>
        @foreach ($payment as $data2)
        @if ($data->payment_method == $data2->method_name)
        <option value="{{ $data2->method_name }}" selected>{{ $data2->method_name }}</option>

@else
<option value="{{ $data2->method_name }}">{{ $data2->method_name }}</option>
@endif
@endforeach
@else
<option value="" > -- Select One --</option>
@foreach ($payment as $data2)

<option value="{{ $data2->method_name }}">{{ $data2->method_name }}</option>
@endforeach
@endif

        </select>
    </div>
</div>
<div class="col-sm-6 col-md-6">
    <div class="mb-3">
@if(Request::route()->named('card_holder.edit'))
@php
 $data =  App\Models\Transaction::find($cardHolder->transaction_id)
 @endphp
{!! Form::label( 'follow_up','Payment *', array('class' => 'form-label')) !!}
{!! Form::number('payment',$data->pay,['class'=>'form-control', 'placeholder'=>'Ex: 365', 'id'=>'validity',]) !!}
@else
{!! Form::label( 'follow_up','Payment *', array('class' => 'form-label')) !!}
{!! Form::number('payment',null,['class'=>'form-control', 'placeholder'=>'Ex: 365', 'id'=>'validity',]) !!}
@endif
</div>
</div>
            </div>

        </div>


            </div>
	        </div>
	    </div> --}}
