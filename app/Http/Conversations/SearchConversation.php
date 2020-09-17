<?php

namespace App\Http\Conversations;

use App\User;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class SearchConversation extends Conversation
{
    public function find()
    {

        $question = Question::create("Select field");

        $question->addButtons( [
            Button::create('first_name')->value('first_name'),
            Button::create('last_name')->value('last_name'),
            Button::create('phone')->value('phone'),
            Button::create('platform_id')->value('platform_id'),
            Button::create('status')->value('status'),
        ]);

        $this->ask($question, function (Answer $field) {
            $this->ask('Value:', function (Answer $value) use ($field) {
                $users = User::where($field, 'like', '%' . $value . '%')->get();
                if (empty($users->first())) {
                    $this->bot->reply('Not find :(');
                    $this->bot->startConversation(new ChangeConversation());
                    return false;
                }
                foreach ($users as $user) {
                    $this->say($user->first_name . ' ' . $user->last_name);
                }
                $this->bot->startConversation(new ChangeConversation());
            });
        });

    }

    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        $this->find();
    }
}