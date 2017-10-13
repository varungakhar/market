<div>
    <div class="mgt10"></div>
    <div class="table-title gd-help-manual">SMS 발송 내역 상세 리스트</div>
    <div>
        <table class="table table-cols">
            <colgroup>
                <col class="width-sm"/>
                <col class="width-lg"/>
                <col class="width-sm"/>
                <col/>
                <col class="width-3xs"/>
            </colgroup>
            <tbody>
            <tr>
                <th>검색어</th>
                <td colspan="3">
                    <div class="form-inline">
                        <?php echo gd_select_box(
                            null, 'smsKey', [
                            'all'               => '=통합검색=',
                            'receiverName'      => '이름',
                            'receiverCellPhone' => '수신번호',
                        ], '', $search['smsKey']
                        ); ?>
                        <input type="text" name="smsKeyword" value="<?php echo $search['smsKeyword']; ?>" class="form-control width-xl" placeholder="키워드를 입력해 주세요."/>
                    </div>
                </td>
                <td rowspan="3">
                    <input type="button" value="검색" class="btn btn-gray" onclick="layer_list_search();"/>
                </td>
            </tr>
            <tr>
                <th>발송결과</th>
                <td>
                    <div class="form-inline">
                        <?php echo gd_select_box(null, 'smsSendStatus', $smsSendStatus, '', $search['smsSendStatus'], '= 발송결과 ='); ?>
                    </div>
                </td>
                <th>실패사유</th>
                <td>
                    <div class="form-inline">
                        <?php echo gd_select_box(null, 'smsErrorCode', $smsErrorCode, '', $search['smsErrorCode'], '= 실패사유 ='); ?>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<form id="frmList" name="frmList" action="" method="post">
    <input type="hidden" name="mode"/>
    <input type="hidden" name="smsLogSno" value="<?= $search['smsLogSno'] ?>"/>
    <table class="table table-rows table-fixed">
        <colgroup>
            <col class="width3p"/>
            <col class="width5p"/>
            <col class="width20p"/>
            <col class="width10p"/>
            <col class="width20p"/>
            <col class="width15p"/>
            <col class="width30p"/>
        </colgroup>
        <thead>
        <tr>
            <th>
                <input class="js-checkall" type="checkbox" data-target-name="sno">
            </th>
            <th>번호</th>
            <th>SMS 수신일시</th>
            <th>이름</th>
            <th>수신번호</th>
            <th>발송결과</th>
            <th>실패사유</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if (is_array($data) && count($data) > 0) {
            foreach ($data as $key => $val) {
                ?>
                <tr class="text-center">
                    <td>
                        <input name="sno[]" type="checkbox" value="<?php echo $val['sno']; ?>"/>
                    </td>
                    <td class="font-num"><?php echo number_format($page->idx--); ?></td>
                    <td class="font-date"><?php echo $val['receiverDt']; ?></td>
                    <td class="font-date"><?php echo $val['receiverName']; ?></td>
                    <td class="font-date"><?php echo gd_number_to_phone($val['receiverCellPhone']); ?></td>
                    <td><?php echo $smsSendStatus[$val['sendCheckFl']]; ?></td>
                    <td></td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr>
                <td class="center no-data" colspan="7">검색된 정보가 없습니다.</td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
    <div class="table-action">
        <div class="pull-left">
            <input type="button" value="선택 삭제" class="btn btn-white js-remove-selected"/>
        </div>
    </div>
</form>

<div class="text-center"><?php echo $page->getPage('layer_list_search(\'PAGELINK\')'); ?></div>

<div class="text-center">
    <input type="button" value="검색내역 전체 재발송" class="btn btn-gray all-member-resend"/>
    <input type="button" value="선택내역 전체 재발송" class="btn btn-gray select-member-resend"/>
    <input type="button" value="닫기" class="btn btn-white" onclick="layer_close();"/>
</div>
<div class="text-center"></div>

<script type="text/javascript">
    $(document).ready(function () {
        // 선택한 팝업 삭제
        $('.js-remove-selected').click(function () {
            var chkCnt = $('input[name=\'sno[]\']:checkbox:checked').length;

            if (chkCnt < 1) {
                BootstrapDialog.show({
                    title: '선택한 SMS 문구 삭제',
                    type: BootstrapDialog.TYPE_WARNING,
                    message: '삭제할 SMS 문구를 선택해 주세요.',
                });
                return;
            }

            // 선택한 sno
            var delSno = [];
            $.each($('input:checkbox[name="sno[]"]:checked'), function (key, value) {
                var $value = $(value);
                delSno.push($($value).val());
            });

            BootstrapDialog.show({
                title: '선택한 SMS 문구 삭제',
                message: '선택한 ' + chkCnt + ' 개의 SMS 문구를 정말로 삭제 하시겠습니까? 삭제시 복구가 불가능합니다.',
                buttons: [
                    {
                        id: 'btn-cancel',
                        label: '삭제 취소',
                        action: function (dialogItself) {
                            dialogItself.close();
                        }
                    },
                    {
                        id: 'btn-del',
                        label: '선택한 SMS 문구 삭제',
                        cssClass: 'btn-danger',
                        action: function (dialog) {
                            var $delButton = this;
                            var $cancelButton = dialog.getButton('btn-cancel');
                            $delButton.disable();
                            $cancelButton.disable();
                            $delButton.spin();
                            dialog.setClosable(false);
                            dialog.setMessage('선택한 ' + chkCnt + ' 개의 움직이는 배너를 삭제 중입니다.');

                            var params = {
                                mode: 'deleteSmsContents',
                                delSno: delSno
                            };

                            $.post('<?php echo $pageUrl?>', params, function (data) {
                                dialog.close();
                                if (data == 'OK') {
                                    layer_list_search('page=<?php echo $search['page'];?>');
                                } else {
                                    alert('삭제시 오류가 발생하였습니다.');
                                }
                            });
                        }
                    }
                ]
            });
            return;
        });

        $('.all-member-resend').click(function () {
            if ($('td.no-data').length > 0) {
                BootstrapDialog.show({
                    title: '검색내역 전체 재발송',
                    type: BootstrapDialog.TYPE_WARNING,
                    message: '검색된 정보가 없습니다.'
                });
                return false;
            } else {
                var $frm_list = $("#frmList");
                var sms_key = $('select[name=\'smsKey\']').val();
                var sms_keyword = $('input[name=\'smsKeyword\']').val();
                var sms_send_status = $('select[name=\'smsSendStatus\']').val();
                var sms_error_code = $('select[name=\'smsErrorCode\']').val();
                $frm_list.find(':hidden[name="smsKey"]').remove();
                $frm_list.find(':hidden[name="smsKeyword"]').remove();
                $frm_list.find(':hidden[name="smsSendStatus"]').remove();
                $frm_list.find(':hidden[name="smsErrorCode"]').remove();
                $frm_list.append('<input type="hidden" name="smsKey" value="' + sms_key + '"/>');
                $frm_list.append('<input type="hidden" name="smsKeyword" value="' + sms_keyword + '"/>');
                $frm_list.append('<input type="hidden" name="smsSendStatus" value="' + sms_send_status + '"/>');
                $frm_list.append('<input type="hidden" name="smsErrorCode" value="' + sms_error_code + '"/>');
                $frm_list.attr('target', '_blank');
                $frm_list.attr('action', './sms_send.php');
                $("[name='mode']").val('resend_all_member');
                $frm_list.submit();
            }
        });

        $('.select-member-resend').click(function () {
            if ($('input:checkbox[name="sno[]"]:checked').length) {
                var $frm_list = $("#frmList");
                $frm_list.attr('target', '_blank');
                $frm_list.attr('action', './sms_send.php');
                $("[name='mode']").val('resend_select_member');
                $frm_list.submit();
            } else {
                BootstrapDialog.show({
                    title: '선택내역 전체 재발송',
                    type: BootstrapDialog.TYPE_WARNING,
                    message: '재발송할 내역을 선택해 주세요.'
                });
                return;
            }
        });
    });

    // 페이지 출력
    function layer_list_search(pagelink) {
        var smsLogSno = $('input[name=\'keyword\']').val();
        var smsKey = $('select[name=\'smsKey\']').val();
        var smsKeyword = $('input[name=\'smsKeyword\']').val();
        var smsSendStatus = $('select[name=\'smsSendStatus\']').val();
        var smsErrorCode = $('select[name=\'smsErrorCode\']').val();

        if (typeof pagelink == 'undefined') {
            pagelink = '';
        }
        var parameters = {
            'layerFormID': '<?php echo $layerFormID?>',
            'smsLogSno': '<?php echo $search['smsLogSno']?>',
            'smsKey': smsKey,
            'smsKeyword': smsKeyword,
            'smsSendStatus': smsSendStatus,
            'smsErrorCode': smsErrorCode,
            'page': pagelink
        };

        $.get('<?php echo $pageUrl?>', parameters, function (data) {
            $('#<?php echo $layerFormID?>').html(data);
        });
    }
</script>
