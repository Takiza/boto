<?php

namespace App\Http\Controllers;

use App\User;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Http\Request;
use App\Conversations\ExampleConversation;

class BotManController extends Controller
{
    /**
     * Place your BotMan logic here.
     */
    public function handle()
    {
        $botman = app('botman');

        $botman->listen();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tinker()
    {
        return view('tinker');
    }

    /**
     * Loaded through routes/botman.php
     * @param  BotMan $bot
     */
    public function startConversation(BotMan $bot)
    {
        $users = User::all();
        $bot->reply($users->toJson());
//        $bot->startConversation(new ExampleConversation());
    }

    public function find(BotMan $bot)
    {
        $question = Question::create("What do u want?");

        $question->addButtons( [
            Button::create('id')->value('id'),
            Button::create('first_name')->value('first_name'),
            Button::create('last_name')->value('last_name'),
            Button::create('phone')->value('phone'),
            Button::create('platform_id')->value('platform_id'),
            Button::create('status')->value('status'),
        ]);

//        $bot->reply('ok');
        $bot->ask($question, function (Answer $answer) {
            $user = User::firstWhere($answer, 1)->toJson();
            return $this->bot->reply($user->id);
        });
    }
}
