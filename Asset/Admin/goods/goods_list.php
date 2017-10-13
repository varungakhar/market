<div class="page-header js-affix">
    <h3><?=end($naviMenu->location); ?></h3>
    <div class="btn-group">
        <input type="button" value="상품 등록" class="btn btn-red-line js-register"/>
    </div>
</div>

<?php include($goodsSearchFrm); ?>

<form id="frmList" action="" method="get" target="ifrmProcess">
    <input type="hidden" name="mode" value="">
    <div class="table-action" style="margin:0;">
        <div class="pull-left">
            <button type="button" class="btn btn-black js-check-sale">상품 노출/판매 수정</button>
            <button type="button" class="btn btn-white js-check-soldout">상품 품절처리</button>
            <button type="button" class="btn btn-white js-check-copy">선택 복사</button>
            <button type="button" class="btn btn-white js-check-delete">선택 삭제</button>
        </div>
    </div>
    <table class="table table-rows">
        <thead>
        <tr>
            <th class="width5p center"><input type="checkbox" class="js-checkall" data-target-name="goodsNo"></th>
            <th class="width5p">번호</th>
            <th class="width10p">상품코드</th>
            <th class="width-2xs">이미지</th>
            <th class="width40p">상품명</th>
            <th class="width10p">판매가</th>
            <?php if(gd_is_plus_shop(PLUSSHOP_CODE_PURCHASE) === true && gd_is_provider() === false) { ?><th class="width10p">매입처</th><?php } ?>
            <th class="width10p">공급사</th>
            <th class="width10p">노출상태</th>
            <th class="width10p">판매상태</th>
            <th class="width5p">재고</th>
            <th class="width10p">등록일 / 수정일</th>
            <th class="width5p">수정</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if (gd_isset($data)) {
            $arrGoodsDisplay = array('y' => '노출함', 'n' => '노출안함');
            $arrGoodsSell = array('y' => '판매함', 'n' => '판매안함');
            $arrGoodsTax = array('t' => '과세', 'f' => '면세');
            $arrGoodsApply = array('a'    => '승인요청','y'   => '승인완료','r'  => '반려','n'  => '철회',);
            $arrDeliveryFree = array('one' => '해당 상품만', 'goods' => '상품별 배송', 'all' => '모두 무료');
            foreach ($data as $key => $val) {
                list($totalStock,$stockText) = gd_is_goods_state($val['stockFl'],$val['totalStock'],$val['soldOutFl']);


                if($val['applyFl'] !='y') {
                    $displayText = $arrGoodsApply[$val['applyFl']];
                    $sellText = $arrGoodsApply[$val['applyFl']];
                } else {
                    $displayText = $arrGoodsDisplay[$val['goodsDisplayFl']];
                    $sellText = $arrGoodsSell[$val['goodsSellFl']];
                }

                ?>
                <tr>
                    <td class="center"><input type="checkbox" name="goodsNo[<?=$val['goodsNo']; ?>]" value="<?=$val['goodsNo']; ?>" <?php if($val['applyFl'] !='y') { echo "disabled = 'true'"; }  ?> /></td>
                    <td class="center number"><?=number_format($page->idx--); ?></td>
                    <td class="center number"><?=$val['goodsNo']; ?></td>
                    <td class="width-2xs center">
                            <?=gd_html_goods_image($val['goodsNo'], $val['imageName'], $val['imagePath'], $val['imageStorage'], 40, $val['goodsNm'], '_blank'); ?>
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

                            if($val['timeSaleSno']) {
                                echo "<img src='" . PATH_ADMIN_GD_SHARE . "img/time-sale.png' alt='타임세일' />";
                            }
                            ?>

                        </div>
                    </td>
                    <td class="center">
                        <div><span class="font-num"><?=gd_currency_display($val['goodsPrice']); ?></span></div>
                    </td>
                    <?php if(gd_is_plus_shop(PLUSSHOP_CODE_PURCHASE) === true && gd_is_provider() === false) { ?>  <td class="center lmenu"><?=$val['purchaseNm']; ?><?php } ?>
                    <td class="center lmenu"><?=$val['scmNm']; ?>
                    <td class="center lmenu"><?=$displayText; ?></td>
                    <td class="center lmenu"><?=$sellText; ?></td>
                    <td class="center number"><?=$totalStock; ?></td>
                    <td class="center date">
                        <?=gd_date_format('Y-m-d', $val['regDt']); ?>
                        <?php if ($val['modDt']) { echo "<br/>" . gd_date_format('Y-m-d', $val['modDt']);} ?>
                    </td>
                    <td class="center padlr10"><a href="./goods_register.php?goodsNo=<?=$val['goodsNo']; ?>&page=<?=$page->page[now]?>" class="btn btn-white btn-sm">수정</a></td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr>
                <td class="center" colspan="12">검색된 정보가 없습니다.</td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>

    <div class="table-action">
        <div class="pull-left">
            <button type="button" class="btn btn-black js-check-sale">상품 노출/판매 수정</button>
            <button type="button" class="btn btn-white js-check-soldout">상품 품절처리</button>
            <button type="button" class="btn btn-white js-check-copy">선택 복사</button>
            <button type="button" class="btn btn-white js-check-delete">선택 삭제</button>
        </div>
        <div class="pull-right">
            <button type="button" class="btn btn-white btn-icon-excel js-excel-download" data-target-form="frmSearchGoods" data-target-list-form="frmList" data-target-list-sno="goodsNo" data-search-count="<?=$page->recode['total']?>" data-total-count="<?=$page->recode['amount']?>">엑셀다운로드</button>
        </div>
    </div>
</form>
<div class="text-center"><?=$page->getPage();?></div>

<script type="text/javascript">
    <!--
    $(document).ready(function () {

        // 삭제
        $('button.js-check-delete').click(function () {

            var chkCnt = $('input[name*="goodsNo"]:checked').length;

            if (chkCnt == 0) {
                alert('선택된 상품이 없습니다.');
                return;
            }

            dialog_confirm('선택한 ' + chkCnt + '개 상품을  정말로 삭제하시겠습니까?\n삭제 된 상품은 [삭제상품 리스트]에서 확인 가능합니다.', function (result) {
                if (result) {
                    $('#frmList input[name=\'mode\']').val('delete_state');
                    $('#frmList').attr('method', 'post');
                    $('#frmList').attr('action', './goods_ps.php');
                    $('#frmList').submit();
                }
            });

        });

        $('button.js-check-copy').click(function () {
            var chkCnt = $('input[name*="goodsNo"]:checked').length;
            if (chkCnt == 0) {
                alert('선택된 상품이 없습니다.');
                return;
            }
            dialog_confirm('선택한 ' + chkCnt + '개 상품을  정말로 복사하시겠습니까?', function (result) {
                if (result) {
                    $('#frmList input[name=\'mode\']').val('copy');
                    $('#frmList').attr('method', 'post');
                    $('#frmList').attr('action', './goods_ps.php');
                    $('#frmList').submit();
                }
            });

        });

        $('button.js-check-soldout').click(function () {
            var chkCnt = $('input[name*="goodsNo"]:checked').length;
            if (chkCnt == 0) {
                alert('선택된 상품이 없습니다.');
                return;
            }

            dialog_confirm('선택한 ' + chkCnt + '개 상품을 품절처리 하시겠습니까?', function (result) {
                if (result) {
                    $('#frmList input[name=\'mode\']').val('soldout');
                    $('#frmList').attr('method', 'post');
                    $('#frmList').attr('action', './goods_ps.php');
                    $('#frmList').submit();
                }
            });

        });

        // 노출 설정
        $('button.js-check-sale').click(function () {

            var chkCnt = $('input[name*="goodsNo"]:checked').length;

            if (chkCnt == 0) {
                alert('선택된 상품이 없습니다.');
                return;
            }

            var childNm = 'goods_sale';
            var addParam = {
                mode: 'simple',
                layerTitle: '노출 및 판매상태 설정',
                layerFormID: childNm + "Layer",
                parentFormID: childNm + "Row",
                dataFormID: childNm + "Id",
                dataInputNm: childNm
            };
            layer_add_info(childNm, addParam);
        });


        // 등록
        $('.js-register').click(function () {
            location.href = './goods_register.php';
        });

        $('select[name=\'pageNum\']').change(function () {
            $('#frmSearchGoods').submit();
        });

        $('select[name=\'sort\']').change(function () {
            $('#frmSearchGoods').submit();
        });

    });

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
