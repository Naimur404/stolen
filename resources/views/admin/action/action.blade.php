<div class="btn-group">
    @if (request()->routeIs('payment_method.index'))
   {{-- @can($permission) --}}


    <button class="btn btn-primary btn-xs open-modal" value="{{ $id }}">Edit</button>
    {{-- @endcan --}}

    @else
    <form action="{{ $edit }}" method="GET">

        <button type="submit" class="btn btn-primary btn-xs open-modal">Edit</button>
      </form>
    @endif



<!-- Button trigger modal -->
{{-- @can($permissiondelete) --}}
<button type="button" class="btn btn-danger btn-xs" data-bs-toggle="modal" data-bs-target="#staticBackdrop{{ $id }}">
  Delete
  </button>
{{-- @endcan --}}
  <!-- Modal -->
  <div class="modal fade" id="staticBackdrop{{ $id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">Are You Sure want To Delete?</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        {{-- <div class="modal-body">
          ...
        </div> --}}
        <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-xs" data-bs-dismiss="modal">Close</button>
          @if (request()->routeIs('payment_method.index') || request()->routeIs('employee_management.index') || request()->routeIs('customer_lead.index') || request()->routeIs('card_holder.index') || request()->routeIs('health-service-log.index'))

            <form action="{{ $delete }}" method="POST">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}

                <button type="submit" class="btn btn-danger btn-xs">Parmanent Delete</button>
                    </form>

          @else
          <form action="{{ $delete }}" method="GET">

            <button type="submit" class="btn btn-danger btn-xs">Parmanent Delete</button>
                </form>
          @endif

        </div>
      </div>
    </div>
  </div>

</div>
 {{-- <a href="{{ $delete }}" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-pill btn-danger btn-sm">Delete</a> --}}
