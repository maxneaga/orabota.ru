<?php // Format the rating output
 function formatted_rating($rating, $comment = FALSE) {
    if ($rating > 0) {
        if ($comment) {
            return '<span class="label label-success" style="margin-right:10px;">&nbsp;'.lang('good').'&nbsp;</span>';
        }
        return '<span class="text-success">+ '.$rating.'</span>';
    }
    else if ($rating < 0) {
        if ($comment) {
            return '<span class="label label-important" style="margin-right:10px;">&nbsp;'.lang('bad').'&nbsp;</span>';
        }
        return '<span class="text-error">- '.abs($rating).'</span>';
    }

    if ($comment) {
            return '<span class="label" style="margin-right:10px;">&nbsp;'.lang('neutral').'&nbsp;</span>';
        }
    return $rating;
 }
?>

<div class="row-fluid margin-30 clearboth">
    <div class="clearboth span12 well square">
        <h2><?=$user->first_name.' '.$user->last_name; ?></h2>
        <span class="muted" style="line-height: 30px;"><?=lang('age');?>: <?=$user->birth_date; ?></span>
    </div>

    

    <?php if (!empty($comments)) { ?>
    <div class="margin-120 centered alert alert-info"><?=lang('reviews_disclaimer');?></div>
    <?php foreach ($comments as $comment) { ?>
        <?php if ($this->employer->get($comment->employer_id)->accept_pms) { ?>
        <div class="pull-right margin-20">
            <a href="#new_message" onclick="$('#new_message_orgid').val('<?=$comment->org_id;?>'); $('#new_message_subject').val('<?=$user->first_name.' '.$user->last_name; ?>'); $('#message_receiver_title').html('<?=htmlspecialchars($comment->employer_job_title); ?> <?=lang('at');?> <?=htmlspecialchars($comment->org_name);?>'); $('#new_message_form_err').hide();" data-toggle="modal" data-placement="left" title="<?=lang('contact_review_author');?>" class="transparent"><i class="icon-envelope"></i></a><br>
        </div>
        <?php } ?>
        <p class="margin-20 margin-in well">
            <?=formatted_rating($comment->rating, TRUE); ?> <strong><?=$comment->job_title; ?></strong>
            <span class="pull-right"><strong><?=lang('review_by'); ?>:</strong> <?=$comment->employer_job_title; ?></strong> <?=lang('at');?> <u><?=$comment->org_name; ?></u><br>
            <span class="muted pull-right"><small><em><?=$comment->country.', '.$comment->region; ?></em></small></span></span><br>
            <span class="muted"><small><em><?=$comment->date; ?></em></small></span><br>
            <?=nl2br($comment->comment); ?>
        </p>
    <?php }} ?>

    <?php if (!empty($comments_nodob)) { ?>
    <div class="centered alert"><?=lang('nodb_reviews_disclaimer');?></div>
    <?php foreach ($comments_nodob as $comment) { ?>
        <?php if ($this->employer->get($comment->employer_id)->accept_pms) { ?>
        <div class="pull-right margin-20">
            <a href="#new_message" onclick="$('#new_message_orgid').val('<?=$comment->org_id;?>'); $('#new_message_subject').val('<?=$user->first_name.' '.$user->last_name; ?>'); $('#message_receiver_title').html('<?=htmlspecialchars($comment->employer_job_title); ?> <?=lang('at');?> <?=htmlspecialchars($comment->org_name);?>'); $('#new_message_form_err').hide();" data-toggle="modal" data-placement="left" title="<?=lang('contact_review_author');?>" class="transparent"><i class="icon-envelope"></i></a><br>
        </div>
        <?php } ?>
        <p class="margin-20 margin-in well">
            <?=formatted_rating($comment->rating, TRUE); ?> <strong><?=$comment->job_title; ?></strong>
            <span class="pull-right"><strong><?=lang('review_by'); ?>:</strong> <?=$comment->employer_job_title; ?></strong> <?=lang('at');?> <u><?=$comment->org_name; ?></u><br>
            <span class="muted pull-right"><small><em><?=$comment->country.', '.$comment->region; ?></em></small></span></span><br>
            <span class="muted"><small><em><?=$comment->date; ?></em></small></span><br>
            <?=nl2br($comment->comment); ?>
        </p>
    <?php }} ?>

</div>