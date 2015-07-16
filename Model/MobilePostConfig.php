<?php
/*
* モバイルポスト プラグイン
* 設定モデル
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
 App::uses('MobilePostAppModel', 'MobilePost.Model');

 /**
 * モバイルポスト 設定モデル
 *
 * @package baser.plugins.mobile_post
 */
class MobilePostConfig extends MobilePostAppModel {
  
/**
 * クラス名
 *
 * @var	string
 * @access	public
 */
	public $name = 'MobilePostConfig';
/**
 * DB接続設定
 *
 * @var	string
 * @access	public
 */
	public $useDbConfig = 'plugin';
/**
 * プラグイン名
 *
 * @var	string
 * @access	public
 */
	public $plugin = 'MobilePost';

}
