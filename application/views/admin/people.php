<div class="row-fluid margin-30 clearboth">
    <ul class="breadcrumb">
        <li><a href="/admin"><?=lang('admin_home');?></a> <span class="divider">/</span></li>
        <li class="active"><?=lang('people');?></li>
    </ul>

    <?=form_open("/admin/search_people", 'id="adm_search_people_form"'); ?>
    <div class="input-append">
        <?=form_input('search_text', '', 'class="lite span3"'); ?>
        <?=form_submit('search', lang('search'), 'class="btn"'); ?>
    </div>
    <?=form_close(); ?>

    <?=form_open("/admin/mass_remove_people", 'id="adm_people_form"');?>
    <div>
        <?=form_submit('remove', lang('remove_selected'), 'class="btn"');?>
    </div>

    <table class="margin-20 table table-striped" style="width: 50%">
    <tbody>
    <tr>
        <th style="width: 5%"> </th>
        <th><?=lang('name');?></th>
        <th><?=lang('bdate');?></th>
    </tr>
    <?php foreach ($people as $man) { ?>
        <tr>
            <td><?=form_checkbox('uid[]', $man->id);?></td>
            <td><a href="/people/view/<?=$man->id; ?>"><?=$man->first_name.' '.$man->last_name; ?></a></td>
            <td><?=$man->birth_date; ?></td>
        </tr>
    <?php } ?>
    </tbody>
    </table>
    <?php echo form_close();?>

    <p><?=$pagination;?></p>

</div>
