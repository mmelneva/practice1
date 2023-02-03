<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Merge environments
    |--------------------------------------------------------------------------
    |
    | List of environments, where assets should be merged.
    |
     */
    'merge_environments' => ['production'],

    /*
    |--------------------------------------------------------------------------
    | Groups
    |--------------------------------------------------------------------------
    |
    | Groups of assets to run over a set of filters into an output file.
    | By default, all paths to files begin in the public_path() directory.
    | The order of asset definition is maintained in the output file.
    |
     */

    'groups' => [
        'client_css' => [
            'assets' => [
                'vendor/prettyPhoto/css/prettyPhoto.css',
                'vendor/swiper/css/swiper.min.css',
                'css/client/index.css',
                'css/client/barmain.css',
                'css/client/forms.css',
                'css/client/catalog.css',
                'css/client/filters.css',
                'css/client/chars.css',
                'css/client/checkboxes.css',
                'css/client/jquery.formstyler.css',
                'css/client/jcarousel.responsive.css',
                'css/client/jcarousel.connected-carousels.css',
                'css/client/jquery.royalslider.css',
                'css/client/jquery.slider.range.css',
                'css/client/jquery.tabs_ui.css',
                'css/client/pages.css',
                'css/client/plus-minus.css',
                'css/client/popups.css',
                'css/client/responsive.css',
                'css/client/services.css',
                'css/client/tovar.css',
                'css/client/userbar.css',
                'css/client/grid.css',
            ],
            'filters' => ['css_min', 'embed_css', 'css_url_rebase', 'strip_bom'],
            'output' => 'css/compiled/client.css'
        ],
        'client_js' => [
            'assets' => [
                'vendor/jquery-2.1.3.min.js',
                'vendor/html5shiv.min.js',
                'vendor/jquery-ui.min.js',
                'vendor/jquery.actual.min.js',
                'vendor/jquery.numeric.min.js',
                'vendor/jquery.inputmask/jquery.inputmask.bundle.min.js',
                'vendor/jquery.validate/jquery.validate.min.js',
                'vendor/jquery.validate/additional-methods.min.js',
                'vendor/jquery.validate/additional-methods.ext.js',
                'vendor/jquery.validate/localization/messages_ru.ext.js',
                'vendor/jquery.validate/localization/messages_ru.js',
                'vendor/prettyPhoto/js/jquery.prettyPhoto.js',
                'vendor/swiper/js/swiper.jquery.min.js',
                'js/client/jquery.royalslider-9.5.4.js',
                'js/client/jquery.jcarousel.js',
                'js/client/jcarousel.responsive.js',
                'js/client/jcarousel.connected-carousels.js',
                'js/client/jquery.slider.range.js',
                'js/client/jquery.formstyler-1.6.2.min.js',
                'js/client/height.resize.js',
                'js/common/phone_mask.js',
                'js/client/counters_targeting.js',
                'js/client/start.js',
                'js/client/catalog.js',
//                'js/client/count_inputs_hooks.js',
                'js/client/callback.js',
                'js/client/reviews.js',
                'js/client/order.js',
                'js/client/measurement.js',
                'js/client/product_info_popup.js',

                'js/common/numeric.js',
                'js/common/pretty_photo.js',
            ],
            'filters' => ['js_min'],
            'output' => 'js/compiled/client.js'
        ],
        'admin_css' => [
            'assets' => [
                'vendor/twitter-bootstrap/css/bootstrap.min.css',
                'vendor/font-awesome/css/font-awesome.min.css',
                'vendor/twitter-bootstrap/css/bootstrap-theme.min.css',
                'vendor/prettyPhoto/css/prettyPhoto.css',
                'vendor/datetimepicker/css/jquery.datetimepicker.css',
                'vendor/select2/select2.min.css',
                'vendor/select2/select2-bootstrap.min.css',
                'css/admin/base.css',
                'css/admin/element_list.css',
                'css/admin/forms.css',
                'css/admin/guest.css',
                'css/admin/menu.css',
                'css/admin/admin_users.css',
                'css/admin/admin_roles.css',
                'css/admin/node_structure.css',
                'css/admin/settings.css',
                'css/admin/additional_attributes.css',
                'css/admin/attribute_allowed_values.css',
                'css/admin/orders.css',
                'css/admin/checkbox_tree.css',
                'css/admin/product_type_page.css',
                'css/admin/callbacks.css',
                'css/admin/related_products.css',
                'css/admin/image_gallery.css',
                'css/admin/reviews.css',
            ],
            'filters' => ['css_min', 'embed_css', 'css_url_rebase', 'strip_bom'],
            'output' => 'css/compiled/admin.css'
        ],
        'admin_js' => [
            'assets' => [
                'vendor/jquery-2.1.3.min.js',
                'vendor/jquery-ui.min.js',
                'vendor/jquery.inputmask/jquery.inputmask.bundle.min.js',
                'vendor/twitter-bootstrap/js/bootstrap.min.js',
                'vendor/prettyPhoto/js/jquery.prettyPhoto.js',
                'vendor/tinymce/tinymce.min.js',
                'vendor/datetimepicker/js/jquery.datetimepicker.js',
                'vendor/jquery.numeric.min.js',
                'vendor/select2/select2.min.js',
                'vendor/select2/i18n/ru.js',
                'packages/barryvdh/laravel-elfinder/js/elfinder.min.js',
                'js/admin/bootstrap_customization.js',
                'js/common/pretty_photo.js',
                'js/admin/menu.js',
                'js/admin/hooks.js',
                'js/admin/sortable_tree.js',
                'js/admin/admin_users.js',
                'js/admin/tinymce_init.js',
                'js/admin/structure.js',
                'js/admin/settings.js',
                'js/admin/related_products.js',
                'js/admin/element_list.js',
                'js/admin/attribute_allowed_values.js',
                'js/admin/additional_attributes.js',
                'js/admin/file_upload.js',
                'js/admin/checkbox_tree.js',
                'js/admin/product_type_page.js',
                'js/admin/datetimepicker_init.js',
                'js/admin/catalog.js',
                'js/admin/pagination.js',
                'js/admin/image_gallery.js',
                'js/admin/redirects.js',
                'js/common/numeric.js',
                'js/common/phone_mask.js',
            ],
            'filters' => ['js_min'],
            'output' => 'js/compiled/admin.js'
        ],
    ],
    /*
    |--------------------------------------------------------------------------
    | Filters
    |--------------------------------------------------------------------------
    |
    | Name => class key-values for filters to use.
    | The use of closure based filters are also possible.
    |
     */

    'filters' => [
        'strip_bom' => 'Diol\LaravelAssets\Filter\StripBomFilter',
        'css_min' => 'Assetic\Filter\CssMinFilter',
        'embed_css' => 'Diol\LaravelAssets\Filter\LimitedEmbedCss',
        'css_url_rebase' => 'Diol\LaravelAssets\Filter\CssUrlRebase',
        'js_min' => 'Assetic\Filter\JSMinFilter',
    ],
];
