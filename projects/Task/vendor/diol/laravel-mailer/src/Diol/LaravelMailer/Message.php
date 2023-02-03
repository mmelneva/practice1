<?php namespace Diol\LaravelMailer;

/**
 * Class Message
 * @package Diol\LaravelMailer
 */
class Message extends \Illuminate\Mail\Message
{
    /**
     * Modify source address and get list of address or single address.
     *
     * @param string|array $address
     * @return array
     */
    private function modifyAddress($address)
    {
        if (is_string($address)) {
            $address = array_filter(
                array_map('trim', explode(',', $address)),
                function ($v) {
                    return !empty($v); // remove empty values
                }
            );
        }

        return count($address) > 0 ? $address : null;
    }

    /**
     * @inheritdoc
     */
    public function to($address, $name = null)
    {
        return parent::to($this->modifyAddress($address), $name);
    }

    /**
     * @inheritdoc
     */
    public function cc($address, $name = null)
    {
        return parent::cc($this->modifyAddress($address), $name);
    }

    /**
     * @inheritdoc
     */
    public function bcc($address, $name = null)
    {
        return parent::bcc($this->modifyAddress($address), $name);
    }

    /**
     * @inheritdoc
     */
    public function replyTo($address, $name = null)
    {
        return parent::replyTo($this->modifyAddress($address), $name);
    }

    /**
     * String representation of the message.
     *
     * @return string
     */
    public function __toString()
    {
        $addressesAsString = function ($array) {
            return implode(', ', array_keys((array)$array));
        };

        $to = $addressesAsString($this->getTo());
        $from = $addressesAsString($this->getFrom());
        $replyTo = $addressesAsString($this->getReplyTo());
        $bcc = $addressesAsString($this->getBcc());
        $cc = $addressesAsString($this->getCc());

        $subject = $this->getSubject();

        $attachmentNames = [];
        foreach ($this->getChildren() as $child) {
            if ($child instanceof \Swift_Attachment) {
                $attachmentNames[] = $child->getFilename();
            }
        }

        $strMessage = '';

        if ($subject) {
            $strMessage .= ", subject: {$subject}";
        }

        if ($to) {
            $strMessage .= ", to: {$to}";
        }

        if ($from) {
            $strMessage .= ", from: {$from}";
        }

        if ($replyTo) {
            $strMessage .= ", replyTo: {$replyTo}";
        }

        if ($bcc) {
            $strMessage .= ", bcc: {$bcc}";
        }

        if ($cc) {
            $strMessage .= ", cc: {$cc}";
        }

        if ($attachmentNames) {
            $strMessage .= ', attachments: ' . implode(', ', $attachmentNames);
        }

        return trim($strMessage, ',');
    }
}
