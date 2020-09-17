<?php

namespace App\Http\Controllers;

use App\User;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
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
//        $user = User::firstWhere('id', 1);
//        $bot->reply($user->first_name);
        $question = Question::create("Select field");

        $question->addButtons( [
            Button::create('first_name')->value('first_name'),
            Button::create('last_name')->value('last_name'),
            Button::create('phone')->value('phone'),
            Button::create('platform_id')->value('platform_id'),
            Button::create('status')->value('status'),
        ]);

//        $bot->reply('ok');
        $bot->ask($question, function (Answer $field) {
            $this->ask('Value:', function (Answer $value) use ($field) {
                $users = User::where($field, 'like', '%' . $value . '%')->get();
                if (empty($users->first())) {
                    return $this->bot->reply('Not find :(');
                }
                foreach ($users as $user) {
                    $this->bot->reply($user->first_name . ' ' . $user->last_name);
                }
                return true;
            });

//            $message = OutgoingMessage::create('This is my text' . $user);
//            $message = response()->json(['id' => $user->id]);
        });
    }

    public function create(BotMan $bot)
    {
        $user = [];

        $question = Question::create("Do u want to create new user?");

        $question->addButtons([
            Button::create('Yes :3')->value(1),
            Button::create('No :|')->value(2),
        ]);

        $fields = [
            'First name' => 'first_name',
            'Last name' => 'last_name',
            'Email' => 'email',
            'Phone' => 'phone',
            'Platform' => 'platform_id',
            'Status' => 'status'
        ];



        $bot->ask($question, function (Answer $answer) use ($fields, $user) {
            if ($answer->getValue() == 1) {

                $this->firstNameInput($this, $user);
//                foreach ($fields as $field) {
//                    $this->ask($field . ':', function (Answer $value) use ($field, $user) {
//                        $user[$field] = $value;
//                        $this->bot->reply($field . ' | ' . $value);
//                    });
//                }
            }
            else $this->bot->reply('-_-');
        });
    }

    public function statusInput($user)
    {
        $this->bot->ask(Question::create('Select status')->addButtons([
            Button::create('Status 1')->value('Status 1'),
            Button::create('Status 2')->value('Status 2'),
        ])
            , function (Answer $status) use ($user) {
                $user['status'] = $status;
                $this->bot->reply('Status' . ': ' . $status);

                User::create([
                    'first_name' => $user['first_name'],
                    'last_name' => $user['last_name'],
                    'email' => $user['email'],
                    'phone' => $user['phone'],
                    'platform_id' => $user['platform_id'],
                    'status' => $user['status']
                ]);
                $this->bot->reply('Created');
            });
    }

    public function platformInput($user)
    {
        $this->ask(Question::create('Select platform')->addButtons([
            Button::create('Platform 1')->value(1),
            Button::create('Platform 2')->value(2),
        ])
            , function (Answer $platform) use ($user) {
                $user['platform_id'] = $platform;
                $this->bot->reply('Platform' . ': ' . $platform);

                $this->statusInput($user);
            });
    }

    public function phoneInput($user)
    {
        $this->ask('Phone:', function (Answer $phone) use ($user) {
            $user['phone'] = $phone;
            $this->bot->reply('Phone' . ': ' . $phone);

            $this->platformInput($user);
        });
    }

    public function emailInput($user)
    {
        $this->ask('Email:', function (Answer $email) use ($user) {
            $user['email'] = $email;
            $this->bot->reply('Email' . ': ' . $email);

            $this->phoneInput($user);
        });
    }

    public function lastNameInput($user)
    {
        $this->ask('Second name:', function (Answer $second) use ($user) {
            $user['last_name'] = $second;
            $this->bot->reply('Second name' . ': ' . $second);

            $this->emailInput($user);
        });
    }

    public function firstNameInput($bot, $user)
    {
        $bot->ask('First name:', function (Answer $value) use ($user) {
            $user['first_name'] = $value;
            $this->bot->reply('First name' . ': ' . $value);

//            $this->lastNameInput($user);
        });
    }
}
