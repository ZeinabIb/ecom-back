<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use BotMan\BotMan\Messages\Incoming\Answer;

class BotManController extends Controller
{

    public function handle()
    {
        $botman = app('botman');

        $botman->hears('hello|hi|hey', function($botman) {
            $botman->reply('Hello! Welcome to our e-commerce platform. How can I assist you today?');
        });

        $botman->hears('user authentication', function($botman) {
            $botman->reply('For user authentication, you can register and log in to the platform. Social media login options are available, and users receive an activation email upon registration.');
        });

        $botman->hears('store management', function($botman) {
            $botman->reply('For store management, sellers can create stores and add categories and products under those stores. Admin approval is required for store activation.');
        });



        $botman->fallback(function($botman) {
            $botman->reply('I\'m sorry, I didn\'t understand that. Could you please repeat or specify your query?');
        });

        $botman->listen();
    }
}
