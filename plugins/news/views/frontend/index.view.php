<h1><?php echo __('News', 'news');?></h1>

<div class="news">

    <?php if(count($records)>0):?>
    
        <div class="news-list">
        
            <?php foreach($records as $row):?>
                <?php $news_url = $site_url.'news/'.$row['id'].'/'.$row['slug'];?>
                <div class="news-item">
                
                    <h2><span class="news-date"><?php echo Date::format($row['date'], 'd.m.Y');?></span>&nbsp; <a href="<?php echo $news_url;?>"><?php echo $row['name'];?></a></h2>
                    <div class="news-content"><?php echo News::getContentShort($row['id'], true, $news_url); ?></div>
                </div><!-- /news-item-->
                
            <?php endforeach;?>
            
        </div><!-- /news-list-->
    
    <?php endif;?>
    
    <div class="news-paginator"><?php News::paginator($current_page, $pages_count, $site_url.'news/page/');?></div>
</div><!-- /news -->