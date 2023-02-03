<?php namespace App\Services\Providers;

use Illuminate\Support\ServiceProvider;

class HtmlBuilderServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }

    public function boot()
    {
        $this->initAdditionalMenu();
    }


    /**
     * Init helpers for additional menu.
     */
    private function initAdditionalMenu()
    {
        $optionStack = [];

        \HTML::macro(
            'additionalMenuOpen',
            function (array $options) use (&$optionStack) {
                array_push($optionStack, $options);

                $attrArray = [];
                $resizeOpt = 'resize-menu-' . array_get($options, 'resize');
                if (!is_null($resizeOpt)) {
                    $resizeWidth = array_get($_COOKIE, $resizeOpt);
                    if (!is_null($resizeWidth)) {
                        $attrArray[] = "style='width: {$resizeWidth}px'";
                    }

                    $attrArray[] = "data-menu-resize='{$resizeOpt}'";
                }

                $attrString = implode(' ', $attrArray);

                return "<div class='menu-column additional-menu' {$attrString}>";
            }
        );

        \HTML::macro(
            'additionalMenuClose',
            function () use (&$optionStack) {
                $options = array_pop($optionStack);
                $ret = '</div>';

                if (!is_null(array_get($options, 'resize'))) {
                    $ret .= '<div class="menu-column additional-menu-resize"></div>';
                }

                return $ret;
            }
        );
    }
}
