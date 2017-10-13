<div>
	<div class="phead_wrap mgt10"><div class="phead">
		<h2>PG 로그 보기 <span>해당 주문건의 PG 결제에 대한 모든 내용 로그입니다.</span></h2>
	</div></div>

	<div>
		<table class="order_table">
		<thead>
		<tr>
			<th>로그내용</th>
		</tr>
		</thead>
		<tr>
			<td class="left">
				<?= nl2br(gd_htmlspecialchars_decode(gd_isset($orderLog['orderPGLog'])));?>
			</td>
		</tr>
		</table>
	</div>
</div>
