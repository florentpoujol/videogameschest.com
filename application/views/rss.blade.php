<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0">
    <channel>
        <ttl>60</ttl>

        @foreach ($feed_data['channel'] as $markup => $value)
        <{{ $markup }}>{{ $value }}</{{ $markup }}>
        @endforeach

        @foreach ($feed_data['items'] as $item)
        <item>
        @foreach ($item as $markup => $value)
            <{{ $markup }}>{{ $value }}</{{ $markup }}>
        @endforeach
        </item>
        @endforeach
    </channel>
</rss>