A PHP wrapper class for Tiny Tiny RSS API.

Usage:

```php
require_once 'ttrssapi.php';

$trss = new TTRSSAPI( 'URL_TO_TTRSS', 'USERNAME', 'PASSWORD');
$cid = $trss -> getCategoryIdByTitle( "CATEGORY_NAME");
$feeds = $trss -> getHeadlines( $cid, 1, 'true', 'false', 'false');
if( isset( $feeds['content'][0]['id'])) {
    $a_id = $feeds['content'][0]['id'];
    $a = $trss -> getArticle( $a_id);
    $a_title = $a['content'][0]['title'];
    $a_url = $a['content'][0]['link'];
    $a_author = $a['content'][0]['author'];
    $a_content = $a['content'][0]['content'];
    print_r( "a_title=".$a_title."\n");
    print_r( "a_url=".$a_url."\n");
    print_r( "a_author=".$a_author."\n");
    print_r( "a_content=".$a_content."\n");
    $status = $trss -> updateArticle( $a_id);
}

```

Copyright (c) 2013 Anatoliy Kultenko "tofik".
Released under the BSD License, see http://opensource.org/licenses/BSD-3-Clause
