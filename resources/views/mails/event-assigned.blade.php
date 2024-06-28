{{--@formatter:off--}}
<x-mail::message>
# New Task Assigned

Dear {{ $userName }},<br/>
<br/>
You have been assigned to a new event for <strong>{{ $event->title }}</strong> scheduled at <strong>{{ $event->start_at['date_time'] }}</strong>.
<br>

<x-mail::button :url="route('events.show', $event)">
    View
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
{{--@formatter:off--}}
