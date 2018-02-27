<?php

/*
 * Tiny Tiny RSS API class definition
 * https://github.com/tofika/tt-rss-api-php-class
 *
 * @author Anatoliy Kultenko "tofik"
 * @license BSD http://opensource.org/licenses/BSD-3-Clause
 */

class TTRSSAPI
{
    private $t_api_url;
    private $t_session_id;

    public function __construct( $url, $username, $password)
    {
        $this -> t_api_url = $url;
        $this -> t_login( $username, $password);
    }

    private function t_login( $username, $password)
    {
        $params = array( "op" => "login", "user" => $username, "password" => $password);
        $params = json_encode( $params);
        $response = $this -> t_api_query( $this->t_api_url, $params);
        if ( $response['code'] == 200) {
            $tarray = json_decode( $response['text'], true);
            if( isset( $tarray['content']['session_id']))
                $this -> t_session_id = $tarray['content']['session_id'];
        }
    }

    private function t_api_query( $url, $postfields)
    {
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_POST, true);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $postfields);
        curl_setopt( $ch, CURLOPT_URL, $url);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
        $response = array();
        $response['text'] = curl_exec( $ch);
        $response['code'] = curl_getinfo( $ch, CURLINFO_HTTP_CODE);
        curl_close( $ch);
        return $response;
    }

    public function getCategories( $unread_only = 'false', $enable_nested = 'true')
    {
        $params = array( "sid" => $this -> t_session_id, "op" => "getCategories", "unread_only" => $unread_only, "enable_nested" => $enable_nested);
        $params = json_encode( $params);
        $response = $this -> t_api_query( $this->t_api_url, $params);
        if ( $response['code'] == 200) {
            $tarray = json_decode( $response['text'], true);
            return $tarray;
        } else return false;
    }

    public function getFeeds( $cat_id = -3, $unread_only = 'false', $limit = 0, $offset = 0, $include_nested = 'true')
    {
        $params = array( "sid" => $this -> t_session_id, "op" => "getFeeds", "cat_id" => $cat_id, "unread_only" => $unread_only,
                         "limit" => $limit, "offset" => $offset, "include_nested" => $include_nested);
        $params = json_encode( $params);
        $response = $this -> t_api_query( $this->t_api_url, $params);
        if ( $response['code'] == 200) {
            $tarray = json_decode( $response['text'], true);
            return $tarray;
        } else return false;
    }

    public function getFeedIdByTitle( $title)
    {
        $tarray = $this -> getFeeds();
        if( !is_array( $tarray)) return false;
        foreach( $tarray['content'] as $key => $value)
        {
            if( $tarray['content'][$key]['title'] == $title)
            return $tarray['content'][$key]['id'];
        }
        return false;
    }

    public function getCategoryIdByTitle( $title)
    {
        $tarray = $this -> getCategories();
        if( !is_array( $tarray)) return false;
        foreach( $tarray['content'] as $key => $value)
        {
            if( $tarray['content'][$key]['title'] == $title)
            return $tarray['content'][$key]['id'];
        }
        return false;
    }

    public function getHeadlines( $feed_id, $limit, $is_cat = 'false', $show_excerpt = 'true', $show_content = 'true', $view_mode = 'unread', $order_by = 'date_reverse')
    {
        $params = array( "sid" => $this -> t_session_id, "op" => "getHeadlines", "feed_id" => $feed_id, "limit" => $limit, "is_cat" => $is_cat,
                         "show_excerpt" => $show_excerpt, "show_content" => $show_content, "view_mode" => $view_mode, "order_by" => $order_by);
        $params = json_encode( $params);
        $response = $this -> t_api_query( $this->t_api_url, $params);
        if ( $response['code'] == 200) {
            $tarray = json_decode( $response['text'], true);
            if( ($tarray["status"] == 0) && ( count( $tarray['content']) > 0)) {
                return $tarray;
            }
        }
        return false;
    }

    public function getArticle( $article_id)
    {
        $params = array( "sid" => $this -> t_session_id, "op" => "getArticle", "article_id" => $article_id);
        $params = json_encode( $params);
        $response = $this -> t_api_query( $this->t_api_url, $params);
        if ( $response['code'] == 200) {
            $tarray = json_decode( $response['text'], true);
            return $tarray;
        } else return false;
    }

    public function updateArticle( $article_ids, $mode = 0, $field = 2) // default set false ($mode = 0) to unread ($field = 2) on article $article_ids
    {
        $params = array( "sid" => $this -> t_session_id, "op" => "updateArticle", "article_ids" => $article_ids, "mode" => $mode, "field" => $field);
        $params = json_encode( $params);
        $response = $this -> t_api_query( $this->t_api_url, $params);
        if ( $response['code'] == 200) {
            $tarray = json_decode( $response['text'], true);
            return $tarray;
        } else return false;
    }
    public function updateFeed($feed_id)
	{
		$params = array( "sid" => $this -> t_session_id, "op" => "updateFeed", "feed_id" => $feed_id);
        $params = json_encode( $params);
        $response = $this -> t_api_query( $this->t_api_url, $params);
        if ( $response['code'] == 200) {
            $tarray = json_decode( $response['text'], true);
            return $tarray;
        } else return false;	
	}

}

?>
