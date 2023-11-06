@if(null !== ($headline ?? null))
{{ $headline }}
------------------------------
@endif
@foreach($button as $button)
{!! $button['slot'] !!}: {{ $button['url'] }}
@endforeach
