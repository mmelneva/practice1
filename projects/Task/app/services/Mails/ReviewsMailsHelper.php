<?php namespace App\Services\Mails;

use App\Models\Reviews;
use Illuminate\Mail\Message;

/**
 * Class ReviewsMailsHelper
 * @package  App\Services\Helpers
 */
class ReviewsMailsHelper
{
    public function sendAdminNewReviewsEmail(Reviews $reviews)
    {
        $reviewsData = $this->getData($reviews);
        $emails = get_valid_emails(\SettingGetter::get('mail.reviews.address'), \Config::get('mail.reviews.address'));

        foreach ($emails as $to) {
            \Mail::send(
                'emails.reviews.admin.new_reviews',
                compact('reviews', 'reviewsData'),
                function (Message $message) use ($reviews, $to) {
                    $message->to($to);
                    set_reply_to_header($message, $reviews->email, $reviews->name);
                    $message->subject(
                        str_replace(
                            ['{root_url}'],
                            [\Request::server('HTTP_HOST')],
                            "Отзыв на сайте {root_url}"
                        )
                    );
                }
            );
        }
    }

    public function sendClientReviewsAnswerEmail(Reviews $reviews)
    {
        $to = $reviews->email;

        if (\Validator::make(compact('to'), ['to' => ['required', 'email']])->passes()) {
            \Mail::send(
                'emails.reviews.client.reviews_answer',
                compact('reviews'),
                function (Message $message) use ($reviews, $to) {
                    $message->to($to);
                    $message->subject(
                        str_replace(
                            ['{root_url}'],
                            [\Request::server('HTTP_HOST')],
                            "Ответ на отзыв на сайте {root_url}"
                        )
                    );
                }
            );

            return true;
        }

        return false;
    }

    private function getData(Reviews $reviews)
    {
        $reviewsData[trans('validation.attributes.full_name')] = $reviews->name;
        $reviewsData[trans('validation.attributes.email')] = $reviews->email;
        $reviewsData[trans('validation.attributes.comment')] = $reviews->comment;
        $reviewsData[trans('validation.attributes.answer')] = $reviews->answer;

        $reviewsData = array_filter($reviewsData, function ($v) {
            $v = trim($v);

            return !empty($v);
        });

        return $reviewsData;
    }
}
