<?php echo '<?xml version="1.0" encoding="utf-8"?>'; ?> 
<rss version="2.0">
    <channel>
        <title><?php echo $channel["title"]; ?></title>
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
            <guid isPermalink="true"><?php echo site_url("$type/".$item->$field_id); ?></guid>
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