<div class="row-fluid margin-30 clearboth">
    <div class="clearboth span12 well well-small square">
        <h2 class="muted"><img src="/assets/img/search-icon-pageinfo.png" style="vertical-align: baseline;" /><?=$searchstr; ?></h2>
    </div>

    <div class="clearboth margin-in">
        <p class="lead"><?=$message; ?></p>

        <table class="lead">
            <tr>
                <td style="min-width: 320px;"></td>
                <td>
                    <span class="muted">
                        <?php
                            if (sizeof($results))
                            {
                                echo lang('age');
                            }
                        ?>
                    </span>
                </td>
            </tr>
            <?php foreach ($results as $result) { ?>
            <tr>
                <td style="min-width: 320px;"><a href="/people/view/<?=$result->id; ?>"><?=$result->first_name.' '.$result->last_name; ?></a></td>
                <td class="centered"><?=age($result->birth_date); ?></td>
            </tr>
            <?php } ?>
        </table>

        <p><?=$pagination;?></p>
    </div>
</div>
