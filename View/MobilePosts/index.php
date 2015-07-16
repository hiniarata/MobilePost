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
      if(!empty($mobilePostConfigs)){
      ?>
        <ul data-role="listview" data-inset="true" data-divider-theme="a">
          <li data-role="list-divider">ブログ一覧</li>
          <?php
          foreach ($mobilePostConfigs as $data) {

            if ($this->BcBaser->getMobilePostBlogStatus($data['MobilePostConfig']['blog_content_id']) == 1) {
              echo '<li>'.$this->BcBaser->getLink(
              $this->BcBaser->getMobilePostBlogTitle($data['MobilePostConfig']['blog_content_id']), array('controller'=>'mobile_posts', 'action'=>'management', $data['MobilePostConfig']['blog_content_id'])).'</li>';
            }
          }
          ?>
        </ul>
      <?php
      }else{
        echo  '<p>モバイル投稿可能なブログがありません。</p>';
      }
      ?>
    </div>
</div>
