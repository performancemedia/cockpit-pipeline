<div>
    <h1>Pipelines</h1>
    <div>
        <form method="post"><input type="submit" value="Run Pipeline"/></form>
    </div>
    <table>
        <tr>
            <th>id</th>
            <th>sha</th>
            <th>ref</th>
            <th>status</th>
            <th>created_at</th>
            <th>updated_at</th>
            <th>web_url</th>
        </tr>
        <?php foreach($pipelines as $pipeline) : ?>
        <tr>
            <td><?= $pipeline['id'] ?></td>
            <td><?= $pipeline['sha'] ?></td>
            <td><?= $pipeline['ref'] ?></td>
            <td><?= $pipeline['status'] ?></td>
            <td><?= $pipeline['created_at'] ?></td>
            <td><?= $pipeline['updated_at'] ?></td>
            <td><?= $pipeline['web_url'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
