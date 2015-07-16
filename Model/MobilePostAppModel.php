<?php
/*
* モバイルポスト プラグイン
* 基底モデル
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
App::uses('BcPluginAppModel', 'Model');
App::uses('Imageresizer', 'Vendor');

/**
* モバイルポスト 基底モデル
*
* @package baser.plugins.mobile_post
*/
class MobilePostAppModel extends BcPluginAppModel {

  /**
   * クラス名
   *
   * @var	string
   * @access	public
   */
  	public $name = 'MobilePostAppModel';

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

/**
 * 画像を削除する。
 *
 * @return	bool
 * @access	public
 */
 public function removeMobilePostImg($data){
    //保存先を指定する
    $saveDir = WWW_ROOT . 'files' . DS . 'mobile_post' . DS;
    $fileName = $data['MobilePost']['file'];
    //ファイル本体の削除
    // echo $saveDir.$fileName; exit;
    @unlink($saveDir.$fileName);
    //サムネイルの削除
    @unlink($saveDir.'thum_'.$fileName);
    return;
 }

/**
 * 画像を保存する。
 *
 * @return	bool
 * @access	public
 */
 public function saveMobilePostImg($data){
    //保存先を指定する
    $saveDir = WWW_ROOT . 'files' . DS . 'mobile_post' . DS;
    //サムネイルにはプレフィックスをつける
    $thumbSuffix = 'thum_';

    //アップロード画像の保存処理
    if (!empty($data['MobilePost']['file']['tmp_name'])) {
        //現在のファイル名と、そこから拡張子を確認する。
        $fileName = $data['MobilePost']['file']['name'];
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);

        //新しいファイル名
        $fileNamePrefix = 'mobile_post_img';
        $fileNameNo = date("YmdHis");
        $newFileName =  $fileNamePrefix.$fileNameNo;
        $filePath = $saveDir . $newFileName . '.' .$ext;
        $thumbPath = $saveDir . $thumbSuffix .  $newFileName . '.' . $ext;
        move_uploaded_file($data['MobilePost']['file']['tmp_name'], $filePath);

        //サムネイル生成
        $Imageresizer = new Imageresizer();
        $Imageresizer->resize($filePath, $thumbPath, 320, 320);
        $data['MobilePost']['file'] = $newFileName . '.' . $ext;

    } else {
        unset($data['MobilePost']['file']);
    }
    return $data;
 }


/**
 * カテゴリデータのオプション取得
 *
 * @return	array
 * @access	public
 */
public function getCategoryOptions($blogContentID = null){
  //除外処理
  if(empty($blogContentID)){
    return false;
  }
  //ブログカテゴリモデル利用開始
  $this->BlogCategory = Classregistry::init('Blog.BlogCategory');
  //ブログカテゴリデータ取得  TODO: カテゴリ固定設定の場合の処理を追加する。
  $blogCategoryDatas = $this->BlogCategory->find('list', array(
    'conditions'=>array(
    'BlogCategory.blog_content_id' => $blogContentID
  ),
    'fields' => array(  //オプション用の配列にする項目をセット
      'id', //１つ目はvalue値になる
      'title' //２つ目は表示名になる
  )));
  //結果を返す
  return $blogCategoryDatas;
}


/**
 * タグのオプション取得
 *
 * @return	array
 * @access	public
 */
public function getTagOptions(){
  //ブログカテゴリモデル利用開始
  $this->BlogTag = Classregistry::init('Blog.BlogTag');
  //ブログカテゴリデータ取得  TODO: カテゴリ固定設定の場合の処理を追加する。
  $blogTagDatas = $this->BlogTag->find('list', array(
      'fields' => array(  //オプション用の配列にする項目をセット
          'id', //１つ目はvalue値になる
          'name' //２つ目は表示名になる
  )));
  return $blogTagDatas;
}


/**
 * 登録画像のリンク取得
 *
 * @return	string
 * @access	public
 */
public function getImgLink($data){
    if(empty($data['MobilePost']['file'])){
        return false;
    }
    $thumImgName = 'thum_'.$data['MobilePost']['file'];
    $saveThumURL =  'files/mobile_post/'. $thumImgName;
    $imgTag = "<img src='$saveThumURL' style='width:100%;' />";
    return $imgTag;
}

}
