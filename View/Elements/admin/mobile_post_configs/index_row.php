<?php
/*データの整形*/
//利用状態
if(!empty($data['BlogContent']['id'])){
  $configStatus = $this->BcBaser->getMobilePostStatus($data['BlogContent']['id']);
    switch ($configStatus){
        case 0:
            $status = "許可しない";
            break;
        case 1:
            $status = "許可する";
            break;
        default:
            $status = '許可しない';
            break;
    }
}else{
    $status = '許可しない';
}
//更新日
if(!empty($data['BlogContent']['id'])){
    $modified = $this->BcBaser->getMobilePostModified($data['BlogContent']['id']);
}else{
    $modified = '未設定';
}
?>
<tr>
    <td class="row-tools">
        <?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_edit.png', array('width' => 24, 'height' => 24, 'alt' => '編集', 'class' => 'btn')), array('action' => 'edit', $data['BlogContent']['id']), array('title' => '編集')) ?>
        <?php
        //初期状態なら表示しない（初期化ボタン）
        if(!empty($data['MobilePostConfig']['id'])){
            $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_delete.png',
                array('width' => 24, 'height' => 24, 'alt' => '初期化', 'class' => 'btn')),
                array('action' => 'delete', $data['MobilePostConfig']['id']),
                array('title' => '初期化', 'class' => 'btn-delete'));
            }
        ?>
    </td>
    <td><?php echo $data['BlogContent']['name'] ?></td>
    <td><?php echo $status ?></td>
    <td><?php echo $modified ?></td>
</tr>
