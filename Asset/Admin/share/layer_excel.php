<script type="text/javascript">
    <!--

    $(document).ready(function () {

        var complied = _.template($('#progressExcel').html());
        $(".modal-content").append(complied());

        $("#frmExcelForm").validate({
            dialog:false,
            submitHandler: function (form) {

                if($("input[name='passwordFl']:checked").val() =='y' && ($("input[name='password']").val() =='' || $("input[name='rePassword']").val() =='')) {
                    alert("비밀번호를 입력해주세요.");
                    return false;
                }

                if($("input[name='passwordFl']:checked").val() =='y' && $("input[name='password']").val() != $("input[name='rePassword']").val()) {
                    alert("동일한 비밀번호를 입력해주세요.");
                    return false;
                }

                var whereDetail = $('#<?=$targetForm?>').serialize();

                if($("input[name='whereFl']:checked").val() =='select') {

                    if($("#<?=$targetListForm?> input[name*='<?=$targetListSno?>']:checked").length) {
                        whereDetail += "&"+$("#<?=$targetListForm?> input[name*='<?=$targetListSno?>']").serialize();
                    } else {
                        alert("리스트에서 다운받을 데이터를 먼저 선택해주세요.");
                        return false;
                    }
                }


                if($("input[name='whereFl']:checked").val() =='search' && $("input[name='searchCount']").val() == 0) {
                    alert("검색된 데이터가 없습니다.");
                    return false;
                }

                <?php if($orderStateMode) { ?>
                    whereDetail += "&statusMode=<?=$orderStateMode?>";
                <?php } ?>

                <?php if($menu =='board') { ?>
                <?php if (gd_is_provider()) { ?>
                whereDetail += "&bdId="+$("input[name='bdId']").val();
                <?php } else { ?>
                whereDetail += "&bdId="+$("select[name='bdId']").val();
                <?php } ?>
                <?php } ?>

                if($("input[name='downloadFileName']").val().trim() =='') {
                    $("input[name='downloadFileName']").val($("select[name='formSno'] option:selected").text());
                }

                $("input[name='whereDetail']").val(whereDetail);

                if($("input[name='excelPageNum']").val().trim() =='' || parseInt($("input[name='excelPageNum']").val().trim()) =='0') {
                    dialog_confirm('엑셀파일당 데이터 최대개수 제한이 없어 대용량 다운로드 시 시간이 오래 걸릴 수 있습니다. 다운로드 요청을 진행하시겠습니까?', function (result) {
                        if (result) {
                            form.target = 'ifrmProcess';
                            form.submit();
                            $(".js-progress-excel").show();
                        } else {
                            return false;
                        }

                    },'확인',{"cancelLabel":'취소',"confirmLabel":'진행'});
                } else {
                    form.target = 'ifrmProcess';
                    form.submit();
                    $(".js-progress-excel").show();
                    return false;
                }
            },
            // onclick: false, // <-- add this option
            rules: {
                formSno: {
                    required: true
                }
            },
            messages: {
                formSno: {
                    required: "다운로드 양식을 선택해주세요."
                }
            }
        });


        set_excel_list();

        $('input[name="passwordFl"]').click(function(e){
            if($(this).val() =='y') {
                $("#js-excel-form-password").show();
            } else {
                $("#js-excel-form-password").hide();
            }
        });

        $("input.js-type-korea").bind('keyup', function () { //익스 11 한글 초중성분리 그래서 test후 replace
            var tmp = $(this).val();
            var pattern = /[^a-zA-Zㄱ-ㅎㅏ-ㅣ가-힣\u119E\u11A20-9!@#$%^_{}~,.]/;
            if (pattern.test(tmp)) {
                $(this).val(tmp.replace(/[^a-zA-Zㄱ-ㅎㅏ-ㅣ가-힣\u119E\u11A20-9!@#$%^_{}~,.]/g,''));
            }
        });

        //영어랑숫자만입력
        $("input.js-type-normal").bind('keyup', function () {
            $(this).val($(this).val().replace(/[^a-z0-9!@#$%^_{}~,.]*/gi, ''));
        });


        $("input[name='excelPageNum']").bind('keyup', function () {
            $(this).val($(this).val().replace(/[^0-9]*/gi, ''));
        });


        $('input[maxlength]').maxlength({
            showOnReady: true,
            alwaysShow: true
        });

        if($("#<?=$targetListForm?> input[name*='<?=$targetListSno?>']:checked").length) {
            $("input[name='whereFl']").eq(0).prop("checked",true);
        } else {
            $("input[name='whereFl']").eq(1).prop("checked",true);
        }

        // maxlength의 경우 display none으로 되어있으면 정상작동 하지 않는다 따라서 페이지 로딩 후 maxlength가 적용된 후 display none으로 강제 처리 (임시방편 처리)
        setTimeout(function(){
            $('#frmExcelForm').find('input[maxlength]').next('span.bootstrap-maxlength').css({top: '1px', left: '255px'});
        }, 1000);

    });

    function set_excel_list(msg) {
        if(msg) {
            alert(msg);
        }

        //필수정보 세팅
        $.post('../share/layer_excel_ps.php', {'mode': 'searchList', 'menu': '<?=$menu?>', 'location': '<?=$location?>' }, function (data) {

            var getData = $.parseJSON(data);
            var addHtml = "";

            if (getData !='') {
                var pageNum = 1;

                $.each(getData, function (key, val) {

                    if(val.state == 'n') var cnt = 1;
                    else var cnt = val.fileName.split('<?=STR_DIVISION?>').length;

                    for(var i = 0; i< cnt; i++) {
                        addHtml += "<tr>";
                        addHtml += "<td class='width-3xs center number'>"+pageNum+"</td>";
                        addHtml += "<td class='width-md center'>"+val.title+"</td>";
                        addHtml += "<td class='width-lg center'>"+val.downloadFileName+"</td>";
                        addHtml += "<td class='width-3xs center'>"+(i+1)+"/"+cnt+"</td>";
                        if(val.state = 'y') {
                            addHtml += "<td class='width-xs center'>생성완료</td>";
                            addHtml += "<td class='width-xs center'>"+val.managerNm+"("+val.managerId+")</td>";
                            addHtml += "<td class='width-xs center'>~"+val.expiryDate+"</td>";
                            addHtml += "<td class='width-xs center'><button type='button' class='btn btn-white btn-xs js-excel-request-download' data-sno='"+val.sno+"' data-key='"+i+"'>다운로드</button></td>";
                        } else {
                            addHtml += "<td class='width-xs center'>생성중</td>";
                            addHtml += "<td class='width-xs center'>"+val.managerNm+"("+val.managerId+")</td>";
                            addHtml += "<td class='width-xs center'></td>";
                            addHtml += "<td class='width-xs center'><button type='button' class='btn btn-white btn-xs js-excel-request-download'>다운로드</button></td>";
                        }

                        pageNum++;
                    }
                });
            } else {
                addHtml +="<tr><td class='center no-data' colspan='8'>다운로드할 엑셀 양식을 선택 후 요청 버튼을 눌러주세요.</td></tr>";
            }

            $("#tblExcelRequest tbody").html(addHtml);
            if(pageNum > 5) $("#tblExcelRequest thead tr th:last").show();
            else $("#tblExcelRequest thead tr th:last").hide();

            $('.js-excel-request-download').on('click', function(){

                if($(this).data('sno')) {
                    $("input[name='sno']").val($(this).data('sno'));
                    $("input[name='excelKey']").val($(this).data('key'));

                    $("#frmExcelRequest").submit();
                    return false;
                } else {
                    alert("파일이 생성되지 않았습니다.");
                    return false;
                }

            });

            if (getData !='') {
                $("#tblExcelRequest tbody").css("display", "block");
                $("#tblExcelRequest thead").css("display", "block");
                $("#tblExcelRequest tbody").css("height", "200px");
                $("#tblExcelRequest tbody").css("overflow-y", "auto");
                $("#tblExcelRequest tbody").css("overflow-x", "hidden");
            }

            $(".js-progress-excel").hide();
            $("#progressView").text("0%");
            $("#progressViewBg").css("width","0%");
        });

    }

    function select_form(location) {

        $.post('../share/layer_excel_ps.php', {'mode': 'search_form', 'menu': '<?=$menu?>','location':location }, function (data) {

            var formList = $.parseJSON(data);
            var addHtml = "<option>선택</option>";
            if(formList) {
                $.each(formList, function (key, val) {
                    addHtml += "<option value='" + val.sno + "'>" + val.title+ "</option>";
                });
            }
            $('select[name="formSno"]').html(addHtml);

        });

    }

    function progressExcel(size) {

        if($.isNumeric(size) == false ){
            size = "100";
        }

        $("#progressView").text(size+"%");
        $("#progressViewBg").css({
            "background-color": "#fa2828",
            "width": size+"%"
        });
    }

    function cancelProgressExcel() {

        dialog_confirm('요청 취소 시 생성 중인 엑셀 다운로드 파일이 모두 삭제됩니다. 진행중인 내용을 취소하고 페이지를 이동하시겠습니까?', function (result) {
            if (result) {

                if ($.browser.msie) {
                    document.execCommand("Stop");
                } else {
                    window.stop(); //works in all browsers but IE
                }

                setTimeout(function(){
                    $(".js-progress-excel").hide();
                    $("#progressView").text("0%");
                    $("#progressViewBg").css("width","0%");
                }, 10);

            } else {
                return false;
            }

        },'확인',{"cancelLabel":'취소',"confirmLabel":'확인'});

    }

    function hide_process() {
        $(".js-progress-excel").hide();
    }

    //-->
</script>
<script type="text/html" id="progressExcel">
    <div class="js-progress-excel" style="position:absolute;width:100%;height:100%;top:0px;left:0px;background:#000000;z-index:1060;opacity:0.5;display:none;"></div>
        <div class="js-progress-excel" style="width:300px;background:#fff;margin:0 auto;position:absolute;z-index:1070;top:250px;padding:20px;left:270px;text-align:center;display:none;">다운로드할 엑셀파일을 생성 중입니다.<br/> 잠시만 기다려주세요.
    <p style="font-size:22px;" id="progressView">0%</p>
    <div style="widtht:260px;height:18px;background:#ccc;margin-bottom:10px;">
        <div id="progressViewBg" style="height:100%">&nbsp;</div>
    </div>
    <a onclick="cancelProgressExcel()" style="cursor:pointer">요청 취소</a>
    </div>
</script>

<form id="frmExcelForm" name="frmExcelForm" action="../share/layer_excel_ps.php" method="post">
    <input type="hidden" name="mode" value="excel" >
    <input type="hidden" name="searchCount" value="<?=$searchCount?>" >
    <input type="hidden" name="totalCount" value="<?=$totalCount?>" >

    <input type="hidden" name="whereDetail" value="" >

    <div id="whereDetail" style="display:none"></div>
    <div class="table-title gd-help-manual">
        다운로드 양식 검색
    </div>
    <table class="table table-cols no-title-line">
        <colgroup>
            <col class="width-md"/>
            <col/>
            <col class="width-md"/>
            <col/>
        </colgroup>
        <tr>
            <th>다운로드 범위</th>
            <td colspan="3">
                <div class="form-inline">
                    <label class="radio-inline"><input type="radio" name="whereFl" value="select">선택내역</label>
                    <label class="radio-inline"><input type="radio" name="whereFl" value="search">검색내역</label>
                   <?php if($menu !='order') { ?> <label class="radio-inline"><input type="radio" name="whereFl" value="total">전체내역</label><?php } ?>
                </div>
            </td>
        </tr>
        <tr>
            <th>메뉴 분류</th>
            <td><div class="form-inline">
                    <select name="menu" class="form-control width-xl" disabled="disabled">
                        <option value="">선택</option>
                        <?php foreach($menuList as $k => $v) { ?>
                            <option value="<?=$k?>" <?php if($menu == $k) { echo "selected='selected'"; }  ?>><?=$v?></option>
                        <?php } ?>
                    </select></div>
            </td>
            <th>상세 항목</th>
            <td>
                <div class="form-inline">
                    <select name="location" class="form-control width-xl" <?php if($menu =='board' ) { ?>onchange="select_form(this.value)"<?php } else { ?>disabled="disabled"<?php } ?>>
                        <option value="">선택</option>
                        <?php foreach($locationList as $k => $v) { ?>
                            <option value="<?=$k?>" <?php if($location == $k) { echo "selected='selected'"; }  ?>><?=$v?></option>
                        <?php } ?>
                    </select></div>
            </td>
        </tr>
        <tr>
            <th>양식 선택</th>
            <td colspan="3">
                <div class="form-inline">
                    <select name="formSno" class="form-control width-xl">
                        <option value="">선택</option>
                        <?php if($menu !='board' ) {
                            foreach ($formList as $k => $v) { ?>
                                <option value="<?= $v['sno'] ?>"><?= $v['title'] ?></option>
                            <?php }
                        }?>
                    </select>
                    <?php if($menu !='promotion' ) { ?><a href="../policy/excel_form_register.php" class='btn btn-gray btn-sm' target="_blank">다운로드 양식 추가</a><?php } ?>
                    <?php if($menu =='goods' && ($location =='goods_list' || $location=='goods_list_delete')) {?>
                    <span class="pdl15 "> <input type="checkbox" name="goodsNameTagFl" value="y">상품명 HTML태그 제외</span>
                    <?php } ?>
                </div>
            </td>
        </tr>
        <tr>
            <th>파일명</th>
            <td colspan="3">
                <div class="form-inline">
                   <input type="text" name="downloadFileName"  class="form-control width-xl js-type-korea js-maxlength" maxlength="50"></div>
            </td>
        </tr>
        <tr>
            <th>비밀번호 사용여부</th>
            <td>
                <div class="form-inline">
                    <label class="radio-inline"><input type="radio" name="passwordFl" value="y" checked="checked">사용</label>
                    <label class="radio-inline"><input type="radio" name="passwordFl" value="n">사용하지않음</label>
                </div>
            </td>
            <th>엑셀파일 당<br/>
                데이터 최대개수
            </th>
            <td>
                <div class="form-inline">
                <label>
                    <input type="text" name="excelPageNum"  class="form-control width-2xs" value="10000"> 개
                </label>
                    </div>
                <p class="notice-info">
                다운로드 시간이 오래 걸리는 경우<br/>
                데이터 최대개수 숫자를 조정하세요
                </p>

            </td>
        </tr>
        <tr id="js-excel-form-password">
            <th>비밀번호 설정</th>
            <td>
                <div class="form-inline">
                    <input type="text" name="password" class="form-control width-xl js-type-normal"/></label>
                </div>
            </td>
            <th>비밀번호 확인</th>
            <td>
                <div class="form-inline">
                    <input type="text" name="rePassword" class="form-control width-xl js-type-normal"/></label>
                </div>
            </td>
        </tr>
    </table>

    <div class="table-btn">
        <input type="submit" value="요청" class="btn btn-lg btn-black">
    </div>
</form>


<div>
<form id="frmExcelRequest" name="frmExcelRequest" action="../share/layer_excel_ps.php" method="post" target="ifrmProcess">
    <input type="hidden"  name="mode" value="download">
    <input type="hidden"  name="excelFileName" value="<?=$excelFileName?>">
    <input type="hidden"  name="sno" value="">
    <input type="hidden"  name="excelKey" value="">
    <table class="table table-rows" id="tblExcelRequest">
        <thead>
        <tr>
            <th class="width-3xs">번호</th>
            <th class="width-md">다운로드 양식명</th>
            <th class="width-lg">파일명</th>
            <th class="width-3xs">파일구분</th>
            <th class="width-xs">파일상태</th>
            <th class="width-xs">요청자</th>
            <th class="width-xs">다운로드 기간</th>
            <th class="width-xs">다운로드</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>



    </form>

</div>
