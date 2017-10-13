<div class="page-header js-affix">
    <h3><?=end($naviMenu->location); ?></h3>
    <div class="btn-group">
        <input type="button" value="저장" class="btn btn-red" id="batchSubmit"/>
    </div>
</div>

<?php include($goodsSearchFrm); ?>

<form id="frmBatchStock" name="frmBatchStock" action="./goods_ps.php" target="ifrmProcess" method="post">
<input type="hidden" name="mode" value="batch_stock" />
    <?php
    foreach ($batchAll as $key => $val) {
        echo '<input type="hidden" name="queryAll['.$key.']" value="'.$val.'" />'.chr(10);
    }
    ?>
<div class="table-responsive">
    <table class="table table-rows table-fixed">
    <thead>
    <tr>
        <th class="width-2xs center" rowspan="2"><input type="checkbox" class="js-checkall" data-target-name="arrGoodsNo[]"></th>
        <th class="width-2xs" rowspan="2">번호</th>
        <th class="width-xs center" rowspan="2">상품코드</th>
        <th class="width-xs" rowspan="2">이미지</th>
        <th class="width-lg center" rowspan="2">상품명</th>
        <th class="width-xs center" rowspan="2">공급사</th>
        <th class=" center" style="width:160px;" colspan="2">노출상태</th>
        <th class=" center"  style="width:160px;"colspan="2">판매상태</th>
        <th class="width-2xs center" rowspan="2">품절상태</th>
        <th class="width-sm" rowspan="2">옵션</th>
        <th class="width-sm" rowspan="2">옵션 노출상태</th>
        <th class="width-sm" rowspan="2">옵션 품절상태</th>
        <th class="width-md" rowspan="2">재고</th>
    </tr>
    <tr>
        <th class="center width-2xs">PC</th>
        <th class="center  width-2xs">모바일</th>
        <th class="center width-2xs">PC</th>
        <th class="center width-2xs">모바일</th>
    </tr>
    <tr>
        <td class="width-xs center" colspan="6" ><button type="button" class="btn btn-black btn-xs" onclick="set_display()" >선택상품 일괄적용</button> </td>
        <td ><select class="form-control " id="setGoodsDisplayFl"><option value="">선택</option><option value="y">노출함</option><option value="n">노출안함</option></select></td>
        <td ><select class="form-control " id="setGoodsDisplayMobileFl"><option value="">선택</option><option value="y">노출함</option><option value="n">노출안함</option></select></td>
        <td ><select class="form-control " id="setGoodsSellFl"><option  value="">선택</option><option value="y">판매함</option><option value="n">판매안함</option></select></td>
        <td ><select class="form-control " id="setGoodsSellMobileFl"><option  value="">선택</option><option value="y">판매함</option><option value="n">판매안함</option></select></td>
        <td ><select class="form-control " id="setSoldOutFl"><option  value="">선택</option><option value="n">정상</option><option value="y">품절</option></select></td>
        <td ></td>
        <td ><select class="form-control " id="setOptionDisplayFl"><option  value="">선택</option><option value="y">노출함</option><option value="n">노출안함</option></select></td>
        <td ><select class="form-control "  id="setOptionSellFl"><option  value="">선택</option><option value="y">정상</option><option value="n">품절</option></select></td>
        <td ><div class="form-inline"><select id="setOptionStockFl" class="form-control "><option  value="">선택</option><option value="p">추가</option><option value="m">차감</option><option value="c">변경</option></select>
                    <input type="text" id="setOptionStockCnt" class="form-control width-2xs"></div>
        </td>
    </tr>
    </thead>
    <tbody>
<?php
if (gd_isset($data) && count($data) > 0 ) {
     $i = 0;
     foreach ($data as $key => $val) {
         $tmp = [];
         for ($i = 1; $i <= 5; $i++) {
             if (!empty($val['optionValue' . $i])) {
                 $tmp[] = $val['optionValue' . $i];
             }
         }
         $optionValue = implode(' / ', $tmp);
         ?>
         <tr>
             <td class="center number">
                 <input type="checkbox" name="arrGoodsNo[]" value="<?=$val['goodsNo'].'_'.$val['sno']; ?>"/>

             </td>
             <td class="center number"><?=number_format($page->idx--); ?></td>
            <?php if ($val['optionNo'] == 1) { ?>
             <td class="center number"  style="border-bottom:0px;" ><?=$val['goodsNo']; ?> <input type="hidden" name="optionFl[<?=$val['goodsNo']?>]" value="<?=$val['optionFl']?>"></td>
             <td class="center" style="border-bottom:0px;"  >
                     <?=gd_html_goods_image($val['goodsNo'], $val['imageName'], $val['imagePath'], $val['imageStorage'], 30, $val['goodsNm'], '_blank')?>
             </td>
             <td style="border-bottom:0px;"  >
                 <a href="./goods_register.php?goodsNo=<?=$val['goodsNo']; ?>" target="_blank"><span class="emphasis_text"><?=$val['goodsNm']; ?></span></a>
             </td>
            <td class="center"style="border-bottom:0px;"  ><?=$val['scmNm']?></td>
            <td class="center lmenu" style="border-bottom:0px;">
                <select class="form-control width-2xs  " name="goods[goodsDisplayFl][<?=$val['goodsNo']?>]">
                    <option value="y" <?php if($val['goodsDisplayFl'] == 'y') { echo "selected='selected'"; } ?> >노출함</option>
                    <option value="n" <?php if($val['goodsDisplayFl'] == 'n') { echo "selected='selected'"; } ?> >노출안함</option>
                </select>
            </td>
            <td class="center lmenu" style="border-bottom:0px;">
                <select class="form-control width-2xs " name="goods[goodsDisplayMobileFl][<?=$val['goodsNo']?>]">
                    <option value="y" <?php if($val['goodsDisplayMobileFl'] == 'y') { echo "selected='selected'"; } ?> >노출함</option>
                    <option value="n" <?php if($val['goodsDisplayMobileFl'] == 'n') { echo "selected='selected'"; } ?> >노출안함</option>
                </select>
            </td>
            <td class="center lmenu" style="border-bottom:0px;">
                <select class="form-control width-2xs " name="goods[goodsSellFl][<?=$val['goodsNo']?>]">
                    <option value="y" <?php if($val['goodsSellFl'] == 'y') { echo "selected='selected'"; } ?> >판매함</option>
                    <option value="n" <?php if($val['goodsSellFl'] == 'n') { echo "selected='selected'"; } ?> >판매안함</option>
                </select>
            </td>
                <td class="center lmenu" style="border-bottom:0px;">
                    <select class="form-control width-2xs " name="goods[goodsSellMobileFl][<?=$val['goodsNo']?>]">
                        <option value="y" <?php if($val['goodsSellMobileFl'] == 'y') { echo "selected='selected'"; } ?> >판매함</option>
                        <option value="n" <?php if($val['goodsSellMobileFl'] == 'n') { echo "selected='selected'"; } ?> >판매안함</option>
                    </select>
                </td>
                <td class="center lmenu" style="border-bottom:0px;">
                    <select class="form-control input-sm" name="goods[soldOutFl][<?=$val['goodsNo']?>]">
                        <option value="n" <?php if($val['soldOutFl'] == 'n') { echo "selected='selected'"; } ?> >정상</option>
                        <option value="y" <?php if($val['soldOutFl'] == 'y') { echo "selected='selected'"; } ?> >품절</option>
                    </select>
                </td>
             <?php } else { ?>
                <td style="border-top:0px;border-bottom:0px;" ></td>
                <td style="border-top:0px;border-bottom:0px;" ></td>
                <td style="border-top:0px;border-bottom:0px;" ></td>
                <td style="border-top:0px;border-bottom:0px;" ></td>
                <td style="border-top:0px;border-bottom:0px;" ></td>
                <td style="border-top:0px;border-bottom:0px;" ></td>
                <td style="border-top:0px;border-bottom:0px;" ></td>
                <td style="border-top:0px;border-bottom:0px;" ></td>
                <td style="border-top:0px;border-bottom:0px;" ></td>
             <?php } ?>
             <td class="center">
                 <?=$optionValue; ?>
             </td>
             <td class="center">
                 <select class="form-control" name="option[optionViewFl][<?=$val['goodsNo']?>][<?=$val['sno']?>]" <?php if($val['optionFl'] =='n') { echo "disabled = 'disabled'"; } ?>>
                     <option value="y" <?php if($val['optionViewFl'] == 'y') { echo "selected='selected'"; } ?> >노출함</option>
                     <option value="n" <?php if($val['optionViewFl'] == 'n') { echo "selected='selected'"; } ?> >노출안함</option>
                 </select>
             </td>
             <td class="center">
                 <select class="form-control" name="option[optionSellFl][<?=$val['goodsNo']?>][<?=$val['sno']?>]" <?php if($val['optionFl'] =='n') { echo "disabled = 'disabled'"; } ?>>
                     <option value="y" <?php if($val['optionSellFl'] == 'y') { echo "selected='selected'"; } ?> >정상</option>
                     <option value="n" <?php if($val['optionSellFl'] == 'n') { echo "selected='selected'"; } ?> >품절</option>
                 </select>
             </td>
             <td><div class="form-inline"><select name="option[stockFl][<?=$val['goodsNo']?>][<?=$val['sno']?>]"  class="form-control"><option value="">선택</option><option value="p">추가</option><option value="m">차감</option><option value="c">변경</option></select>
                     <input type="hidden" name="option[stockCntFix][<?=$val['goodsNo']?>][<?=$val['sno']?>]"  class="form-control width-2xs" value="<?=$val['stockCnt']?>">
                     <input type="text" name="option[stockCnt][<?=$val['goodsNo']?>][<?=$val['sno']?>]"  class="form-control width-2xs" value="<?=$val['stockCnt']?>"></div>
             </td>
         </tr>
         <?php
         unset($arrDisplay);
         $i++;
     }
 } else {
?>
     <tr><td class="no-data" colspan="13">검색된 정보가 없습니다.</td></tr>
    <?php } ?>
    </tbody>
    </table>


</div>
    <div class="center"><?=$page->getPage();?></div>

    <div>

        <table class="table table-cols">
            <colgroup><col class="width-md" /><col /></colgroup>
            <tr>
                <th>조건설정</th>
                <td>
                    <div class="form-inline">
                        <label for="batchAll" class="checkbox-inline">
                            <input type="checkbox" id="batchAll" name="batchAll" value="y" />
                            검색된 상품 전체(<?=number_format($page->recode['total']);?>개 상품)를 수정합니다.
                        </label>
                        <p class="notice-danger mgt5">상품수가 많은 경우 비권장합니다. 가능하면 한 페이지씩 선택하여 수정하세요.</p>
                    </div>
                    <input type="hidden" name="termsFl" value="n" >
                    <table class="table-cols">
                        <tr>
                            <th class="center" colspan="2">노출상태</th>
                            <th class="center" colspan="2">판매상태</th>
                            <th class="width-sm center" rowspan="2">품절상태</th>
                            <th class="width-sm center" rowspan="2">옵션 노출상태</th>
                            <th class="width-sm center" rowspan="2">옵션 품절상태</th>
                            <th class="width-xl center" rowspan="2">재고</th>
                        </tr>
                        <tr>
                            <th class="center">PC</th>
                            <th class="center">모바일</th>
                            <th class="center">PC</th>
                            <th class="center">모바일</th>
                        </tr>
                        <tr>
                            <td><select class="form-control input-sm" name="goodsDisplayFl" ><option value="">선택</option><option value="y">노출함</option><option value="n">노출안함</option></select></td>
                            <td><select class="form-control input-sm" name="goodsDisplayMobileFl" ><option value="">선택</option><option value="y">노출함</option><option value="n">노출안함</option></select></td>
                            <td><select class="form-control input-sm" name="goodsSellFl" ><option  value="">선택</option><option value="y">판매함</option><option value="n">판매안함</option></select></td>
                            <td><select class="form-control input-sm" name="goodsSellMobileFl" ><option  value="">선택</option><option value="y">판매함</option><option value="n">판매안함</option></select></td>
                            <td><select class="form-control input-sm" name="soldOutFl" ><option  value="">선택</option><option value="n">정상</option><option value="y">품절</option></select></td>
                            <td><select class="form-control input-sm" name="optionViewFl" ><option  value="">선택</option><option value="y">노출함</option><option value="n">노출안함</option></select></td>
                            <td><select class="form-control input-sm"  name="optionSellFl" ><option  value="">선택</option><option value="y">정상</option><option value="n">품절</option></select></td>
                            <td><div class="form-inline"><select name="optionStockFl" class="form-control" ><option  value="">선택</option><option value="p">추가</option><option value="m">차감</option><option value="c">변경</option></select>
                                    <input type="text" name="optionStockCnt" class="form-control width-2xs"> <label class="checkbox-inline"><input type="checkbox" name="stockLimit" value="y">무한정 판매</label></div></td>
                        </tr>

                   </table>

                    <div class="notice-info">
                    재고는 "추가/차감/변경" 중 선택하여 수정 가능합니다.<br/>
                    "추가/차감"을 선택하고 저장하면, 현재의 상품 재고에 입력한 수량 만큼 "추가/차감"되고, "변경"을 선택하면 입력한 수량과 재고 수량이 동일하게 변경됩니다.<br/>
                    "무한정 판매"를 체크한 경우 입력한 재고 수량과 관계없이 무한정 판매 상품으로 변경됩니다.<br/>
                    수정 중 상품 주문으로 재고 수량에 변동이 생기면, 현재 재고의 수량과 관계없이 변동된 수량에서 "추가/차감"됩니다.<br/>
                    </div>

                </td>
            </tr>
        </table>


    </div>

</form>

<script type="text/javascript">
<!--
$(document).ready(function(){
    //$('#frmBatchStock').formProcess();




    $( "#batchSubmit" ).click(function() {

        var msg = "";

        var goodsDisplayFl = $('#frmBatchStock select[name="goodsDisplayFl"] option:selected').val();
        var goodsDisplayMobileFl = $('#frmBatchStock select[name="goodsDisplayMobileFl"] option:selected').val();
        var goodsSellFl = $('#frmBatchStock select[name="goodsSellFl"] option:selected').val();
        var goodsSellMobileFl = $('#frmBatchStock select[name="goodsSellMobileFl"] option:selected').val();
        var soldOutFl = $('#frmBatchStock select[name="soldOutFl"] option:selected').val();
        var optionViewFl = $('#frmBatchStock select[name="optionViewFl"] option:selected').val();
        var optionSellFl = $('#frmBatchStock select[name="optionSellFl"] option:selected').val();
        var optionStockFl = $('#frmBatchStock select[name="optionStockFl"] option:selected').val();
        var optionStockCnt = $('#frmBatchStock input[name="optionStockCnt"]').val();
        var stockLimit = $('#frmBatchStock input[name="stockLimit"]').is(":checked");

        if(stockLimit == false &&  goodsDisplayFl == '' &&  goodsDisplayMobileFl == '' &&  goodsSellMobileFl == ''  && soldOutFl == '' &&  goodsSellFl == '' &&  optionViewFl == '' &&  optionSellFl == '' &&  optionStockFl == '' &&  optionStockCnt == '')
        {
            if ($('input[name="arrGoodsNo[]"]:checked').length == 0) {
                $.warnUI('항목 체크', '선택된 항목이 없습니다.');
                return false;
            }

            $('#frmBatchStock input[name="termsFl"]').val('n');

            msg += "선택된 상품을 수정하시겠습니까?\n\n";
            msg += "[주의] 일괄적용 후에는 이전상태로 복원이 안되므로 신중하게 변경하시기 바랍니다.";
        }
        else
        {
            if ($('#batchAll:checked').length == 0) {
                if ($('input[name="arrGoodsNo[]"]:checked').length == 0) {
                    $.warnUI('항목 체크', '선택된 항목이 없습니다.');
                    return false;
                }

                msg += '선택된 상품을 \n\n';
            } else {
                msg += '검색된 전체 상품을 \n\n';
            }

            if(optionStockFl && optionStockCnt =='')
            {
                $.warnUI('항목 체크', '재고를 입력해주세요.');
                return false;
            }


            $('#frmBatchStock input[name="termsFl"]').val('y');


            var goodsDisplayFlText = $('#frmBatchStock select[name="goodsDisplayFl"] option:selected').text();
            var goodsDisplayMobileFlText = $('#frmBatchStock select[name="goodsDisplayMobileFl"] option:selected').text();
            var goodsSellFlText = $('#frmBatchStock select[name="goodsSellFl"] option:selected').text();
            var goodsSellMobileFlText = $('#frmBatchStock select[name="goodsSellMobileFl"] option:selected').text();
            var soldOutFlText = $('#frmBatchStock select[name="soldOutFl"] option:selected').text();
            var optionViewFlText = $('#frmBatchStock select[name="optionViewFl"] option:selected').text();
            var optionSellFlText = $('#frmBatchStock select[name="optionSellFl"] option:selected').text();
            var optionStockFlText = $('#frmBatchStock select[name="optionStockFl"] option:selected').text();

            if(goodsDisplayFl) msg += '상품 노출 상태[PC] : '+goodsDisplayFlText+' \n';
            if(goodsDisplayMobileFl) msg += '상품 노출 상태[모바일] : '+goodsDisplayMobileFlText+' \n';
            if(goodsSellFl) msg += '상품 판매 상태[PC] : '+goodsSellFlText+' \n';
            if(goodsSellMobileFl) msg += '상품 판매 상태[모바일] : '+goodsSellMobileFlText+' \n';
            if(soldOutFl) msg += '상품 판매 상태 : '+soldOutFlText+' \n';
            if(optionViewFl) msg += '옵션 노출 상태 : '+optionViewFlText+' \n';
            if(optionSellFl) msg += '옵션 판매 상태 : '+optionSellFlText+' \n';
            if(optionStockFl) msg += '재고 : '+optionStockCnt+'개 '+optionStockFlText+' 상태 \n';
            if(stockLimit) msg += '재고 : 무한정 상태 \n';


            msg += '\n으로 일괄적으로 수정하시겠습니까?\n\n';
            msg += '[주의] 일괄적용 후에는 이전상태로 복원이 안되므로 신중하게 변경하시기 바랍니다.';
        }


        dialog_confirm(msg, function (result) {
            if (result) {
                $( "#frmBatchStock").submit();
            }
        });
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



function set_display ()
{

    var cnt = $('input[name="arrGoodsNo[]"]:checked').length;

    if(cnt > 0 )
    {

        var setGoodsDisplayFl = $('#frmBatchStock select[id="setGoodsDisplayFl"] option:selected').val();
        var setGoodsSellFl = $('#frmBatchStock select[id="setGoodsSellFl"] option:selected').val();
        var setGoodsDisplayMobileFl = $('#frmBatchStock select[id="setGoodsDisplayMobileFl"] option:selected').val();
        var setGoodsSellMobileFl = $('#frmBatchStock select[id="setGoodsSellMobileFl"] option:selected').val();
        var setSoldOutFl = $('#frmBatchStock select[id="setSoldOutFl"] option:selected').val();
        var setOptionDisplayFl = $('#frmBatchStock select[id="setOptionDisplayFl"] option:selected').val();
        var setOptionSellFl = $('#frmBatchStock select[id="setOptionSellFl"] option:selected').val();
        var setOptionStockFl = $('#frmBatchStock select[id="setOptionStockFl"] option:selected').val();
        var setOptionStockCnt = $('#frmBatchStock input[id="setOptionStockCnt"]').val();



        if(setGoodsDisplayFl!='' || setGoodsSellFl !='' || setGoodsDisplayMobileFl!='' || setGoodsSellMobileFl !=''  || setSoldOutFl !='' || setOptionDisplayFl !='' || setOptionSellFl !='' || setOptionStockFl !='' || setOptionStockCnt !='')
        {
            $('input[name="arrGoodsNo[]"]').each(function (i) {
                if (this.checked) {

                    var tmp = $(this).val().split("_");

                    if(setGoodsDisplayFl) $('#frmBatchStock select[name="goods[goodsDisplayFl]['+tmp[0]+']"]').val(setGoodsDisplayFl);
                    if(setGoodsSellFl) $('#frmBatchStock select[name="goods[goodsSellFl]['+tmp[0]+']"]').val(setGoodsSellFl);
                    if(setGoodsDisplayMobileFl) $('#frmBatchStock select[name="goods[goodsDisplayMobileFl]['+tmp[0]+']"]').val(setGoodsDisplayMobileFl);
                    if(setGoodsSellMobileFl) $('#frmBatchStock select[name="goods[goodsSellMobileFl]['+tmp[0]+']"]').val(setGoodsSellMobileFl);
                    if(setSoldOutFl) $('#frmBatchStock select[name="goods[soldOutFl]['+tmp[0]+']"]').val(setSoldOutFl);
                    if(setOptionDisplayFl) $('#frmBatchStock select[name="option[optionViewFl]['+tmp[0]+']['+tmp[1]+']"]').val(setOptionDisplayFl);
                    if(setOptionSellFl) $('#frmBatchStock select[name="option[optionSellFl]['+tmp[0]+']['+tmp[1]+']"]').val(setOptionSellFl);
                    if(setOptionStockFl) $('#frmBatchStock select[name="option[stockFl]['+tmp[0]+']['+tmp[1]+']"]').val(setOptionStockFl);
                    if(setOptionStockCnt) $('#frmBatchStock input[name="option[stockCnt]['+tmp[0]+']['+tmp[1]+']"]').val(setOptionStockCnt);

                }
            });

        }
        else
        {
            $.warnUI('항목 체크', "일괄적용변경할 항목을 입력해주세요.");
            return false;
        }



    }
    else
    {
        $.warnUI('항목 체크', "선택된 상품이 없습니다.");
        return false;
    }

}



//-->
</script>
