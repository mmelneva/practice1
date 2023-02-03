(function ($, window) {
    $(function () {
        window.tinymceInit = function() {
            /**
             * Glue between tinymce and elfinder
             * @param fieldName
             * @param url
             * @param type
             * @param win
             * @returns {boolean}
             */
             function elFinderBrowser (fieldName, url, type, win) {
                tinymce.activeEditor.windowManager.open({
                    file: '/cc/elfinder/tinymce',// use an absolute path!
                    title: 'Файловый менеджер',
                    width: 900,
                    height: 450
                }, {
                    setUrl: function (url) {
                        win.document.getElementById(fieldName).value = url;
                    }
                });
                return false;
            }

            // Init tinymce
            var initData = {
                selector: '[data-tinymce]',
                menubar: false,
                plugins: "image,link,code,table",
                toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify |  bullist numlist outdent indent | table link unlink image | removeformat code",
                relative_urls: false,
                language : "ru",
                theme: "modern",
                skin: "lightgray",
                file_browser_callback : elFinderBrowser,
                allow_script_urls: true,
                extended_valid_elements: "span[id|class|title|style|data*],script[*],b,i",
                language_url: "/vendor/tinymce/langs/ru.js",
                theme_url: "/vendor/tinymce/themes/modern/theme.min.js",
                skin_url: "/vendor/tinymce/skins/lightgray",
                external_plugins: {
                    image: "/vendor/tinymce/plugins/image/plugin.min.js",
                    link: "/vendor/tinymce/plugins/link/plugin.min.js",
                    code: "/vendor/tinymce/plugins/code/plugin.min.js",
                    table: "/vendor/tinymce/plugins/table/plugin.min.js"
                }
            };

            tinymce.init(initData);
        };

        tinymceInit();
    });
})(jQuery, window);
