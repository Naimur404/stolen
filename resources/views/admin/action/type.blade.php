@if(!empty($row->getRoleNames()))
@foreach($row->getRoleNames() as $v)
   <label class="badge badge-success">{{ $v }}</label>
@endforeach
@else
<label class="badge badge-success">User</label>
@endif
