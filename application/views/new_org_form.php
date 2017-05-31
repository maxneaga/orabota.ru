<script type="text/javascript">
    $(document).ready(function () {

        $("#organization").keyup(function () {
            $.post("/ajax/autocomplete_orgs", $(this).serialize(), function (data) {
                if (data.org_names.length >= 1) {
                    $("#organization").autocomplete({
                      source: data.org_names
                    });
                }
            }, "json");
        });


        $("#job_title").keyup(function () {
            $.post("/ajax/autocomplete_job_titles_employers", $(this).serialize(), function (data) {
                if (data.job_titles.length >= 1) {
                    $("#job_title").autocomplete({
                      source: data.job_titles
                    });
                }
            }, "json");
        });


        $("#region").keyup(function () {
            $.post("/ajax/autocomplete_cities", $(this).serialize(), function (data) {
                if (data.cities.length >= 1) {
                    $("#region").autocomplete({
                      source: data.cities
                    });
                }
            }, "json");
        });

        $("#new_org_form").submit(function (event) {
            event.preventDefault();
            $.post("/ajax/new_org_ajax", $(this).serialize(), function (data) {
                if (data.errors == '') {
                    window.location = "/home";
                } else {
                    $("#alert_msg").html(data.errors);
                    $("#alert").slideDown(350);
                }
            }, "json");
        });
    });
</script>

<div class="row-fluid margin-30 clearboth">
    <div class="clearboth span12 well square">
        <h2><?=lang('add_org');?></h2>
        <span class="muted"><?=lang('add_org_page_desc');?></span>
    </div>

    <?php echo form_open("/home", 'id="new_org_form"');?>
    <p class="pull-left  margin-20">
        <label for="organization" class="lead"><?=lang('your_org_name');?>:</label>
        <?php echo form_input('organization', '', 'id="organization", class="input-xxlarge"');?>
    </p>

    <p class="pull-left margin-20" style="padding-left: 30px;">
        <label for="job_title" class="lead"><?=lang('job_title');?>:</label>
        <?php echo form_input('job_title', '', 'id="job_title", class="input-xlarge"');?>
    </p>

    <p class="clearboth pull-left  margin-20">
        <label for="country" class="lead"><?=lang('country');?>:</label>
        <?php echo form_dropdown('country', $countries, 'id="country"');?>
    </p>

    <p class="pull-left margin-20" style="padding-left: 30px;">
        <label for="region" class="lead"><?=lang('region');?>:</label>
        <?php echo form_input('region', '', 'id="region", class="input-xlarge"');?>
    </p>

    <p class="clearboth"><?php echo form_submit('submit', lang('next').' →', 'class="btn"');?></p>
    
    <?php echo form_close();?>
</div>
