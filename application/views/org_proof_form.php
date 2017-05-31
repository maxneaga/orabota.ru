<script type="text/javascript">
    $(document).ready(function () {
        $("#submit_form").click(function () {
            var form = $("#org_proof_form");
            $.post("/ajax/org_proof_ajax", $(form).serialize(), function (data) {
                if (data.errors == '') {
                    $("#submit_form").html('<?=lang('loading'); ?>');
                    $(form).submit();
                } else {
                    $("#alert_msg").html(data.errors);
                    $("#alert").slideDown(350);
                }
            }, "json");
        });

        $("#not_now").click(function () {
            window.location = '/';
        });
    });
</script>

<div class="row-fluid margin-30 clearboth">
    <div class="clearboth span12 well square">
        <h2><?=lang('become_trusted');?></h2>
        <span class="muted"><?=lang('become_trusted_desc');?></span>
    </div>

    <?php echo form_open_multipart("/home/org_proof", 'id="org_proof_form"');?>

        <p class="pull-left  margin-20">
            <label for="first_name" class="lead"><?=lang('your_first_name');?>:</label>
            <?php echo form_input('first_name', set_value('first_name'), 'id="first_name", class="input-xlarge"');?>
        </p>

        <p class="pull-left margin-20" style="padding-left: 30px;">
            <label for="last_name" class="lead"><?=lang('last_name');?>:</label>
            <?php echo form_input('last_name', set_value('last_name'), 'id="last_name", class="input-xlarge"');?>
        </p>
    
        <p class="clearboth">
            <label for="comment" class="lead" style="line-height: 20px;"><?=lang('comment');?>:<br>
            <small><span class="muted" style="font-size: 12px;"><?=lang('comment_desc');?></span></small></label>
            <?php echo form_textarea('comment', '', 'id="comment", style="width: 587px; resize: none;"');?>
        </p>
    
        <p>
            <label for="userfile" class="lead" style="line-height: 20px;"><?=lang('attach_file');?>:<br>
            <small><span class="muted" style="font-size: 12px;"><?=lang('attach_file_desc');?></span></small></label>
            <?php echo form_upload('userfile', '', 'id="userfile"');?>
        </p>

        <?php echo form_hidden('orgid', $orgid);?>
  
        <br>
        <p><?=form_button('submit_form', lang('not_now'), 'id="not_now", class="btn"');?> &nbsp;&nbsp;&nbsp; <?=form_button('submit_form', lang('done'), 'id="submit_form", class="btn btn-primary"');?></p>
    
    <?php echo form_close();?>

</div>