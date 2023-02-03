<?php namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\Callback;
use App\Models\CallbackTypeConstants;
use App\Services\FormProcessors\CallbackFormProcessor;
use Illuminate\Mail\Message;

class CallbackController extends BaseController
{
    private $formProcessor;

    public function __construct(
        CallbackFormProcessor $formProcessor
    ) {
        $this->formProcessor = $formProcessor;
    }

    public function postStore()
    {
        $status = 'ERROR';
        $content = '';
        if (\Request::ajax()) {
            $inputData = \Input::all();
            $inputData['url_referer'] = \Request::server('HTTP_REFERER');

            /** @var \App\Models\Callback $createdModel */
            $createdModel = $this->formProcessor->create($inputData);

            if (!is_null($createdModel)) {
                $this->sendAdminCallbackEmail($createdModel);
                $status = 'OK';
                $content = '<div class="success">' . trans('validation.model_attributes.callback.form.success') . '</div>';
            } else {
                $errorsArray = [];
                $errors = $this->formProcessor->errors();
                foreach ($errors as $error) {
                    $errorsArray = array_merge($errorsArray, $error);
                }
                if (count($errorsArray) > 0) {
                    $content = '<div class="errors">' . implode('<br>', $errorsArray) . '</div>';
                }
            }
        }

        return \Response::json(compact('status', 'content'));
    }

    /**
     * @param \App\Models\Callback $callback
    */
    public function sendAdminCallbackEmail(Callback $callback)
    {
        $emails = get_valid_emails(\SettingGetter::get('mail.feedback.address'), \Config::get('mail.feedback.address'));

        if ($callback->type == CallbackTypeConstants::CALLBACK) {
            $letterTitle = 'Заявка на обратный звонок';
            $title = 'Оформлена заявка на обратный звонок';
        } elseif ($callback->type == CallbackTypeConstants::MEASUREMENT) {
            $letterTitle = 'Заявка на вызов замерщика';
            $title = 'Оформлена заявка на вызов замерщика';
        } else {
            $letterTitle = 'Сообщение с формы обратной связи';
            $title = 'Отправлено сообщение с формы обратной связи';
        }
        $callbackData = $this->getData($callback);

        foreach ($emails as $email) {
            \Mail::send(
                'emails.callback.admin',
                compact('callback', 'callbackData', 'title'),
                function (Message $message) use ($callback, $email, $letterTitle) {
                    $message->to($email);
                    $message->subject(
                        str_replace(
                            ['{root_url}'],
                            [\Request::server('HTTP_HOST')],
                            $letterTitle . " №{$callback->id} на сайте {root_url}"
                        )
                    );
                }
            );
        }
    }

    private function getData(Callback $callback)
    {
        $callbackData[trans('validation.attributes.full_name')] = $callback->name;
        $callbackData[trans('validation.attributes.phone')] = $callback->phone;
        $callbackData[trans('validation.attributes.appropriate_time')] = $callback->appropriate_time;
        $callbackData[trans('validation.attributes.address')] = $callback->address;
        $callbackData[trans('validation.attributes.comment')] = $callback->comment;

        $callbackData = array_filter(
            $callbackData,
            function ($v) {
                $v = trim($v);

                return !empty($v);
            }
        );

        return $callbackData;
    }
}
