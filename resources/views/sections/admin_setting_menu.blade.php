@section('other-section')
<ul class="nav tabs-vertical">

    @foreach($subMenuSettings as $menu)
        @if(in_array($menu['module'], $modules) && $menu['hidden'] != 1 || $menu['module'] == 'visibleToAll' && $menu['hidden'] != 1 )
            @if($menu['menu_name'] != 'updates' || $menu['hidden'] != 1)
            
                <li class="tab @if(\Illuminate\Support\Facades\Route::currentRouteName() == $menu['route']) active @endif">
                    <a href="{{ isset($menu['children']) ? route($menu['children'][0]['route']) :  route($menu['route']) }}">@lang($menu['translate_name'])</a></li>
                   
                    @else
                @if($global->system_update == 1 || $menu['hidden'] != 1 )
                    <li class="tab @if(\Illuminate\Support\Facades\Route::currentRouteName() == $menu['route']) active @endif">
                        <a href="{{ route($menu['route']) }}">@lang($menu['translate_name'])</a></li>
                @endif
            @endif
        @endif
    @endforeach
</ul>

<script src="{{ asset('plugins/bower_components/jquery/dist/jquery.min.js') }}"></script>
<script>
    var screenWidth = $(window).width();
    if(screenWidth <= 768){

        $('.tabs-vertical').each(function() {
            var list = $(this), select = $(document.createElement('select')).insertBefore($(this).hide()).addClass('settings_dropdown form-control');

            $('>li a', this).each(function() {
                var target = $(this).attr('target'),
                    option = $(document.createElement('option'))
                        .appendTo(select)
                        .val(this.href)
                        .html($(this).html())
                        .click(function(){
                            if(target==='_blank') {
                                window.open($(this).val());
                            }
                            else {
                                window.location.href = $(this).val();
                            }
                        });

                if(window.location.href == option.val()){
                    option.attr('selected', 'selected');
                }
            });
            list.remove();
            $('.filter-section').show()
        });

        $('.settings_dropdown').change(function () {
            window.location.href = $(this).val();
        })

    }
</script>
@endsection
