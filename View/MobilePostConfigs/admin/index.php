<table cellpadding="0" cellspacing="0" class="list-table" id="ListTable">
	<thead>
		<tr>
			<th class="list-tool">
			操作
			</th>
			<th>ブログ</th>
			<th>利用状態</th>
			<th>設定更新日</th>
		</tr>
	</thead>
	<tbody>
<?php if(!empty($datas)): ?>
	<?php $count=1; ?>
	<?php foreach($datas as $data): ?>
		<?php $this->BcBaser->element('mobile_post_configs/index_row', array('data' => $data, 'count' => $count)) ?>
		<?php $count++; ?>
	<?php endforeach; ?>
<?php else: ?>
		<tr>
			<td colspan="6"><p class="no-data">データが見つかりませんでした。</p></td>
		</tr>
<?php endif; ?>
	</tbody>
</table>
