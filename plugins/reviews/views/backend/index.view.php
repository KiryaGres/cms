<?php if (Notification::get('success')) Alert::success(Notification::get('success')); ?>
<?php if (Notification::get('error')) Alert::error(Notification::get('error')); ?>

<h2><?php echo __('Reviews', 'reviews');?></h2><br/>

<div class="btn-group">
    <a class="btn btn-small" href="index.php?id=reviews&action=settings"><?php echo __('Settings', 'reviews');?></a> 
    <button class="btn btn-small dropdown-toggle" data-toggle="dropdown">
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
        <li><a href="#reviewsCodeModal" role="button" data-toggle="modal"><?php echo __('View Embed Code', 'reviews');?></a></li>
    </ul>
</div>
<br clear="both"/><br/>

<form method="post" onSubmit="">
<table class="table table-bordered">
    <thead>
        <tr>
            <th width="20">&nbsp;</th>
            <th width="80"><?php echo __('Date', 'reviews');?></th>
            <th width="130"><?php echo __('Name', 'reviews');?></th>
            <th><?php echo __('Message', 'reviews'); ?></th>
            <?php if (Option::get('reviews_check') == 'yes') { ?>
                <th>&nbsp;</th>
            <?php } ?>
            <th width="50"><?php echo __('Important', 'reviews');?></th>
            <th width="185"><?php echo __('Actions', 'reviews'); ?></th>
        </tr>
    </thead>
    <?php if (count($records) > 0): ?>
    <tbody>
        <?php foreach ($records as $row):?>
        <tr <?php if (Option::get('reviews_check') == 'yes' and $row['check'] == 0) echo 'class="warning"'; ?>>
            <td><input type="checkbox" name="reviews_delete[]" class="reviews-delete" value="<?php echo $row['id'];?>"/></td>
            <td><?php echo Reviews::getdate($row['date']); ?></td>
            <td><?php echo $row['name']; ?></td>
            <td>
                <?php echo $row['message'];?>
                <?php if ($row['answer'] != '') echo '<blockquote style="margin-bottom:0;"><small>'.$row['answer'].'</small></blockquote>';?>
            </td>
            <?php if (Option::get('reviews_check') == 'yes') { ?>
                <td>
                <?php if ($row['check'] == 0) { ?>
                    <a class="btn btn-small btn-danger reviews-check" href="#" data-id="<?php echo $row['id'];?>" title="<?php echo __('Confirmed', 'reviews');?>"><i class="icon-thumbs-up icon-white"></i></a>
                <?php } ?>
                </td>
            <?php } ?>
            <td><a class="btn btn-small <?php if ($row['important']) echo 'btn-info';?> reviews-impotant-check" href="#" data-id="<?php echo $row['id'];?>"><i class="icon-ok <?php if ($row['important']) echo 'icon-white';?>"></i></a></td>
            <td>
                <?php echo Html::anchor(__('Edit', 'reviews'), 'index.php?id=reviews&row_id='.$row['id'].'&action=edit', array('class' => 'btn btn-small')); ?>
                <?php echo Html::anchor(__('Delete', 'reviews'), 'index.php?id=reviews&row_id='.$row['id'].'&action=delete&page='.Request::get('page').'&token='.Security::token(), array('class' => 'btn btn-small', 'onClick'=>'return confirmDelete(\''.__('Sure you want to remove the :item', 'reviews', array(':item'=>$row['name'])).'\')')); ?>
            </td>
        </tr>
        <?php endforeach;?>
    </tbody> 
    <?php endif; ?>
</table>
<?php echo Form::hidden('csrf', Security::token());?>
<input type="checkbox" class="check-all" style="margin-left:16px; margin-right:13px;"/>
<input type="submit" name="submit_delete_reviews" class="btn delete-reviews-button disabled" disabled="disabled" value="<?php echo __('Delete checked', 'reviews');?>" onClick="return confirmDelete('<?php echo __('Sure you want to delete all the selected records', 'reviews');?>');"/>
</form>

<div class="reviews-paginator"><?php Reviews::paginator($current_page, $pages_count, 'index.php?id=reviews&page=');?></div>

<!-- Modal -->
<div id="reviewsCodeModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="reviewsModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel"><?php echo __('Embed Code', 'reviews');?></h3>
    </div>
    <div class="modal-body">
        <?php echo __('PHP Code', 'reviews');?><br/>
        
        <dl class="dl-horizontal">
            <dt><?php echo __('Count reviews', 'reviews');?></dt>
            <dd><code>&lt;?php echo Reviews::count();?&gt;</code></dd>

            <dt><?php echo __('Random 3', 'reviews');?></dt>
            <dd><code>&lt;?php Reviews::show('random', 3);?&gt;</code></dd>
            
            <dt><?php echo __('Last 3 important', 'reviews');?></dt>
            <dd><code>&lt;?php Reviews::show('last', 3, 'important');?&gt;</code></dd>
        </dl>
    
        <?php echo __('Shortcode', 'reviews');?><br/>
        
        <dl class="dl-horizontal">
            <dt><?php echo __('Count reviews', 'reviews');?></dt>
            <dd><code>{reviews show="count"}</code></dd>

            <dt><?php echo __('Random 3', 'reviews');?></dt>
            <dd><code>{reviews show="random" count="3"}</code></dd>
            
            <dt><?php echo __('Last 3 important', 'reviews');?></dt>
            <dd><code>{reviews show="last" count="3" label="important"}</code></dd>
        </dl>
    </div>
</div>