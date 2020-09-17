<?php
use App\Http\Controllers\BotManController;

$botman = resolve('botman');

$botman->hears('Hi', function ($bot) {
    $bot->reply('Hello!');
    $bot->ask('Whats your name?', function($answer, $bot) {
        $bot->say('Welcome '.$answer->getText());
    });
});
$botman->hears('/start', BotManController::class.'@startConversation');
$botman->hears('/find', BotManController::class.'@find');
$botman->hears('/create', BotManController::class.'@create');

$botman->hears('random', \App\Http\Controllers\AllBreedsController::class.'@random');

$botman->fallback(\App\Http\Controllers\FallbackController::class.'@index');
