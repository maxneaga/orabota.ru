<div class="row-fluid margin-30 clearboth">
    <div class="clearboth span12 well square">
        <h2><?=lang('people');?></h2>
    </div>

    <?php
        foreach($name_indexes_en as $index) {
            echo '<span class="lead"><a href="/people/search/'.$index.'">'.$index.'</a>&nbsp;&nbsp;&nbsp;</span>';
        }

        echo '<br><br>';

        foreach($name_indexes_ru as $index) {
            echo '<span class="lead"><a href="/people/search/'.$index.'">'.$index.'</a>&nbsp;&nbsp;&nbsp;</span>';
        }
    ?>

</div>
