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

//        $bot->reply('ok');
        $this->ask($question, function (Answer $field) {
            $this->ask('Value:', function (Answer $value) use ($field) {
                $users = User::where($field, 'like', '%' . $value . '%')->get();
                if (empty($users->first())) {
                    return $this->bot->reply('Not find :(');
                }
                foreach ($users as $user) {
//                    $message = OutgoingMessage::create("<a href=\"http://www.example.com/\">inline URL</a>");
//                    $this->say($user->first_name . ' <b>bold</b> ' . $user->last_name, TelegramDriver::class, ['parse_mode' => 'HTML']);
                    $this->say($user->first_name . ' ' . $user->last_name);
                }
                return true;
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