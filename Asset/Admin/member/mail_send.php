<?php
$jsonMemberData = json_encode($memberData);
$jsonMemberData = gd_isset($jsonMemberData);
?>
<form id="formSendMail" action="../member/mail_ps.php" method="post">
    <div class="page-header js-affix">
        <h3><?= end($naviMenu->location); ?></h3>
        <input type="submit" value="메일발송" class="btn btn-red">
    </div>
    <div class="table-title gd-help-manual">개별/전체메일발송</div>
    <table class="table table-cols member-mail-send">
        <colgroup>
            <col class="width-md"/>
            <col/>
        </colgroup>
        <tr>
            <th>무료메일 잔여건수</th>
            <td><?php if ($freeMailCountView === true) { echo number_format($freeMailCount) . '건'; } else { echo '무제한'; } ?></td>
        </tr>
        <tr>
            <th class="require">제목</th>
            <td>
                <input type="text" name="subject" id="subject" class=" width100p" value="" title="" required="required">

                <div class="notice-info">* 정보통신망법에 따라 영리목적의 광고성 정보 발송 시 사전 수신동의한 회원을 대상으로 해야 하며,제목에 (광고)를 표시해야 합니다.
                </div>
            </td>
        </tr>
        <tr>
            <th class="require">발송자이메일</th>
            <td>
                <input type="text" name="senderEmail" id="senderEmail" class=" width-2xl" value="<?= $centerEmail ?>"
                       title="" required="required">
            </td>
        </tr>
        <tr>
            <th class="require">대상회원 선택</th>
            <td id="selectTarget">
                <div class="radio">
                    <label for="selectMemberFl" class="radio-inline">
                        <input type="radio" name="selectTargetFl" id="selectMemberFl" class="" checked="checked"
                               value="manual">
                        회원직접선택
                    </label>
                    <?php
                    if (empty($memberData)) {
                        ?>
                        <input type="button" value="선택하기" class="btn btn-sm btn-gray" id="btnSearchMember">
                        <span class="display-none search-count" id="divSearchCount">
                            <a href="#" id="linkSearchMember">
                                <span id="receiveTotal" class="js-receive-total"></span>
                                명
                                <span class="text-red">(수신거부 대상자 </span>
                                <span id="rejectCount" class="text-red js-reject-count"></span>
                                <span class="text-red">명 포함)</span>
                            </a>
                        </span>
                        <?php
                    } else {
                        echo $memberData['memNm'] . '(' . $memberData['email'] . ')';
                        if ($memberData['maillingFl'] == 'n') {
                            echo '<span class="text-red">(수신거부한 회원입니다.)</span>';
                        }
                    }
                    ?>
                </div>
                <div class="radio mgt15">
                    <div class="form-inline">
                        <label for="selectGroupFl" class="radio-inline">
                            <input type="radio" name="selectTargetFl" id="selectGroupFl" class="" value="group">
                            회원등급선택
                        </label>
                        <?= gd_select_box_by_group_list(null, '회원등급선택', 'disabled="disabled"'); ?></div>
                    <div class="checkbox">
                        <label for="sendAgreeGroupFl1">
                            &nbsp;&nbsp;&nbsp;
                            <input type="checkbox" name="sendAgreeGroupFl" id="sendAgreeGroupFl1" disabled="disabled"
                                   value="y">
                            수신동의한 회원에게만 발송
                        </label>
                    </div>
                </div>
                <div class="radio mgt15">
                    <label for="selectAllFl" class="radio-inline all-member">
                        <input type="radio" name="selectTargetFl" id="selectAllFl" class="" value="all">
                        전체회원
                    </label>
                    <div class="checkbox">
                        <label for="sendAgreeAllFl1">
                            &nbsp;&nbsp;&nbsp;
                            <input type="checkbox" name="sendAgreeAllFl" id="sendAgreeAllFl1" class=""
                                   disabled="disabled" value="y">
                            수신동의한 회원에게만 발송
                        </label>
                    </div>
                </div>
                <span class="notice-info">* 정보통신망법에 따라 수신거부한 회원에게는 광고성정보를 발송할 수 없으며, 위반 시 과태료가 부과됩니다.</span>
            </td>
        </tr>
        <tr>
            <th class="require">내용</th>
            <td><textarea name="contents" rows="26" style="height:400px;" id="editor"
                          class=" width100p" type="editor"></textarea>
            </td>
        </tr>
        <tr>
            <th>수신동의</th>
            <td>
                <div class="checkbox">
                    <label for="agreeReceiveWordsFl" class="checkbox-inline">
                        <input type="checkbox" value="y" id="agreeReceiveWordsFl" name="agreeReceiveWordsFl"
                               checked="checked">
                        수신동의문구를 메일에 포함합니다.
                    </label>
                </div>
                <div>
                        <textarea name="agreeReceiveWords" id="agreeReceiveWords" rows="3"
                                  class=" width100p">본 메일은 <?= $today; ?> 기준, 메일 수신에 동의하신 회원님께 발송한 메일입니다.
                        </textarea>
                </div>
            </td>
        </tr>
        <tr>
            <th>수신거부</th>
            <td>
                <div class="checkbox">
                    <label for="rejectReceiveWordsFl" class="checkbox-inline">
                        <input type="checkbox" value="y" id="rejectReceiveWordsFl" name="rejectReceiveWordsFl"
                               checked="checked">
                        수신거부기능 및 문구를 메일에 포함합니다
                    </label>
                </div>
                <div>
                        <textarea name="rejectReceiveWords" id="rejectReceiveWords" rows="4" class=" width100p"
                                  disabled="disabled">
※ [메일내용]에 자체 수신거부기능을 넣으신 경우, 체크해제를 하면 됩니다.
- 이메일의 수신을 원하지 않으시면 [수신거부]를 클릭해 주세요.
- If you don’t want to receive this mail, click here.
                        </textarea>
                </div>
            </td>
        </tr>
    </table>
</form>
<div class="display-none" id="divMaillingList"></div>
<script type="text/javascript" src="<?= PATH_ADMIN_GD_SHARE ?>script/smart/js/HuskyEZCreator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?= PATH_ADMIN_GD_SHARE ?>script/smart/js/editorLoad.js" charset="utf-8"></script>
<script type="text/javascript">
    <!--
    $(document).ready(function () {
        var $formSendMail = $('#formSendMail');

        /**
         * 폼 검증
         */
        $formSendMail.validate({
            ignore: [],
            rules: {
                subject: "required",
                senderEmail: "email",
                contents: {
                    required: function (textarea) {
                        var editorcontent = oEditors.getById[textarea.id].getIR();	// 에디터의 내용 가져오기.
                        editorcontent = editorcontent.replace(/<img[^>]*>/gi, '이미지').replace(/<[^>]*>/gi, '').replace('&nbsp;', '');
                        return editorcontent.length === 0;
                    }
                }
            }, messages: {
                subject: "제목을 입력해주세요.",
                senderEmail: "메일 주소를 입력해주세요.",
                contents: "내용을 입력해주세요."
            }, submitHandler: function (form) {
                oEditors.getById["editor"].exec("UPDATE_CONTENTS_FIELD", []);	// 에디터의 내용이 textarea에 적용됩니다.
                var data = $(form).serializeArray();
                data.push({name: "mode", value: "mailSend"});
                data.push({name: "sendType", value: "manual"});
                var recvList = [<?= $jsonMemberData; ?>];
                if (_.isNull(recvList[0])) {
                    recvList.shift();
                }
                var $tbody = $('tbody tr', '#divMaillingList');
                if ($('#selectMemberFl').prop('checked')) {
                    if ($tbody.length < 1 && recvList.length < 1) {
                        alert('선택된 회원이 없습니다.');
                        return false;
                    }
                }

                $.each($tbody.clone(), function (idx, item) {
                    var $item = $(item);
                    $item.find('.snote').remove();
                    $item.find('td:eq(5) .notice-ref').remove();
                    var receiver = {
                        memNo: $item.find(':checkbox').val(),
                        memNm: $item.find('td:eq(3)').text(),
                        memId: $item.find('td:eq(2) span.eng').text(),
                        email: $.trim($item.find('td:eq(5)').text())
                    };
                    if (_.isNull(receiver) === false) {
                        recvList.push(receiver);
                    }
                });
                data.push({name: "rcverList", value: JSON.stringify(recvList)});
                post_with_reload("../member/mail_ps.php", data);
            }
        });

        // 대상선택 라디오버튼 클릭
        $('input[name="selectTargetFl"]').click(function (e) {
            $('#groupSno').rules('remove');
            var radio = $('.radio');
            var obj = this;
            radio.each(function (index, item) {
                if ($(item).has(obj).length > 0) {
                    $(item).find('input[type="button"], :checkbox, select').prop({
                        disabled: false
                    });
                } else {
                    $(item).find('#divSearchCount').remove();
                    $(item).find('select option:first').prop("selected", true);
                    $(item).find('input[type="button"], :checkbox, select').prop({
                        disabled: true,
                        checked: false
                    });
                }
            });

            // 대상선택-회원등급선택
            if (e.target.id === 'selectGroupFl') {
                $('#sendAgreeGroupFl1').prop('checked', true);
                $('#groupSno').rules('add', {
                    required: true,
                    messages: {
                        required: "회원등급을 선택해 주세요."
                    }
                });
            }

            // 대상선택-전체회원
            if (e.target.id === 'selectAllFl') {
                $('#sendAgreeAllFl1').prop('checked', true);
                var params = [];
                params.push({name: "mode", value: "mailingAgreeCount"});
                ajax_with_layer('../member/member_ps.php', params, function (data, textStatus, jqXHR) {
                    $(obj).closest('label').after(appendCount(data.all, data.reject));
                });
            }
        });

        // 대상선택-회원등급선택
        $('select[name="groupSno"]').on({
            change: function (e) {
                var value = $(this).val();
                if (_.isEmpty(value) === false) {
                    var params = [];
                    params.push({name: "mode", value: "mailingAgreeCount"});
                    params.push({name: "groupSno", value: value});
                    ajax_with_layer('../member/member_ps.php', params, function (data, textStatus, jqXHR) {
                        $(e.target).next('#divSearchCount').remove();
                        $(e.target).after(appendCount(data.all, data.reject));
                    });
                }
            }
        });

        // 대상선택-직접선택
        $('#btnSearchMember, #linkSearchMember', $formSendMail).click(function (e) {
            e.preventDefault();
            window.open('../share/popup_add_member.php?sendMode=mail', 'member_search', 'width=1450, height=760, scrollbars=no');
        });
    });

    /**
     * 메일 수신거부 문구 html 생성
     * @param all
     * @param reject
     * @returns {string}
     */
    function appendCount(all, reject) {
        var html = [];
        html.push('&nbsp;&nbsp;<span class="search-count" id="divSearchCount">');
        html.push('<span id="receiveTotal">' + all + '</span>명');
        html.push('<span class="text-red">(수신거부 대상자</span>');
        html.push('<span id="rejectCount" class="text-red">' + reject + '</span>');
        html.push('<span class="text-red">명 포함)</span>');
        html.push('</span>');

        return html.join('');
    }
    //-->
</script>
