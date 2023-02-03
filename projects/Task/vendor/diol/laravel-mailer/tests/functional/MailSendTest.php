<?php namespace tests\functional;

use Diol\LaravelMailer\Message;
use tests\TestCase;

/**
 * Class MailSendTest
 * @package Diol\integration
 */
class MailSendTest extends TestCase
{

    public function testSendForProductionEnvironment()
    {
        $this->app['env'] = 'production';

        $swiftMailerMock = \Mockery::mock('Swift_Mailer');
        $this->app['mailer']->setSwiftMailer($swiftMailerMock);

        $swiftMailerMock->shouldReceive('send')->once()
            ->andReturnUsing(function (\Swift_Message $msg) {
                $this->assertEquals(['to1@test.com' => null, 'to2@test.com' => null], $msg->getTo());
                $this->assertEquals(['cc1@test.com' => null, 'cc2@test.com' => null], $msg->getCc());
                $this->assertEquals('diol.tech.info@gmail.com', $msg->getReturnPath());
                $this->assertEquals(
                    [
                        'diol.test@gmail.com' => null,
                        'diol-test@mail.ru' => null,
                        'diol-test@lenta.ru' => null,
                        'diol-test@yandex.ru' => null,
                    ],
                    $msg->getBcc()
                );
                $this->assertEquals(['replyTo1@test.com' => null, 'replyTo2@test.com' => null], $msg->getReplyTo());

                $this->assertEquals('Test subject', $msg->getSubject());
                $this->assertEquals('test mail content!', $msg->getBody());
            });

        \Mail::send(
            'test_mail',
            [],
            function (Message $message) {
                $message->to('to1@test.com,to2@test.com,');
                $message->cc(['cc1@test.com', 'cc2@test.com']);
                $message->replyTo(['replyTo1@test.com', 'replyTo2@test.com']);

                $message->subject('Test subject');
            }
        );
    }

    public function testSendForLocalEnvironment()
    {
        $this->app['env'] = 'local';

        $swiftMailerMock = \Mockery::mock('Swift_Mailer');
        $this->app['mailer']->setSwiftMailer($swiftMailerMock);

        $swiftMailerMock->shouldReceive('send')->once()
            ->andReturnUsing(function (\Swift_Message $msg) {
                $this->assertEquals(['diol-test@yandex.ru' => null], $msg->getTo());
                $this->assertNull($msg->getCc());
                $this->assertNull($msg->getBcc());
                $this->assertEquals(['replyTo1@test.com' => null, 'replyTo2@test.com' => null], $msg->getReplyTo());

                $this->assertEquals('diol.tech.info@gmail.com', $msg->getReturnPath());

                $this->assertEquals('Test subject', $msg->getSubject());
                $this->assertEquals('test mail content!', $msg->getBody());
            });

        \Mail::send(
            'test_mail',
            [],
            function (Message $message) {
                $message->to('to1@test.com,to2@test.com');
                $message->bcc(['bcc1@test.com', 'bcc2@test.com']);
                $message->cc(['cc1@test.com', 'cc2@test.com']);
                $message->replyTo(['replyTo1@test.com', 'replyTo2@test.com']);

                $message->subject('Test subject');
            }
        );
    }

    public function testForDefaultReplyTo()
    {
        $this->app['env'] = 'local';

        \Config::set('mail.reply_to.address', 'replyTo10@test.com,replyTo20@test.com');

        $swiftMailerMock = \Mockery::mock('Swift_Mailer');
        $this->app['mailer']->setSwiftMailer($swiftMailerMock);

        $swiftMailerMock->shouldReceive('send')->once()
            ->andReturnUsing(function (\Swift_Message $msg) {
                $this->assertEquals(['replyTo10@test.com' => null, 'replyTo20@test.com' => null], $msg->getReplyTo());
            });

        \Mail::send(
            'test_mail',
            [],
            function (Message $message) {
                $message->to('to1@test.com,to2@test.com,');
            }
        );
    }
}
