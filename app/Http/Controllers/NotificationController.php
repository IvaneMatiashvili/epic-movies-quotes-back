<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
	public function index(): JsonResponse
	{
		$user = auth()->user();
		$notifications = $user->notifications;
		$start = (int) request()->query('start');

		$filterNotifications = collect($notifications)
			->filter(function ($notification) {
				return $notification->user_id === auth()->user()->id;
			});

		$sortedNotifications = collect($filterNotifications)->sortDesc()->slice($start)->take(10)->flatten();
		if ($start === 0)
		{
			$activeNotifications = collect($filterNotifications)->filter(function ($notification) {
				return $notification->is_notification_on === 1;
			});
			$responseArr = [$sortedNotifications, $activeNotifications->count()];
		}
		else
		{
			$responseArr = [$sortedNotifications];
		}
		return response()->json($responseArr, 200);
	}

	public function deleteAll(): JsonResponse
	{
		$user = auth()->user();
		collect($user->notifications)->each(function ($notification) {
			$notification->update(['is_notification_on' => false]);
		});

		return response()->json(['success'=> 'notifications removed successfully'], 200);
	}

	public function delete(): JsonResponse
	{
		$removedNotificationId = request()->input('removed_notification_id');
		Notification::where('id', $removedNotificationId)->update(['is_notification_on' => false]);

		return response()->json(['success'=> 'notification removed successfully'], 200);
	}
}
