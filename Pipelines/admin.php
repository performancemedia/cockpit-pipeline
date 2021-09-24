<?php

$app->on('admin.init', function() {

    // bind admin routes /pipelines/*
    $this->bindClass('Pipelines\\Controller\\Admin', 'pipelines');


    $this->on('cockpit.view.settings.item', function() {
        $this->renderView("pipelines:views/partials/settings-link.php");
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
        }
    });
});