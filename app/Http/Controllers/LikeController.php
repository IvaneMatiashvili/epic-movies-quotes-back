<?php

namespace App\Http\Controllers;

use App\Events\NotificationStored;
use App\Models\Likes;
use App\Models\Notifications;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LikeController extends Controller
{
	public function storeOrDeleteLike(Request $request): JsonResponse
	{
		$user = auth()->user();
		if (isset($request['like_id']))
		{
			Likes::where('id', $request['like_id'])->first()->delete();
			return response()->json(['success' => 'like deleted successfully'], 200);
		}
		else
		{
			$like = Likes::create(['user_id' => $user->id, 'quote_id' => $request['quote_id'], 'like' => true]);

			if ((int)$request['user_id'] !== $user->id)
			{
				$notification = Notifications::create(['is_notification_on' => true,
					'notificatable_id'                                         => $like->id,
					'notificatable_type'                                       => 'App\Models\Likes',

					'user_id' => $request['user_id'],
				]);

				$createdNotification = Notifications::where('id', $notification->id)->first();
				event(new NotificationStored($createdNotification));
			}

			return response()->json($like, 201);
		}
	}
}
