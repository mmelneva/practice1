<?php namespace App\Services\Composers;

class ClientPhoneNumbersComposer
{
    public function compose($view)
    {
        $phoneNumber = \SettingGetter::get('phone_numbers.0');

        if (!empty($phoneNumber)) {
            if (preg_match('@^(.*)\s(.*)$@', $phoneNumber, $matches)) {
                $phone = '<span class="lite">' . $matches[1] . '</span> ' . $matches[2];
            } else{
                $phone = $phoneNumber;
            }
            $phoneBlock = '<a href="tel: ' . phoneToTelFormat($phoneNumber) . '">' . $phone . '</a>';
        } else {
            $phoneBlock = '';
        }

        $view->with('phoneNumberFirst', $phoneBlock);
    }
}
