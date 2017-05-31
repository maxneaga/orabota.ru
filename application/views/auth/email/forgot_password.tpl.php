<html>
<body>
	<h1><?=lang('reset_password_for');?> <?php echo $identity;?></h1>
	<p><?=lang('click_link_to');?> <?php echo anchor('main/reset_password/'. $forgotten_password_code, lang('reset_your_pass'));?>.</p>
</body>
</html>
