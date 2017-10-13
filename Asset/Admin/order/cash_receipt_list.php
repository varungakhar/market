<div class="page-header js-affix">
	<h3><?php echo end($naviMenu->location); ?>
		<small>현금영수증 신청/대기/완료 처리된 주문 리스트 입니다.</small>
	</h3>
	<div class="btn-group">
		<a href="./cash_receipt_register.php" class="btn btn-red" role="button">현금영수증 개별발급</a>
	</div>
</div>
<form id="frmSearchManager" name="frmSearchManager" method="get" class="js-form-enter-submit">
	<input type="hidden" name="detailSearch" value="<?php echo $search['detailSearch'];?>" />
	<input type="hidden" name="sort[name]" value="<?=$sort['fieldName']?>">
	<input type="hidden" name="sort[mode]" value="<?=$sort['sortMode']?>" />

    <div class="table-title gd-help-manual">현금영수증 검색</div>

	<div class="search-detail-box">
		<table class="table table-cols">
			<colgroup>
				<col class="width-sm"/>
				<col/>
			</colgroup>
			<tbody>
			<tr>
				<th>검색어</th>
				<td colspan="3" >
					<div class="form-inline">
						<?php echo gd_select_box('key','key',$search['combineSearch'],null,$search['key'],null);?>
						<input type="text" name="keyword" value="<?php echo $search['keyword']; ?>" class="form-control width-md" placeholder="키워드를 입력해 주세요." />
					</div>
				</td>
			</tr>
			<tr>
				<th>기간검색</th>
				<td colspan="3">
					<div class="form-inline">
						<?php echo gd_select_box('treatDateFl', 'treatDateFl', ['regDt' => '등록일', 'modDt' => '수정일', 'processDt' => '처리일'], '', $search['treatDateFl']); ?>
						<div class="input-group js-datepicker">
							<input type="text" name="treatDate[start]" value="<?php echo $search['treatDate']['start'];?>" class="form-control width-xs" placeholder="수기입력 가능" />
							<span class="input-group-addon"><span class="btn-icon-calendar"></span></span>
						</div>
						~
						<div class="input-group js-datepicker">
							<input type="text" name="treatDate[end]" value="<?php echo $search['treatDate']['end'];?>" class="form-control width-xs" placeholder="수기입력 가능" />
							<span class="input-group-addon"><span class="btn-icon-calendar"></span></span>
						</div>
						<div class="btn-group js-dateperiod" data-toggle="buttons" data-target-name="treatDate">
							<label class="btn btn-white btn-sm hand"><input type="radio" value="0">오늘</label>
							<label class="btn btn-white btn-sm hand"><input type="radio" value="7">7일</label>
							<label class="btn btn-white btn-sm hand"><input type="radio" value="15">15일</label>
							<label class="btn btn-white btn-sm hand"><input type="radio" value="30">1개월</label>
							<label class="btn btn-white btn-sm hand"><input type="radio" value="90">3개월</label>
							<label class="btn btn-white btn-sm hand"><input type="radio" value="-1" checked="checked">전체</label>
						</div>
					</div>
				</td>
			</tr>
			</tbody>
			<colgroup>
				<col class="width-sm"/>
				<col/>
				<col class="width-sm"/>
				<col class="width-xl"/>
			</colgroup>
			<tbody class="js-search-detail" class="display-none">
			<tr>
				<th>상품가격</th>
				<td colspan="3" class="form-inline">
					<input type="text" name="settlePrice[]" value="<?php echo $search['settlePrice'][0];?>" class="form-control width-md" /> ~
					<input type="text" name="settlePrice[]" value="<?php echo $search['settlePrice'][1];?>" class="form-control width-md" />
				</td>
			</tr>
			<tr>
				<th>발행용도</th>
				<td class="form-inline">
                    <label class="radio-inline"><input type="radio" name="useFl" value="" <?php echo gd_isset($checked['useFl']['']); ?> />전체</label>
					<?php echo gd_radio_box('useFl', $arrUseFl, $search['useFl']);?>
				</td>
				<th>인증 종류</th>
				<td class="form-inline">
                    <label class="radio-inline"><input type="radio" name="certFl" value="" <?php echo gd_isset($checked['certFl']['']); ?> />전체</label>
					<?php echo gd_radio_box('certFl', $arrCertFl, $search['certFl']);?>
				</td>
			</tr>
			<tr>
				<th>거래번호</th>
				<td class="form-inline"><input type="text" name="pgTid" value="<?php echo $search['pgTid'];?>" class="form-control width-md" /></td>
				<th>발행 상태</th>
				<td class="form-inline">
                    <label class="radio-inline"><input type="radio" name="statusFl" value="" <?php echo gd_isset($checked['statusFl']['']); ?> />전체</label>
					<?php echo gd_radio_box('statusFl', $arrStatus, $search['statusFl']);?>
				</td>
			</tr>
			</tbody>
		</table>
		<button type="button" class="btn btn-sm btn-link js-search-toggle bold">상세검색<span>펼침</span></button>
	</div>
	<div class="table-btn">
		<input type="submit" value="검색" class="btn btn-lg btn-black"/>
	</div>


    <div class="table-header">
        <div class="pull-left">
            검색 <strong><?php echo number_format($page->recode['total']);?></strong>개 /
            전체 <strong><?php echo number_format($page->recode['amount']);?></strong>개
        </div>
        <div class="pull-right form-inline">
            <?php echo gd_select_box('sort', 'sort', $search['sortList'], null, $sort, null); ?>
            <?php echo gd_select_box('pageNum', 'pageNum', gd_array_change_key_value([10, 20, 30, 40, 50, 60, 70, 80, 90, 100, 200, 300, 500]), '개 보기', Request::get()->get('pageNum'), null); ?>
        </div>
    </div>
</form>

<form id="frmList" name="frmList" action="" method="post">
	<input type="hidden" name="mode" />
	<table class="table table-rows table-fixed">
		<thead>
		<tr>
			<th class="width3p"><input class="js-checkall" type="checkbox" data-target-name="orderNo"></th>
            <th class="width3p">번호</th>
            <th class="width7p">신청일자</th>
            <th class="width7p">처리일자</th>
			<th class="width10p">주문번호</th>
			<th class="width7p">신청자</th>
			<th class="width7p">발급금액</th>
			<th class="width7p">결제방법</th>
            <th class="width7p">주문상태</th>
			<th class="width7p">발행상태</th>
			<th class="width5p">정보</th>
			<th class="width5p">영수증</th>
            <th class="width5p">처리</th>
		</tr>
		</thead>
		<tbody>
<?php
    if (gd_isset($data)) {
        // 발행 상태 변경
        foreach ($arrStatus as $sKey => $sVal) {
            if ($sKey === 'y') {
                $arrStatus[$sKey] = '<span class="text-blue">' . $sVal . '</span>';
            }
            if ($sKey === 'c') {
                $arrStatus[$sKey] = '<span class="text-gray">' . $sVal . '</span>';
            }
            if ($sKey === 'd') {
                $arrStatus[$sKey] = '<span class="text-darkred">' . $sVal . '</span>';
            }
            if ($sKey === 'f') {
                $arrStatus[$sKey] = '<span class="text-orange-red">' . $sVal . '</span>';
            }
        }

        // 현금 영수증 리스트
        foreach ($data as $key => $val) {
            if ($val['issueMode'] === 'a') {
                $strSettleKind = '<span class="notice-ref font12">' . $arrIssue[$val['issueMode']] . '</span>';
                $strOrderStatus = '<span class="notice-ref font12">' . $arrIssue[$val['issueMode']] . '</span>';
                $strIssueStatus = '';
                $strOrderNo = '<span class="text-darkgray">'.$val['orderNo'] . '</span>';
            } else {
                $strSettleKind = gd_isset($setSettleKind[$val['settleKind']]['name']);
                $strOrderStatus = gd_isset($setStatus[$val['orderStatus']]);
                if ($val['issueMode'] === 'p') {
                    $strIssueStatus = '<br/><span class="notice-ref notice-sm">(' . $arrIssue[$val['issueMode']] . ')</span>';
                } else {
                    $strIssueStatus = '';
                }
                $strOrderNo = '<a href="./order_view.php?orderNo='.$val['orderNo'].'" target="_blank">'.$val['orderNo'].'</a>';
            }

            // 일괄 발급 처리
            if ($val['statusFl'] === 'r' || $val['statusFl'] === 'f') {
                $selectedApprovalChk = true;
            } else {
                $selectedApprovalChk = false;
            }
?>
            <tr class="text-center">
                <td><input name="orderNo[]" type="checkbox" value="<?php echo $val['orderNo']; ?>" <?php if ($selectedApprovalChk === false) { echo 'disabled="disabled"';  }?>/></td>
                <td class="font-num"><?php echo number_format($page->idx--); ?></td>
                <td class="font-date"><?php echo gd_date_format('Y-m-d H:i', $val['regDt']);?></td>
                <td class="note date"><?php echo gd_date_format('Y-m-d H:i', $val['processDt']);?></td>
                <td class="number bold"><?php echo $strOrderNo;?></td>
                <td class="notice-ref"><?php echo $val['requestNm'];?></td>
                <td class="font-num"><?php echo gd_currency_display($val['settlePrice']);?></td>
                <td class=""><?php echo $strSettleKind;?></td>
                <td class=""><?php echo $strOrderStatus;?></td>
                <td class=""><?php echo $arrStatus[$val['statusFl']] . $strIssueStatus;?></td>
                <td class=""><button type="button" onclick="cash_receipt_process('<?php echo $val['orderNo'];?>');" class="btn-dark-gray btn-5">보기</button></td>
                <td class="">
                    <?php
                    if ($val['statusFl'] === 'y' || $val['statusFl'] === 'c') {
                        echo '<button type="button" onclick="pg_receipt_view(\'cash\',\'' . $val['orderNo'] . '\');" class="btn-dark-gray btn-5">영수증</button>';
                    }
                    ?>
                </td>
                <td class="">
                    <?php
                    if ($val['statusFl'] === 'r') {
                        if ($val['issueMode'] === 'a' || ($val['issueMode'] === 'u' && in_array(substr($val['orderStatus'], 0, 1), $statusReceiptPossible))) {
                            echo '<button type="button" data-orderno="' . $val['orderNo'] . '" class="btn btn-black btn-5 js-approval ">발급</button> ';
                        }
                        echo '<button type="button" data-orderno="' . $val['orderNo'] . '" class="btn btn-white btn-5 js-deny">거절</button> ';
                    } elseif ($val['statusFl'] === 'y' && $val['issueMode'] !='p') {
                        echo '<button type="button" data-orderno="' . $val['orderNo'] . '" class="btn btn-white btn-5 js-cancel">취소</button> ';
                    } elseif ($val['statusFl'] === 'c') {
                        echo '<button type="button" data-orderno="' . $val['orderNo'] . '" class="btn btn-white btn-5 js-delete">삭제</button> ';
                    } elseif ($val['statusFl'] === 'd') {
                        echo '<button type="button" data-orderno="' . $val['orderNo'] . '" class="btn btn-black btn-5 js-request">재요청</button> ';
                        echo '<button type="button" data-orderno="' . $val['orderNo'] . '" class="btn btn-white btn-5 js-delete">삭제</button> ';
                    } elseif ($val['statusFl'] === 'f') {
                        echo '<button type="button" data-orderno="' . $val['orderNo'] . '" class="btn btn-black btn-5 js-approval">재발급</button> ';
                        echo '<button type="button" data-orderno="' . $val['orderNo'] . '" class="btn btn-white btn-5 js-delete">삭제</button> ';
                    }

                    ?>
                </td>
            </tr>
<?php
        }
    } else {
?>
        <tr>
            <td class="no-data" colspan="12">검색된 정보가 없습니다.</td>
        </tr>
<?php
    }
?>
        </tbody>
    </table>
    <div class="table-action">
        <div class="pull-left">
            <span class="action-title">선택한 현금영수증을</span>
            <button type="button" class="btn btn-white js-approval-selected">일괄발급</button>
        </div>
        <div class="pull-right">
        </div>
    </div>
</form>

<div class="center"><?php echo $page->getPage(); ?></div>

<script type="text/javascript">
    <!--
    $(document).ready(function (n) {
        // 현금영수증 발급
        $('.js-approval').click(function (e) {
            var orderNo = $(this).data('orderno');
            BootstrapDialog.show({
                title: '현금영수증 발급',
                message: '[' + orderNo + '] 현금영수증 발급 처리를 하겠습니까?',
                buttons: [{
                    id: 'btn-approval',
                    label: '현금영수증 발급',
                    cssClass: 'btn-red',
                    action: function(dialog) {
                        var $approvalButton = this;
                        var $closeButton = dialog.getButton('btn-close');
                        $approvalButton.disable();
                        $closeButton.disable();
                        $approvalButton.spin();
                        dialog.setClosable(false);
                        dialog.setMessage('현금영수증 발급 중입니다.');
                        $.ajax({
                            type: 'POST'
                            , url: '../order/cash_receipt_ps.php'
                            , data: {'mode': 'pg_approval', 'modeType': 'list', 'orderNo': orderNo}
                            , success: function (res) {
                                dialog.setClosable(true);
                                $closeButton.enable();
                                if (res == 'SUCCESS') {
                                    dialog.getModalBody().html('<?php echo __('현금영수증 발급 되었습니다.');?>');
                                } else {
                                    dialog.setTitle('현금영수증 발급 실패');
                                    dialog.setType(BootstrapDialog.TYPE_DANGER);
                                    dialog.getModalBody().html('<?php echo __('현금영수증 발급에 실패 하였습니다.');?>');
                                }
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                            }
                        });
                    }
                },
                    {
                        id: 'btn-close',
                        label: '닫기',
                        action: function(dialogItself){
                            dialogItself.close();
                        }
                    }]
            });
        });

        // 현금영수증 발급 취소
        $('.js-cancel').click(function (e) {
            var orderNo = $(this).data('orderno');
            BootstrapDialog.show({
                title: '현금영수증 발급 취소',
                type: BootstrapDialog.TYPE_WARNING,
                message: '[' + orderNo + '] 현금영수증 발급 취소 처리를 하겠습니까?',
                buttons: [{
                    id: 'btn-cancel',
                    label: '현금영수증 발급 취소',
                    cssClass: 'btn-warning',
                    action: function(dialog) {
                        var $cancelButton = this;
                        var $closeButton = dialog.getButton('btn-close');
                        $cancelButton.disable();
                        $closeButton.disable();
                        $cancelButton.spin();
                        dialog.setClosable(false);
                        dialog.setMessage('현금영수증 발급 취소 중입니다.');
                        $.ajax({
                            type: 'POST'
                            , url: '../order/cash_receipt_ps.php'
                            , data: {'mode': 'pg_cancel', 'modeType': 'list', 'orderNo': orderNo}
                            , success: function (res) {
                                dialog.setClosable(true);
                                $closeButton.enable();
                                if (res == 'SUCCESS') {
                                    dialog.getModalBody().html('<?php echo __('현금영수증 발급 취소 되었습니다.');?>');
                                } else {
                                    dialog.setTitle('현금영수증 발급 취소 실패');
                                    dialog.setType(BootstrapDialog.TYPE_DANGER);
                                    dialog.getModalBody().html('<?php echo __('현금영수증 발급 취소에 실패 하였습니다.');?>');
                                }
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                            }
                        });
                    }
                },
                    {
                        id: 'btn-close',
                        label: '닫기',
                        action: function(dialogItself){
                            dialogItself.close();
                        }
                    }]
            });
        });

        // 현금영수증 거절
        $('.js-deny').click(function (e) {
            var orderNo = $(this).data('orderno');
            BootstrapDialog.confirm({
                title: '현금영수증 신청 거절',
                type: BootstrapDialog.TYPE_WARNING,
                message: '[' + orderNo + '] 현금영수증 신청 거절 처리를 하겠습니까? 거절 후 삭제나 재발행이 가능합니다.',
                closable: false,
                callback: function(result) {
                    if (result) {
                        $.ajax({
                            type: 'POST'
                            , url: '../order/cash_receipt_ps.php'
                            , data: {'mode': 'cash_receipt_deny', 'orderNo': orderNo}
                            , success: function (res) {
                                if (res == 'SUCCESS') {
                                    alert('거절 처리 되었습니다.');
                                } else {
                                    alert('거절 처리에 실패 하였습니다.');
                                }
                                location.reload(true);
                            }
                        });
                    }
                }
            });
        });

        // 현금영수증 재요청
        $('.js-request').click(function (e) {
            var orderNo = $(this).data('orderno');
            BootstrapDialog.confirm({
                title: '현금영수증 재요청',
                type: BootstrapDialog.TYPE_INFO,
                message: '[' + orderNo + '] 현금영수증을 재요청 하시겠습니까?',
                closable: false,
                callback: function(result) {
                    if (result) {
                        $.ajax({
                            type: 'POST'
                            , url: '../order/cash_receipt_ps.php'
                            , data: {'mode': 'cash_receipt_request', 'orderNo': orderNo}
                            , success: function (res) {
                                if (res == 'SUCCESS') {
                                    alert('재요청 처리 되었습니다.');
                                } else {
                                    alert('재요청 처리에 실패 하였습니다.');
                                }
                                location.reload(true);
                            }
                        });
                    }
                }
            });
        });

        // 현금영수증 삭제
        $('.js-delete').click(function (e) {
            var orderNo = $(this).data('orderno');
            BootstrapDialog.confirm({
                title: '현금영수증 삭제',
                type: BootstrapDialog.TYPE_DANGER,
                message: '[' + orderNo + '] 현금영수증을 정말로 삭제 하시겠습니까?<br/>삭제시 복구가 불가능합니다.',
                closable: false,
                callback: function(result) {
                    if (result) {
                        $.ajax({
                            type: 'POST'
                            , url: '../order/cash_receipt_ps.php'
                            , data: {'mode': 'cash_receipt_delete', 'orderNo': orderNo}
                            , success: function (res) {
                                if (res == 'SUCCESS') {
                                    alert('삭제 되었습니다.');
                                } else {
                                    alert('삭제에 실패 하였습니다.');
                                }
                                location.reload(true);
                            }
                        });
                    }
                }
            });
        });

        // 선택한 현금영수증 일괄 발급
        $('.js-approval-selected').click(function () {
            var chkCnt = $('input[name=\'orderNo[]\']:checkbox:checked').length;

            if (chkCnt < 1) {
                BootstrapDialog.show({
                    title: '선택한 현금영수증 일괄 발급',
                    type: BootstrapDialog.TYPE_WARNING,
                    message: '일괄 발급할 현금영수증을 선택해 주세요.',
                });
                return;
            }

            BootstrapDialog.show({
                title: '선택한 현금영수증 일괄 발급',
                message: '선택한 ' + chkCnt + ' 개의 현금영수증을 일괄 발급 처리 하시겠습니까?',
                buttons: [{
                    id: 'btn-approval',
                    label: '일괄 발급',
                    cssClass: 'btn-red',
                    action: function(dialog) {
                        var $approvalButton = this;
                        var $closeButton = dialog.getButton('btn-close');
                        $approvalButton.disable();
                        $closeButton.disable();
                        $approvalButton.spin();
                        //dialog.setClosable(false);
                        dialog.setMessage('선택한 ' + chkCnt + ' 개의 현금영수증을 일괄 발급 처리 중입니다.');

                        $('#frmList input[name=\'mode\']').val('pg_approval_selected');
                        $('#frmList').attr('method', 'post');
                        $('#frmList').attr('target', 'ifrmProcess');
                        $('#frmList').attr('action', './cash_receipt_ps.php');
                        $('#frmList').submit();
                    }
                },
                    {
                        id: 'btn-close',
                        label: '닫기',
                        action: function(dialogItself){
                            dialogItself.close();
                        }
                    }]
            });
            return;
        });

        $('select[name=\'pageNum\']').change(function () {
            $('#frmSearchManager').submit();
        });

        $('select[name=\'sort\']').change(function () {
            $('#frmSearchManager').submit();
        });

    });
    //-->
</script>
