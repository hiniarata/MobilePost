<div data-role="content" data-theme="a" style="background: #E8E8E8;">
  <?php
    $message = $this->Session->flash();
    if(!empty($message)) : ?>
      <div class="ui-body-a ui-corner-all">
          <?php echo $message; ?>
      </div>
    <?php endif; ?>

    <div class="ui-corner-all custom-corners">
      <?php
      if(!empty($blogContentData)){
      ?>
      <ul data-role="listview" data-inset="true" data-divider-theme="a">
        <li data-role="list-divider"><?php echo $blogContentData['BlogContent']['title'] ?>の管理</li>
        <?php  echo '<li>'.$this->BcBaser->getLink('新規投稿', array('controller'=>'mobile_posts', 'action'=>'add', $blogContentData['BlogContent']['id'])).'</li>'; ?>
        <?php  echo '<li>'.$this->BcBaser->getLink('記事一覧', array('controller'=>'mobile_posts', 'action'=>'post_list', $blogContentData['BlogContent']['id'])).'</li>'; ?>
      </ul>
      <?php
      }else{
        echo  '<p>モバイル投稿可能なブログがありません。</p>';
      }
  ?>
    </div>
</div>
