<?php

namespace App\Http\Controllers;

use App\Http\Conversations\ChangeConversation;
use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;

class FallbackController extends Controller
{
    public function index(BotMan $bot)
    {
        $bot->reply('Sorry, I did not understand these commands.');
        $bot->startConversation(new ChangeConversation());
    }
}
