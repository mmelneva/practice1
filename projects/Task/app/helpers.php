<?php

/*
|--------------------------------------------------------------------------
| Application helpers
|--------------------------------------------------------------------------
|
| Different helper functions.
|
*/

if (!function_exists('asset_timed')) {
    /**
     * Generate an asset path with modified time as parameter for the application.
     *
     * @param $path
     * @param null $secure
     * @return string
     * @throws Exception
     */
    function asset_timed($path, $secure = null)
    {
        $asset = asset($path, $secure);

        $file = public_path($path);
        if (file_exists($file)) {
            $version = md5(filemtime($file));
        }

        return isset($version) ? "{$asset}?v={$version}" : $asset;
    }
}

if (!function_exists('scope_field_name')) {
    /**
     * Make form field scoped.
     * Example:
     *  scope_field_name('name', ['user', 'info']) #=> user[info][name]
     *
     * @param $name
     * @param array $scope
     * @return string
     */
    function scope_field_name($name, array $scope = [])
    {
        $nameArray = array_merge($scope, [$name]);

        $head = array_shift($nameArray);
        $tail = array_map(
            function ($n) {
                return "[{$n}]";
            },
            $nameArray
        );

        return $head . implode('', $tail);
    }
}

if (!function_exists('scope_dot_name')) {
    /**
     * Make form field dotted.
     * Example:
     *  scope_dot_name('name', ['user', 'info']) #=> user.info.name
     *
     * @param $name
     * @param array $scope
     * @return string
     */
    function scope_dot_name($name, array $scope = [])
    {
        $nameArray = array_merge($scope, [$name]);

        return implode('.', $nameArray);
    }
}

if (!function_exists('scope_dash_name')) {
    /**
     * Make form field dotted.
     * Example:
     *  scope_dash_name('name', ['user', 'info']) #=> user_info_name
     *
     * @param $name
     * @param array $scope
     * @return string
     */
    function scope_dash_name($name, array $scope = [])
    {
        $nameArray = array_merge($scope, [$name]);

        return implode('_', $nameArray);
    }
}

if (!function_exists('wrap_with_paginator')) {
    /**
     * Wrap url with pagination query according to paginator.
     *
     * @param $url
     * @param $paginator
     * @return string
     */
    function wrap_with_paginator($url, $paginator)
    {
        if ($paginator instanceof Illuminate\Pagination\Paginator) {
            return wrap_with_page($url, $paginator->getCurrentPage());
        }

        return $url;
    }
}

if (!function_exists('wrap_with_page')) {
    /**
     * Wrap url with pagination query according to page.
     *
     * @param $url
     * @param $page
     * @return string
     */
    function wrap_with_page($url, $page)
    {
        if ($page > 1) {
            $httpQuery = http_build_query(['page' => $page]);
        } else {
            $httpQuery = '';
        }

        if (!empty($httpQuery)) {
            $url .= '?' . $httpQuery;
        }

        return $url;
    }
}

if (!function_exists('array_get_array')) {
    /**
     * Get an array by dot notation from other array.
     * If element is not array, will return empty array.
     *
     * @param $array
     * @param $key
     * @return array
     */
    function array_get_array($array, $key)
    {
        $result = array_get($array, $key);
        if (!is_array($result)) {
            $result = [];
        }

        return $result;
    }
}

if (!function_exists('out_datetime')) {
    /**
     * Out date format.
     *
     * @param \Carbon\Carbon $datetime
     * @return string
     */
    function out_datetime(\Carbon\Carbon $datetime)
    {
        return $datetime->format('d.m.Y H:i:s');
    }
}


if (!function_exists('price_format')) {
    /**
     * Price format.
     *
     * @param $price
     * @return string
     */
    function price_format($price)
    {
        $formatPrice = '';

        $price = floatval($price);
        if (!empty($price)) {
            $formatPrice = number_format(ceil($price), 0, '.', '′ ') . ' <span class="rur">руб</span>';
        }

        return $formatPrice;
    }
}

if (!function_exists('errors_contain')) {
    /**
     * Check that errors contain needles.
     *
     * @param \Illuminate\Support\ViewErrorBag $errors
     * @param $needles
     * @return int
     */
    function errors_contain(\Illuminate\Support\ViewErrorBag $errors, $needles)
    {
        return count(array_filter(array_keys($errors->getMessages()), function ($v) use ($needles) {
            return Str::contains($v, $needles);
        }));
    }
}

if (!function_exists('array_has_all_same')) {
    /**
     * Does the array elements are all the same?
     *
     * @param array $array
     * @return bool
     */
    function array_has_all_same(array $array)
    {
        return count(array_unique($array)) == 1;
    }
}

if (!function_exists('get_valid_emails')) {
    /**
     * Get array of valid emails
     *
     * @param string $emails
     * @param string $default
     * @return array
     */
    function get_valid_emails($emails, $default = '')
    {
        $validEmails = [];
        foreach (explode(',', $emails) as $email) {
            $email = trim($email);
            if (\Validator::make(['email' => $email], ['email' => ['required', 'email']])->passes()) {
                $validEmails[] = $email;
            }
        }

        if (count($validEmails) == 0) {
            if (\Validator::make(['email' => $default], ['email' => ['required', 'email']])->passes()) {
                $validEmails[] = $default;
            }
        }

        return $validEmails;
    }
}

if (!function_exists('get_first_valid_email')) {
    /**
     * Get first valid email
     *
     * @param string $emails
     * @param string $default
     * @return array
     */
    function get_first_valid_email($emails, $default = '')
    {
        $validEmails = get_valid_emails($emails, $default);

        if (count($validEmails) > 0) {
            return $validEmails[0];
        }

        return '';
    }
}

if (!function_exists('set_reply_to_header')) {
    /**
     * Set replyTo mail header
     * @param \Illuminate\Mail\Message $message
     * @param string $email
     * @param string $name
     */
    function set_reply_to_header(Illuminate\Mail\Message $message, $email, $name)
    {
        if (\Validator::make(['email' => $email], ['email' => ['required', 'email']])->passes()) {
            $message->replyTo($email, $name);
        }
    }
}

if (!function_exists('phoneToTelFormat')) {
    /**
     * phone to tel: \d+ convert
     * @param $string
     * @return mixed
     */
    function phoneToTelFormat($string)
    {
        return str_replace(' ', '', strtr(strip_tags($string), '()-', '   '));
    }
}

if (!function_exists('mb_ucfirst')) {
    /**
     *  Make a multi-bytes string's first character uppercase.
     * @param $str
     * @return string
     */
    function mb_ucfirst($str)
    {
        $fc = mb_strtoupper(mb_substr($str, 0, 1));
        return $fc . mb_substr($str, 1);
    }
}


if (!function_exists('mb_lcfirst')) {
    /**
     * Make a multi-bytes string's first character lowercase.
     *
     * @param $str
     * @return mixed
     */
    function mb_lcfirst($str)
    {
        $fc = mb_strtolower(mb_substr($str, 0, 1));
        return $fc . mb_substr($str, 1);
    }
}

if (!function_exists('explode_with_trim')) {
    /**
     *  Explode and trim string to array.
     *
     * @param string $str
     * @param string $glue
     *
     * @return array
     */
    function explode_with_trim($str, $glue = ',')
    {
        return array_filter(
                array_map(
                        function ($v) {
                            return trim($v); // remove whitespaces for each string
                        },
                        explode($glue, $str)
                ),
                function ($v) {
                    return !empty($v); // remove empty values
                }
        );
    }
}

