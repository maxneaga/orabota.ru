<!DOCTYPE html>
<head>
    <?php
        $this->lang->load('global', $this->language->get());
    ?>
    <title><?=$title; ?></title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <?php if (isset($site_description)) { ?>
    <meta name="description" content="<?=$site_description; ?>">
    <?php } ?>
    <meta property="og:image" content="http://orabota.ru/assets/img/logo.gif" />
	<meta property="og:url" content="http://orabota.com" />
	<meta property="og:title" content="orabota" />
	<meta property="og:description" content="Rate your employees. Get to know your potential employees better." />

    <link href='http://fonts.googleapis.com/css?family=PT+Sans&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <link href="/assets/css/jquery-ui-1.10.2.custom.min.css" rel="stylesheet" media="screen">
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="/assets/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
    <link href="/assets/css/custom.css" rel="stylesheet" media="screen">
    
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
        <script src="/assets/js/html5shiv.js"></script>
    <![endif]-->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/jquery-ui-1.10.2.custom.min.js"></script>
	<!--[if lt IE 10]>
        <script type="text/javascript">
    $(document).ready(function () {
        $('[placeholder]').focus(function() {
      var input = $(this);
      if (input.val() == input.attr('placeholder')) {
        input.val('');
        input.removeClass('placeholder');
      }
    }).blur(function() {
      var input = $(this);
      if (input.val() == '' || input.val() == input.attr('placeholder')) {
        input.addClass('placeholder');
        input.val(input.attr('placeholder'));
      }
    }).blur().parents('form').submit(function() {
      $(this).find('[placeholder]').each(function() {
        var input = $(this);
        if (input.val() == input.attr('placeholder')) {
          input.val('');
        }
      })
    });

    });
        </script>
    <![endif]-->
    <script type="text/javascript">
        $(document).ready(function () {
            // Enable tooltips
            $("a").tooltip();
           
            $('.close').click(function () {
                if ($(this).parent().hasClass('alert'))
                    $(this).parent().slideUp(200);
            });

            <?php if ($this->session->flashdata('success_msg')) { ?>
                $("#success_msg").html("<?=$this->session->flashdata('success_msg'); ?>");
                 $("#success").slideDown(350);
            <?php } ?>

            // Remove the success message
            setTimeout(function() {
                $('#success').slideUp(200);
            }, 7000);
        });
		

	// ANALYTICS
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-33857774-3']);
	_gaq.push(['_setDomainName', 'orabota.ru']);
	_gaq.push(['_trackPageview']);

	(function() {
	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
    </script>
</head>
<body>
<div class="container">
    <div class="masthead">
        <form name="people_search_form" class="nav pull-right" action="/people/search" method="post">
            <input type="text" name="searchstr" class="input-xxlarge searchbox" placeholder="<?=$this->lang->line('people_search');?>">
        </form>
        <h2 class="logo"><a href="/" class="none"><?=lang('site_title');?><span class="label" style="vertical-align: top; font-family: Helvetica,Arial,sans-serif">Beta</span></a></h2>
    </div>

    <div class="alert alert-error clearboth fade in" id="alert">
        <button type="button" class="close">&times;</button>
        <div id="alert_msg"></div>
    </div>

    <div class="alert alert-success clearboth fade in" id="success">
        <button type="button" class="close">&times;</button>
        <div id="success_msg"></div>
    </div>
