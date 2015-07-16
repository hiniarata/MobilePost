<?php
/**
 * データベース初期化
 */
	$this->Plugin->initDb('plugin', 'MobilePost');
    
/**
 * 投稿画像保存フォルダ生成
 */
	$filesPath = WWW_ROOT.'files';
	$savePath = $filesPath.DS.'mobile_post';
	
	if(is_writable($filesPath) && !is_dir($savePath)){
		mkdir($savePath);
	}
	if(!is_writable($savePath)){
		chmod($savePath, 0777);
	}
    
?>
