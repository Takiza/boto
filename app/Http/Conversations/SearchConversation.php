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

        $question = Question::create('Select field');

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
                $this->next($users);
            });
        });

    }

    public function next($users)
    {
        $question = Question::create('What is next?');

        $question->addButtons([
            Button::create('Actions')->value(1),
            Button::create('Back')->value(2),
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
        $question = Question::create('Select user');

        foreach ($users as $user) {
            $question->addButtons([Button::create($user->first_name . ' ' . $user->last_name)->value($user->id)]);
        }

        $this->ask($question, function (Answer $value) use ($users) {
            $user = $users->firstWhere('id', $value->getValue());
            $this->actions($user);
        });
    }

    public function actions($user)
    {
        $question = Question::create('Select action');

        $question->addButtons([
            Button::create('Edit')->value(1),
            Button::create('Delete')->value(2),
            Button::create('Back')->value(3)
        ]);
        $this->ask($question, function (Answer $answer) use ($user) {
            if ($answer->getValue() == 1) {
                $this->bot->startConversation(new EditConversation($user));
            }
            elseif ($answer->getValue() == 2) {
                $this->bot->startConversation(new DeleteConversation($user->id));
            }
            else {
                $this->bot->startConversation(new ChangeConversation());
            }
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