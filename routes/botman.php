<?php
use App\Http\Controllers\BotManController;

$botman = resolve('botman');

$botman->hears('/start', BotManController::class.'@startConversation');

$botman->fallback(\App\Http\Controllers\FallbackController::class.'@index');
