<div class="row-fluid margin-30 clearboth">
    <ul class="breadcrumb">
        <li class="active"><?=lang('admin_home');?></li>
    </ul>
    <div class="span2 centered">
        <a href="/admin/activity"><img src="/assets/img/admin/mail-icon.png" /><br>
        Проверка действий [ <?=$total_activity; ?> ]</a>
    </div>
    <div class="span2 centered">
        <a href="/admin/orgs/all"><img src="/assets/img/admin/profile-icon.png" /><br>
        <?=lang('employers');?> [ <?=$total_orgs; ?> ]</a>
    </div>
    <div class="span2 centered">
        <a href="/admin/people"><img src="/assets/img/admin/users-icon.png" /><br>
        <?=lang('people');?> [ <?=$total_people; ?> ]</a>
    </div>

</div>
