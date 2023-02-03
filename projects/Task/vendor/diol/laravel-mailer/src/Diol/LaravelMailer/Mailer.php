<?php namespace Diol\LaravelMailer;

use Illuminate\Mail\Message;
use Diol\LaravelMailer\Message as MessageExt;

use App;
use Config;

/**
 * Class Mailer
 * @package  App\Service\MailMailer
 */
class Mailer extends \Illuminate\Mail\Mailer
{
    private $bcc;
    private $substitutedTo;
    private $returnPath;
    private $replyTo;

    /**
     * Send a new message using a view.
     *
     * @param  string|array $view
     * @param  array $data
     * @param  \Closure|string $callback
     * @return void
     */
    public function send($view, array $data, $callback)
    {
        // Send mail.
        parent::send($view, $data, function (Message $message) use ($callback) {
            $message = new MessageExt($message->getSwiftMessage());

            $callback($message);
            $headers = $message->getSwiftMessage()->getHeaders();

            // Substitute "to" header
            if (in_array(App::environment(), array_get($this->substitutedTo, 'environments', ['local']))) {
                $headers->get('to')->setFieldBodyModel([
                    array_get($this->substitutedTo, 'address') => array_get($this->substitutedTo, 'name')
                ]);

                $headers->remove('cc');
                $headers->remove('bcc');
            }

            // Set "blind copy"
            if (in_array(App::environment(), array_get($this->bcc, 'environments', ['production']))) {
                if (array_get($this->bcc, 'enabled', true)) {
                    $message->bcc(array_get($this->bcc, 'address'), array_get($this->bcc, 'name'));
                }
            }

            // Set default return path
            if ($this->returnPath) {
                $message->returnPath($this->returnPath);
            }

            // Set default "Reply-To"
            if (is_null($headers->get('reply-to'))) {
                $replyToAddress = array_get($this->replyTo, 'address');
                if ($replyToAddress) {
                    $message->replyTo($replyToAddress, array_get($this->replyTo, 'name'));
                }
            }
        });
    }

    /**
     * Send a new message using a view via original method (without modification of headers).
     *
     * @param $view
     * @param array $data
     * @param $callback
     */
    public function sendOriginal($view, array $data, $callback)
    {
        parent::send($view, $data, $callback);
    }

    /**
     * @param mixed $bcc
     */
    public function setBcc($bcc)
    {
        $this->bcc = $bcc;
    }

    /**
     * @param mixed $substitutedTo
     */
    public function setSubstitutedTo($substitutedTo)
    {
        $this->substitutedTo = $substitutedTo;
    }

    /**
     * @param string $returnPath
     */
    public function setReturnPath($returnPath)
    {
        $this->returnPath = $returnPath;
    }

    /**
     * Disable Bcc sending.
     */
    public function disableBccSending()
    {
        array_set($this->bcc, 'enabled', false);
    }

    /**
     * Enable Bcc sending.
     */
    public function enableBccSending()
    {
        array_set($this->bcc, 'enabled', true);
    }

    /**
     * @param mixed $replyTo
     */
    public function setReplyTo($replyTo)
    {
        $this->replyTo = $replyTo;
    }
}
