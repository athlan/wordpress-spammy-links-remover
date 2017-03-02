<?php

$siteUrl = get_site_url();

$getLinksInContent = function($str) {
    $pattern = '#<a(.*?)href=((\'|"))(http(s)?:(.*?))(\'|")(.*?)>(.*?)</a>#';
    $flags = 0;

    preg_match_all ($pattern, $str, $matches, $flags);
    $res = array();
    foreach($matches[0] as $k => $v) {
        $res[] = array(
            'content' => $v,
            'url' => $matches[4][$k],
        );
    }

    return $res;
};

$getLinksInContentExternal = function($row) {
    $url = strtolower($row['url']);
    
    if(strpos($url, $siteUrl) === 0) {
        return false;
    }
    
    return strpos($url, 'http:') === 0 || strpos($url, 'https:') === 0;
};

$queryListPosts = function($filter = array()) {
    $queryArgs = array(
        'paged' => is_numeric($filter['page']) ? (int) $filter['page'] : 1,
        'posts_per_page' => is_numeric($filter['limit']) ? (int) $filter['limit'] : 20,
        'orderby' => 'date',
        'order' => 'DESC',
        'post_type' => 'any',
    );
    $posts = new WP_Query($queryArgs);

    return $posts;
};

$queryPost = function($id) {
    $queryArgs = array(
        'p' => $id,
        'post_type' => 'any',
    );
    $posts = new WP_Query($queryArgs);

    return $posts;
};

$action = function() use ($queryListPosts, $queryPost, $getLinksInContent, $getLinksInContentExternal) {
    global $wpdb;

    if($_GET['id']) {
        $results = $queryPost($_GET['id']);
        $result = $results->posts[0];

        if(isset($_POST['content_removal']) && is_array($_POST['content_removal'])) {
            $replacements = stripslashes_deep($_POST['content_removal']);

            $clean = str_replace($replacements, '', $result->post_content);

            $updated = $wpdb->update($wpdb->posts, array(
                'post_content' => $clean,
            ), array(
                'ID' => $result->ID,
            ));

            $results = $queryPost($_GET['id']);
            $result = $results->posts[0];
        }

        require __DIR__ . '/../view/item.view.php';
    }
    else {
        $filter = array();

        $filter['limit'] = 50;

        if($_GET['p']) {
            $filter['page'] = (int) $_GET['p'];
        }

        $results = $queryListPosts($filter);

        require __DIR__ . '/../view/list.view.php';
    }
};

return $action;
