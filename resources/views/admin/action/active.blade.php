@if ($row->is_active == 1 || $row->has_purchased == 1 || $row->is_paid == 1)

<div class="media-body text-end icon-state">

    <label class="switch">
        <a href="{{ $active }}">
      <input type="checkbox" checked><span class="switch-state"></span>
    </a>
    </label>

  </div>
  @elseif ($row->is_active == 0 || $row->has_purchased == 0 || $row->is_paid == 0)
  <div class="media-body text-end icon-state">

    <label class="switch">
        <a href="{{ $inactive }}">
      <input type="checkbox"><span class="switch-state"></span>
    </a>
    </label>

  </div>
@endif
