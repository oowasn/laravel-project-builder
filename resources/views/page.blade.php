@extends("layout.$template")

<!-- #### Area Content ###-->
@section('content')
    @foreach($tabSection AS $sect)
        @php($var = 'data' . ucfirst( camelCase($sect['view'])) )
        @php($$var = $data[$sect['view']] ?? [])
        @php($donnees = $sect)
        @include("sections.$sect[view]", compact($var, 'donnees', 'page'))
    @endforeach
@endsection