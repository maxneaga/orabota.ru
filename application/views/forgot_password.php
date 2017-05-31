<div class="row-fluid margin-30 clearboth">

    <div class="span12 well square">
        <h2><?=lang('forgot_password');?></h2>
        <span class="muted"><?=lang('forgot_password_desc');?></span>
    </div>

<div class="alert alert-info centered"><?=$message;?></div>

<?php echo form_open("/main/forgot_password");?>

    <p>
        <label for="email" class="lead">E-mail:</label>
        <?php echo form_input('email', set_value('email'), 'id="email", class="input-xlarge"');?>
    </p>
      
      <p><?php echo form_submit('submit', lang('submit'), 'class="btn"');?></p>
      
<?php echo form_close();?>

</div>
