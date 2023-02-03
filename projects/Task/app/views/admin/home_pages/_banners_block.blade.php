<fieldset id="banners-container" class="bordered-group grouped-field-list-container">
    <legend>{{{ trans('validation.attributes.banners') }}}
        @if(count($banners) > 0)
            <span class="toggle-expand glyphicon glyphicon-collapse-{{ $errors->has() ? 'up' : 'down' }}"></span>
        @endif
    </legend>
    <div class="form-group @if(count($banners) > 0 && !$errors->has()) dnone @endif">
        <ul class="grouped-field-list" id="banner_list" data-element-list="container">
            @foreach($banners as $key => $banner)
                @include('admin.home_pages._banner_element')
            @endforeach
        </ul>

        <span data-load-element-url="{{{ action('App\Controllers\Admin\HomePagesController@getBannerElement') }}}"
              data-element-list-target="banner_list" data-element-list="add"
              class="btn btn-default btn-xs grouped-field-list-add">Добавить</span>
    </div>
</fieldset>
