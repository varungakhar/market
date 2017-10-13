<div class="container-fluid">
    <form name="formCounsel" id="formCounsel">
        <input type="hidden" name="memNo" value="<?= $memberData['memNo']; ?>"/>

        <div class="row">
            <div class="col-xs-12">
                <table class="table table-cols">
                    <colgroup>
                        <col class="width-xs">
                        <col>
                        <col class="width-xs">
                        <col>
                    </colgroup>
                    <tbody>
                    <tr>
                        <th>등록자</th>
                        <td><?= $managerData['managerNm'] . '(' . $managerData['managerId'] . ')'; ?></td>
                        <th>상담회원</th>
                        <td><?= $memberData['memNm'] . '(' . $memberData['memId'] . ')'; ?></td>
                    </tr>
                    <tr>
                        <th>
                            상담수단
                        </th>
                        <td>
                            <select name="method" id="counselMethod" class="form-control">
                                <option value="p">전화</option>
                                <option value="m">메일</option>
                            </select>
                        </td>
                        <td>상담구분</td>
                        <td>
                            <select name="kind" id="counselType" class="form-control">
                                <option value="o">주문</option>
                                <option value="d">배송</option>
                                <option value="c">취소환불</option>
                                <option value="e">오류</option>
                                <option value="etc">기타</option>
                            </select>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">상담내용</h3>
                    </div>
                    <div class="panel-body">
                        <textarea name="contents" class="form-control" rows="10" maxlength="1000"></textarea>
                    </div>
                    <div class="panel-footer center">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-red btn-register">등록</button>
                            <button type="button" class="btn btn-gray btn-cancel">취소</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    $('#formCounsel').validate({
        submitHandler: function (form) {
            var params = $(form).serializeArray();
            params.push({name: "mode", value: "register"});
            params.push({name: "memNo", value: $('input[name=memNo]').val()});

            $.post('../share/member_crm_counsel_ps.php', params, function (data) {
                alert(data);
                opener.location.reload();
                location.reload();
            });
        }
    });

    $('.btn-cancel').click(function () {
        self.close();
    });
</script>
