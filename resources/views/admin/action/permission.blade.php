@foreach($row->permissions as $data)
<span class="badge badge-info" style="color: white">{{ $data->name }}</span>
@endforeach

