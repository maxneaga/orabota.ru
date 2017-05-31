<script type="text/javascript">
    $(document).ready(function () {
        $("#oauth_btn").click(function () {
            window.location = "<?=$oauth_link; ?>";
        });

        $("#no_google_btn").click(function () {
			$('#login_options').fadeOut("1000", function(){
				$("#login_form_div").fadeIn("1000");
			});
        });

        var words = [
            '<?=lang("employees");?>',
            '<?=lang("co-workers");?>',
            '<?=lang("employers");?>',
            '<?=lang("partners");?>'
            ], i = 1;

        setInterval(function(){
            $('#rateyour').fadeOut(function(){
                $(this).html(words[i++]).fadeIn();
                if (i==words.length)
                    i=0;
            });
        }, 2000); // 2 seconds
    });
</script>

<!-- Main Page -->
<div class="well margin-50">
    <div class="row-fluid clearboth">
        <div class="span6" style="text-align: right"><h1><?=lang('rate_your');?></h1></div> 
        <div class="span6"><h1><span id="rateyour"><?=lang('employees');?></span></h1></div>
    </div>

    <div class="row-fluid">
        <div class="span12 centered lead muted margin-30"><?=lang('main_desc');?></div>
    </div>

    <div class="row-fluid">
        <div class="span12 centered">
			<div id="login_options">
				<button type="button" class="btn btn-primary btn-large" id="oauth_btn"><?=lang('sign_in_with_google');?></button><br>
				<small>
					<span class="muted">- <?=$this->lang->line('or');?> -</span><br>
					<button type="button" class="btn-link" id="no_google_btn"><?=$this->lang->line('no_google_acc');?></button>
				</small>
			</div>
			<?php $this->load->view('forms/login_form') ?>
        </div>
    </div>	
</div>
