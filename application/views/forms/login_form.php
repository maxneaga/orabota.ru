<script type="text/javascript">
    $(document).ready(function () {
        $("#login").submit(function (event) {
            event.preventDefault();
            $.post("/ajax/login_ajax", $(this).serialize(), function (data) {
                if (data.errors == '') {
                    window.location = "/";
                } else {
                    $("#alert_msg").html(data.errors);
                    $("#alert").slideDown(350);
                }
            }, "json");
        });
    });
</script>



<div class="login-form well centered" id="login_form_div">
    <p class="centered"><strong><?=lang('create_or_sign_in');?></strong></p>

    <?=form_open("/", 'id="login"');?>

    <p>
        <?=form_input('email', '', 'placeholder="E-mail"');?>
    </p>

    <p>
        <?php $ph = lang('password');
            echo form_password('password', '', "placeholder=\"$ph\"");
        ?>
        <small class="pull-right"><a href="/main/forgot_password"><?=lang('forgot_password');?></a></small>
    

        <label class="checkbox pull-left"><?=form_checkbox('remember', '1', FALSE, 'id="remember"');?>
            <small><?=lang('remember_me');?></small>
        </label>
    </p>

    <p class="centered"><?=form_submit('submit', lang('create_or_sign_in'), 'class="btn btn-primary"');?></p>

    <?=form_close();?>
</div>
