<?php
/*
* モバイルポスト プラグイン
* ヘルパー
*
* PHP 5.4.x
*
* @copyright    Copyright (c) hiniarata co.ltd
* @link         https://hiniarata.jp
* @package      Voice Plugin Project
* @since        ver.0.9.0
*/
/**
* Include files
*/
App::import('Model', 'MobilePost.MobilePostConfig');
App::import('Model', 'Blog.BlogContent');
App::uses('AppHelper', 'View/Helper');

/**
* モバイルポスト ヘルパー
*
* @package baser.plugins.mobile_post
*/
class MobilePostBaserHelper extends AppHelper {


  /**
  * 状態の取得
  *
  * @param int $blogContentId
  * @return int
  * @access	public
  */
  public function getMobilePostStatus($blogContentId = null){
    /* 除外処理 */
    if (empty($blogContentId)) {
      return false;
    }
    $mobileConfigModel = new MobilePostConfig();
    $config = $mobileConfigModel->find('first', array('conditions' => array(
      'MobilePostConfig.blog_content_id' => $blogContentId
    )));
    if (empty($config)) {
      return false;
    } else {
      return $config['MobilePostConfig']['status'];
    }
  }


  /**
  * 更新日の取得
  *
  * @param int $blogContentId
  * @return datetime
  * @access	public
  */
  public function getMobilePostModified($blogContentId = null){
    /* 除外処理 */
    if (empty($blogContentId)) {
      return false;
    }
    $mobileConfigModel = new MobilePostConfig();
    $config = $mobileConfigModel->find('first', array('conditions' => array(
      'MobilePostConfig.blog_content_id' => $blogContentId
    )));
    if (empty($config)) {
      return false;
    } else {
      return $config['MobilePostConfig']['modified'];
    }
  }


  /**
  * ブログのタイトルの取得
  *
  * @param int $blogContentId
  * @return string
  * @access	public
  */
  public function getMobilePostBlogTitle($blogContentId = null){
    /* 除外処理 */
    if (empty($blogContentId)) {
      return false;
    }
    $blogContent = new BlogContent();
    $blogData = $blogContent->findById($blogContentId);
    if(!empty($blogData)){
      return $blogData['BlogContent']['title'];
    } else {
      return false;
    }
  }


  /**
  * ブログの公開状態の取得
  *
  * @param int $blogContentId
  * @return int
  * @access	public
  */
  public function getMobilePostBlogStatus($blogContentId = null){
    /* 除外処理 */
    if (empty($blogContentId)) {
      return false;
    }
    $blogContent = new BlogContent();
    $blogData = $blogContent->findById($blogContentId);
    if(!empty($blogData)){
      return $blogData['BlogContent']['status'];
    } else {
      return false;
    }
  }

}
