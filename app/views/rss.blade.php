<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0">
    <channel>
        @foreach ($feed_data['channel'] as $markup => $value)
            <{{ $markup }}>{{ $value }}</{{ $markup }}>
        @endforeach

        @foreach ($feed_data['items'] as $item)
            <item>
                @foreach ($item as $markup => $value)
                    <?php
                    if ($markup == 'guid isPermalink="false"') {
                        $closing_markup = 'guid';
                    } else $closing_markup = $markup;

                    if ($markup == 'description') $value = '<![CDATA['.$value.']]>';
                    ?>

                    <{{ $markup }}>{{ $value }}</{{ $closing_markup }}>
                @endforeach
            </item>
        @endforeach
    </channel>
</rss>