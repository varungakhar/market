<div>
    <div class="mgt10"></div>
    <div>
        <table class="table table-cols no-title-line">
            <colgroup><col class="width-md" /><col /></colgroup>
            <tr>
                <th>공급사명</th>
                <td>
                    <div class="form-inline">
                        <input type="text" name="companyNm" value="<?php echo $search['companyNm'];?>" class="form-control" />
                        <input type="button" value="검색" class="btn btn-black btn-hf" onclick="layer_list_search();" />
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>

<table class="table table-rows">
    <thead>
    <tr>
        <th class="width10p"> <?php if ($mode == 'radio') { ?>선택 <?php } else { ?><input type="checkbox" id="allCheck" value="y" class="js-checkall" data-target-name="layer_scm[]" /><?php } ?></th>
        <th class="width10p">번호</th>
        <th>공급사명</th>
        <th>상태</th>
        <th class="width20p">등록일</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if (gd_isset($data) && is_array($data)) {
        $i = 0;
        foreach ($data as $key => $val) {
            if ($val['scmType'] == 'y') {
                $scmType = '운영';
                $addDisabled = '';
            } else if ($val['scmType'] == 'x') {
                $scmType = '탈퇴';
                $addDisabled = 'disabled="disabled"';
            }
            ?>
            <tr class="text-center">
                <td>
                    <?php if ($mode == 'radio') { ?>
                        <input type="radio" id="layer_scm_<?php echo $val['scmNo'];?>" name="layer_scm" value="<?php echo $val['scmNo'];?>" <?=$addDisabled?> />
                    <?php } else { ?>
                        <input type="checkbox" id="layer_scm_<?php echo $val['scmNo'];?>" name="layer_scm[]" value="<?php echo $val['scmNo'];?>" <?=$addDisabled?> />
                    <?php } ?>
                </td>
                <td><?php echo number_format($page->idx--);?></td>
                <td class="text-left">
                    <label for="layer_scm_<?php echo $val['scmNo'];?>" class="hand">
                        <?php echo $val['companyNm'];?>
                        <input type="hidden" id="companyNm_<?php echo $val['scmNo'];?>" value="<?php echo gd_htmlspecialchars($val['companyNm']);?>" />
                        <input type="hidden" id="scmCommission_<?php echo $val['scmNo'];?>" value="<?php echo gd_htmlspecialchars($val['scmCommission']);?>" />
                    </label>
                </td>
                <td><?php echo $scmType;?></td>
                <td><?php echo gd_date_format('Y-m-d', $val['regDt']);?></td>
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
<div class="text-center"><?php echo $page->getPage('layer_list_search(\'PAGELINK\')');?></div>
<div class="text-center"><input type="button" value="확인" class="btn btn-lg btn-black js-close" /></span></div>

<script type="text/javascript">
    <!--
    $(document).ready(function(){
        $('.js-close').click(function(e){
            if ($('input[id*=\'layer_scm_\']:checked').length == 0) {
                alert('공급사를 선택해 주세요!');
                return false;
            }

            var applyGoodsCnt = 0;
            var chkGoodsCnt = 0;

            var resultJson = {
                "mode": "<?php echo $mode?>",
                "parentFormID": "<?php echo $parentFormID?>",
                "dataFormID": "<?php echo $dataFormID?>",
                "dataInputNm": "<?php echo $dataInputNm?>",
                "childRow": "<?php echo $childRow?>",
                "info": []
            };

            $('input[id*=\'layer_scm_\']:checked').each(function() {
                var scmNo = $(this).val();
                var companyNm = $('#companyNm_' + scmNo).val();
                var scmCommission = $('#scmCommission_' + scmNo).val();

                if ($('#' + resultJson.dataFormID + '_' + scmNo).length == 0) {
                    resultJson.info.push({"scmNo": scmNo, "scmNoNm": companyNm, "scmCommission": scmCommission});
                    applyGoodsCnt++;
                }
                chkGoodsCnt++;
            });

            if (applyGoodsCnt > 0) {
                <?php if ($callFunc) { ?>
                <?=$callFunc?>(resultJson);
                <?php } else { ?>
                displayTemplate(resultJson);
                <?php } ?>

                if (applyGoodsCnt != chkGoodsCnt) {
                    alert('선택한 '+chkGoodsCnt+'개의 공급사 중 '+applyGoodsCnt+'개의 공급사가 추가 되었습니다.');
                }

                if (chkGoodsCnt > 0) {
                    $('#' + resultJson.parentFormID).addClass('active');
                } else {
                    $('#' + resultJson.parentFormID).removeClass('active');
                }


                $('div.bootstrap-dialog-close-button').click();
            } else {
                alert('동일한 공급사가 이미 존재합니다.');
            }
        });

        $('div.bootstrap-dialog-close-button').click(function() {
           // if ($(':button[name*=\'scmNoNm\']').length == 0) {
            if ($('input[name*=\'scmNo\']').length == 0) {
                $('[name=scmFl][value=n]').prop('checked',true);
            }
        });

        $('input').keyup(function(e) {
            if (e.which == 13) layer_list_search();
        });

    });

    // 페이지 출력
    function layer_list_search(pagelink) {
        var companyNm = $('input[name=\'companyNm\']').val();

        if (typeof pagelink == 'undefined') {
            pagelink = '';
        }
        var parameters = {
            'layerFormID': '<?php echo $layerFormID?>',
            'parentFormID': '<?php echo $parentFormID?>',
            'dataFormID': '<?php echo $dataFormID?>',
            'dataInputNm': '<?php echo $dataInputNm?>',
            'childRow': '<?php echo $childRow?>',
            'callFunc': '<?php echo $callFunc?>',
            'mode': '<?php echo $mode?>',
            'key': 'companyNm',
            'keyword': companyNm,
            'pagelink': pagelink
        };

        $.get('<?php echo URI_ADMIN;?>share/layer_scm.php', parameters, function (data) {
            $('#<?php echo $layerFormID?>').html(data);
        });
    }

    // 화면출력
    function displayTemplate(data) {
        if (data.mode == 'input')   $("#" + data.parentFormID).html('');

        $.each(data.info, function (key, val) {
            var addHtml = '';
            var compiledData = {
                scmNoNm: val.scmNoNm,
                scmNo: val.scmNo,
                dataFormID: data.dataFormID,
                dataInputNm: data.dataInputNm,
                inputArr: (data.mode == 'radio' ? '' : '[]')
            };
            var complied = _.template($('#scmSimpleTemplate').html());
            if (data.mode == 'search') {
                complied = _.template($('#scmSearchTemplate').html());
            } else if (data.mode == 'check') {
                var childRow = data.childRow ? Number(data.childRow) : 0;
                complied = _.template($('#scmCheckTemplate').html());
                compiledData = {
                    key: key + childRow,
                    scmNoNm: val.scmNoNm,
                    scmNo: val.scmNo,
                    dataFormID: data.dataFormID,
                    dataInputNm: data.dataInputNm,
                    inputArr: (data.mode == 'radio' ? '' : '[]')
                };
            }
            addHtml += complied(compiledData);
            if (data.mode == 'radio') $('#' + data.parentFormID).html(addHtml);
            else $('#' + data.parentFormID).append(addHtml);
        });

        if (data.info.length > 0 && !$('#' + data.parentFormID).children().is('h5') && data.mode != 'check') {
            $('#' + data.parentFormID).prepend('<h5>선택된 공급사 : </h5>');
        }
    }
    //-->
</script>
<script type="text/html" id="scmSimpleTemplate">
    <div id="<%=dataFormID%>_<%=scmNo%>" class="btn-group btn-group-xs">
        <input type="hidden" name="<%=dataInputNm%><%=inputArr%>" value="<%=scmNo%>">
        <input type="hidden" name="<%=dataInputNm%>Nm<%=inputArr%>" value="<%=scmNoNm%>">
        <span class="btn"><%=scmNoNm%></span>
        <button type="button" class="btn btn-icon-delete" data-toggle="delete" data-target="#<%=dataFormID%>_<%=scmNo%>">삭제</button>
    </div>
</script>
<script type="text/html" id="scmSearchTemplate">
    <div id="<%=dataFormID%>_<%=scmNo%>" class="btn-group btn-group-xs">
        <input type="hidden" name="<%=dataInputNm%>[]" value="<%=scmNo%>">
        <input type="hidden" name="<%=dataInputNm%>Nm[]" value="<%=scmNoNm%>">
        <span class="btn"><%=scmNoNm%></span>
        <button type="button" class="btn btn-icon-delete" data-toggle="delete" data-target="#<%=dataFormID%>_<%=scmNo%>">삭제</button>
    </div>
</script>
<script type="text/html" id="scmCheckTemplate">
    <tr id="<%=dataFormID%>_<%=scmNo%>">
        <td class="center"><span class="number"><%=(key + 1)%></span><input type="hidden" name="<%=dataInputNm%>[]" value="<%=scmNo%>"/>
            <input type="hidden" name="<%=dataInputNm%>Nm[]" value="<%=scmNoNm%>"/></td>
        <td><%=scmNoNm%></td>
        <td class="center">
            <input type="button" class="btn btn-sm btn-gray" data-toggle="delete" data-target="#<%=dataFormID%>_<%=scmNo%>" value="삭제"/>
        </td>
    </tr>
</script>
