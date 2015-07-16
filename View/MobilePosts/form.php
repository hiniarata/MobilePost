<div data-role="content" data-theme="a" style="background: #E8E8E8;">
    <div class="ui-corner-all custom-corners">
      <div class="ui-bar ui-bar-a">
        <?php
        if($this->action == 'edit'){
            echo '<h3>編集画面</h3>';
        }else{
            echo '<h3>新規投稿</h3>';
        }
        ?>
      </div>
      <div class="ui-body ui-body-a">
        <?php echo $this->Session->flash(); ?>
        <?php
        /*memo
         *  BcFormヘルパーにて、form にmultipart/form-dataを指定すると
         * ポストできなくなる模様。
         */
        ?>
        <?php echo $this->Form->create('MobilePost' ,array('type' => 'file', 'novalidate' => false)) ?>
        <?php echo $this->Form->hidden('BlogPost.blog_content_id', array('value' => $blogContentID)) ?>
        <?php echo $this->Form->input('BlogPost.category_id', array(
            'type' => 'select',
            'options' => $categoryOptions,
            'data-mini' => 'true',
            'data-inline' => 'true',
            'label' => false,
             'empty' => 'カテゴリ'
        )) ?>
        <?php echo $this->Form->error('BlogPost.category_id') ?>
        <?php echo $this->Form->text('BlogPost.name', array('placeholder'=>'タイトル')) ?>
        <?php echo $this->Form->error('BlogPost.name') ?>
        <?php if (!empty($blogContentData['BlogContent']['use_content'])): ?>
          <?php echo $this->Form->textarea('BlogPost.content', array('placeholder'=>'概要')) ?>
          <?php echo $this->Form->error('BlogPost.content') ?>
        <?php endif; ?>
        <?php echo $this->Form->textarea('BlogPost.detail', array('placeholder'=>'本文')) ?>
        <?php echo $this->Form->error('BlogPost.detail') ?>
        <?php echo $this->Form->input('BlogPost.status', array(
            'type' => 'select',
            'options' => array("非公開", "公開"),
            'data-role' => 'slider',
            'label' => false
        )) ?>
        <?php echo $this->Form->error('BlogPost.status') ?>

        <div data-role="collapsible" data-theme="a" data-content-theme="a">
            <h4>本文画像アップロード</h4>
            <?php
            //登録画像の表示を行う
            if(!empty($mobilePostImg)){
                echo '<div>'."<img src='".$mobilePostImg."' style='max-width:100%;' />".'</div>'; //画像タグ
                $options = array('yes'=>'画像を削除する。');
                echo $this->Form->input('MobilePost.img_delete',  array(
                      'type' => 'select',
                      'multiple' => 'checkbox',
                      'options' => $options,
                      'label' => false,
                      'div' => false,
                      'id'=> false
                ));
            }
            ?>
            <?php echo $this->Form->file( 'MobilePost.file'); ?>
            <?php echo $this->Form->error('MobilePost.file') ?>
            <fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">
            <?php
            /* Memo:
             *  CakePHPのFormヘルパーで、そのまま出力すると、MobileJQueryのFormUIと上手く連携がとれないので、
             *  下記のような方法で選択肢毎にバラバラに出力する。
             */
            //デフォルト値の設定
            if(!empty($mobilePostData['MobilePost']['file_position'])){
                switch ($mobilePostData['MobilePost']['file_position']){
                    case 0:
                        $filePosition = 0;
                        break;
                    case 1:
                        $filePosition = 1;
                        break;
                    default :
                        break;
                }
            }else{
                $filePosition = 0;
            }
            echo $this->Form->radio('MobilePost.file_position', array(
               0 => '前'
            ),  array(
                'value' => $filePosition //checkedの値。全部の選択肢で揃えないとエラーが出る。
            ));
            echo $this->Form->radio('MobilePost.file_position', array(
               1 => '後'
            ),  array(
                'value' => $filePosition
            ));
            ?>
            </fieldset>
            <?php echo $this->Form->error('MobilePost.file_position') ?>
        </div>
        <?php
        if($this->action == 'edit' || $this->action == 'smartphone_edit'){
            echo $this->Form->hidden('BlogPost.id', array('value' => $postData['BlogPost']['id'])) ;
            echo $this->Form->hidden('MobilePost.id', array('value' => $mobilePostData['MobilePost']['id'])) ;
        }
        ?>
        <?php echo $this->Form->hidden('BlogPost.user_id', array('value' => $userID)) ?>
        <?php echo $this->Form->hidden('MobilePost.blog_content_id', array('value' => $blogContentID)) ?>
        <?php echo $this->Form->hidden('BlogPost.posts_date_date', array('value' => date("Y-m-d"))) ?>
        <?php echo $this->Form->hidden('BlogPost.posts_date_time', array('value' => date("H:i:m"))) ?>
        <?php echo $this->Form->hidden('BlogPost.exclude_search', array('value' => 0)) ?>
        <?php
        if($this->action == 'edit' || $this->action == 'smartphone_admin_edit'){
            echo $this->Form->submit('更新する', array('div' => false, 'style' => 'background:#999;' , 'type'=>'submit'));
        }else{
            echo $this->Form->submit('保存する', array('div' => false, 'style' => 'background:#999;' , 'type'=>'submit'));
        }
        ?>
        <?php echo $this->Form->end() ?>
      </div>
    </div>
</div>
