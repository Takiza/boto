<?php


namespace App\Http\Conversations;


use App\User;
use BotMan\BotMan\Messages\Conversations\Conversation;

class DeleteConversation extends Conversation
{
    public function __construct($id)
    {
        User::destroy($id);
    }

    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        $this->say('Deleted');
        $this->bot->startConversation(new ChangeConversation());
    }
}