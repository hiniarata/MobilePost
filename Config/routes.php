<?php
//一括で指定せず、パラメータをとるアクションに対しては、
//:idをいれてやないと、id部分にsmartphone=noが入ってしまう。
//ログイン・ログアウト
Router::connect('/s/mobile_post/mobile_posts/login',
  array(
    'plugin' => 'mobile_post',
    'controller' => 'mobile_posts',
    'action' => 'login',
));
Router::connect('/s/mobile_post/mobile_posts/logout',
  array(
    'plugin' => 'mobile_post',
    'controller' => 'mobile_posts',
    'action' => 'logout',
));
Router::connect('/s/mobile_post/mobile_posts/index',
  array(
    'plugin' => 'mobile_post',
    'controller' => 'mobile_posts',
    'action' => 'index',
));
Router::connect('/s/mobile_post/mobile_posts',
  array(
    'plugin' => 'mobile_post',
    'controller' => 'mobile_posts',
    'action' => 'index',
));
Router::connect('/s/mobile_post/mobile_posts/',
  array(
    'plugin' => 'mobile_post',
    'controller' => 'mobile_posts',
    'action' => 'index',
));

//ブログ管理メニュー
Router::connect('/s/mobile_post/mobile_posts/management/:id',
  array(
    'plugin' => 'mobile_post',
    'controller' => 'mobile_posts',
    'action' => 'management'
  //params
  ),array(
    'id' => '[0-9]+',
  ));

//記事の一覧
Router::connect('/s/mobile_post/mobile_posts/post_list/:id',
  array(
    'plugin' => 'mobile_post',
    'controller' => 'mobile_posts',
    'action' => 'post_list'
  //params
  ),array(
    'id' => '[0-9]+',
  ));

//記事の作成
Router::connect('/s/mobile_post/mobile_posts/add/:id',
  array(
    'plugin' => 'mobile_post',
    'controller' => 'mobile_posts',
    'action' => 'add'
  //params
  ),array(
    'id' => '[0-9]+',
  ));

//編集フォーム
Router::connect('/s/mobile_post/mobile_posts/edit/:id',
  array(
    'plugin' => 'mobile_post',
    'controller' => 'mobile_posts',
    'action' => 'edit'
  //params
  ),array(
    'id' => '[0-9]+',
  ));
