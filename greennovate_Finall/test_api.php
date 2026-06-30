<?php
Auth::loginUsingId(3);
$req = new Illuminate\Http\Request();
$res = app('App\Http\Controllers\ChatController')->getMessages($req);
echo $res->getContent();
