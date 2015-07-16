<?php
/*
* モバイルポスト プラグイン
* 設定コントローラー
*
* PHP 5.4.x
*
* @copyright    Copyright (c) hiniarata co.ltd
* @link         https://hiniarata.jp
* @package      Voice Plugin Project
* @since        ver.0.9.0
*/

/**
* モバイルポスト 設定コントローラー
*
* @package baser.plugins.mobile_post
*/
class MobilePostConfigsController extends MobilePostAppController {


/**
 * クラス名
 *
 * @var	string
 * @access	public
 */
	public $name = 'MobilePostConfigs';


/**
 * モデル
 *
 * @var	array
 * @access	public
 */
	public $uses = array('Plugin', 'MobilePost.MobilePostConfig', 'Blog.BlogContent');


/**
 * コンポーネント
 *
 * @var	array
 * @access	public
 */
	public $components = array('BcAuth','Cookie','BcAuthConfigure','Paginator');


/**
 * サブメニュー
 *
 * @var	array
 * @access	public
 */
	public $subMenuElements = array('mobile_post');


/**
	* beforeFilter
	*
	* @return	void
	* @access 	public
	*/
  public function beforeFilter() {
    parent::beforeFilter();
  }


/**
 * 設定一覧
 *
 * @return	void
 * @access	public
 */
	public function admin_index() {
		//データの取得
		$this->Paginator->settings = array(
		     'limit' => 20,
		     'order' => array(
		          'BlogContent.id' => 'desc'
		      )
		 );
		$datas = $this->Paginator->paginate('BlogContent');
		$this->set("datas", $datas);
		//ページタイトル
		$this->pageTitle = 'MobilePost設定一覧';
	}


/**
 * 設定の変更
 *
 * @return	void
 * @access	public
 */
	public function admin_edit($blogContentID = null) {
		/*除外処理*/
		//ブログのIDが入っていない。
		if(empty($blogContentID)){
			$this->setMessage('IDの取得に失敗しました。', true);
			$this->redirect(array('controller' => 'MobilePostConfigs' ,'action' => 'admin_index'));
		}
		//ブログデータの取得
		$blogContentData = $this->BlogContent->find('first', array('conditions'=>array(
			'BlogContent.id' => $blogContentID
		)));
		//データ取得の成否
		if(empty($blogContentData)){
			$this->setMessage('データの取得に失敗しました。', true);
			$this->redirect(array('controller' => 'MobilePostConfigs' ,'action' => 'admin_index'));
		}else{
			$this->set('blogContentData', $blogContentData);
		}

		/* 保存処理 */
		//更新ボタンの押下
		if(!empty($this->request->data)){
			//データ整形の為に変数へ代入
			$insertData['MobilePostConfig'] = $this->request->data['MobilePostConfig'];
			$insertData['MobilePostConfig']['blog_content_id'] = $blogContentID;
			if (!empty($this->request->data['MobilePostConfig']['id'])) {
				$this->mobileConfig->id = $this->request->data['MobilePostConfig']['id'];
			}
			if($this->MobilePostConfig->save($insertData)){
       	//clearViewCache(); //Viewキャッシュが残ってしまう。
				$this->MobilePostConfig->saveDbLog($blogContentData['BlogContent']['title'].'のモバイル投稿設定を更新しました。');
				$this->setMessage('設定を更新しました。', false);
				$this->redirect(array('controller' => 'MobilePostConfigs' ,'action' => 'admin_index'));
			}else{
				$this->setMessage('データの保存に失敗しました', true);
				$this->redirect(array('controller' => 'MobilePostConfigs' ,'action' => 'admin_index'));
			}

		//初期表示
		} else {
			//モバイルポスト用のデータを検索する。
			$mobileConfig = $this->MobilePostConfig->find('first', array('conditions' => array(
				'MobilePostConfig.blog_content_id' => $blogContentData['BlogContent']['id']
			)));
			//$mobileConfig = $this->MobilePostConfig->find('all');
			//var_dump($mobileConfig);exit;
			if (!empty($mobileConfig)) {
				$this->request->data['MobilePostConfig'] = $mobileConfig['MobilePostConfig'];
				$this->set('mobileConfig', $mobileConfig);
			}
		}

		/* 表示設定 */
		//ページタイトル
		$this->pageTitle = 'MobilePost設定変更';
		//レンダー
		$this->render('form');
	}


/**
 * 設定の初期化
 *
 * @return	void
 * @access	public
 */
	public function admin_delete($mobilePostConfigID = null) {
		/* 除外処理 */
		if(empty($mobilePostConfigID)){
			$this->setMessage('IDの取得に失敗しました。', true);
			$this->redirect(array('controller' => 'MobilePostConfigs' ,'action' => 'admin_index'));
		}
		//データ取得
		$mobilePostConfigData = $this->MobilePostConfig->find('first', array('conditions' => array(
			'id' => $mobilePostConfigID
		)));
		$blogContentData = $this->BlogContent->find('first', array('conditions' => array(
			'id' => $mobilePostConfigData['MobilePostConfig']['blog_content_id']
		)));
		//取得失敗
		if(empty($mobilePostConfigData) || empty($blogContentData)){
			$this->setMessage('データの取得に失敗しました。', true);
			$this->redirect(array('controller' => 'MobilePostConfigs' ,'action' => 'admin_index'));
		}

		/* 削除実行 */
		//初期化実行
		if($this->MobilePostConfig->delete($mobilePostConfigID)){
      $this->MobilePostConfig->saveDbLog($blogContentData['BlogContent']['title'].'のモバイル投稿設定を初期化しました。');
      $this->setMessage('設定を初期化しました。', false);
      $this->redirect(array('controller' => 'MobilePostConfigs' ,'action' => 'admin_index'));
		}else{
      $this->setMessage('データの初期化に失敗しました', true);
      $this->redirect(array('controller' => 'MobilePostConfigs' ,'action' => 'admin_index'));
		}
	}
}
