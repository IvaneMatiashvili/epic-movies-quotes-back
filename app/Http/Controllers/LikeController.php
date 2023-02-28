<?php

namespace App\Http\Controllers;

use App\Events\NotificationStored;
use App\Models\Like;
use App\Models\Notification;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LikeController extends Controller
{
	public function storeOrDeleteLike(Request $request): JsonResponse
	{
		$user = auth()->user();
		$quote = Quote::where('id', $request['quote_id'])->first();
		$userLike = $quote->likes->where('user_id', $user->id)->first();
		if ($userLike !== null)
		{
			Like::where('id', $userLike->id)->first()->delete();
			return response()->json(['success' => 'like deleted successfully'], 200);
		}
		else
		{
			$like = Like::create(['user_id' => $user->id, 'quote_id' => $request['quote_id']]);

			if ((int)$request['user_id'] !== $user->id)
			{
				$notification = Notification::create(['is_notification_on'  => true,
					'notificatable_id'                                         => $like->id,
					'notificatable_type'                                       => 'App\Models\Like',

					'user_id' => $request['user_id'],
				]);

				$createdNotification = Notification::where('id', $notification->id)->first();
				event(new NotificationStored($createdNotification));
			}

			return response()->json($like, 201);
		}
	}
}
