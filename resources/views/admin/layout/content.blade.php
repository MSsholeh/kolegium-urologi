@extends(!Request::ajax() ? 'admin.layout.main' : 'admin.layout.main-ajax')

@section('content')

    @yield('content')

@endsection
