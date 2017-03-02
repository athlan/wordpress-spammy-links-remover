<?php

/* @var $results WP_Query */
$row = $result;

$links = $getLinksInContent($row->post_content);

?>
<p>
    Post:
</p>

<style type="text/css">
    .hasExternal td {
        background-color: rgba(255, 0, 0, 0.31);
    }
</style>

<form action="" method="post">
    <table class="wp-list-table widefat fixed striped posts">
        <thead>
        <tr>
            <th>Remove</th>
            <th>Url</th>
            <th>Content</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($links as $link) : ?>
            <?php

            $isExternal = $getLinksInContentExternal($link);

            $css = array();

            if($isExternal) {
                $css[] = 'hasExternal';
            }

            ?>
            <tr class="<?php echo implode($css) ?>">
                <td>
                    <input type="checkbox" name="content_removal[]" value="<?php echo htmlentities($link['content']) ?>" />
                </td>
                <td>
                    <?php echo $link['url'] ?>
                </td>
                <td>
                    <?php echo $link['content'] ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <p>
        <input type="submit" class="button" value="Remove selected" />
    </p>
</form>

<?php echo($row->post_content) ?>
