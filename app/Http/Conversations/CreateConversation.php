<?php

namespace App\Http\Conversations;

use App\User;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Support\Facades\Validator;

class CreateConversation extends Conversation
{
    public function askCreate()
    {
        $question = Question::create("Do u want to create new user?");

        $question->addButtons([
            Button::create('Yes :3')->value(1),
            Button::create('No :|')->value(2),
        ]);

        $this->ask($question, function (Answer $answer) {
            if ($answer->getValue() == 1) {
                $this->askFirstName();
            }

            else {
                $this->bot->reply('-_-');
                $this->bot->startConversation(new ChangeConversation());
            }
        });
    }

    public function askFirstName()
    {
        $this->ask('First name:', function (Answer $answer) {
            $this->bot->userStorage()->save([
                'first_name' => $answer->getText(),
            ]);

            $this->say('First name: ' . $answer->getText());
            $this->askLastName();
        });
    }

    public function askLastName()
    {
        $this->ask('Last name:', function (Answer $answer) {
            $this->bot->userStorage()->save([
                'last_name' => $answer->getText(),
            ]);

            $this->say('Last name: ' . $answer->getText());
            $this->askEmail();
        });
    }

    public function askEmail()
    {
        $this->ask('Email: ', function (Answer $answer) {
            $validator = Validator::make(['email' => $answer->getText()], [
                'email' => 'email',
            ]);

            if ($validator->fails()) {
                return $this->repeat('That doesn\'t look like a valid email. Please enter a valid email.');
            }

            $this->bot->userStorage()->save([
                'email' => $answer->getText(),
            ]);

            $this->askPhone();
        });
    }

    public function askPhone()
    {
        $this->ask('Phone: ', function (Answer $answer) {
            $this->bot->userStorage()->save([
                'phone' => $answer->getText(),
            ]);

            $this->askPlatform();
        });
    }

    public function askPlatform()
    {
        $question = Question::create("Select platform");

        $question->addButtons([
            Button::create('Platform 1')->value(1),
            Button::create('Platform 2')->value(2),
        ]);

        $this->ask($question, function (Answer $answer) {
            $this->bot->userStorage()->save([
                'platform_id' => $answer->getText(),
            ]);

            $this->askStatus();
        });
    }

    public function askStatus()
    {
        $question = Question::create("Select status");

        $question->addButtons([
            Button::create('Status 1')->value('Status 1'),
            Button::create('Status 2')->value('Status 2'),
        ]);

        $this->ask($question, function (Answer $answer) {
            $this->bot->userStorage()->save([
                'status' => $answer->getText(),
            ]);

            $this->createUser();
        });
    }

    public function createUser()
    {
        $user = $this->bot->userStorage()->find();
        User::create([
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'email' => $user['email'],
            'phone' => $user['phone'],
            'platform_id' => $user['platform_id'],
            'status' => $user['status']
        ]);
        $this->bot->reply('Created');
        $this->bot->startConversation(new ChangeConversation());
    }

    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        $this->askCreate();
    }
}
