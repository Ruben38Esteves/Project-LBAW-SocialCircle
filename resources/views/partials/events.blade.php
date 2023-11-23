<div class="event">
    <div class="eventHeader">
        {{$event->title}}
        {{$event->content }}
    </div>
    <?php $posts=$event->posts; ?>
    @each('partials.posts', $posts, 'post')
</div>