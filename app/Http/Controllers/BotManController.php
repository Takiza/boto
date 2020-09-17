<?php

namespace App\Http\Controllers;

use App\Http\Conversations\ChangeConversation;
use App\Http\Conversations\CreateConversation;
use App\Http\Conversations\SearchConversation;
use App\User;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\Drivers\Telegram\TelegramDriver;
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
        $bot->startConversation(new ChangeConversation());
    }

//    public function find(BotMan $bot)
//    {
////        $user = User::firstWhere('id', 1);
////        $bot->reply($user->first_name);
//        $question = Question::create("Select field");
//
//        $question->addButtons( [
//            Button::create('first_name')->value('first_name'),
//            Button::create('last_name')->value('last_name'),
//            Button::create('phone')->value('phone'),
//            Button::create('platform_id')->value('platform_id'),
//            Button::create('status')->value('status'),
//        ]);
//
////        $bot->reply('ok');
//        $bot->ask($question, function (Answer $field) {
//            $this->ask('Value:', function (Answer $value) use ($field) {
//                $users = User::where($field, 'like', '%' . $value . '%')->get();
//                if (empty($users->first())) {
//                    return $this->bot->reply('Not find :(');
//                }
//                foreach ($users as $user) {
//                    $message = '------------------------------------------------ <br>';
//                    $message .= 'Name : '.$user->first_name.'<br>';
//                    $message .= 'Email : '.$user->first_name.'<br>';
//                    $message .= 'Mobile : '.$user->first_name.'<br>';
//                    $message .= 'Service : '.$user->first_name.'<br>';
//                    $message .= 'Date : '.$user->first_name.'<br>';
//                    $message .= 'Time : '.$user->first_name.'<br>';
//                    $message .= '------------------------------------------------';
//
//                    $this->say('Great. Your booking has been confirmed. Here is your booking details. <br><br>'.$message);
////                    $message = OutgoingMessage::create("<a href=\"http://www.example.com/\">inline URL</a>");
////                    $this->say($user->first_name . ' <b>bold</b> ' . $user->last_name, TelegramDriver::class, ['parse_mode' => 'HTML']);
////                    $this->bot->say($message, $user->first_name, TelegramDriver::class,['parse_mode' => 'HTML']);
//                }
//                return true;
//            });
//
////            $message = OutgoingMessage::create('This is my text' . $user);
////            $message = response()->json(['id' => $user->id]);
//        });
//    }
}
