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

$botman->hears('random', \App\Http\Controllers\AllBreedsController::class.'@random');

$botman->fallback(\App\Http\Controllers\FallbackController::class.'@index');

//$botman->hears('Hello BotMan!', function($bot) {
//    $bot->reply('Hello!');
//    $bot->ask('Whats your name?', function($answer, $bot) {
//        $bot->say('Welcome '.$answer->getText());
//    });
//});
//
//$botman->listen();

