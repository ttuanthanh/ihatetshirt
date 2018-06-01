@if(Input::get('type') == 'trash')
<a href="{{ URL::route($view.'.index', query_vars('type=0&s=0')) }}">All ({{ number_format($all) }})</a> | 
<a href="{{ URL::route($view.'.index', query_vars('type=trash&s=0')) }}" class="sbold">Trashed ({{ number_format($trashed) }})</a>
@else
<a href="{{ URL::route($view.'.index', query_vars('type=0&s=0')) }}" class="sbold">All ({{ number_format($all) }})</a> | 
<a href="{{ URL::route($view.'.index', query_vars('type=trash&s=0')) }}">Trashed ({{ number_format($trashed) }})</a>
@endif