<script type="text/javascript">
    
    <?php if($this->language->get() == 'russian') { ?>
    function show_alt_lang_div() {
        $("#add_name_alt_p").fadeOut(500);
        $("#alt_lang_div").delay(500).fadeIn(500);
    }
    <?php } ?>

    $(document).ready(function () {

        $(".collapse").collapse();
        
        // Check if New Organization is selected from the dropdown
        $("#orgid").change(function(){
            if ($(this).val() == 0) {
                window.location = '/home/new_org';
            }
        });

        // Check if birth date unknown
        $("#nodob").click(function(){
            if ($('#nodob').is(':checked')) {
                $("#birth_year").prop('disabled', true);
                $("#birth_month").prop('disabled', true);
                $("#birth_day").prop('disabled', true);
            } else {
                $("#birth_year").prop('disabled', false);
                $("#birth_month").prop('disabled', false);
                $("#birth_day").prop('disabled', false);
            }
        });

        <?php if($this->language->get() == 'russian') { ?>
        // handle the alternative names for russian users
        $("#first_name").keyup(function () {
            var text = $("#first_name").val();

            if (text.length > 1)
                return true;

            if (!text) {
                $("#add_name_alt_p").hide();
                $("#alt_lang_div").hide();
            }

            var regex = /[а-яё]/gi;
            match = regex.exec(text);
            if (match) {
                $("#name_layout_pre").html('латинский');
                $("#name_layout").html('латинице');
                $("#add_name_alt_p").show();
                return true;
            }

            var regex = /[a-z]/gi;
            match = regex.exec(text);
            if (match) {
                $("#name_layout_pre").html('кириллический');
                $("#name_layout").html('кириллице');
                $("#add_name_alt_p").show();
                return true;
            }
        });
        <?php } ?>

        $("#job_title").keyup(function () {
            $.post("/ajax/autocomplete_job_titles", $(this).serialize(), function (data) {
                if (data.job_titles.length >= 1) {
                    $("#job_title").autocomplete({
                        source: data.job_titles
                    });
                }
            }, "json");
        });

        $("#employee_review_form").submit(function (event) {
            event.preventDefault();
            $.post("/ajax/employee_review_ajax", $(this).serialize(), function (data) {
                if (data.errors == '') {
                    window.location = "/home";
                } else {
                    $("#alert_msg").html(data.errors);
                    $("html, body").animate({ scrollTop: 0 }, "slow");
                    $("#alert").slideDown(350);
                }
            }, "json");
        });
    });
</script>


<div class="row-fluid margin-30 clearboth">
    <div class="clearboth span12 well square">
        <h2><?=lang('new_review');?></h2>
        <span class="muted"><?=lang('new_employee_desc');?></span>
    </div>

    <?php echo form_open("/", 'id="employee_review_form"');?>

        <p class="clearboth pull-left  margin-20">
            <label for="orgid" class="lead"><?=lang('rate_as');?>:</label>
            <?php echo form_dropdown('orgid', $orgs, NULL, 'id="orgid"');?>
        </p>

        <p class="clearboth pull-left  margin-20">
            <label for="first_name" class="lead"><?=lang('emp_first_name');?>:</label>
            <?php echo form_input('first_name', '', 'id="first_name" class="input-xlarge"');?>
        </p>

        <p class="pull-left margin-20" style="padding-left: 30px;">
            <label for="last_name" class="lead"><?=lang('last_name');?>:</label>
            <?php echo form_input('last_name', '', 'id="last_name" class="input-xlarge"');?>
        </p>
    
        <p class="pull-left margin-20" style="padding-left: 50px;">
            <label for="birth_year" class="lead"><?=lang('birth_date');?>:</label>
            <?php
            echo form_input('bd_year', '', 'id="birth_year" class="input-mini" placeholder="'.lang('year').'" maxlength="4"'); 
            echo form_input('bd_month', '', 'id="birth_month" placeholder="'.lang('month').'" class="input-mini" style="margin-left:-1px;" maxlength="2"');
            echo form_input('bd_day', '', 'id="birth_day" placeholder="'.lang('day').'" class="input-mini" style="margin-left:-1px;" maxlength="2"');
            ?>
            <label class="checkbox"><?=form_checkbox('nodob', '1', FALSE, 'id="nodob"');?>
                <small><?=lang('birth_date_unknown');?></small>
            </label>
        </p>

        <?php if($this->language->get() == 'russian') { ?>
        <p id="add_name_alt_p" class="clearboth margin-30" style="display: none;">
            <a href="#" id="add_name_alt_link" class="nodecorate" onclick="show_alt_lang_div()">+ <span class="underlined">Добавить <span id="name_layout_pre">латинский</span> вариант</span></a>
        </p>

        <div id="alt_lang_div" class="clearboth" style="display: none">
            <p class="pull-left">
                <label for="first_name" class="lead">Имя на <span id="name_layout" class="emphasized">латинице</span>:</label>
                <?php echo form_input('first_name_alt', '', 'id="first_name_alt" class="input-xlarge"');?>
            </p>

            <p class="pull-left" style="padding-left: 30px;">
                <label for="last_name" class="lead">Фамилия:</label>
                <?php echo form_input('last_name_alt', '', 'id="last_name_alt" class="input-xlarge"');?>
            </p>
        </div>
        <?php } ?>

        <p class="clearboth">
            <label for="job_title" class="lead"><?=lang('job_title');?>:</label>
            <?php echo form_input('job_title', '', 'id="job_title" class="input-xxlarge"');?>
        </p>

        <!--<p class="clearboth margin-30">
            <a class="accordion-toggle nodecorate" data-toggle="collapse" data-target="#div_review">&#x25BC; <span class="underlined"><?=lang('leave_employee_review');?></span></a>
        </p>-->
    
        <!--<div id="div_review" class="collapse in">-->
            <p class="clearboth lead"><?=lang('satisfaction');?>:<br />
                <label class="radio">
                    <?=form_radio('rating', 'good', FALSE, 'id="good"');?> <span class="label label-success">&nbsp;<?=lang('good');?>&nbsp;</span>
                </label>
                <label class="radio">
                    <?=form_radio('rating', 'neutral', TRUE, 'id="neutral"');?> <span class="label">&nbsp;<?=lang('neutral');?>&nbsp;</span>
                </label>
                <label class="radio">
                    <?=form_radio('rating', 'bad', FALSE, 'id="bad"');?> <span class="label label-important">&nbsp;<?=lang('bad');?>&nbsp;</span>
                </label>
            </p>
    
            <p class="clearboth">
                <label for="comment" class="lead"><?=lang('comment');?>:</label>
                <?=form_textarea('comment', '', 'id="comment" style="width: 587px; resize: none;"');?>
            </p>
        <!--</div>-->
        
        <p class="clearboth margin-20">
            <label class="checkbox"><?=form_checkbox('tos_agree', '1', $this->employee->reviewed_by());?>
            <?=lang('tos_agree');?>
            </label>
        </p>

        <p><?php echo form_submit('submit_form', lang('done'), 'class="btn btn-primary"');?></p>
    
    <?php echo form_close();?>

</div>