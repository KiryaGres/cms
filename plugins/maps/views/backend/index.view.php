<h2><?php echo __('Maps', 'maps');?></h2>
<ul class="nav nav-tabs">
    <li <?php if (Notification::get('address')) { ?>class="active"<?php } ?>><a href="#address" data-toggle="tab"><?php echo __('Add address', 'maps'); ?></a></li>
    <li <?php if (Notification::get('settings')) { ?>class="active"<?php } ?>><a href="#settings" data-toggle="tab"><?php echo __('Settings', 'maps'); ?></a></li>
</ul>
         
<div class="tab-content tab-page">
    <div class="tab-pane <?php if (Notification::get('address')) { ?>active<?php } ?>" id="address">
        <?php 
        echo (
            Form::open().
            __('Address', 'maps').Html::Br().
            Form::input('address').Html::Br().
            __('Phones', 'maps').Html::Br().
            Form::input('phones').Html::Br().
            Form::hidden('csrf', Security::token()).
            Form::submit('submit_add_address', __('Add address', 'maps'), array('class' => 'btn')).
            Form::close()
        );
        ?>            
    </div>
    <div class="tab-pane <?php if (Notification::get('settings')) { ?>active<?php } ?>" id="settings">
        <?php 
        $zoom = $zoomc = array_combine(range(3,20), range(3,20));

        echo (
            '<form onSubmit="return mapsSettingsSave(this);" method="post">'.
            '<div style="float:left; margin-right:15px;">'.
            __('Width maps (px)', 'maps').Html::Br().
            Form::input('width', Option::get('map_width')).Html::Br().
            __('Height maps (px)', 'maps').Html::Br().
            Form::input('height', Option::get('map_height')).Html::Br().
            '</div><div style="float;">'.
            __('Zoom', 'maps').Html::Br().
            //Form::input('zoom', Option::get('map_zoom')).Html::Br().
            Form::select('zoom', $zoom, Option::get('map_zoom')).Html::Br().
            __('Zoom plus', 'maps').Html::Br().
            //Form::input('zoomc', Option::get('map_zoomc')).Html::Br().
            Form::select('zoomc', $zoomc, Option::get('map_zoomc')).Html::Br().
            '</div>'.
            Form::hidden('csrf', Security::token()).
            Form::hidden('siteurl', Option::get('siteurl')).
            Form::hidden('maps_submit_settings', true).
            Form::submit('maps_submit_set', __('Save', 'maps'), array('class' => 'btn')).
            '<div id="maps-settings-result"></div>'.
            Form::close()
        );
        ?>  
    </div>
</div>
<br/>
<table class="table table-bordered">
    <thead>
        <tr>
            <td><?php echo __('Address and phones', 'maps');?></td>
            <td width="30%">&nbsp;</td>
        </tr>
    </thead>
    <tbody>
    <?php     
    if(count($records)>0) {
        foreach($records as $row) {
            echo (
                '<tr><td><b>'.$row['address'].'</b>'.
                Html::Br().
                $row['phones'].'</td><td>'.
                Html::anchor(__('Delete', 'maps'), 'index.php?id=maps&delete_id='.$row['id'].'&token='.Security::token(), 
                    array('class' => 'btn btn-actions', 'onclick' => "return confirmDelete('".__('Delete?', 'maps')."')")).
                '</td></tr>'
            );
        }
    }
    ?>
    </tbody>
</table>