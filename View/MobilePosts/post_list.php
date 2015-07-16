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
      if(!empty($mobilePostDatas)){
      ?>
      <ul data-role="listview" data-inset="true" data-divider-theme="a">
        <li data-role="list-divider">記事一覧</li>
        <?php
        foreach ($mobilePostDatas as $data) {
          echo '<li>'.$this->BcBaser->getLink($data['BlogPost']['name'], array('controller'=>'mobile_posts', 'action'=>'edit', $data['MobilePost']['id'])).'</li>';
        }
        ?>
      </ul>
      <?php
      }else{
        echo  '<p>モバイル投稿で編集可能な記事がありません。</p>';
      }
      ?>
      <div data-role="controlgroup"  data-type="horizontal">
      <?php
      echo $this->Paginator->first('先頭', array('data-role'=>'button', 'data-inline' => 'true'),null);
      echo $this->Paginator->prev('前へ', array('data-role'=>'button', 'data-inline' => 'true'),null);
      echo $this->Paginator->next('次へ', array('data-role'=>'button', 'data-inline' => 'true'),null);
      echo $this->Paginator->last('最後', array('data-role'=>'button', 'data-inline' => 'true'),null);
      ?>
      </div>
    </div>
</div>
