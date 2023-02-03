<?php namespace Diol\LaravelErrorSender;

use Exception;
use Diol\LaravelErrorSender\Exception\EmptyAlertMailAddress;
use Diol\LaravelMailer\Message;

/**
 * Class ErrorSender
 * @package Diol\LaravelErrorSender
 */
class ErrorSender
{
    /**
     * @var ExceptionStorage
     */
    private $exceptionStorage;

    /**
     * @param ExceptionStorage $exceptionStorage
     */
    public function setExceptionStorage(ExceptionStorage $exceptionStorage): void
    {
        $this->exceptionStorage = $exceptionStorage;
    }

    /**
     * Send alert mail with error dump.
     *
     * @param \Exception $e
     * @param int $code
     */
    public function send(\Exception $e, $code = 500)
    {
        if ($this->isExceptionForIgnore($e)) {
            return;
        }

        try {
            if (\App::runningInConsole()) {
                $consoleAppClass = 'Symfony\Component\Console\Application';

                $consoleAppFile = (new \ReflectionClass($consoleAppClass))->getFileName();
                $inputDir = dirname($consoleAppFile) . '/Input';

                // For unknown or ambiguous commands NOT send mail with alert
                if ($e instanceof \InvalidArgumentException) {
                    if ($e->getFile() === $consoleAppFile) {
                        return;
                    }
                }

                // For incorrect arguments or options of commands NOT send mail with alert
                if ($e instanceof \RuntimeException || $e instanceof \LogicException) {
                    if (dirname($e->getFile()) === $inputDir) {
                        return;
                    }
                }
            }

            // Flush all view buffers before rendering template of mail
            \View::flushSections();

            if (method_exists(\App::offsetGet('mailer'), 'disableBccSending')) {
                // Disable sending to bcc addresses
                \App::offsetGet('mailer')->disableBccSending();
            }

            if (!\App::runningInConsole()) {
                $this->sendMail($e, $code);

            } elseif ($this->exceptionStorage->available()) {
                if (!$this->exceptionStorage->has($e)) {
                    $this->sendMail($e, $code);
                    $this->exceptionStorage->put($e);
                }
            } else {
                $this->sendMail($e, $code);
            }

        } catch (\Exception $e) {
            \Log::alert($e);
        }
    }

    /**
     * Send mail
     *
     * @param Exception $e
     * @param $code
     * @throws Exception
     */
    private function sendMail(Exception $e, $code)
    {
        // Get alert email address
        $alertMailAddress = \Config::get('laravel-error-sender::alert_mail.address');
        if (!empty($alertMailAddress)) { // Send alert message with error dump
            \App::offsetGet('mailer')->send(
                'laravel-error-sender::alert',
                ['exception' => $e, 'code' => $code, 'sapi' => php_sapi_name()],
                function (Message $message) use ($e, $alertMailAddress) {
                    $message->to($alertMailAddress);
                    $message->subject(\Config::get('laravel-error-sender::alert_mail.subject'));

                    // Get filename for attachment with error dump
                    $errorDumpFilename = \Config::get('laravel-error-sender::error_dump.file_name');

                    // Get error dump content.
                    $errorDumpContent = str_replace(
                        '<script src="//',
                        '<script src="http://',
                        \App::offsetGet('errors_sender.exception.debug')->display($e)->getContent()
                    );

                    $archiveFile = $this->tryToCreateDumpArchive($errorDumpFilename, $errorDumpContent);

                    if (false !== $archiveFile) {
                        $message->attach($archiveFile, ['as' => "{$errorDumpFilename}.tar"]);
                    } else {
                        $message->attachData($errorDumpContent, "{$errorDumpFilename}.html");
                    }
                }
            );
        }
    }
    
    /**
     * Try to create tar archive with error dump.
     *
     * @param $dumpFileName
     * @param $dumpContent
     * @return false|string path to `tar` file
     */
    private function tryToCreateDumpArchive($dumpFileName, $dumpContent)
    {
        if (class_exists('\PharData', true)) {
            try {
                do {
                    $path = sys_get_temp_dir() . '/' . mt_rand() . '.tar';
                } while (is_file($path));

                $a = new \PharData($path);
                $a->addFromString("{$dumpFileName}.html", $dumpContent);

                return is_file($path) ? $path : false;
            } catch (\Exception $e) {
                \Log::alert($e);
            }
        }
        return false;
    }

    /**
     * Check that exception is for ignore.
     *
     * @param \Exception $e
     * @return bool
     */
    private function isExceptionForIgnore(\Exception $e)
    {
        $ignoreExceptions = \Config::get('laravel-error-sender::ignore_exceptions');

        if (!is_array($ignoreExceptions)) {
            $ignoreExceptions = [];
        }

        return in_array(get_class($e), $ignoreExceptions, true);
    }
}
