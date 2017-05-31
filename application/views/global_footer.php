</div>

<!-- Contact Us Form -->
<div id="contact_us" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
    <h3 id="myModalLabel"><?=$this->lang->line('contact_contact_us');?></h3>
  </div>
  <div class="modal-body">
        <p id="contact_form_err" class="text-error centered fade in"></p>
        <?php $this->load->view('forms/contact_form') ?>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true"><?=$this->lang->line('cancel');?></button>
    <button class="btn btn-primary" data-loading-text="<?=lang('loading');?>" id="contact_form_submit"><?=$this->lang->line('send_message');?></button>
  </div>
</div>

<!-- New Message Form -->
<div id="new_message" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
    <h3 id="myModalLabel"><?=lang('new_message');?></h3>
  </div>
  <div class="modal-body">
        <p id="new_message_form_err" class="text-error centered fade in"></p>
        <?php $this->load->view('forms/new_message_form') ?>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true"><?=$this->lang->line('cancel');?></button>
    <button class="btn btn-primary" data-loading-text="<?=lang('loading');?>" id="new_message_form_submit"><?=$this->lang->line('send_message');?></button>
  </div>
</div>

<div id="fb-root"></div>

<script type="text/javascript">(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/<?=strtolower($this->language->get(TRUE));?>_<?=strtoupper($this->language->get(TRUE));?>/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));


    $(document).ready(function () {
        $("#contact_form_err").hide();
        $("#new_message_form_err").hide();

        $("#language_select").change(function () {
            window.location.href = '<?=$this->config->item('base_url'); ?>main/lang/'+this.value;
        });

        $("#language_select").val("<?=$this->language->get(true); ?>");
        $("#lang_icon").html('<img src="/assets/img/flags/<?=$this->language->get(true); ?>.png" alt="<?=$this->language->get(true); ?>" style="margin-top: -10px;">');

        $("#contact_form_submit").click(function () {
            $("#contact_form_submit").button('loading');
            var form = $("#contact_form");
            $.post("/ajax/contact_us_ajax", $(form).serialize(), function (data) {
                if (data.errors == '') {
                    $('#contact_us').modal('hide');
                    $("#success_msg").html('<?=$this->lang->line('contact_message_sent');?>');
                    $("#success").slideDown(350);
                    $("#contact_form_submit").button('reset');
                } else {
                    $("#contact_form_err").html(data.errors);
                    $("#contact_form_err").fadeIn(1000);
                    $("#contact_form_submit").button('reset');
                }
            }, "json");
        });


        $("#new_message_form_submit").click(function () {
            $("#new_message_form_submit").button('loading');
            var form = $("#new_message_form");
            $.post("/ajax/contact_review_author_ajax", $(form).serialize(), function (data) {
                if (data.errors == '') {
                    $('#new_message').modal('hide');
                    $("#success_msg").html('<?=$this->lang->line('contact_message_sent');?>');
                    $("#success").slideDown(350);
                    $("#new_message_form_submit").button('reset');
                } else {
                    $("#new_message_form_err").html(data.errors);
                    $("#new_message_form_err").fadeIn(1000);
                    $("#new_message_form_submit").button('reset');
                }
            }, "json");
        });
    });
</script>

<div id="footer" class="container">
        <hr>
        <div class="pull-left muted">
            <?php if ($_SERVER['REQUEST_URI'] == '/' || $_SERVER['REQUEST_URI'] == '/index.php') { ?>
            <small><small>Change language:</small></small><br>
			<span id="lang_icon"></span>
            <select class="language_select" id="language_select">
                <option value="ru" id="lang_russian">Русский</option>
				<option value="en" id="lang_english">English</option>
                <option value="ro" id="lang_romanian">Română</option>
			</select><br>
            <?php } ?>
        </div>
        <div class="pull-left margin-25 margin-in">
            <div class="fb-like" data-href="https://www.facebook.com/orabota.ru" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false"></div>
        </div>
        <div class="pull-right margin-25">
            <small>
                <a href="/people/search/all"><?=$this->lang->line('people_index');?></a>&nbsp;&nbsp;
                    |&nbsp;&nbsp;<a href="/page/tos"><?=$this->lang->line('tos');?></a>&nbsp;&nbsp;
                    |&nbsp;&nbsp;<a href="#contact_us" data-toggle="modal"><?=$this->lang->line('contact_us');?></a>
            </small>
        </div>
        <div class="pull-right margin-25 margin-in muted">
            <small>&copy; 2013 - orabota</small>
        </div>

    <div class="clearboth container centered">
		<p class="lead"><?=lang('tell_friends');?></p>
		<script type="text/javascript">(function() {
		  if (window.pluso)if (typeof window.pluso.start == "function") return;
		  if (window.ifpluso==undefined) { window.ifpluso = 1;
			var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
			s.type = 'text/javascript'; s.charset='UTF-8'; s.async = true;
			s.src = ('https:' == window.location.protocol ? 'https' : 'http')  + '://share.pluso.ru/pluso-like.js';
			var h=d[g]('body')[0];
			h.appendChild(s);
		  }})();</script>
		<div class="pluso" data-background="transparent" data-options="big,square,line,horizontal,nocounter,theme=04" data-services="vkontakte,odnoklassniki,facebook,twitter,email,google" data-url="http://orabota.ru/" data-title="Отзывы о работниках" data-description="orabota.ru — ваш уникальный помощник при найме сотрудников."></div>
	</div>
</div>

</body>
</html>