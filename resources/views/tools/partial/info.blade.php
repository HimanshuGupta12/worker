<b>ID:</b> {{ /*isset($tool->tool_code) ? explode('-', $tool->tool_code)[1] : $tool->company_tool_id*/ $tool->company_tool_id }}<br>
<b>Name:</b> {{ $tool->name }}<br>
<b>Model:</b> {{ $tool->model }}<br>
<b>Belongs to:</b> {{ $tool->possessor?->possessorName() }}<br>
<b>Category:</b> {{ $tool->category?->name }}<br>
<b>Price:</b> {{ $tool->price }}<br>
<b>Added:</b> {{ $tool->created_at->format(dateFormat()) }}<br>
<b>Next inventorization:</b> {{ $tool->next_inventorization_at?->format(dateFormat()) }}<br>
<b>Inventoried:</b> {{ $tool->inventoried_at?->format(dateTimeFormat()) }}<br>
<b>Last changed:</b> {{ $tool->updated_at->format(dateTimeFormat()) }}<br>
<b>Purchased:</b> {{ $tool->purchased_at?->format(dateFormat()) }}<br>
@include('partial.color_status', compact('tool'))
@if ($tool->showUnbalanced())
    <b>Balancing:</b> <span class="badge bg-danger">Not balanced</span>
@elseif ($tool->showBalanced())
    <b>Balancing:</b> <span class="badge bg-success">Balanced</span>
@endif
