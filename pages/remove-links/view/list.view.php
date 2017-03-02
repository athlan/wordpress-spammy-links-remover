<?php

/* @var $results WP_Query */

$page = $results->query['paged'];

?>
<p>
    List:
</p>

<p>
    Page <?php echo $results->query['paged'] ?> of <?php echo $results->max_num_pages ?>
</p>

<p>
    <?php if($page > 1) : ?>
        <a href="?<?php echo http_build_query(array_merge($_GET, array('p' => $page-1))) ?>" class="button">prev</a>
    <?php endif; ?>
    <?php if($page < $results->max_num_pages) : ?>
        <a href="?<?php echo http_build_query(array_merge($_GET, array('p' => $page+1))) ?>" class="button">next</a>
    <?php endif; ?>
</p>

<style type="text/css">
    .hasExternal td {
        background-color: rgba(255, 0, 0, 0.31);
    }
</style>

<table class="wp-list-table widefat fixed striped posts">
    <thead>
        <tr>
            <th>Title</th>
            <th>Type</th>
            <th>Infected links</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($results->posts as $row) : ?>
        <?php

        $linksInContent = $getLinksInContent($row->post_content);
        $linksInContentCount = count($linksInContent);

        $linksInContentExternal = array_filter($linksInContent, function($row) use($getLinksInContentExternal) {
            return $getLinksInContentExternal($row);
        });
        $linksInContentExternalCount = count($linksInContentExternal);

        $css = array();

        if($linksInContentExternalCount > 0) {
            $css[] = 'hasExternal';
        }

        ?>
        <tr class="<?php echo implode($css) ?>">
            <td>
                <a href="?<?php echo http_build_query(array_merge($_GET, array('id' => $row->ID))) ?>">
                    <?php echo $row->post_title ?>
                </a>
            </td>
            <td><?php echo $row->post_type ?></td>
            <td>
                links: <?php echo $linksInContentCount ?>
                external: <?php echo $linksInContentExternalCount ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>