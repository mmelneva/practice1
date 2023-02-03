<?php namespace App\Services\Filters;

/**
 * Class RedirectsFilter
 * @package App\Services\Filters
 */
class RedirectsFilter
{
    public function filter()
    {
        if (!\Request::ajax() && !in_array(\Request::method(), ['POST', 'PUT', 'DELETE'])){
            $rules = $this->getRuleList();
            $path = $this->normalizePath(\Request::getRequestUri());

            foreach ($rules as $rule => $redirectTo) {
                if (@preg_match("@{$rule}@", $path)) {
                    return \Redirect::to($redirectTo, 301);
                }
            }
        }
    }

    private function normalizePath($path)
    {
        return urldecode('/' . trim(trim($path, '/')));
    }

    private function getRuleList()
    {
        $rules = [];

        $rawData = \SettingGetter::get('redirects.rules');
        $rows = json_decode($rawData, true);

        if (is_array($rows)) {
            foreach ($rows as $rule => $url) {
                $rule = trim($rule);
                $url = $this->normalizePath($url);

                if (!empty($rule)) {
                    $rules[$rule] = $url;
                }
            }
        }

        return $rules;
    }
}
