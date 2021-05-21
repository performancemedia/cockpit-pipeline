<?php

$app->on('admin.init', function() {


    // bind admin routes /pm-pipelines/*
    $this->bindClass('PmPipelines\\Controller\\Admin', 'pm-pipelines');


    $this->on('cockpit.view.settings.item', function() {
        $this->renderView("pmpipelines:views/partials/settings-link.php");
    });

    $this->on('app.layout.header', function() {

        if ($file = $this->path('#storage:components.json')) {

            $content = @file_get_contents($file);

            if (!$content) {
                return;
            }

            ?>
            <script>
                window.CP_LAYOUT_COMPONENTS = App.$.extend(window.CP_LAYOUT_COMPONENTS || {}, <?=$content?>);
            </script>
            <?php
            //echo $this->script('#config:components.js', time());
        }
    });
});