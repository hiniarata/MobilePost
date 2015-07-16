<?php
/*
* モバイルポスト プラグイン
* 投稿コントローラー
*
* PHP 5.4.x
*
* @copyright    Copyright (c) hiniarata co.ltd
* @link         https://hiniarata.jp
* @package      Voice Plugin Project
* @since        ver.0.9.0
*/

/**
* モバイルポスト 投稿コントローラー
*
* @package baser.plugins.mobile_post
*/
class MobilePostsController extends MobilePostAppController {


/**
 * クラス名
 *
 * @var	string
 * @access	public
 */
	public $name = 'MobilePosts';


/**
 * モデル
 *
 * @var	array
 * @access	public
 */
	public $uses = array('Plugin', 'User','MobilePost.MobilePost','MobilePost.MobilePostConfig', 'Blog.BlogContent', 'Blog.BlogPost', 'Blog.BlogTag', 'Blog.BlogCategory');


 /**
 * ヘルパー
 *
 * @var array
 */
	public $helpers = array('BcPage','BcHtml', 'BcTime', 'BcForm','Blog.Blog');


/**
 * コンポーネント
 *
 * @var	array
 * @access	public
 */
  public $components = array( 'Cookie','Paginator','BcAuth');


/**
 * サブメニュー
 *
 * @var	array
 * @access	public
 */
	public $subMenuElements = array('');


	/**
	* 共通設定
	*
	* @return	void
	* @access	public
	*/
	public function beforeFilter() {
	  //基底の設定
	  parent::beforeFilter();

		/* 認証設定 */
		//専用セッションキーの指定
		BcAuthComponent::$sessionKey = "BcAuth.MobilePost";
		//未認証許可
	  $this->BcAuth->allow('login' ,'smartphone_login');
	  // 認証時の設定
	  $this->BcAuth->authenticate = array(
	    'Form' => array(
	      // 認証時に使用するモデル
	      'userModel' => 'User',
	      // 認証時に使用するモデルのユーザ名とパスワードの対象カラム
	      'fields' => array('username' => 'name' , 'password'=>'password'),
	  ));
		//ログインアクション
	  $this->BcAuth->loginAction = array(
			'controller' => 'mobile_posts',
			'action' => 'login',
			'plugin' => 'mobile_post');
	  $this->BcAuth->loginRedirect = array(
			'controller' => 'mobile_posts',
			'action' => 'index',
			'plugin' => 'mobile_post');
	  $this->BcAuth->logoutRedirect = array(
			'controller' => 'mobile_posts',
			'action' => 'login',
			'plugin' => 'mobile_post');
	  //自動ログイン
	  $userID = $this->BcAuth->user('id');
	  if(empty($userID)){
	    $cookieData = $this->Cookie->read('BcAuth');
	    if(!empty($cookieData)){
	    $loginData['User']['username'] = $cookieData['MobilePost']['username'];
	    $loginData['User']['password'] = $cookieData['MobilePost']['password'];
	      if($this->BcAuth->login($loginData)){
	        $this->Session->setFlash(__('ログインしました。'));
	        $this->redirect($this->BcAuth->redirect());
	      }
	    }
	  }

		/* 表示設定 */
	  //専用レイアウト適用
	  $this->layout = 'MobilePost.mobile_post';
	}


	/**
	* ブログ一覧
	*
	* @return	void
	* @access	public
	*/
	public function index() {
		//データの取得
		$mobilePostConfigs = $this->MobilePostConfig->find('all', array(
			'MobilePostConfig.status' => 1
		));
		//データのセット
		$this->set('mobilePostConfigs', $mobilePostConfigs);
	  //タイトル
	  $this->pageTitle = 'ブログ一覧';
	}


/**
 * 各ブログ管理トップ
 *
 * @return	void
 * @access	public
 */
	public function management($blogID = null){
  //除外処理
	if (empty($blogID)) {
		$blogID = $this->request->params['id'];
	}
  if (empty($blogID)) {
    $this->setMessage('無効なIDです。', true);
    $this->redirect(array('action' => 'index'));
  }
  //ブログデータ取得
  $blogContentData = $this->BlogContent->find('first', array('conditions'=>array(
    'BlogContent.id' => $blogID
  )));
	//表示設定
  $this->set('blogContentData', $blogContentData);
  $this->pageTitle = $blogContentData['BlogContent']['title'].'の管理';
}


/**
* 記事の追加
*
* @return	void
* @access	public
*/
public function add($blogID = null){
  //除外処理
	if (empty($blogID)) {
		$blogID = $this->request->params['id'];
	}
  if (empty($blogID)) {
    $this->setMessage('無効なIDです。', true);
    $this->redirect(array('action' => 'index'));
  }else{
      $this->set('blogContentID', $blogID);
  }

  //ブログデータ取得
  $blogContentData = $this->BlogContent->find('first', array('conditions'=>array(
    'BlogContent.id' => $blogID
  )));
  $this->set('blogContentData', $blogContentData);
  //セレクトボックスの為に配列化する。
  $categoryOptions = $this->MobilePost->getCategoryOptions($blogID);
  $this->set('categoryOptions',$categoryOptions);
  //タグデータを取得する。
  $tagOptions = $this->MobilePost->getTagOptions();
  $this->set('tagOptions', $tagOptions);
  //モバイルポスト設定を取得する。
  $mobilePostConfig = $this->MobilePostConfig->find('first', array('conditions' => array(
    'blog_content_id' => $blogID
  )));
  $this->set('mobilePostConfig', $mobilePostConfig);

  /* 投稿ボタン押下後 */
  if(!empty($this->request->data)){
		/* ブログ保存の為の整形 */
		// 改行コードのタグ化
    if(!empty($this->request->data['BlogPost']['content'])){
        $this->request->data['BlogPost']['content'] = nl2br($this->request->data['BlogPost']['content']);
    }else{
        $this->request->data['BlogPost']['content'] = ''; //変数定義だけしないとエラーになる？
    }
    if(!empty($this->request->data['BlogPost']['detail'])){
        $this->request->data['BlogPost']['detail'] = nl2br($this->request->data['BlogPost']['detail']);
    }

    //noを生成
    $this->request->data['BlogPost']['no'] = $this->BlogPost->getMax('no', array('BlogPost.blog_content_id' => $blogID)) + 1;

    //公開期日
    if(!empty($this->request->data['BlogPost']['publish_begin_date'])){
        $this->request->data['BlogPost']['publish_begin'] = $this->request->data['BlogPost']['publish_begin_date']." ".$this->request->data['BlogPost']['publish_begin_time'];
    }else{
        $this->request->data['BlogPost']['publish_begin'] = null;
    }
    if(!empty($this->request->data['BlogPost']['publish_end_date'])){
        $this->request->data['BlogPost']['publish_end'] = $this->request->data['BlogPost']['publish_end_date']." ".$this->request->data['BlogPost']['publish_end_time'];
    }else{
        $this->request->data['BlogPost']['publish_end'] = null;
    }

    //投稿日
    $this->request->data['BlogPost']['posts_date'] = $this->request->data['BlogPost']['posts_date_date']." ".$this->request->data['BlogPost']['posts_date_time'];

    //携帯投稿専用のデータ整形と画像の保存
    $mobileData = array();
    $mobileData = $this->MobilePost->saveMobilePostImg($this->request->data);

    //画像アップが本文の前か後かを確認する。
    if(!empty($mobileData['MobilePost']['file'])){
      //画像URL整形
			$webrootPath = Router::fullbaseUrl() . $this->request->webroot;
      $saveURL = $webrootPath.'/files/mobile_post/'. $mobileData['MobilePost']['file'];
      $saveThumURL = $webrootPath.'/files/mobile_post/thum_'. $mobileData['MobilePost']['file'];
      $imgTag = "<div class='mobilePostImage'><a href='$saveURL' rel='colorbox' title=''><img src='$saveThumURL' /></a></div>";
      //画像の挿入箇所確認と挿入実行
      switch ($mobileData['MobilePost']['file_position']){
        case 0:
          $this->request->data['BlogPost']['detail'] = $imgTag . $this->request->data['BlogPost']['detail'];
          break;
        case 1:
          $this->request->data['BlogPost']['detail'] =  $this->request->data['BlogPost']['detail'] . $imgTag;
          break;
      }
    }

    //保存処理実行
		$this->BlogPost->create();
    if($this->BlogPost->save($this->request->data, false)){
        clearViewCache(); //Viewキャッシュが残ってしまう。
        //直前に保存したレコードのIDのを取得
        $mobileData['MobilePost']['blog_post_id'] = $this->BlogPost->getLastInsertId();
        //保存実行
        if($this->MobilePost->save($mobileData)){
          $this->setMessage('記事を追加しました。', true);
          $this->redirect(array('action' => 'post_list', $mobileData['MobilePost']['blog_content_id']));
        }else{
          $this->setMessage('MobilePostの保存処理に失敗しました。', true);
        }
    }else{
      $this->setMessage('保存処理に失敗しました。', true);
    }
  }

		//表示設定
	  $this->pageTitle = $blogContentData['BlogContent']['title'].'の新規記事作成';
    $this->set('userID', $this->BcAuth->user('id'));
    $this->render('form');
}


	/**
	* 編集可能記事一覧
	*
	* @return	void
	* @access	public
	*/
	public function post_list($blogContentID = null) {
		//除外処理
		if (empty($blogContentID)) {
			$blogContentID = $this->request->params['id'];
		}
	  if (empty($blogContentID)) {
	    $this->setMessage('無効なIDです。', true);
	    $this->redirect(array('action' => 'index'));
	  }else{
	    $this->set('blogContentID', $blogContentID);
	  }

	  //データ取得（携帯から投稿したものだけを取得する。）
	  $this->BlogPost->bindModel(array(
	    'hasOne' => array(
	    'MobilePost' => array(
	      'className'  => 'MobilePost.MobilePost',
	      'foreignKey' => 'blog_post_id'
	    )
	  )), false);

		//ページネーション
	  $this->Paginator->settings = array(
	      'conditions' => array('MobilePost.blog_content_id' => $blogContentID),
	      'limit' => 20,
	      'order' => array('BlogPost.id' => 'desc')
	  );
	  $mobilePostDatas = $this->Paginator->paginate('BlogPost');

	  //セットする。
	  $this->set('mobilePostDatas', $mobilePostDatas);
	}


	/**
	* 編集
	*
	* @return	void
	* @access	public
	*/
	public function edit($postID = null){
		//除外処理
		if (empty($postID)) {
			$postID = $this->request->params['id'];
		}
		if (empty($postID)) {
	    $this->setMessage('無効なIDです。', true);
	    $this->redirect(array('action' => 'index'));
		}

		/* 各種データを取得する  */
		//携帯投稿TBLからデータを取得する
		$mobilePostData = $this->MobilePost->find('first', array('conditions' => array(
	    'MobilePost.id' => $postID
		)));
		//データの取得
		$postData = $this->BlogPost->find('first', array('conditions' => array(
	    'BlogPost.id' => $mobilePostData['MobilePost']['blog_post_id']
		)));
		if(empty($postData)){
	    $this->setMessage('データが取得できませんでした。', true);
	    $this->redirect(array('action' => 'index'));
		}

		//画像を取得する。
		if (!empty($mobilePostData['MobilePost']['file'])) {
			$thumImgName = 'thum_'.$mobilePostData['MobilePost']['file'];
			$saveThumURL =  'files/mobile_post/'. $thumImgName;
			$mobilePostImg = Router::fullbaseUrl() . $this->request->webroot . $saveThumURL;
			$this->set('mobilePostImg', $mobilePostImg);
		}
		//モバイルポスト設定を取得する。
		$mobilePostConfig = $this->MobilePostConfig->find('first', array('conditions' => array(
	    'blog_content_id' => $postData['BlogPost']['blog_content_id']
		)));
		$this->set('mobilePostConfig', $mobilePostConfig);
		//ブログ設定取得
		$blogContentData = $this->BlogContent->find('first', array('conditions' => array(
	    'BlogContent.id' =>  $postData['BlogPost']['blog_content_id']
		)));

		/* フォーム生成用にデータを整理する */
		//セレクトボックスの為に配列化する。
		$categoryOptions = $this->MobilePost->getCategoryOptions($postData['BlogPost']['blog_content_id']);
		$this->set('categoryOptions',$categoryOptions);
		//タグデータを取得する。
		$tagOptions = $this->MobilePost->getTagOptions();
		$this->set('tagOptions', $tagOptions);

	/* データの受信処理 */
	//POSTボタン押下後
	if(!empty($this->request->data)){
    /* データの整理 */
		//テキストエリアの改行
    if(!empty($this->request->data['BlogPost']['content'])){
      $this->request->data['BlogPost']['content'] = nl2br($this->request->data['BlogPost']['content']);
    }else{
      $this->request->data['BlogPost']['content'] = ''; //変数定義だけしないとエラーになる？
    }
    if(!empty($this->request->data['BlogPost']['detail'])){
      $this->request->data['BlogPost']['detail'] = nl2br($this->request->data['BlogPost']['detail']);
    }

    //noを生成
    $this->request->data['BlogPost']['no'] = $postData['BlogPost']['no'];
    //公開期日
    if(!empty($this->request->data['BlogPost']['publish_begin_date'])){
        $this->request->data['BlogPost']['publish_begin'] = $this->request->data['BlogPost']['publish_begin_date']." ".$this->request->data['BlogPost']['publish_begin_time'];
    }else{
        $this->request->data['BlogPost']['publish_begin'] = null;
    }
    if(!empty($this->request->data['BlogPost']['publish_end_date'])){
        $this->request->data['BlogPost']['publish_end'] = $this->request->data['BlogPost']['publish_end_date']." ".$this->request->data['BlogPost']['publish_end_time'];
    }else{
        $this->request->data['BlogPost']['publish_end'] = null;
    }

    //投稿日
    $this->request->data['BlogPost']['posts_date'] = $this->request->data['BlogPost']['posts_date_date']." ".$this->request->data['BlogPost']['posts_date_time'];

    //携帯投稿専用のデータ整形
    $mobileData = array();
    $mobileData = $this->request->data;

    /* 画像に関する処理 */
    //画像の削除があれば消してしまう。
    if(!empty($this->request->data['MobilePost']['img_delete'][0])  && $this->request->data['MobilePost']['img_delete'][0] == 'yes' ){
      //まずファイルの削除
      $this->MobilePost->removeMobilePostImg($mobilePostData);
      //UNSET
      if(!empty($mobilePostData['MobilePost']['file'])){
				$mobileData['MobilePost']['file'] = '';
        $this->MobilePost->id = $postID;
        $this->MobilePost->saveField('file', '');//明示的に空にする
      }

    //削除指示がない場合で、前の画像が残っていれば指定場所にタグを挿入する。
    }else{
			//元々の画像があれば画像名を取得する。
      if(!empty($mobilePostData['MobilePost']['file'])){
        //画像URL整形
				$webrootPath = Router::fullbaseUrl() . $this->request->webroot;
        $saveURL = $webrootPath.'/files/mobile_post/'. $mobilePostData['MobilePost']['file'];
        $saveThumURL = $webrootPath.'/files/mobile_post/thum_'. $mobilePostData['MobilePost']['file'];
        $imgTag = "<div class='mobilePostImage'><a href='$saveURL' rel='colorbox' title=''><img src='$saveThumURL' /></a></div>";
        //画像の挿入箇所確認と挿入実行
        switch ($mobileData['MobilePost']['file_position']){
          case 0:
            $this->request->data['BlogPost']['detail'] = $imgTag . $this->request->data['BlogPost']['detail'];
            break;
          case 1:
            $this->request->data['BlogPost']['detail'] =  $this->request->data['BlogPost']['detail'] . $imgTag;
            break;
        }
      }
    }

    //画像のリサイズと保存。
    $mobileData = $this->MobilePost->saveMobilePostImg($mobileData);

    //画像アップが本文の前か後かを確認する。
		if(!empty($mobileData['MobilePost']['file'])){
      //画像URL整形
			$webrootPath = Router::fullbaseUrl() . $this->request->webroot;
      $saveURL = $webrootPath.'/files/mobile_post/'. $mobileData['MobilePost']['file'];
      $saveThumURL = $webrootPath.'/files/mobile_post/thum_'. $mobileData['MobilePost']['file'];
      $imgTag = "<div class='mobilePostImage'><a href='$saveURL' rel='colorbox' title=''><img src='$saveThumURL' /></a></div>";
      //画像の挿入箇所確認と挿入実行
      switch ($mobileData['MobilePost']['file_position']){
        case 0:
          $this->request->data['BlogPost']['detail'] = $imgTag . $this->request->data['BlogPost']['detail'];
          break;
        case 1:
          $this->request->data['BlogPost']['detail'] =  $this->request->data['BlogPost']['detail'] . $imgTag;
          break;
      }
    }

		if(empty($this->request->data['BlogPost']['content'])){
			$this->request->data['BlogPost']['content'] = '';
		}

    /* SAVEとリダイレクト */
    //保存処理実行
    if($this->BlogPost->save($this->request->data, false)){
      //直前に保存したレコードのIDのを取得
      $mobileData['MobilePost']['blog_post_id'] = $mobilePostData['MobilePost']['blog_post_id'];
      //保存実行
      if($this->MobilePost->save($mobileData)){
        $this->setMessage('記事を追加しました。', true);
        $this->redirect(array('action' => 'post_list', $mobileData['MobilePost']['blog_content_id']));
      }else{
        $this->setMessage('MobilePostの保存処理に失敗しました。', true);
      }
    }else{
        $this->setMessage('保存処理に失敗しました。', true);
    }

	//保存ボタンでなかったら
	}else{
    //公開日だけ作成する必要あり。
    if(!empty($postData['BlogPost']['publish_begin'])){
      $timeStamp = strtotime($postData['BlogPost']['publish_begin']);
      $this->set('setTimeBegin',  date("Y/m/d", $timeStamp));
    }
    if(!empty($postData['BlogPost']['publish_end'])){
      $timeStamp = strtotime($postData['BlogPost']['publish_end']);
      $this->set('setTimeEnd',  date("Y/m/d", $timeStamp));
    }
    //本文からリンクを削除する。
    if(!empty($mobilePostData['MobilePost']['file'])){
      //画像URL整形
			$webrootPath = Router::fullbaseUrl() . $this->request->webroot;
      $saveURL =  $webrootPath. '/files/mobile_post/'. $mobilePostData['MobilePost']['file'];
      $saveThumURL = $webrootPath.'/files/mobile_post/thum_'. $mobilePostData['MobilePost']['file'];
      $imgTag = "<div class='mobilePostImage'><a href='$saveURL' rel='colorbox' title=''><img src='$saveThumURL' /></a></div>";
      //上のHTMLを削除する。
      $postData['BlogPost']['detail'] = str_replace($imgTag, '' ,$postData['BlogPost']['detail']) ;
      //HTMLを除去する。
      $postData['BlogPost']['detail']  = strip_tags( $postData['BlogPost']['detail'] );
    }
    //表示データを作る。
    $this->request->data = $postData;
	}

	/* 表示に関する処理  */
	//set
	$this->set('blogContentData' , $blogContentData);
	$this->set('userID', $this->BcAuth->user('id'));
	$this->set('blogContentID', $postData['BlogPost']['blog_content_id']);
	$this->set('mobilePostData', $mobilePostData);
	$this->set('postData', $postData);
	//render
	$this->render('form');
	}


/**
 * ログイン
 *
 * @return	void
 * @access	public
 */
	public function login() {
    //ログイン処理実行
    if (!empty($this->request->data)) {
      if ($this->BcAuth->login()) {
          $this->Session->setFlash(__('ようこそ。モバイル投稿画面へログインしました。'));
          //Cookie保存のチェックが入っていれば
          if(!empty($this->request->data['User']['cookie'][0]) && $this->request->data['User']['cookie'][0] == "yes"){
            $this->Cookie->write('BcAuth.MobilePost', $this->request->data['User'], false, '+4 weeks');
          }
          $this->redirect($this->BcAuth->redirect());
      } else {
          $this->Session->setFlash(__('ログインIDまたはパスワードが間違っています。'));
      }
    }
    //タイトル
    $this->pageTitle = 'ログイン';
	}


 /**
 * [スマホ] ログイン
 *
 * @return	void
 * @access	public
 */
  public function smartphone_login() {
      $this->setAction('login');
  }


/**
 * ログアウト
 *
 * @return	void
 * @access	public
 */
	public function logout(){
	  $logoutRedirect = $this->BcAuth->logout();
	  $this->Cookie->delete(BcAuthComponent::$sessionKey);
	  $this->setMessage('ログアウトしました');
	  $this->redirect($logoutRedirect);
	}


 /**
 * [スマホ] ログアウト
 *
 * @return	void
 * @access	public
 */
  public function smartphone_logout() {
      $this->setAction('logout');
  }


}
