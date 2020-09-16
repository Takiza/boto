<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;

class AllBreedsController extends Controller
{
    public function random(BotMan $bot)
    {
        $bot->reply('hi, pedrila');
    }
}
