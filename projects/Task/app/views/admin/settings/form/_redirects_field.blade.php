<div id="redirectsContainer">
    <div class="row" style="padding-top: 4px;">
        <div class="col-sm-4"><label>Правило</label></div>
        <div class="col-sm-1"></div>
        <div class="col-sm-4"><label>Ссылка</label></div>
        <div class="col-sm-1"></div>
    </div>

    <div>
        <div data-element-list="rules">
            @if ($rows = json_decode($setting->value, true))
                <?php $index = 0; ?>
                @foreach($rows as $rule => $url)
                    @include('admin.settings.form._redirects_field._row', ['rule' => $rule, 'url' => $url])
                    <?php $index++; ?>
                @endforeach
            @else
                @include('admin.settings.form._redirects_field._row', ['index' => 0])
            @endif
        </div>

        @include('admin.settings.form._redirects_field._template_row')
        <div class="row">
            <div class="col-sm-9"></div>
            <div class="col-sm-1">
                <button type="button" class="btn btn-default" data-rule-action="add"><i class="fa fa-plus"></i></button>
            </div>
        </div>
    </div>
</div>