<script type="text/javascript">
    $(document).ready(function () {
        $().dropdown('toggle');

        $('.dropdown-menu input, .dropdown-menu label').click(function (e) {
            e.stopPropagation();
        });

        /*
        *  Employee info sidebar
        */
        var active_comment_id;
        $(".employee_link").click(function () {
            // reset the rating buttons
            $(".employee_info_rating").addClass("transparent");
            $("#employee_info_name").html('<?=lang('loading'); ?>');
            $("#employee_info_comment").html('');

            var employee_org_ids = this.id.split('_');
            var employee_name = $("#" + this.id).html();

            $.post("/ajax/get_review_details_ajax", { employee_id: employee_org_ids[1], org_id: employee_org_ids[3] }, function (data) {
                if (data.errors == '') {
                    // Set employee's name
                    $("#employee_info_name").html(employee_name);
                    // rating
                    $("#employee_info_" + data.comment.rating).removeClass("transparent");
                    // comment
                    $("#employee_info_comment").html(data.comment.comment);
                    // Check if comment empty -> display placeholder text
                    if (data.comment.comment == '') {
                        $("#employee_info_comment").html('<span class="muted"><?=lang('leave_employee_review');?></span>');
                    }
                    $("#employee_info_textarea").val(data.comment.comment);

                    active_comment_id = data.comment.id;
                } else {
                    alert(data.errors);
                }
            }, "json");


            $("#employee_info").hide();
            $("#employee_info_id").html(this.id);
            $("#employee_info").fadeIn(300);
        });

        $(".employee_info_rating").click(function () {
            var employee_rating_id = this.id;
            employee_rating = employee_rating_id.split('_');
            employee_rating = employee_rating[2];

            $.post("/ajax/set_employee_rating", { comment_id: active_comment_id, rating: employee_rating });

            $(".employee_info_rating").addClass("transparent");
            $("#" + employee_rating_id).removeClass("transparent");
        });

        // Comment inline edit
        $("#employee_info_comment").click(function () {
            $("#employee_info_comment").hide();
            $("#employee_info_comment_edit").show();
            $("#employee_info_textarea").focus();
        });

        $("#employee_info_comment_edit").focusout(function () {
            $("#employee_info_comment").html($("#employee_info_textarea").val());
            // Check if comment empty -> display placeholder text
            if ($("#employee_info_textarea").val() == '') {
                $("#employee_info_comment").html('<span class="muted"><?=lang('leave_employee_review');?></span>');
            }
            $.post("/ajax/set_employee_comment", { comment_id: active_comment_id, comment: $("#employee_info_textarea").val() });
            $("#employee_info_comment_edit").hide();
            $("#employee_info_comment").show();
        });

        //  ---------------------

        $("#submit_update_password").click(function () {
            var form = $("#update_password");
            $.post("/ajax/update_password_ajax", $(form).serialize(), function (data) {
                if (data.errors == '') {
                    alert('Password changed!');
                } else {
                    alert(data.errors);
                }
            }, "json");
        });
    });

    function toggle_show_password(show) {
        $('#new_password').get(0).type = 'password';
        if (show) {
            $('#new_password').get(0).type = 'text';
        }
    }

    function toggle_accept_pms(accept) {
        $.post("/ajax/set_accept_pms_ajax/"+accept);
    }
</script>


<div class="row-fluid margin-30 clearboth">
    <div class="clearboth span12 well well-small square">
            <div class="pull-right">

                <!-- Settings button & drop-down -->
                <div class="btn-group">
                <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="icon-cog"></i> <?=lang('settings');?>
                    <span class="caret"></span>
                </a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                    <!-- Show password update form only if registered with password -->
                    <?php if ($this->user->password != '0') { ?>
                    <li>
                        <?=form_open('/', 'id="update_password"'); ?>
                        <div class="input-append margin-in">
                            <?=form_password('new_password', '', 'id="new_password", placeholder="'.lang('new_password').'" class="lite"'); ?>
                            <?=form_button('submit_update_password', lang('update'), 'id="submit_update_password", class="btn"');?>
                        </div>
                        <label class="checkbox margin-in"><?=form_checkbox('show_password', 'show', FALSE, 'onClick="toggle_show_password(this.checked);", id="show_password"'); ?> <?=lang('show_password');?></label>
                        <?=form_close(); ?>
                    </li>
                    <li class="clearboth divider"></li>
                    <?php } ?>
                    <!-- -->
                    <li><label class="checkbox margin-in"><?=form_checkbox('accept_pms', 'accept', (bool)$this->user->accept_pms, 'onClick="toggle_accept_pms(this.checked);", id="accept_pms"'); ?> <?=lang('accept_private_messages');?></label></li>
                    <li><a href="/main/logout"><i class="icon-user"></i> <?=lang('logout');?></a></li>
                </ul>
                </div>
            </div>
        
        <a href="/home/new_employee_review" class="btn btn-primary lead"><span class="xbold emphasized">+</span> <?=lang('add_review');?></a>&nbsp;
        <a href="/recommend" class="btn"><i class="icon-bookmark"></i> <?=lang('request_recommendation');?></a>
    </div>

    <div id="employee_info" class="clearboth span5 pull-right well" style="display: none;">
        <p id="employee_info_name" class="lead"></p>
        <p>
            <span id="employee_info_1" class="employee_info_rating label label-success transparent" style="margin-right:10px;">&nbsp;<?=lang('good'); ?>&nbsp;</span>
            <span id="employee_info_0" class="employee_info_rating label btn-inverse transparent" style="margin-right:10px;">&nbsp;<?=lang('neutral'); ?>&nbsp;</span>
            <span id="employee_info_-1" class="employee_info_rating label label-important transparent" style="margin-right:10px;">&nbsp;<?=lang('bad'); ?>&nbsp;</span>
        </p>
        <p id="employee_info_comment"></p>
        <p id="employee_info_comment_edit" style="display: none"><textarea id="employee_info_textarea"></textarea></p>
        <p id="employee_info_id" class="hidden"></p>
    </div>

    <table class="margin-in">
        <tbody>
        <?php foreach ($orgs as $org) { ?>
            <tr>
                <td class="lead" colspan="2"><?=$org->job_title;?> <?=lang('at');?> <?=$org->organization;?></td>
            </tr>
            <?php foreach ($org->employees as $emp) { ?>
            <tr>
                <td><a id="employee_<?=$emp->id; ?>_org_<?=$org->id; ?>" class="employee_link" href="#<?=$emp->id; ?>"><?=$emp->first_name.' '.$emp->last_name; ?></a></td>
            </tr>
            <?php } ?>
            <tr><td colspan="2">&nbsp;</td></tr>
        <?php } ?>
        </tbody>
    </table>

</div>