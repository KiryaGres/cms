<div class="news">
    <div class="news-item">
        <h1><?php echo $row['h1'];?></h1>
        
        <div class="news-status">
            <div class="news-fleft">
                <a href="<?php echo $site_url;?>news" class="news-back"><?php echo __('See all news', 'news');?></a> &nbsp;/&nbsp; 
                <?php echo Date::format($row['date'], 'd.m.Y');?> &nbsp;/&nbsp; 
                <?php echo __('Hits count', 'news');?>: <?php echo $row['hits'];?>
            </div>
            <div class="news-fright">&nbsp;<?php Action::run('news_item_status', array('id' => $row['id']));?></div>
        </div>
        
        <div class="news-content"><?php echo News::getContentShort($row['id'], false);?></div>

    </div><!-- /news-item-->
</div>
<?php Action::run('news_current_footer', array('id' => $row['id']));?>