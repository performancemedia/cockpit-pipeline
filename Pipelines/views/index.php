<style>
    table span:hover {
        cursor: default !important;
    }
</style>

<div>
    <h1>Pipelines</h1>
    <div class="uk-grid uk-grid-small uk-align-right">
        <form method="post" action="?stage">
            <input class="uk-button uk-button-primary uk-button-large" type="submit" value="Publish to Stage"/>
        </form>
        <form method="post" action="?prod">
            <input class="uk-button uk-button-danger uk-button-large" type="submit" value="Publish to Production"/>
        </form>
    </div>
    <table class="uk-table uk-table-middle uk-table-responsive">
        <thead class="uk-text-light uk-text-uppercase">
            <tr>
                <th>id</th>
                <th>SHA</th>
                <th>Ref</th>
                <th>Status</th>
                <th>Created at</th>
                <th>Updated at</th>
                <th>Web URL</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($pipelines as $pipeline) : ?>
            <tr>
                <td><?= $pipeline['id'] ?></td>
                <td><?= $pipeline['sha'] ?></td>
                <td>
                    <span class="uk-button uk-button-small" style="background: transparent; border: 1px solid #CCC">
                        <?= $pipeline['ref'] ?>
                    </span>
                </td>
                <td>
                <?php
                    switch($pipeline['status']){
                        case 'passed':
                            $style='background: #4EBA6F; color: #FFF';
                        break;
                        case 'running':
                            $style='background: #2D95BF; color: #FFF';
                        break;
                        case 'canceled':
                            $style='background: #F0C419';
                        break;
                        case 'failed':
                            $style='background: #E74C3C; color: #FFF';
                        default:
                            $var='';
                        break;
                    }
                    $html="<span class='uk-button uk-button-small' style='".$style."'>".$pipeline['status']."</span>";
                    echo $html;
                ?>
                </td>
                <td><?= $pipeline['created_at'] ?></td>
                <td><?= $pipeline['updated_at'] ?></td>
                <td>
                    <a href=<?= $pipeline['web_url'] ?> class="uk-button uk-button-default" target="_blank">Open</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
