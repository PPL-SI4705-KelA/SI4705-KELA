<?php
$request = Illuminate\Http\Request::create('/message/2', 'DELETE');
$request->headers->set('X-Requested-With', 'XMLHttpRequest');
$request->headers->set('Accept', 'application/json');

// We need to act as the user.
$user = App\Models\User::first(); // Assuming this user is the sender of message 2
Auth::login($user);

$response = app()->handle($request);
echo "Status: " . $response->getStatusCode() . "\n";
echo "Content: " . $response->getContent() . "\n";
