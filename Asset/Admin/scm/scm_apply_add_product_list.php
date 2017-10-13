<script type="text/javascript">
    <!--
    $(document).ready(function () {


        // 삭제
        $('button.checkDelete').click(function () {
            var chkCnt = $(':checkbox:checked').length;
            if (chkCnt == 0) {
                alert('선택된 상품이 없습니다.');
                return;
            }
            if (confirm('선택한 ' + chkCnt + '개 상품을  정말로 삭제하시겠습니까?\n삭제시 정보는 복구 되지 않습니다.')) {
                $('#frmList input[name=\'mode\']').val('delete');
                $('#frmList').attr('method', 'post');
                $('#frmList').attr('action', '../goods/add_goods_ps.php');
                $('#frmList').submit();
            }
        });

        $('button.checkApply').click(function () {

            var chkCnt = $('input[name*="addGoodsNo"]:checked').length;

            if(chkCnt > 0) {
                var addMsg = "";
                $('#frmList input[name*="addGoodsNo["]:checkbox:checked').each(function () {
                    console.log($(this).data('apply-fl'));
                    if($(this).data('apply-fl') !='a') {
                        addMsg = "승인 요청 상품 외에 상태 변경 불가능합니다.<br/>";
                        $(this).prop("checked",false);
                        chkCnt--;
                    }
                });
            }

            if (chkCnt == 0) {
                if(addMsg) {
                    alert(addMsg);
                } else {
                    alert('선택된 상품이 없습니다.');
                }
                return;
            }

            dialog_confirm(addMsg+' 선택한 ' + chkCnt + '개 상품을  정말로 승인하시겠습니까?', function (result) {
                if (result) {
                    $('#frmList input[name=\'mode\']').val('apply');
                    $('#frmList').attr('method', 'post');
                    $('#frmList').attr('action', '../goods/add_goods_ps.php');
                    $('#frmList').submit();
                }
            });

        });

        $('button.checkReject').click(function () {

            var chkCnt = $('input[name*="addGoodsNo"]:checked').length;

            if(chkCnt > 0) {
                var addMsg = "";
                $('#frmList input[name*="addGoodsNo["]:checkbox:checked').each(function () {
                    console.log($(this).data('apply-fl'));
                    if($(this).data('apply-fl') !='a') {
                        addMsg = "승인 요청 상품 외에 상태 변경 불가능합니다.<br/>";
                        $(this).prop("checked",false);
                        chkCnt--;
                    }
                });
            }

            if (chkCnt == 0) {
                if(addMsg) {
                    alert(addMsg);
                } else {
                    alert('선택된 상품이 없습니다.');
                }
                return;
            }

            var data = '<div class="text-center">'+addMsg+'선택한 ' + chkCnt + '개 상품을  정말로 반려 하시겠습니까?<div>';

            layer_popup(data+$("#lay_reject").html(), '선택상품 반려');
        });


        $('select[name=\'pageNum\']').change(function () {
            $('input[name=\'pageNum\']').val($(this).val());
            $('#frmSearchBase').submit();
        });

        $('select[name=\'sort\']').change(function () {
            $('input[name=\'sort\']').val($(this).val());
            $('#frmSearchBase').submit();
        });

    });

    /**
     * 반려사유
     */
    function layer_apply_msg(msg) {

        layer_popup("<div>"+msg+"</div>", '반려 사유');
    }


    /**
     * 반려
     */
    function sumbit_reject() {

        $('#frmList input[name=\'applyMsg\']').val($(".bootstrap-dialog-body input[name='applyMsg']").val());


        $('#frmList input[name=\'mode\']').val('applyReject');
        $('#frmList').attr('method', 'post');
        $('#frmList').attr('action', '../goods/add_goods_ps.php');
        $('#frmList').submit();

    }



    /**
     * 정보 보기
     *
     * @param string modeStr 레이어창 종류
     * @param string typeStr 레이어창 타입
     * @param string sno 사은품 증정 sno
     */
    function layer_info_view(addGoodsNo)
    {
        var loadChk	= $('#viewInfoForm').length;
        var title = "변경 이력";

        $.post('./layer_add_goods_apply_info.php',{ addGoodsNo : addGoodsNo }, function(data){
            if (loadChk == 0) {
                data = '<div id="viewInfoForm">'+data+'</div>';
            }
            var layerForm = data;

            BootstrapDialog.show({
                title:title,
                size : BootstrapDialog.SIZE_WIDE,
                message: $(layerForm),
                closable: true
            });

        });

    }

    /**
     * 카테고리 연결하기 Ajax layer
     */
    function layer_register(typeStr, mode, isDisabled) {

        var addParam = {
            "mode": mode,
        };

        if (typeStr == 'scm') {
            $('input:radio[name=scmFl]:input[value=y]').prop("checked", true);
        }

        if (!_.isUndefined(isDisabled) && isDisabled == true) {
            addParam.disabled = 'disabled';
        }

        layer_add_info(typeStr,addParam);
    }
    //-->
</script>

<div class="page-header js-affix">
    <h3><?php echo end($naviMenu->location); ?></h3>
</div>

<form id="frmSearchBase" name="frmSearchBase" method="get" class="js-form-enter-submit">
    <input type="hidden" name="detailSearch" value="<?php echo $search['detailSearch']; ?>"/>
    <input type="hidden" name="searchDateFl" value="applyDt"/>
    <input type="hidden" name="sort"/>
    <input type="hidden" name="pageNum"/>

    <div class="table-title">
        추가 상품 승인 검색
    </div>
    <div class="search-detail-box">
        <table class="table table-cols">
            <colgroup>
                <col class="width-sm"/>
                <col/>
                <col class="width-sm"/>
                <col/>
            </colgroup>
            <tbody>
            <tr>
                <th>검색어</th>
                <td colspan="3"><div class="form-inline">
                        <?php echo gd_select_box('key', 'key', $search['combineSearch'], null, $search['key'], null); ?>
                        <input type="text" name="keyword" value="<?php echo $search['keyword']; ?>" class="form-control"/>
                    </div>
                </td>
            </tr>
            <tr>
                <th>승인요청일</th>
                <td colspan="3">
                    <div class="form-inline">
                        <div class="input-group js-datepicker">
                            <input type="text" class="form-control width-xs" name="searchDate[]" value="<?php echo $search['searchDate'][0]; ?>" />
                            <span class="input-group-addon"><span class="btn-icon-calendar"></span></span>
                        </div>
                        ~
                        <div class="input-group js-datepicker">
                            <input type="text" class="form-control width-xs" name="searchDate[]" value="<?php echo $search['searchDate'][1]; ?>" />
                            <span class="input-group-addon"><span class="btn-icon-calendar"></span></span>
                        </div>
                        <?= gd_search_date($search['searchPeriod'],'searchDate[]') ?>
                    </div>
                </td>
            </tr>
            <tr>
                <th>승인구분</th>
                <td>
                    <?php foreach($search['applyTypeList'] as $k => $v) { ?>
                        <label class="radio-inline">
                            <input type="radio" name="applyType" value="<?=$k?>" <?php echo gd_isset($checked['applyType'][$k]); ?> /><?=$v?>
                        </label>
                    <?php } ?>
                </td>
                <th>승인상태</th>
                <td>
                    <?php foreach($search['applyFlList'] as $k => $v) { ?>
                        <label class="radio-inline">
                            <input type="radio" name="applyFl" value="<?=$k?>" <?php echo gd_isset($checked['applyFl'][$k]); ?>  /><?=$v?>
                        </label>
                    <?php } ?>
                </td>
            </tr>
            </tbody>
            <tbody class="js-search-detail" class="display-none">
            <tr>
                <th>브랜드</th>
                <td>
                    <label><input type="text" name="brandCdNm" value="<?php echo $search['brandCdNm']; ?>"
                                  class="form-control"/> </label>

                    <button type="button" class="btn btn-sm btn-default" onclick="layer_register('brand', 'radio')">브랜드검색</button>
                    &nbsp;&nbsp;<label class="checkbox-inline"><input type="checkbox" name="brandNoneFl" value="y" <?php echo gd_isset($checked['brandNoneFl']['y']); ?>> 브랜드 미지정 상품</label>
                    <div id="brandLayer" class="width100p">
                        <?php if ($search['brandCd']) { ?>
                            <span id="idbrand<?= $search['brandCd'] ?>" class="pull-left">
                        <input type="hidden" name="brandCd" value="<?= $search['brandCd'] ?>"/>
                        </span>
                        <?php } ?>
                    </div>
                </td>
                <th>제조사</th>
                <td>
                    <input type="text" name="makerNm" value="<?php echo $search['makerNm']; ?>" class="form-control width-sm"/>
                </td>
            </tr>
            <tr>
                <th>재고</th>
                <td>
                    <label class="radio-inline"><input type="radio" name="stockUseFl"
                                  value="all" <?php echo gd_isset($checked['stockUseFl']['all']); ?>/>전체</label>
                    <label class="radio-inline"><input type="radio" name="stockUseFl"
                                  value="n" <?php echo gd_isset($checked['stockUseFl']['n']); ?>/>제한없음</label>
                    <label class="radio-inline"><input type="radio" name="stockUseFl"
                                  value="u" <?php echo gd_isset($checked['stockUseFl']['u']); ?>/>재고있음</label>
                    <label class="radio-inline"><input type="radio" name="stockUseFl"
                                  value="z" <?php echo gd_isset($checked['stockUseFl']['z']); ?>/>재고없음</label>
                </td>
                <th>판매가</th>
                <td> <div class="form-inline">
                        <input type="text" name="goodsPrice[0]" value="<?php echo $search['goodsPrice'][0]; ?>"
                               class="form-control"/> ~ <input type="text" name="goodsPrice[1]"
                                                               value="<?php echo $search['goodsPrice'][1]; ?>"
                                                               class="form-control"/> </div>
                </td>
            </tr>
            <tr>
                <th>노출여부</th>
                <td>
                    <label class="radio-inline"><input type="radio" name="viewFl"
                                  value="all" <?php echo gd_isset($checked['viewFl']['all']); ?>/>전체</label>
                    <label class="radio-inline"><input type="radio" name="viewFl"
                                  value="y" <?php echo gd_isset($checked['viewFl']['y']); ?>/>노출함</label>
                    <label class="radio-inline"><input type="radio" name="viewFl"
                                  value="n" <?php echo gd_isset($checked['viewFl']['n']); ?>/>노출안함</label>
                </td>
                <th>품절여부</th>
                <td>
                    <label class="radio-inline"><input type="radio" name="soldoutFl"
                                  value="all" <?php echo gd_isset($checked['soldoutFl']['all']); ?>/>전체</label>
                    <label class="radio-inline"><input type="radio" name="soldoutFl"
                                  value="y" <?php echo gd_isset($checked['soldoutFl']['y']); ?>/>정상</label>
                    <label class="radio-inline"><input type="radio" name="soldoutFl"
                                  value="n" <?php echo gd_isset($checked['soldoutFl']['n']); ?>/>품절</label>
                </td>
            </tr>
            </tbody>
        </table>
        <button type="button" class="btn btn-sm btn-link js-search-toggle bold">상세검색 <span>펼침</span></button>
    </div>


    <div class="text-center table-btn">
        <input type="submit" value="검색" class="btn btn-lg btn-black">
    </div>

    <div class="table-header">
        <div class="pull-left">
            검색 <strong><?php echo number_format($page->recode['total']);?></strong>개 /
            전체 <strong><?php echo number_format($page->recode['amount']);?></strong>개
        </div>
        <div class="pull-right form-inline">
            <?php echo gd_select_box('sort', 'sort', $search['sortList'], null, $search['sort'], null); ?>
            <?php echo gd_select_box('pageNum', 'pageNum', gd_array_change_key_value([10, 20, 30, 40, 50, 60, 70, 80, 90, 100, 200, 300, 500]), '개 보기', Request::get()->get('pageNum'), null); ?>
        </div>
    </div>
</form>


<div>
    <form id="frmList" action="" method="get" target="ifrmProcess">
        <input type="hidden" name="mode" value="">
        <input type="hidden" name="applyMsg" value="">
        <table class="table table-rows table-fixed">
            <thead>
            <tr>
                <th class="width5p center"><input type="checkbox" class="js-checkall" data-target-name="addGoodsNo"></th>
                <th class="width5p">번호</th>
                <th class="width10p">승인상태</th>
                <th class="width10p">승인구분</th>
                <th class="width10p">공급사</th>
                <th class="width-2xs">이미지</th>
                <th class="width40p">상품명</th>
                <th class="width10p">판매가</th>
                <th class="width5p">재고</th>
                <th class="width10p">승인요청일</th>
                <th class="width5p">변경이력</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (gd_isset($data)) {

                foreach ($data as $key => $val) {
                    // 상품 재고

                     $totalStock = number_format($val['totalStock']);

                    ?>
                    <tr <?php if($val['applyFl'] =='a') { echo "style=background:#efefef"; } ?>>
                        <td class="center"><input type="checkbox" name="addGoodsNo[<?php echo $val['addGoodsNo']; ?>]" value="<?php echo $val['addGoodsNo']; ?>" data-apply-fl="<?=$val['applyFl']?>"/></td>
                        <td class="center number"><?php echo number_format($page->idx--); ?></td>
                        <td class="center number"><?php echo $search['applyFlList'][$val['applyFl']]; ?> <?php if($val['applyFl'] =='r') { ?> <input type="button" value="사유" onclick="layer_apply_msg('<?=$val['applyMsg']?>')"> <?php } ?> </td>
                        <td class="center number"><?php echo $search['applyTypeList'][$val['applyType']]; ?><input type="hidden" name="applyType[<?php echo $val['addGoodsNo']; ?>]" value="<?php echo $val['applyType']; ?>"/></td>
                        <td class="center lmenu"><?php echo $val['scmNm']; ?>
                        <td>
                            <div class="width-2xs">
                                <?php echo gd_html_add_goods_image($val['addaddGoodsNo'], $val['imageNm'], $val['imagePath'], $val['imageStorage'], 30, $val['goodsNm'], '_blank'); ?>
                            </div>
                        </td>
                        <td>
                            <div onclick="addgoods_register_popup('<?php echo $val['addGoodsNo']; ?>'<?php if(gd_is_provider() === true) { echo ",'1'"; } ?>);"
                                 class="hand"><?php echo $val['goodsNm']; ?></div>
                        </td>
                        <td class="center">
                            <div><span class="font-num"><?php echo gd_currency_display($val['goodsPrice']); ?></span></div>
                        </td>
                        <td class="center number"><?php echo $totalStock; ?></td>
                        <td class="center date">
                            <?php echo gd_date_format('Y-m-d', $val['applyDt']); ?>
                        </td>
                        <td class="center"><input type="button" value="보기" class="btn btn-dark-gray btn-sm" onclick="layer_info_view('<?=$val['addGoodsNo']?>')"></td>
                    </tr>
                    <?php
                }
            } else {
                ?>
                <tr>
                    <td class="center" colspan="10">검색된 정보가 없습니다.</td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>

        <div class="table-action">
            <div class="pull-left">
                <button type="button" class="btn btn-white checkApply">선택상품 승인</button>
                <button type="button" class="btn btn-white checkReject">선택상품 반려</button>
                <button type="button" class="btn btn-white checkDelete">선택상품 삭제</button>
            </div>
            <div class="pull-right">
                <!-- <button type="button" class="btn btn-white btn-icon-excel">엑셀다운로드</button> -->
            </div>
        </div>


    </form>
    <div class="center"><?php echo $page->getPage(); ?></div>


    <div class="display-none"  id="lay_reject">
        <table class="table table-cols">
            <tbody>

            <tr>
                <th>반려사유</th>
                <td><label><input type="text" name="applyMsg" class="form-control"/></label>
                </td>
            </tr>
            </tbody>
        </table>
        <div><button class="btn  btn-default checkReStoreConfirm" type="button" onclick="sumbit_reject();">확인</button></div>
    </div>

</div>

