<?php namespace App\Services\Mails;

use App\Models\Order;
use App\Services\DataProviders\Order\OrderDataProvider;
use Illuminate\Mail\Message;

/**
 * Class OrderMailsHelper
 * @package App\Services\Mails
 */
class OrderMailsHelper
{
    public function __construct(OrderDataProvider $orderDataProvider)
    {
        $this->orderDataProvider = $orderDataProvider;
    }

    public function sendClientCompleteEmail(Order $order)
    {
        $orderData = $this->orderDataProvider->getPrintData($order);
        $to = $order->email;

        $emailRules = ['to' => ['required', 'email']];
        if (\Validator::make(compact('to'), $emailRules)->passes()) {
            \Mail::send(
                'emails.order.client.complete',
                compact('order', 'orderData'),
                function (Message $message) use ($order, $to) {
                    $message->to($to);
                    $message->subject(
                        str_replace(
                            ['{root_url}'],
                            [\Request::server('HTTP_HOST')],
                            "Заказ на сайте {root_url}"
                        )
                    );
                }
            );


            return true;
        }

        return false;
    }

    public function sendAdminCompleteEmail(Order $order)
    {
        $orderData = $this->orderDataProvider->getPrintData($order, true);
        $emails = get_valid_emails(\SettingGetter::get('mail.feedback.address'), \Config::get('mail.feedback.address'));

        foreach ($emails as $to) {
            \Mail::send(
                'emails.order.admin.complete',
                compact('order', 'orderData'),
                function (Message $message) use ($order, $to) {
                    $message->to($to);
                    set_reply_to_header($message, $order->email, $order->name);
                    $message->subject(
                        str_replace(
                            ['{root_url}'],
                            [\Request::server('HTTP_HOST')],
                            "Заказ на сайте {root_url}"
                        )
                    );
                }
            );
        }
    }
}
