<?=form_open("/", 'id="contact_form"');?>

<?php
    $user_email = '';
    $p_email_class = '';
    if ($this->ion_auth->logged_in())
    {
        $user_email = $this->user->email;
        $p_email_class = 'hidden';
    }
?>

<p class="<?=$p_email_class;?>">
    <?=form_input('email', $user_email, 'style="width:97%" placeholder="'.$this->lang->line('contact_your_email').'"');?>
</p>

<p>
    <?=form_textarea('message', '', 'style="width: 97%; resize: none;" placeholder="'.$this->lang->line('contact_message').'"');?>
</p>
<?=form_close();?>
