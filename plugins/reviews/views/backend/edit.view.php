<?php if (Notification::get('success')) Alert::success(Notification::get('success')); ?>
<?php if (Notification::get('error')) Alert::error(Notification::get('error')); ?>

<h2><?php echo __('Edit', 'reviews');?></h2><br/>

<ul class="breadcrumb">
    <li><a href="index.php?id=reviews"><?php echo __('Reviews', 'reviews');?></a> <span class="divider">/</span></li>
    <li class="active"><?php echo __('Edit', 'reviews');?></li>
</ul>

<?php       
echo (
    Form::open().
    
    Form::label('name', __('Name', 'reviews')).    
    Form::input('name', $row['name'], array('class' => (isset($errors['name_empty'])) ? 'input-xlarge error-field' : 'input-xlarge')).
    ((isset($errors['name_empty'])) ? Html::nbsp(4).'<span style="color:red;">'.$errors['name_empty'].'</span>' : '').
    
    Form::label('date', __('Date', 'reviews')).    
    Form::input('date', Date::format($row['date'], 'Y-m-d H:i:s'), array('class' => (isset($errors['date_empty'])) ? 'input-xlarge error-field' : 'input-xlarge')).
    ((isset($errors['date_empty'])) ? Html::nbsp(4).'<span style="color:red;">'.$errors['date_empty'].'</span>' : '').
    
    Form::label('message', __('Message', 'reviews')).    
    Form::textarea('message', $row['message'], array('style' => 'height:100px', 'class' => (isset($errors['message_empty'])) ? 'span7 error-field' : 'span7')).
    ((isset($errors['message_empty'])) ? Html::nbsp(4).'<span style="color:red;">'.$errors['message_empty'].'</span>' : '').
    
    Form::label('answer', __('Answer admin', 'reviews')).    
    Form::textarea('answer', $row['answer'], array('style' => 'height:100px', 'class' => 'span7')).
    
    Html::br().
    Form::hidden('csrf', Security::token()).
    Form::submit('submit_save_and_exit', __('Save and Exit', 'reviews'), array('class' => 'btn')).Html::nbsp(2).
    Form::submit('submit_save', __('Save', 'reviews'), array('class' => 'btn')).
    Form::close()
);
?>