<?php
$colors = [
    'operational' => 'success',
    'in service' => 'warning',
    'broken' => 'warning',
    'lost' => 'warning',
    'decommissioned' => 'danger',
];
?>
<b>Status:</b> <span class="badge bg-{{ $colors[$tool->status->name] }}">{{ $tool->status->name }}</span><br>
@if ($tool->status_description)
    <b>Status description:</b> {{ $tool->status_description }}<br>
@endif
@if ($tool->status_photo)
    <img src="{{ Storage::url($tool->status_photo) }}" alt="image" style="width: 100px;"><br>
@endif
