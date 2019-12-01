<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use Illuminate\Notifications\DatabaseNotification;

$factory->define(DatabaseNotification::class, function (Faker $faker) {
    return [
       'id' => \Str::uuid()->toString(),
       'type' => 'App\Notification\ThreadWasUpdated',
       'notifiable_id' => function () {
           return auth()->id()?: factory('App\User')->create()->id;
       },
         'notifiable_type' => 'App\User',
         'data' => ['foo' => 'bar']
    ];
});
