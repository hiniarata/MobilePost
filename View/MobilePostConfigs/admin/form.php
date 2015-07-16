<!--nocache-->
<!-- form -->
<h2>基本項目</h2>

<?php echo $this->BcForm->create('MobilePostConfig') ?>
<div class="section">
  <table cellpadding="0" cellspacing="0" class="form-table">
    <tr>
        <th class="col-head">ブログアカウント名</th>
        <td class="col-input">
            <?php echo $blogContentData['BlogContent']['name'] ?>
        </td>
    </tr>
    <tr>
        <th class="col-head"><?php echo $this->BcForm->label('MobilePostConfig.status', 'モバイル投稿') ?></th>
        <td class="col-input">
            <?php echo $this->BcForm->input('MobilePostConfig.status', array('type' => 'radio',
                'options' => array(0 => "許可しない ", 1 =>"許可する"),
            )) ?>
            <?php echo $this->BcForm->error('MobilePostConfig.status') ?>
            <?php echo $this->Html->image('admin/icn_help.png', array('id' => 'helpListStatus', 'class' => 'btn help', 'alt' => 'ヘルプ')) ?>
            <div id="helpListStatus" class="helptext">
                <ul>
                    <li>このブログでモバイル投稿を使用するかどうかを選択してください。</li>
                    <li>「使用しない」を選択すると、このプラグインを使って投稿できなくなります。</li>
                </ul>
            </div>
        </td>
    </tr>
  </table>
</div>
<?php
if(!empty($mobileConfig['MobilePostConfig']['id'])){
    echo $this->BcForm->input('MobilePostConfig.id', array('type' => 'hidden',
        'value' => $mobileConfig['MobilePostConfig']['id']
    ));
} ?>
<!-- button -->
<div class="submit">
<?php echo $this->BcForm->submit('保存', array('div' => false, 'class' => 'button')) ?>
<?php if(!empty($mobileConfig['MobilePostConfig']['id'])): ?>
    <?php $this->BcBaser->link('初期化',
            array('action' => 'delete', $mobileConfig['MobilePostConfig']['id']),
            array('class' => 'button'),
            sprintf('%s のモバイル投稿設定を本当に初期化してもいいですか？', $blogContentData['BlogContent']['name']),
            false); ?>
<?php endif ?>
</div>

<?php echo $this->BcForm->end() ?>
<!--/nocache-->
