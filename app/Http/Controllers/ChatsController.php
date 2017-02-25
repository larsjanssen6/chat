<?php

namespace App\Http\Controllers;

use App\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatsController extends Controller
{
	/**
	 * ChatsController constructor.
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
    {
    	return view('chat');
    }

	/**
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function fetchMessages()
    {
    	return Message::with('user')->get();
    }

	/**
	 * @param Request $request
	 * @return array
	 */
	public function sendMessage(Request $request)
    {
    	$user = Auth::user();

    	$message = $user->messages()->create([
			'message'   => $request->input('message')
	    ]);

	    broadcast(new MessageSent($user, $message))->toOthers();

	    return ['status' => 'Message Sent!'];
    }
}
