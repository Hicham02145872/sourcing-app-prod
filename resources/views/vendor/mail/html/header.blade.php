@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://www.fastsourcingbrothers.com/images/logo1.png" class="logo" alt="Fast Sourcing Brothers Logo" style="width:150px; max-width:100%; height:auto;">
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
