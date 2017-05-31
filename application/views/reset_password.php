<div class="row-fluid margin-30 clearboth">

    <div class="span12 well square">
        <h2><?=lang('password_reset');?></h2>
        <span class="muted"><?=lang('password_reset_desc');?></span>
    </div>

    <div class="alert alert-info centered"><?=$message;?></div>


    <?php echo form_open('/main/reset_password/' . $code);?>
      
	    <p>
            <label for="new_password" class="lead"><?=lang('new_password');?> (<?=lang('at_least');?> <?php echo $min_password_length;?> <?=lang('chars_long');?>):</label>
		    <?=form_input($new_password);?>
	    </p>

	    <p>
		    <label for="new_password_confirm" class="lead"><?=lang('confirm_new_pass');?>:</label>
		    <?php echo form_input($new_password_confirm);?>
	    </p>

	    <?php echo form_input($user_id);?>

	    <p><?php echo form_submit('submit', lang('done'), 'class="btn btn-primary"');?></p>
      
    <?php echo form_close();?>

</div>