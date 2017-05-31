<?=form_open("/", 'id="new_message_form"');?>

<?php if (!$this->ion_auth->logged_in())
{
    echo lang('send_pm_please_login');
}
else
{
?>

<p class="muted">
    <strong><?=lang('to');?>:</strong> &nbsp;<span id="message_receiver_title"></span>
</p>
<hr style="margin: 10px; 0;">
<p>
    <?=form_textarea('message', '', 'id="new_message_from_message" style="width: 97%; resize: none;" placeholder="'.$this->lang->line('contact_message').'"');?>
    <?=form_input(array('name' => 'orgid', 'type'=>'hidden', 'id' =>'new_message_orgid'));?>
    <?=form_input(array('name' => 'subject', 'type'=>'hidden', 'id' =>'new_message_subject'));?>
</p>
<?=form_close();?>

<?php 
}
?>