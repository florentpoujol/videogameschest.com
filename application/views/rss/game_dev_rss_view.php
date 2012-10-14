<?php echo '<?xml version="1.0" encoding="utf-8"?>';?> 
<rss version="2.0"
    xmlns:content="http://purl.org/rss/1.0/modules/content/"
    xmlns:wfw="http://wellformedweb.org/CommentAPI/"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:atom="http://www.w3.org/2005/Atom"
    xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
    xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
    >
    <channel>
        <title><?php echo $channel["title"]; ?></title>
        <link><?php echo $channel["link"]; ?></link>
        <description><?php echo $channel["description"]; ?></description>
        <lastBuildDate><?php echo $channel["lastBuildDate"]; ?></lastBuildDate>
        
        <?php
        if (is_object($items)):
            foreach ($items->result() as $item):
        ?> 
        <item>
            <title><?php echo $item->name; ?></title>
            <link><?php echo site_url("$type/".$item->name); ?></link>
<?php
$field_id = $type."_id";
?>
            <guid><?php echo site_url("$type/".$item->$field_id); ?></guid>
<?php
$item->data = json_decode($item->data, true);

$description = parse_bbcode( substr( $item->data["pitch"], 0, $site_data->feed->pitch_extract_length ) )." ...";
?>
            <description><?php echo $description; ?></description>
            <pubDate><?php echo date_create($item->publication_date)->format($site_data->date_formats->english); ?></pubDate>
        </item>
        <?php
            endforeach;
        endif;
        ?> 
    </channel>
</rss> 