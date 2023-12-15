<?php 

use App\Models\User;

$user = User::where('id', $notification->userid)->first();


if($notification->viewed){
	if($notification->notification_type=='received_message') { ?>
		<a class="notifcontainer" href="{{ url('/home') }}">
			<div class='notification-Viewed'>
				<p>{{ $notification->text() }}</p>
				<p>{{ $notification->created_at }}</p>
			</div>
		</a>
	<?php } else {?>
		<a class="notifcontainer" href="{{ url('/profile/'.$user->username.'') }}">
			<div class='notification-Viewed'>
				<p>{{ $notification->text() }}</p>
				<p>{{ $notification->created_at }}</p>
				<?php if($notification->notification_type=='request_friendship'){ ?>
					<ul class="friendshipbuttons">
						<li>
							<button >✓</button>
						</li>
						<li>
							<button >✕</button>
						</li>
					</ul>
				<?php } ?>
			</div>
		</a>
<?php } }else{ if($notification->notification_type=='received_message'){ ?>
	<a class="notifcontainer" href="{{ url('/home') }}">
		<div class='notification-notViewed'>
			<p>{{ $notification->text() }}</p>
			<form action="{{ route('markNotifViewed', ['id' => $notification->notificationid]) }}" method="POST">
				@csrf
				<button type="submit">Mark as Viewed</button>
			</form>
			<p>{{ $notification->created_at }}</p>
		</div>
	</a>
<?php }else{ ?>
	<a class="notifcontainer" href="{{ url('/profile/'.$user->username.'') }}">
		<div class='notification-notViewed'>
			<p>{{ $notification->text() }}</p>
			<?php if($notification->notification_type=='request_friendship'){ ?>
				<ul class="friendshipbuttons">
					<li>
						<button >✓</button>
					</li>
					<li>
						<button >✕</button>
					</li>
				</ul>
			<?php } ?>
			<form action="{{ route('markNotifViewed', ['id' => $notification->notificationid]) }}" method="POST">
				@csrf
				<button type="submit">Mark as Viewed</button>
			</form>
			<p>{{ $notification->created_at }}</p>
		</div>
	</a>
<?php } } ?>