<ul id="publisher-list">
@foreach ($data as $publishers)
 

    <li>{{ $publishers['displayName'] }}</li>

  
@endforeach
</ul>