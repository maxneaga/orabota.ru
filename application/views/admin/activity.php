<div class="row-fluid margin-30 clearboth">

    <ul class="breadcrumb">
        <li><a href="/admin"><?=lang('admin_home');?></a> <span class="divider">/</span></li>
        <li class="active">Последние действия</li>
    </ul>

    <h3>Комментарии</h3>
    <table class="margin-20 table table-striped" style="width: 100%">
    <tbody>
    <tr>
        <th>ID</th>
        <th>Дата</th>
        <th>Должность</th>
        <th>Комментарий</th>
        <th>Действия</th>
    </tr>
    <?php foreach ($comments as $comment) { ?>
        <tr>
            <td><a href="/people/view/<?=$comment->employee_id;?>" target="_blank"><?=$comment->employee_id;?></a></td>
            <td><?=$comment->date;?></td>
            <td><?=$comment->job_title;?></td>
            <td><?=$comment->comment;?></td>
            <td>
                <a href="/admin/approve_comment/<?=$comment->id;?>" class="btn btn-success"><i class="icon-user icon-ok icon-white"></i> Допустимо</button>
                <a href="/admin/delete_comment/<?=$comment->id;?>" class="nodecorate"><i class="icon-user icon-trash"></i> Удалить</a>
            </td>
        </tr>
    <?php } ?>
    </tbody>
    </table>

</div>
