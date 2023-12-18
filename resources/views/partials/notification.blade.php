@php
use App\Models\User;

$user = User::where('id', $notification->userid)->first();
@endphp

@if($notification->notification_type=='received_message')
	<a class="notifcontainer" href="/messages/{{ $user->username }}">
@else
	<a class="notifcontainer" href="/profile/{{ $user->username }}">
@endif
	<div class="{{ $notification->viewed ? 'notification-Viewed' : 'notification-notViewed' }}">
		<div class="user_header_img">
			<img src="/images/default-user.jpg">
		</div>
		<ul class="user_names">
			<li class="user_full_name">
				{{ $notification->text() }}
			</li>
			<li>
				<ul class="notificationbuttons">
					@if($notification->notification_type=='request_friendship')
						<li>
							<button>✓</button>
						</li>
						<li>
							<button>✕</button>
						</li>
					@endif
					@if(!$notification->viewed)
						<li>
							<form action="{{ route('markNotifViewed', ['id' => $notification->notificationid]) }}" method="POST">
								@csrf
								<button type="submit">Viewed</button>
							</form>
						</li>
					@endif
						<li class="notifdate">
							{{ $notification->created_at }}
						</li>
				</ul>
			</li>
		</ul>
	</div>
</a>
