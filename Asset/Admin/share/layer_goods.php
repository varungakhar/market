<div>
	<div class="mgt10"></div>
	<div>
		<form id="layer_search_goods_frm">
		<table class="table-cols no-title-line mgb10">
		<colgroup><col class="width-sm" /><col /></colgroup>
		<tr>
			<th>검색어</th>
			<td> <div class="form-inline">
				<?php echo gd_select_box('key','key',$search['combineSearch'],null,$search['key']);?>
				<input type="text" name="keyword" value="<?php echo $search['keyword'];?>" class="form-control" />
				</div>
			</td>
		</tr>
		<tr>
			<th>카테고리 선택</th>
			<td><div class="form-inline">
					<?php echo $cate->getMultiCategoryBox('layerCateGoods', gd_isset($search['cateGoods']), 'class="form-control"'); ?>
					<label class="checkbox-inline">
						<input type="checkbox" name="categoryNoneFl" value="y" <?php echo gd_isset($checked['categoryNoneFl']['y']); ?>> 미지정 상품
					</label>
				</div>
			</td>
		</tr>
			<tr>
				<th>브랜드</th>
				<td><div class="form-inline">
						<?php echo $brand->getMultiCategoryBox(null, gd_isset($search['brand']), 'class="form-control"'); ?>
						<label class="checkbox-inline"><input type="checkbox" name="brandNoneFl" value="y" <?php echo gd_isset($checked['brandNoneFl']['y']); ?>> 미지정 상품</label>
					</div>
				</td>
			</tr>
		<tr>
			<th>기간검색</th>
			<td>
				<div class="form-inline">
					<select name="searchDateFl" class="form-control">
						<option value="regDt" <?php echo gd_isset($selected['searchDateFl']['regDt']); ?>>등록일</option>
						<option value="modDt" <?php echo gd_isset($selected['searchDateFl']['modDt']); ?>>수정일</option>
					</select>

					<div class="input-group js-datepicker">
						<input type="text" class="form-control width-xs" name="searchDate[0]" value="<?php echo $search['searchDate'][0]; ?>" />
						<span class="input-group-addon"><span class="btn-icon-calendar"></span></span>
					</div>
					~
					<div class="input-group js-datepicker">
						<input type="text" class="form-control width-xs" name="searchDate[1]" value="<?php echo $search['searchDate'][1]; ?>" />
						<span class="input-group-addon"><span class="btn-icon-calendar"></span></span>
					</div>
				</div>

			</td>
		</tr>
		</table>
		<p class="center"><input type="button" value="검색" class="btn btn-hf btn-black" onclick="layer_list_search();"></p>
		</form>
	</div>
</div>

<div>
	<table class="table table-rows">
	<thead>
	<tr>
		<th class="width3p"><input type="checkbox" id="allCheck" value="y" onclick="check_toggle(this.id,'layer_goods_');" /></th>
		<th class="width3p">번호</th>
		<th class="width3p">이미지</th>
		<th class="width10p">상품명</th>
		<th class="width5p">판매가</th>
		<th class="width5p">공급사</th>
		<th class="width3p">재고</th>
		<th class="width3p">품절상태</th>
		<th class="width3p">PC쇼핑몰<br />노출상태</th>
		<th class="width3p">모바일쇼핑몰<br />노출상태</th>
	</tr>
	</thead>
	<tbody>
<?php
    if (gd_isset($data) && is_array($data)) {
        $i = 0;
        foreach ($data as $key => $val) {

			list($totalStock,$stockText) = gd_is_goods_state($val['stockFl'],$val['totalStock'],$val['soldOutFl']);
			$goodsDisplay = $goodsDisplayMobile = '노출함';
			if ($val['goodsDisplayFl'] != 'y') $goodsDisplay = '노출안함';
			if ($val['goodsDisplayMobileFl'] != 'y') $goodsDisplayMobile = '노출안함';

?>
	<tr id="tbl_goods_<?php echo $val['goodsNo'];?>">
		<td class="center"><input type="checkbox" id="layer_goods_<?php echo $val['goodsNo'];?>" name="layer_goods_<?php echo $i;?>" value="<?php echo $val['goodsNo'];?>" /></td>
		<td class="center"><?php echo number_format($page->idx--);?></td>
		<td>
			<div class="width-2xs">
				<?php echo gd_html_goods_image($val['goodsNo'], $val['imageName'], $val['imagePath'], $val['imageStorage'], 30, $val['goodsNm'], '_blank', 'id="goodsImage_'.$val['goodsNo'].'"');?>
			</div>
		</td>
		<td>
            <a href="../goods/goods_register.php?goodsNo=<?php echo $val['goodsNo'];?>" target="_blank"><?php echo gd_remove_tag($val['goodsNm']);?></a>
			<input type="hidden" id="goodsNm_<?php echo $val['goodsNo'];?>" value="<?php echo gd_remove_tag($val['goodsNm']);?>" />
			<input type="hidden" id="regDt_<?php echo $val['goodsNo'];?>" value="<?php echo gd_date_format('Y-m-d', $val['regDt']); ?>" />
		</td>
		<td id="goodsPrice_<?php echo $val['goodsNo'];?>" class="center"><?php echo number_format($val['goodsPrice']);?> 원</td>
		<td id="scmNm_<?php echo $val['goodsNo'];?>"><?php echo $val['scmNm'];?></td>
		<td id="totalStock_<?php echo $val['goodsNo'];?>" class="center"><?php echo $totalStock;?></td>
		<td id="stockTxt_<?php echo $val['goodsNo'];?>"  class="center" ><?=$stockText?></td>
		<td class="center" id="displayPc_<?php echo $val['goodsNo'];?>"><?php echo $goodsDisplay; ?></td>
		<td class="center" id="displayMobile_<?php echo $val['goodsNo'];?>"><?php echo $goodsDisplayMobile; ?></td>
	</tr>
<?php
            $i++;
        }
    } else {
?>
	<tr>
		<td class="center" colspan="8">검색을 이용해 주세요.</td>
	</tr>
<?php
    }
?>

	</tbody>
	</table>

	<div class="center"><?php echo $page->getPage('layer_list_search(\'PAGELINK\')');?></div>
</div>
<div class="text-center"><input type="button" value="확인" class="btn btn-lg btn-black" onclick="select_code();" /></div>

<script type="text/javascript">
	<!--
	$(document).ready(function(){

		$('input').keydown(function(e) {
			if (e.keyCode == 13) {
				layer_list_search();
				return false
			}
		});
	});

	function layer_list_search(pagelink)
	{

		if (typeof pagelink == 'undefined') {
			pagelink		= '';
		}
		var frm = $("#layer_search_goods_frm").serializeArray();
		var cateGoods	= '';
		var brandGoods	= '';
		var parameters		= {
			'layerFormID'	: '<?php echo $layerFormID?>',
			'parentFormID'	: '<?php echo $parentFormID?>',
			'dataFormID'	: '<?php echo $dataFormID?>',
			'dataInputNm'	: '<?php echo $dataInputNm?>',
			'scmFl'	: '<?php echo $scmFl?>',
			'scmNo'	: '<?php echo $scmNo?>',
			'mode'	: '<?php echo $mode?>',
			'callFunc'	: '<?php echo $callFunc?>',
			'childRow'	: '<?php echo $childRow?>',
			'pagelink'		: pagelink
		};

		$.each(frm, function(i, field){
			if(field.name) parameters[field.name] = field.value;
		});


		for (var i = <?php echo DEFAULT_DEPTH_CATE;?>; i > 0; i--) {
			if ($('#layerCateGoods'+i).val()) {
				cateGoods	= $('#layerCateGoods'+i).val();
				break;
			}
		}
		for (var i = <?php echo DEFAULT_DEPTH_BRAND;?>; i > 0; i--) {
			if ($('#brand'+i).val()) {
				brandGoods	= $('#brand'+i).val();
				break;
			}
		}

		parameters['cateGoods[]'] = cateGoods;
		parameters['brand[]'] = brandGoods;

		$.get('../share/layer_goods.php', parameters, function(data){
			$('#<?php echo $layerFormID?>').html(data);
		});
	}

	function select_code()
	{
		if ($('#<?php echo $layerFormID?> input[id*=\'layer_goods_\']:checked').length == 0) {
			alert('상품을 선택해 주세요!');
			return false;
		}

		var checkboxCnt		= $('#<?php echo $layerFormID?> input[id*=\'layer_goods_\']').length;
		var applyGoodsCnt	= 0;
		var chkGoodsCnt		= 0;
		var resultJson = {
			"mode": "<?php echo $mode?>",
			"parentFormID": "<?php echo $parentFormID?>",
			"dataFormID": "<?php echo $dataFormID?>",
			"dataInputNm": "<?php echo $dataInputNm?>",
			"childRow": "<?php echo $childRow?>",
			"info": []
		};

		$('#<?php echo $layerFormID?> input[id*=\'layer_goods_\']:checked').each(function() {
			var goodsNo		= $(this).val();
			var goodsNm		= $('#goodsNm_'+goodsNo).val();
			var goodsImg	= $('#goodsImage_'+goodsNo).get(0).src;
			var goodsInfo		= $('#tbl_goods_'+goodsNo).html();
			var goodsPrice		= $('#goodsPrice_'+goodsNo).html();
			var scmNm		= $('#scmNm_'+goodsNo).html();
			var regDt		= $('#regDt_'+goodsNo).val();
			var totalStock		= $('#totalStock_'+goodsNo).html();
			var stockTxt		= $('#stockTxt_'+goodsNo).html();
			var displayPc		= $('#displayPc_'+goodsNo).html();
			var displayMobile		= $('#displayMobile_'+goodsNo).html();

			if ($('#<?php echo $dataFormID?>_'+goodsNo).length == 0) {

				resultJson.info.push({"goodsNo": goodsNo, "goodsNm": goodsNm, "scmNm": scmNm, "goodsImg": goodsImg, "goodsInfo": goodsInfo, "goodsPrice": goodsPrice, "regDt": regDt, "totalStock": totalStock, "stockTxt": stockTxt,"displayPc": displayPc,"displayMobile": displayMobile});

				applyGoodsCnt++;
			}
			chkGoodsCnt++;
		});

		if (applyGoodsCnt > 0) {

			<?php if($callFunc) { ?>
			<?=$callFunc?>(resultJson);
			<?php } else { ?>
			displayTemplate(resultJson);
			<?php } ?>
			if (applyGoodsCnt != chkGoodsCnt) {
				alert('선택한 '+chkGoodsCnt+'개의 상품중 '+applyGoodsCnt+'개의 상품이 추가 되었습니다.');
			}
			// 선택된 버튼 div 토글
			if (chkGoodsCnt > 0) {
				$('#' + resultJson.parentFormID).addClass('active');
			} else {
				$('#' + resultJson.parentFormID).removeClass('active');
			}
			$('div.bootstrap-dialog-close-button').click();
		} else {
			alert('동일한 상품이 이미 존재합니다.');
		}
	}
	/**
	 * 상품 기본 출력
	 * @param data
	 */
	function displayTemplate(data) {
		if (data.dataInputNm == '') {
			data.dataInputNm = 'goodsNo';
		}

		if (data.info.length > 0 && !$('#' + data.parentFormID).children().is('h5') && (data.mode != 'simple' && data.mode != 'recom')) {
			$('#' + data.parentFormID).prepend('<h5>선택된 상품</h5>');
		}

		var parentFormCount = $('#' + data.parentFormID+' tr').length;

		if(data.mode == 'search'){
			$.each(data.info, function (key, val) {
				var addHtml = "";
                addHtml += '<div id="' + data.dataFormID + '_' + val.goodsNo + '"  class="btn-group btn-group-xs">';
                addHtml += '<input type="hidden" name="' + data.dataInputNm + '[]" value="' + val.goodsNo + '" />';
                addHtml += '<input type="hidden" name="' + data.dataInputNm + 'Nm[]" value="' + val.goodsNm + '" />';
                addHtml += '<button type="button" class="btn btn-gray">' + val.goodsNm + '</button>';
				addHtml += '<button type="button" class="btn btn-red" data-toggle="delete" data-target="#'+data.dataFormID+'_'+ val.goodsNo+'">삭제</button>';
                addHtml += '</div>';
				$("#" + data.parentFormID).append(addHtml);
			});
		} else if(data.mode == 'simple'){
			$.each(data.info, function (key, val) {
				var addHtml = "";
                addHtml += '<tr id="' + data.dataFormID + '_' + val.goodsNo + '">';
                addHtml += '<td class="center"><span class="number">' + (key + 1 + Number(data.childRow) + parentFormCount) + '</span><input type="hidden" name="' + data.dataInputNm + '[]" value="' + val.goodsNo + '" /></td>';
                addHtml += '<td class="center"><a href="<?php echo URI_HOME; ?>goods/goods_view.php?goodsNo=' + val.goodsNo + '" target="_blank"><img src="' + val.goodsImg + '" align="absmiddle" width="50" alt="' + val.goodsNm + '" title="' + val.goodsNm + '" /></a></td>';
                addHtml += '<td><a href="../goods/goods_register.php?goodsNo=' + val.goodsNo + '" target="_blank">' + val.goodsNm + '</a></td>';
                addHtml += '<td  class="center"><input type="button"  data-toggle="delete"  data-target="#'+data.dataFormID+'_'+ val.goodsNo+'" value="삭제" class="btn btn-sm btn-gray"/></td>';
                addHtml += '</tr>';
				$("#" + data.parentFormID).append(addHtml);
			});
		} else if (data.mode == 'recom') {
			if ($("#" + data.parentFormID + " #tbl_recom_goods_tr_none").length > 0) $("#" + data.parentFormID + " #tbl_recom_goods_tr_none").remove();
			$.each(data.info, function (key, val) {
				var addHtml = "";
				addHtml += '<tr id="' + data.dataFormID + '_' + val.goodsNo + '" class="recom_tr">';
				addHtml += '<td class="center"><input type="checkbox" name="del[]" value="' + val.goodsNo + '"></td>';
				addHtml += '<td class="center"><span class="number"><span>' + (key + 1 + Number(data.childRow)) + '</span><input type="hidden" name="' + data.dataInputNm + '[]" value="' + val.goodsNo + '" /></td>';
                addHtml += '<td class="center"><a href="<?php echo URI_HOME; ?>goods/goods_view.php?goodsNo=' + val.goodsNo + '" target="_blank"><img src="' + val.goodsImg + '" align="absmiddle" width="50" alt="' + val.goodsNm + '" title="' + val.goodsNm + '" /></a></td>';
                addHtml += '<td><a href="../goods/goods_register.php?goodsNo=' + val.goodsNo + '" target="_blank">' + val.goodsNm + '</a></td>';
				addHtml += '<td class="center">' + val.goodsPrice + '</td>';
				addHtml += '<td class="center">' + val.scmNm + '</td>';
				addHtml += '<td class="center">' + val.totalStock + '</td>';
				addHtml += '<td class="center">' + val.stockTxt + '</td>';
				addHtml += '<td class="center">' + val.displayPc + '</td>';
				addHtml += '<td class="center">' + val.displayMobile + '</td>';
				addHtml += '</tr>';
				$("#" + data.parentFormID).append(addHtml);
			});
		} else {
			$.each(data.info, function (key, val) {
				var addHtml = "";
                addHtml += '<tr id="' + data.dataFormID + '_' + val.goodsNo + '" >';
                addHtml += '<input type="hidden" name="' + data.dataInputNm + '[]" value="' + val.goodsNo + '" />';
                addHtml += val.goodsInfo;
                addHtml += '</tr>';
				$("#" + data.parentFormID).append(addHtml);
			});
		}
	}
	//-->
</script>
