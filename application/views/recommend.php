<script type="text/javascript">
    $(document).ready(function () {
        $("#recommend_form").submit(function (event) {
            event.preventDefault();
            $("#recommend_form_submit").button('loading');
            $.post("/ajax/recommend_me_ajax", $(this).serialize(), function (data) {
                if (data.errors == '') {
                    $("#recommend_form_submit").button('reset');
                    window.location = "/home";
                } else {
                    $("#recommend_form_submit").button('reset');
                    $("#alert_msg").html(data.errors);
                    $("#alert").slideDown(350);
                }
            }, "json");
        });
    });
</script>

<div class="row-fluid margin-30 clearboth">
    <div class="clearboth span12 well square">
        <h2><?=$this->lang->line('page_title');?></h2>
        <span class="muted"><?=$this->lang->line('page_desc');?></span>
    </div>
<?=form_open("/recommend", 'id="recommend_form"'); ?>

    <p>
        <label for="email" class="lead"><?=$this->lang->line('employers_email');?>:</label>
        <?php echo form_input('email', set_value('email'), 'id="email", class="input-xlarge"');?>
    </p>

    <p class="pull-left  margin-20">
        <label for="first_name" class="lead"><?=$this->lang->line('your_first_name');?>:</label>
        <?php echo form_input('first_name', $first_name, 'id="first_name", class="input-xlarge"');?>
    </p>

    <p class="pull-left margin-20" style="padding-left: 30px;">
        <label for="last_name" class="lead"><?=$this->lang->line('last_name');?>:</label>
        <?php echo form_input('last_name', $last_name, 'id="last_name", class="input-xlarge"');?>
    </p>
    
    <p class="pull-left margin-20" style="padding-left: 50px;">
        <label for="birth_year" class="lead"><?=$this->lang->line('birth_date');?>:</label>
        <?php
        echo form_input('bd_year', $birth_year, 'id="birth_year", class="input-mini", placeholder="'.$this->lang->line('year').'", maxlength="4"'); 
        echo form_input('bd_month', $birth_month, 'placeholder="'.$this->lang->line('month').'", class="input-mini", style="margin-left:-1px;", maxlength="2"');
        echo form_input('bd_day', $birth_day, 'placeholder="'.$this->lang->line('day').'", class="input-mini", style="margin-left:-1px;", maxlength="2"');
        ?>
    </p>

    <p class="clearboth">
        <?=form_submit('submit', $this->lang->line('send_request'), 'data-loading-text="'.lang('loading').'" class="btn btn-primary" id="recommend_form_submit"'); ?>
    </p>
<?=form_close(); ?>
</div>