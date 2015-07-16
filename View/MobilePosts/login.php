<div data-role="content" data-theme="a" style="background: #E8E8E8;">
    <div class="ui-corner-all custom-corners">
      <div class="ui-bar ui-bar-a">
        <h3>ログイン認証</h3>
      </div>
      <div class="ui-body ui-body-a">
        <?php echo $this->BcForm->create('User' ,array('url' => array('controller' => 'MobilePosts', 'action' => 'login'), 'data-ajax'=>'false')) ?>
        <?php echo $this->BcForm->text('name', array('placeholder'=>'アカウント名')) ?>
        <?php echo $this->BcForm->input('password', array('type'=>'password', 'placeholder'=>'パスワード')) ?>
        <?php 
        $options = array('yes'=>'パスワードを保存する。');
        echo $this->BcForm->input('cookie',  array(
              'type' => 'select', 
              'multiple' => 'checkbox',
              'options' => $options,
              'label' => false,
              'div' => false,
              'id'=> false
        ));  ?>
        <?php echo $this->BcForm->submit('ログイン', array('div' => false, 'style' => 'background:#999;' , 'type'=>'submit')) ?>
        <?php echo $this->BcForm->submit('リセット', array('div' => false, 'type'=>'reset')) ?>
        <?php echo $this->BcForm->end() ?>
      </div>
    </div>
</div>