<?php

namespace App\Http\Conversations;

use App\User;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class SearchConversation extends Conversation
{
    public function search()
    {

        $question = Question::create("Select field");

        $question->addButtons( [
            Button::create('first_name')->value('first_name'),
            Button::create('last_name')->value('last_name'),
            Button::create('phone')->value('phone'),
            Button::create('platform_id')->value('platform_id'),
            Button::create('status')->value('status'),
        ]);

        $this->ask($question, function (Answer $answer) {
            $this->ask('Value:', function (Answer $value) use ($answer) {
                $users = User::where($answer, 'like', '%' . $value . '%')->get();
                if (empty($users->first())) {
                    $this->bot->reply('Not find :(');
                    $this->bot->startConversation(new ChangeConversation());
                    return false;
                }
                foreach ($users as $user) {
                    $this->say($user->first_name . ' ' . $user->last_name);
                }
                $this->edit($users);
            });
        });

    }

    public function edit($users)
    {
        $question = Question::create("Would u like to edit one of this users?");

        $question->addButtons([
            Button::create('Yes')->value(1),
            Button::create('No')->value(2),
        ]);

        $this->ask($question, function (Answer $answer) use ($users) {
            if ($answer->getValue() == 1) {
                $this->select($users);
            }
            else $this->bot->startConversation(new ChangeConversation());
        });
    }

    public function select($users)
    {
        $select = Question::create("Select user");

        foreach ($users as $user) {
            $select->addButtons([Button::create($user->first_name . ' ' . $user->last_name)->value($user->id)]);
        }

        $this->ask($select, function (Answer $value) use ($users) {
            $user = $users->firstWhere('id', $value->getValue());
            $this->bot->startConversation(new EditConversation($user));
        });
    }

    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        $this->search();
    }
}