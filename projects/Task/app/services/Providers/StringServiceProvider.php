<?php namespace App\Services\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class StringServiceProvider
 * @package  App\Services\Providers
 */
class StringServiceProvider extends ServiceProvider
{
    /**
     * @inheritdoc
     */
    public function register()
    {
    }

    public function boot()
    {
        $this->initStringMacros();
    }

    private function initStringMacros()
    {
        \Str::macro(
            'formatDate',
            function ($dateString, $format = 'Y-m-d') {
                return date($format, strtotime($dateString));
            }
        );

        \Str::macro(
            'month',
            function ($time = null, $genitiveCase = true) {
                $locale = \App::getLocale();

                $pairs['ru'] = [
                    'January' => 'Январь',
                    'February' => 'Февраль',
                    'March' => 'Март',
                    'April' => 'Апрель',
                    'May' => 'Май',
                    'June' => 'Июнь',
                    'July' => 'Июль',
                    'August' => 'Август',
                    'September' => 'Сентябрь',
                    'October' => 'Октябрь',
                    'November' => 'Ноябрь',
                    'December' => 'Декабрь',
                ];

                if (is_null($time)) {
                    $month = date('F');
                } else {
                    $month = date('F', strtotime($time));
                }

                $transMonth = array_get($pairs, "{$locale}.{$month}", $month);

                if ($genitiveCase && $locale == 'ru') {
                    $transMonth = $month == 'March' || $month == 'August' ?
                        "{$transMonth}а" : mb_substr($transMonth, 0, -1) . 'я';
                }

                return $transMonth;
            }
        );

        \Str::macro(
            'formatDecimal',
            function ($number, $dec_point = ',', $thousands_sep = ' ') {
                $original = $number;
                $number = floatval(preg_replace('/\s+/', '', str_replace(',', '.', $number)));

                if (!is_float($number)) {
                    return $original;
                }

                return (string)number_format(
                    $number,
                    0 == \Str::fractionalPart($number) ? 0 : 2,
                    $dec_point,
                    $thousands_sep
                );
            }
        );

        \Str::macro(
            'fractionalPart',
            function ($number, $epsilon = 2) {
                return round($number - floor($number), $epsilon);
            }
        );

        \Str::macro(
            'fractionalPartAsInteger',
            function ($number, $epsilon = 2) {
                return \Str::fractionalPart($number, $epsilon) * pow(10, $epsilon);
            }
        );

        \Str::macro(
            'formatFractionalPart',
            function ($number, $epsilon = 2, $padString = '0') {
                $fractionalPart = \Str::fractionalPartAsInteger($number);

                return str_pad($fractionalPart, $epsilon, $padString, STR_PAD_LEFT);
            }
        );

        \Str::macro(
            'formatFileSize',
            function ($size) {
                $round = 0;
                $sizeType = [1 => 'Kб', 2 => 'Мб'];

                $type = 'байт';
                $fileSize = $size;
                foreach ($sizeType as $key => $val) {
                    if (($tempSize = $size / pow(1024, $key)) >= 1) {
                        $type = $val;
                        $fileSize = $tempSize;
                        if ($key == 2) {
                            $round = 2;
                        }
                    } else {
                        break;
                    }
                }

                return round($fileSize, $round) . ' ' . $type;
            }
        );

        \Str::macro(
            'transliterate',
            function ($str) {
                $pairs = [
                    'А' => 'A',
                    'Б' => 'B',
                    'В' => 'V',
                    'Г' => 'G',
                    'Д' => 'D',
                    'Е' => 'E',
                    'Ё' => 'E',
                    'Ж' => 'J',
                    'З' => 'Z',
                    'И' => 'I',
                    'Й' => 'Y',
                    'К' => 'K',
                    'Л' => 'L',
                    'М' => 'M',
                    'Н' => 'N',
                    'О' => 'O',
                    'П' => 'P',
                    'Р' => 'R',
                    'С' => 'S',
                    'Т' => 'T',
                    'У' => 'U',
                    'Ф' => 'F',
                    'Х' => 'H',
                    'Ц' => 'TS',
                    'Ч' => 'CH',
                    'Ш' => 'SH',
                    'Щ' => 'SCH',
                    'Ъ' => '',
                    'Ы' => 'YI',
                    'Ь' => '',
                    'Э' => 'E',
                    'Ю' => 'YU',
                    'Я' => 'YA',
                    'а' => 'a',
                    'б' => 'b',
                    'в' => 'v',
                    'г' => 'g',
                    'д' => 'd',
                    'е' => 'e',
                    'ё' => 'e',
                    'ж' => 'j',
                    'з' => 'z',
                    'и' => 'i',
                    'й' => 'y',
                    'к' => 'k',
                    'л' => 'l',
                    'м' => 'm',
                    'н' => 'n',
                    'о' => 'o',
                    'п' => 'p',
                    'р' => 'r',
                    'с' => 's',
                    'т' => 't',
                    'у' => 'u',
                    'ф' => 'f',
                    'х' => 'h',
                    'ц' => 'ts',
                    'ч' => 'ch',
                    'ш' => 'sh',
                    'щ' => 'sch',
                    'ъ' => 'y',
                    'ы' => 'yi',
                    'ь' => '',
                    'э' => 'e',
                    'ю' => 'yu',
                    'я' => 'ya'
                ];

                return strtr($str, $pairs);
            }
        );

        \Str::macro(
            'alias',
            function ($str) {
                $str = \Str::transliterate($str);
                $str = mb_strtolower($str);

                $str = preg_replace('/[()]/', '', $str);
                $str = preg_replace('/[^a-z0-9_]/', '-', $str);
                $str = preg_replace('/-(-)+/', '-', $str);
                $str = preg_replace('/(^-|-$)/', '', $str);

                return $str;
            }
        );

        \Str::macro(
            'pluralize',
            function ($n, array $forms) {
                return $n % 10 == 1 && $n % 100 != 11 ? $forms[0] :
                    ($n % 10 >= 2 && $n % 10 <= 4 && ($n % 100 < 10 || $n % 100 >= 20) ? $forms[1] : $forms[2]);
            }
        );
    }
}
