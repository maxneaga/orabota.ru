<div class="row-fluid margin-30 clearboth">
    <ul class="breadcrumb">
        <li><a href="/admin"><?=lang('admin_home');?></a> <span class="divider">/</span></li>
        <li class="active"><?=lang('employers');?></li>
    </ul>

    <?=form_open("/admin/search_org", 'id="adm_search_org_form"'); ?>
    <div class="input-append">
        <?=form_input('search_text', '', 'class="lite span3"'); ?>
        <?=form_submit('search', lang('search'), 'class="btn"'); ?>
    </div>
    <?=form_close(); ?>

    <?php if ($orgs[0]->status == 'pending') { ?>
    <?=form_open("/admin/mass_reject_org", 'id="adm_orgs_form"');?>
    <div>
        <?=form_button('pool', lang('start_pool'), 'class="btn btn-primary" onClick="window.location=\'/admin/pool\'"'); ?>&nbsp;&nbsp;
        <?=form_submit('reject', lang('reject_selected'), 'class="btn"');?>
    </div>
    <?php } ?>


    <table class="margin-20 table table-striped" style="width: 70%">
    <tbody>
    <tr>
        <th style="width: 50%"><?=lang('job_title');?></th>
        <th style="width: 40%"><?=lang('comment');?></th>
        <th style="width: 10%"><?=lang('status');?></th>
    </tr>
    <?php foreach ($orgs as $org) { ?>
        <tr>
            <td><?=form_checkbox('orgid[]', $org->id);?> <a href="/admin/pool/<?=$org->id; ?>"><?=$org->job_title;?> <?=lang('at');?> <?=$org->organization;?></a></td>
            <td><?=substr($org->comment, 0, 30);?></td>
            <td class="centered"><?=$org->status; ?></td>
        </tr>
    <?php } ?>
    </tbody>
    </table>
    <?php echo form_close();?>

    <p><?=$pagination;?></p>


</div>
