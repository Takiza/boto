<?php

namespace App\Http\Conversations;

use App\User;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Support\Facades\Validator;

class EditConversation extends Conversation
{
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function askFirstName()
    {
        $this->ask('Current first name is ' . $this->user['first_name'] . '. Enter new value', function (Answer $answer) {
            $this->bot->userStorage()->save([
                'first_name' => $answer->getText(),
            ]);

            $this->say('First name: ' . $answer->getText());
            $this->askLastName();
        });
    }

    public function askLastName()
    {
        $this->ask('Current last name is ' . $this->user['last_name'] . '. Enter new value', function (Answer $answer) {
            $this->bot->userStorage()->save([
                'last_name' => $answer->getText(),
            ]);

            $this->say('Last name: ' . $answer->getText());
            $this->askEmail();
        });
    }

    public function askEmail()
    {
        $this->ask('Current email is ' . $this->user['email'] . '. Enter new value', function (Answer $answer) {
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
        $this->ask('Current phone is ' . $this->user['phone'] . '. Enter new value', function (Answer $answer) {
            $this->bot->userStorage()->save([
                'phone' => $answer->getText(),
            ]);

            $this->askPlatform();
        });
    }

    public function askPlatform()
    {
        $question = Question::create( 'Current platform is '. $this->user['platform_id'] . '. Select new value');

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
        $question = Question::create('Current status is '. $this->user['status'] . '. Select new value');

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
        User::find($this->user->id)->update([
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'email' => $user['email'],
            'phone' => $user['phone'],
            'platform_id' => $user['platform_id'],
            'status' => $user['status']
        ]);
        $this->bot->reply('Updated');
        $this->bot->startConversation(new ChangeConversation());
    }

    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        $this->askFirstName();
    }
}
