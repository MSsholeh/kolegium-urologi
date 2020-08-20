@yield('content')

<script type="text/javascript">
    $(document).ready(function() {
        Daster.initAjax();
    });
</script>

@stack('scripts')
