@extends(!Request::ajax() ? 'web.layout.main' : 'web.layout.main-ajax')

@section('content')

    @yield('content')

@endsection
