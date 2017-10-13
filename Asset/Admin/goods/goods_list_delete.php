<script type="text/javascript">
    <!--
    $(document).ready(function () {

        $('input[name*=\'goodsPrice\']').number_only();

        delivery_switch('<?=$search['deliveryFl'];?>');

        // 삭제
        $('button.checkDelete').click(function () {
            var chkCnt = $('input[name*="goodsNo"]:checked').length;
            if (chkCnt == 0) {
                alert('선택된 상품이 없습니다.');
                return;
            }

            dialog_confirm('선택한 ' + chkCnt + '개 상품을  정말로 삭제하시겠습니까?<br/>삭제시 정보는 복구 되지 않습니다.', function (result) {
                if (result) {
                    $('#frmList input[name=\'mode\']').val('delete');
                    $('#frmList').attr('method', 'post');
                    $('#frmList').attr('action', './goods_ps.php');
                    $('#frmList').submit();
                }
            });
        });

        //복구선택
        $('button.checkReStore').click(function () {
            var chkCnt = $('input[name*="goodsNo"]:checked').length;
            if (chkCnt == 0) {
                alert('선택된 상품이 없습니다.');
                return;
            }

            var data = '<div class="text-center">선택한 ' + chkCnt + '개 상품을  정말로 복구 하시겠습니까?<div>';

            layer_popup(data+$("#lay_reStore").html(), '선택상품 복구');
        });

        $('select[name=\'pageNum\']').change(function(){
            $('#frmSearchGoods').submit();
        });

        $('select[name=\'sort\']').change(function(){
            $('#frmSearchGoods').submit();
        });

    });


    //복구
    function sumbit_restore() {

        $('#frmList input[name=\'goodsDisplayFl\']').val($(".bootstrap-dialog-body input:radio[name='chkGoodsDisplayFl']:checked").val());
        $('#frmList input[name=\'goodsSellFl\']').val($(".bootstrap-dialog-body input:radio[name='chkGoodsSellFl']:checked").val());

        $('#frmList input[name=\'mode\']').val('goods_restore');
        $('#frmList').attr('method', 'post');
        $('#frmList').attr('action', './goods_ps.php');
        $('#frmList').submit();

    }

    /**
     * 배송 정책 종류 선택
     *
     * @param string thisID 종류 ID
     */
    function delivery_switch(thisID) {
        if (thisID == 'free') {
            $('#deliveryConf_free').show();
        } else {
            $('#deliveryConf_free').hide();
        }
    }

    /*
    **
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
    <h3><?=end($naviMenu->location); ?> </h3>
</div>

<?php include($goodsSearchFrm); ?>

<div>
    <form id="frmList" action="" method="get" target="ifrmProcess" >
    <input type="hidden" name="mode" value="">
    <input type="hidden" name="goodsDisplayFl" value="">
    <input type="hidden" name="goodsSellFl" value="">
    <table class="table table-rows">
        <thead>
        <tr>
            <th class="width5p"><input type="checkbox" id="allCheck" value="y" class="js-checkall" data-target-name="goodsNo"></th>
            <th class="width5p">번호</th>
            <th class="width10p">상품코드</th>
            <th class="width10p">이미지</th>
            <th class="width40p">상품정보</th>
            <th class="width10p">판매가</th>
            <?php if(gd_is_plus_shop(PLUSSHOP_CODE_PURCHASE) === true && gd_is_provider() === false) { ?><th class="width10p">매입처</th><?php } ?>
            <th class="width10p">공급사</th>
            <th class="width5p">노출상태</th>
            <th class="width5p">판매상태</th>
            <th class="width5p">재고</th>
            <th class="width10p">등록일/삭제일</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if (gd_isset($data)) {
            $arrGoodsDisplay = array('y' => '노출함', 'n' => '노출안함');
            $arrGoodsSell = array('y' => '판매함', 'n' => '판매안함');
            $arrGoodsTax = array('t' => '과세', 'f' => '면세');
            $arrDeliveryFree = array('one' => '해당 상품만', 'goods' => '상품별 배송', 'all' => '모두 무료');
            foreach ($data as $key => $val) {
                // 상품 재고
                if ($val['stockFl'] == 'n') {
                    $totalStock = '∞';
                } else {
                    $totalStock = number_format($val['totalStock']);
                }
                ?>
                <tr>
                    <td class="center"><input type="checkbox" name="goodsNo[<?=$val['goodsNo']; ?>]" value="<?=$val['goodsNo']; ?>"/></td>
                    <td class="center number"><?=number_format($page->idx--); ?></td>
                    <td><?=$val['goodsNo']?></td>
                    <td>
                        <div class="center">
                            <?=gd_html_goods_image($val['goodsNo'], $val['imageName'], $val['imagePath'], $val['imageStorage'], 40, $val['goodsNm'], '_blank'); ?>
                        </div>
                    </td>
                    <td>
                        <div onclick="goods_register_popup('<?=$val['goodsNo']; ?>' <?php if(gd_is_provider() === true) { echo ",'1'"; } ?>);"
                             class="hand"><?=$val['goodsNm']; ?></div>
                        <div
                            class="notice-ref notice-sm"><?=Globals::get('gDelivery.' . $val['deliveryFl']); ?><?php if ($val['deliveryFl'] == 'free') {
                                echo '(' . $arrDeliveryFree[$val['deliveryFree']] . ')';
                            } ?></div>
                        <div>
                            <?php
                            // 기간 제한용 아이콘
                            if (empty($val['goodsIconCdPeriod']) === false && is_array($val['goodsIconCdPeriod']) === true) {
                                foreach ($val['goodsIconCdPeriod'] as $iKey => $iVal) {
                                    echo gd_html_image(UserFilePath::icon('goods_icon', $iVal['iconImage'])->www(), $iVal['iconNm']) . ' ';
                                }
                            }
                            // 상품 아이콘
                            if (empty($val['goodsIconCd']) === false && is_array($val['goodsIconCd']) === true) {
                                foreach ($val['goodsIconCd'] as $iKey => $iVal) {
                                    echo gd_html_image(UserFilePath::icon('goods_icon', $iVal['iconImage'])->www(), $iVal['iconNm']) . ' ';
                                }
                            }
                            // 품절 체크
                            if ($val['soldOutFl'] == 'y' || ($val['stockFl'] == 'y' && $val['totalStock'] <= 0)) {
                                echo gd_html_image(UserFilePath::icon('goods_icon')->www() . '/' . 'icon_soldout.gif', '품절상품');
                            }
                            ?>
                        </div>
                    </td>
                    <td class="center">
                        <div><span class="font-num"><?=gd_money_format($val['goodsPrice']); ?></span> 원</div>
                    </td>
                    <?php if(gd_is_plus_shop(PLUSSHOP_CODE_PURCHASE) === true && gd_is_provider() === false) { ?>  <td class="center lmenu"><?=$val['purchaseNm']; ?><?php } ?>
                    <td class="center number"><?=$val['scmNm']; ?></td>
                    <td class="center lmenu"><?=$arrGoodsDisplay[$val['goodsDisplayFl']]; ?></td>
                    <td class="center lmenu"><?=$arrGoodsSell[$val['goodsSellFl']]; ?></td>
                    <td class="center number"><?=$totalStock; ?></td>
                    <td class="center date"><?=gd_date_format('Y-m-d', $val['regDt']); ?></td>
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
            <button type="button" class="btn btn-white checkReStore">상품복구</button>
            <button type="button" class="btn btn-white checkDelete">완전삭제</button>
        </div>
        <div class="pull-right">
            <button type="button" class="btn btn-white btn-icon-excel js-excel-download" data-target-form="frmSearchGoods" data-search-count="<?=$page->recode['total']?>" data-total-count="<?=$page->recode['amount']?>" data-target-list-form="frmList" data-target-list-sno="goodsNo">엑셀다운로드</button>
        </div>
    </div>
    </form>

    <div class="center"><?=$page->getPage(); ?></div>



    <div class="display-none"  id="lay_reStore">
        <table class="table table-cols">
            <tbody>
            <tr>
                <th>노출상태</th>
                <td><label><input type="radio" name="chkGoodsDisplayFl"  value="y"  checked='checked' />노출함</label>
                    <label><input type="radio" name="chkGoodsDisplayFl"  value="n"  />노출안함</label>
                </td>
            </tr>
            <tr>
                <th>판매상태</th>
                <td><label><input type="radio" name="chkGoodsSellFl"  value="y"  checked='checked'/>판매함</label>
                    <label><input type="radio" name="chkGoodsSellFl"  value="n" />판매안함</label>
                </td>
            </tr>
            </tbody>
        </table>
        <div><button class="btn  btn-default checkReStoreConfirm" type="button" onclick="sumbit_restore();">확인</button></div>
    </div>

</div>
