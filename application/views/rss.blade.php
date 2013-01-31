<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0">
    <channel>
        <ttl>60</ttl>

        @foreach ($feed_data['channel'] as $markup => $value)
            <{{ $markup }}>{{ $value }}</{{ $markup }}>
        @endforeach
        
        <!--
        <channel options/>

        <language>en</language>
        <image>
          <url>http://bootsnipp.com/img/logo.jpg</url>
        </image>
        <managingEditor></managingEditor>
        <webMaster></webMaster>
        <rating>SFW</rating>
        <category>Webdesign</category> 
        -->

        @foreach ($feed_data['items'] as $item)
            <item>
                 @foreach ($item as $markup => $value)
                    <{{ $markup }}>{{ $value }}</{{ $markup }}>
                @endforeach
                <!--
                <title>Call to action large button</title>
                <description>Describe something and call to action! &lt;br&gt;&lt;a href="http://bootsnipp.com/snipps/call-to-action-large-button"&gt;&lt;img src="http://bootsnipp.com/uploads/UpdKvYmmkUilvLiTuGbCJqNMlMplKCVQ_small.png" /&gt;&lt;/a&gt;&lt;br&gt;By: Ross Masters</description>
                <link>http://bootsnipp.com/snipps/call-to-action-large-button</link>
                <author>Bootsnipp Admin</author>
                <guid>http://bootsnipp.com/snipps/call-to-action-large-button</guid>
                <pubDate>Mon, 21 Jan 2013 20:55:48 -0800</pubDate> -->
            </item>
        @endforeach
    </channel>
</rss>
