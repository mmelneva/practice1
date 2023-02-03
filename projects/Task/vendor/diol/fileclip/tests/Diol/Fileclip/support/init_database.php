<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Events\Dispatcher;

$capsule = new Capsule();
$capsule->addConnection(
    [
        'driver' => 'sqlite',
        'database' => ':memory:',
    ]
);
$capsule->setAsGlobal();
$capsule->setEventDispatcher(new Dispatcher);
$capsule->bootEloquent();

Capsule::schema()->dropIfExists('nodes');
Capsule::schema()->create(
    'nodes',
    function (Blueprint $table) {
        $table->increments('id');
        $table->string('foo')->nullable();
        $table->string('bar')->nullable();
        $table->timestamps();
    }
);

Capsule::schema()->dropIfExists('images');
Capsule::schema()->create(
    'images',
    function (Blueprint $table) {
        $table->increments('id');
        $table->string('image')->nullable();
        $table->timestamps();
    }
);
