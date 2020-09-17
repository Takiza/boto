<?php

namespace App\Http\Conversations;

use App\User;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class ChangeConversation extends Conversation
{
    public function askChange()
    {

        $question = Question::create("What do u want?");

        $question->addButtons( [
            Button::create('Search users')->value(SearchConversation::class),
            Button::create('Create user')->value(CreateConversation::class),
        ]);

        $this->ask($question, function (Answer $answer) {
            $value = $answer->getValue();
            $this->bot->startConversation(new $value());
        });
    }

    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        $this->askChange();
    }
}