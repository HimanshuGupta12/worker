<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (!empty($logo))
<img src="{{ url('/img/email-logo.png') }}" class="logo" alt="{{ config('app.name') }}" style="width: 170px; height: auto;">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
