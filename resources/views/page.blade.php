@extends("layout.$template")

    <!-- #### Area Content ###-->
    @section('contenu')
        @foreach($tabSection AS $sect)
            @php($var = 'data' . ucfirst(camelCase($sect['vue'])))
            @php($$var = $data[$sect['vue']] ?? [])
            @php($donnees = $sect)
            @include("sections.$sect[vue]", compact($var, 'donnees', 'page'))
        @endforeach
    @endsection