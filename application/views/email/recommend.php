<?php
    $this->lang->load('email/recommend_email', $this->language->get());
?>
<html>
<body>
	<h1><?=lang('request_from'); ?> <?=$first_name; ?> <?=$last_name; ?></h1>
	<p>
	    <?=lang('hello'); ?>,<br />
	    <strong><?=$first_name; ?> <?=$last_name; ?></strong> <?=lang('claims_and_requested'); ?> <?=anchor(); ?>.
	</p>
	
	<p>
	    <?=lang('would_appreciate'); ?> <?=$first_name; ?> <?=$last_name; ?>. <?=lang('be_objective'); ?> <?=$first_name; ?><?=lang('performance_commitment'); ?>.
	</p>

	<p>
	    <?=lang('if_forgot'); ?>, <?=$first_name; ?><?=lang('birthday'); ?> <strong><?=$bd_year.'-'.$bd_month.'-'.$bd_day; ?></strong> <?=lang('ymd'); ?>.
	</p>

    <p>
	    <?=lang('signature'); ?>
	</p>
</body>
</html>
