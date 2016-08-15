<?php
include __DIR__. '/vendor/koraktor/steam-condenser/lib/steam-condenser.php';
include __DIR__.'/vendor/autoload.php';
require __DIR__.'/ns2.inc.php';

$discord = new \Discord\Discord([
    'token' => 'x',
]);
$ns2 = new ns2();

$discord->on('ready', function ($discord) {
    echo "Bot is ready.", PHP_EOL;
  
    $discord->on('message', function ($message) {
        echo "Recieved a message from {$message->author->username}: {$message->content}", PHP_EOL;
	global $ns2;
	if ($message->content == "?playground") {
		$reply = $ns2->replyServer($ns2->getDetails("85.195.247.117","27016"));
		$message->reply($reply);
	}
	if ($message->content == "?hamsterwheel") {
		$reply = $ns2->replyServer($ns2->getDetails("85.195.247.117","27018"));
		$message->reply($reply);
	}
	if ($message->content == "?faded") {
		$reply = $ns2->replyServer($ns2->getDetails("85.195.247.117","27020"));
		$message->reply($reply);
	}
	if ($message->content == "?defense") {
		$reply = $ns2->replyServer($ns2->getDetails("85.195.247.117","27034"));
		$message->reply($reply);
	}
	if ($message->content == "?siege") {
		$reply = $ns2->replyServer($ns2->getDetails("85.195.247.117","27036"));
		$message->reply($reply);
	}
	if ($message->content == "?gungame") {
		$reply = $ns2->replyServer($ns2->getDetails("85.195.247.117","27030"));
		$message->reply($reply);
	}

    });
});

$discord->run();
