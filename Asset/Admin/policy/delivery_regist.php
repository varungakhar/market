<form id="frmDeliveryRegist" name="frmDeliveryRegist" action="delivery_ps.php" method="post">
    <input type="hidden" name="mode" value="regist"/>
    <input type="hidden" name="basic[sno]" value="<?= $data['basic']['sno'] ?>">
    <input type="hidden" name="basic[managerNo]" value="<?=$data['basic']['managerNo']?>">
    <input type="hidden" name="basic[printFl]" value="<?= $data['basic']['printFl'] ?>">
    <input type="hidden" name="basic[printPrice]" value="<?= $data['basic']['printPrice'] ?>">
    <input type="hidden" name="basic[printPriceFl]" value="<?= $data['basic']['printPriceFl'] ?>">

    <div class="page-header js-affix">
        <h3><?php echo end($naviMenu->location); ?></h3>
        <div class="btn-group">
            <input type="button" value="목록" class="btn btn-white btn-icon-list" onclick="goList('./delivery_config.php');" />
            <input type="submit" value="저장" class="btn btn-red">
        </div>
    </div>

    <div class="table-title gd-help-manual">
        배송비조건
    </div>

    <table class="table table-cols">
        <colgroup>
            <col class="width-md">
            <col>
            <col class="width-md">
            <col>
        </colgroup>
        <tbody>
        <?php if (gd_use_provider() === true) { ?>
            <tr>
                <th>공급사 구분</th>
                <td colspan="3" class="js-scm">
                    <?php if ($data['count'] != 1 && $data['basic']['defaultFl'] != 'y') { ?>
                        <label class="radio-inline">
                            <input type="radio" name="scmFl" value="0" <?php echo gd_isset($checked['scmFl'][0]); ?>/>본사
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="scmFl" value="1" class="js-layer-register" <?php echo gd_isset($checked['scmFl'][1]); ?> data-type="scm" data-mode="radio"/>
                            공급사
                        </label>
                        <input type="button" value="공급사 선택" class="btn btn-sm btn-gray js-layer-register" data-type="scm" data-mode="radio" data-call-func="setScmNo"/>

                        <div id="scmLayer" class="selected-btn-group <?= $data['manage']['scmNo'] > 1 ? 'active' : '' ?>">
                            <h5>선택된 공급사 : </h5>
                            <?php if ($data['manage']['scmNo'] > 1) { ?>
                                <div id="idscm" class="btn-group btn-group-xs">
                                    <input type="hidden" name="scmNo" value="<?= $data['manage']['scmNo'] ?>"/>
                                    <input type="hidden" name="scmNoNm" value="<?= $data['manage']['companyNm'] ?>"/>
                                    <span class="btn"><?= $data['manage']['companyNm'] ?></span>
                                    <button type="button" class="btn btn-icon-delete" data-toggle="delete" data-target="#idscm">삭제</button>
                                </div>
                            <?php } else { ?>
                                <input type="hidden" name="scmNo" value="1"/>
                                <input type="hidden" name="scmNoNm" value="본사"/>
                            <?php } ?>
                        </div>
                    <?php } else {
                        $scmFlKey = array_keys($checked['scmFl']);
                        ?>
                        <input type="hidden" name="scmFl" value="<?= $scmFlKey[0] ?>"/>

                        <label class="radio-inline">
                            <input type="radio" disabled="disabled" value="0" <?php echo gd_isset($checked['scmFl'][0]); ?>/>본사
                        </label>
                        <label class="radio-inline">
                            <input type="radio" disabled="disabled" value="1" class="js-layer-register" <?php echo gd_isset($checked['scmFl'][1]); ?> data-type="scm"
                                   data-mode="radio"/> 공급사
                        </label>

                        <div id="scmLayer" class="selected-btn-group <?= $data['manage']['scmNo'] > 1 ? 'active' : '' ?>">
                            <h5>선택된 공급사 : </h5>
                            <?php if ($data['manage']['scmNo'] > 1) { ?>
                                <div id="idscm" class="btn-group btn-group-xs">
                                    <input type="hidden" name="scmNo" value="<?= $data['manage']['scmNo'] ?>"/>
                                    <input type="hidden" name="scmNoNm" value="<?= $data['manage']['companyNm'] ?>"/>
                                    <span class="btn"><?= $data['manage']['companyNm'] ?></span>
                                    <button type="button" class="btn btn-icon-delete" disabled="disabled" data-toggle="delete" data-target="#idscm">삭제</button>
                                </div>
                            <?php } else { ?>
                                <input type="hidden" name="scmNo" value="1"/>
                                <input type="hidden" name="scmNoNm" value="본사"/>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </td>
            </tr>
        <?php } else { ?>
            <input type="hidden" name="scmNo" value="1"/>
            <input type="hidden" name="scmNoNm" value="본사"/>
        <?php } ?>
        <tr>
            <th class="require">배송비조건명</th>
            <td colspan="3">
                <input type="text" name="basic[method]" value="<?= $data['basic']['method'] ?>" class="form-control width-lg js-maxlength" maxlength="20">
            </td>
        </tr>
        <tr>
            <th>배송비조건 설명</th>
            <td colspan="3">
                <input type="text" name="basic[description]" value="<?= $data['basic']['description'] ?>" class="form-control width-3xl js-maxlength" maxlength="100">
            </td>
        </tr>
        <tr>
            <th>배송비 유형</th>
            <td>
                <label class="mgb0">
                    <?= gd_select_box('basic_fixFl', 'basic[fixFl]', $mode['fix'], null, $data['basic']['fixFl']) ?>
                </label>
            </td>
            <th>부가세율</th>
            <td>
                <label class="mgb0">
                    <?= gd_select_box(null, 'basic[taxPercent]', $deliveryTax, '%', $selectedDeliveryTax) ?>
                </label>
            </td>
        </tr>
        </tbody>
        <tbody id="basicDeliveryConfigFee">
        <!-- 동적 배송비 설정 영역 -->
        </tbody>
        <tbody>
        <tr>
            <th>배송비 부과방법</th>
            <td colspan="3">
                <div class="radio">
                    <label title="상품 등록/수정시 상품별 개별 배송 정책을 사용할수 있습니다.!">
                        <input type="radio" name="basic[goodsDeliveryFl]" value="y" <?php echo gd_isset($checked['goodsDeliveryFl']['y']); ?> /> 배송비조건별
                    </label>
                    <span class="notice-info">같은 배송비 조건이 적용된 상품끼리 배송비를 1회 부과합니다.</span>
                </div>
                <div class="radio">
                    <label title="상품 등록/수정시 상품별 개별 배송 정책이 설정이 되있어도, &quot;기본 배송 정책&quot;이 적용 됩니다.!">
                        <input type="radio" name="basic[goodsDeliveryFl]" value="n" <?php echo gd_isset($checked['goodsDeliveryFl']['n']); ?> /> 상품별
                    </label>
                    <span class="notice-info">배송비 조건과 상관없이 상품별로 배송비를 부과합니다.</span>
                </div>
                <div id="basicDeliveryConfigFeeWeight" class="<?= $data['basic']['fixFl'] == 'weight' ? '' : 'display-none' ?>">
                    <hr>
                    <div class="form-inline">
                        <label class="checkbox" for="rangeLimitFl">
                            <input type="checkbox" id="rangeLimitFl" name="basic[rangeLimitFl]" value="y" <?php echo gd_isset($checked['rangeLimitFl']['y']); ?>/> 범위 제한 :
                        </label>
                        <input type="text" name="basic[rangeLimitWeight]" value="<?=gd_isset($data['basic']['rangeLimitWeight'])?>" /> <?=gd_weight_string()?> 이상
                    </div>
                    <p class="notice-info">해외배송비 설정용도로 등록시에는 "배송비조건별"로 선택하시길 권장합니다.</p>
                    <p class="notice-danger">범위 제한 기능을 체크하면, 입력된 무게범위와 상관없이 해당 범위부터 배송비부과가 불가하여 구매가 불가합니다.<br>배송비조건별/동일 배송비조건의 상품들의 총 무게로 제한을 두며, 상품별의 경우 상품 각각의 무게로 제한을 둡니다.</p>
                </div>
            </td>
        </tr>
        <tr>
            <th>배송비 결제방법</th>
            <td colspan="3">
                <label class="radio-inline" title="해당 배송 방법은 &quot;선불&quot; 정책만 사용하고자 할때 선택을 하시면 됩니다.!">
                    <input type="radio" name="basic[collectFl]" value="pre" <?php echo gd_isset($checked['collectFl']['pre']); ?>> 주문 시 결제(선불)
                </label>
                <label class="radio-inline" title="해당 배송 방법은 &quot;착불(후불)&quot; 정책만 사용하고자 할때 선택을 하시면 됩니다.!">
                    <input type="radio" name="basic[collectFl]" value="later" <?php echo gd_isset($checked['collectFl']['later']); ?>> 상품수령 시 결제(착불)
                </label>
                <label class="radio-inline" title="해당 배송 방법은 &quot;착불(후불)&quot; 정책만 사용하고자 할때 선택을 하시면 됩니다.!">
                    <input type="radio" name="basic[collectFl]" value="both" <?php echo gd_isset($checked['collectFl']['both']); ?>> 주문 시 선택(선불/착불)
                </label>
            </td>
        </tr>
        <tr>
            <th>지역별<br/>추가배송비</th>
            <td colspan="3" class="input_area" id="basicDeliveryConfigaddArea0">
                <label class="radio-inline">
                    <input type="radio" name="basic[areaFl]" class="js-areaFl" value="y" <?php echo gd_isset($checked['areaFl']['y']); ?> /> 있음
                </label>
                <label class="radio-inline">
                    <input type="radio" name="basic[areaFl]" class="js-areaFl" value="n" <?php echo gd_isset($checked['areaFl']['n']); ?> /> 없음
                </label>
                <div id="areaGroupSelect" class="form-inline mgt10 <?= $data['basic']['areaFl'] == 'y' ? '' : 'display-none' ?>">
                    <?php echo gd_select_box('areaGroupNo', 'basic[areaGroupNo]', $mode['areaGroupList'], null, $data['basic']['areaGroupNo'] ?: $mode['areaGroupSelected']); ?>
                    <button type="button" class="btn btn-xs btn-gray js-popup-register" data-type="delivery_post" data-layer="layerDeliveryPostForm" data-size="wide-sm"
                            data-sno="<?= $data['basic']['sno'] ?>">지역 및 배송비 추가
                    </button>
                </div>
            </td>
        </tr>
        </tbody>
        <tbody>
        <tr>
            <th>출고지 주소</th>
            <td colspan="3">
                <label title="출고지 주소가 사업장 주소와 동일한 경우 &quot;사업장 주소와 동일&quot;을 선택하세요!" class="radio-inline">
                    <input type="radio" name="basic[unstoringFl]" value="same" class="js-unstoringFl" <?php echo gd_isset($checked['unstoringFl']['same']); ?> />사업장 주소와 동일
                </label>
                <label title="출고지 주소가 사업장 주소와 다른 경우 &quot;주소 등록&quot;을 선택하세요!" class="radio-inline">
                    <input type="radio" name="basic[unstoringFl]" value="new" class="js-unstoringFl" <?php echo gd_isset($checked['unstoringFl']['new']); ?> />주소 등록
                </label>
                <div id="unstoringFl_new" class="mgt5 <?= $data['basic']['unstoringFl'] == 'new' ?: 'display-none' ?>">
                    <div class="form-inline mgb5">
                        <label title="&quot;우편번호찾기&quot;를 통해서 &quot;우편번호&quot;를 입력해 주세요!">
                            <input type="text" name="basic[unstoringZonecode]" value="<?php echo $data['basic']['unstoringZonecode']; ?>" maxlength="5"
                                   class="form-control width-2xs"/>
                            <input type="hidden" name="basic[unstoringZipcode]" value="<?php echo $data['basic']['unstoringZipcode']; ?>"/>
                            <span id="unstoringZipcodeText" class="number <?php if (strlen($data['basic']['unstoringZipcode']) != 7) {
                                echo 'display-none';
                            } ?>">(<?php echo $data['basic']['unstoringZipcode']; ?>)</span>
                            <button type="button" onclick="postcode_search('basic[unstoringZonecode]', 'basic[unstoringAddress]', 'basic[unstoringZipcode]');"
                                    class="btn btn-gray btn-sm">우편번호찾기
                            </button>
                        </label>
                    </div>
                    <div class="form-inline">
                        <label title="&quot;우편번호찾기&quot;를 통해서 &quot;출고지 주소&quot;를 입력해 주세요!">
                            <input type="text" name="basic[unstoringAddress]" value="<?php echo $data['basic']['unstoringAddress']; ?>"
                                   class="form-control width-2xl"/>
                        </label>
                        <label title="&quot;우편번호찾기&quot;를 통해서 &quot;출고지 상세주소&quot;를 입력해 주세요!">
                            <input type="text" name="basic[unstoringAddressSub]" value="<?php echo $data['basic']['unstoringAddressSub']; ?>"
                                   class="form-control width-2xl"/>
                        </label>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <th>반품/교환지 주소</th>
            <td colspan="3">
                <label title="반품/교환지 주소가 사업장 주소와 동일한 경우 &quot;사업장 주소와 동일&quot;을 선택하세요!" class="radio-inline">
                    <input type="radio" name="basic[returnFl]" value="same" class="js-returnFl" <?php echo gd_isset($checked['returnFl']['same']); ?> />사업장 주소와 동일
                </label>
                <label title="반품/교환지 주소가 사업장 주소와 동일한 경우 &quot;사업장 주소와 동일&quot;을 선택하세요!" class="radio-inline">
                    <input type="radio" name="basic[returnFl]" value="unstoring" class="js-returnFl" <?php echo gd_isset($checked['returnFl']['unstoring']); ?> />출고지 주소와 동일
                </label>
                <label title="반품/교환지 주소가 사업장 주소와 다른 경우 &quot;주소 등록&quot;을 선택하세요!" class="radio-inline">
                    <input type="radio" name="basic[returnFl]" value="new" class="js-returnFl" <?php echo gd_isset($checked['returnFl']['new']); ?> />주소 등록
                </label>
                <div id="returnFl_new" class="mgt5 <?= $data['basic']['returnFl'] == 'new' ?: 'display-none' ?>">
                    <div class="form-inline mgb5">
                        <label title="&quot;우편번호찾기&quot;를 통해서 &quot;우편번호&quot;를 입력해 주세요!">
                            <input type="text" name="basic[returnZonecode]" value="<?php echo $data['basic']['returnZonecode']; ?>" maxlength="5"
                                   class="form-control width-2xs"/>
                            <input type="hidden" name="basic[returnZipcode]" value="<?php echo $data['basic']['returnZipcode']; ?>"/>
                            <span id="returnZipcodeText" class="number <?php if (strlen($data['basic']['returnZipcode']) != 7) {
                                echo 'display-none';
                            } ?>">(<?php echo $data['basic']['returnZipcode']; ?>)</span>
                            <button type="button" onclick="postcode_search('basic[returnZonecode]', 'basic[returnAddress]', 'basic[returnZipcode]');" class="btn btn-gray btn-sm">
                                우편번호찾기
                            </button>
                        </label>
                    </div>
                    <div class="form-inline">
                        <label title="&quot;우편번호찾기&quot;를 통해서 &quot;반품/교환지 주소&quot;를 입력해 주세요!">
                            <input type="text" name="basic[returnAddress]" value="<?php echo $data['basic']['returnAddress']; ?>"
                                   class="form-control width-2xl"/>
                        </label>
                        <label title="&quot;우편번호찾기&quot;를 통해서 &quot;반품/교환지 상세주소&quot;를 입력해 주세요!">
                            <input type="text" name="basic[returnAddressSub]" value="<?php echo $data['basic']['returnAddressSub']; ?>"
                                   class="form-control width-2xl"/>
                        </label>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
    <p>
        <label class="checkbox-inline">
            <?php if ($data['count'] != 1 && $data['basic']['defaultFl'] != 'y') { ?>
                <input type="checkbox" name="basic[defaultFl]" value="y" class="js-default-fl"/>
            <?php } else { ?>
                <input type="checkbox" name="basic[defaultFl]" checked="checked" disabled="disabled"/>
                <input type="hidden" name="basic[defaultFl]" value="y"/>
            <?php } ?>
            상품등록 시 기본으로 노출되도록 설정합니다.
        </label>
    </p>
</form>

<script type="text/html" id="templateMethodFixed">
    <tr>
        <th>배송비 설정</th>
        <td colspan="3" class="form-inline">
            <input type="hidden" name="charge[sno][]" value="">
            <input type="hidden" name="charge[unitStart][]" value="0">
            <input type="hidden" name="charge[unitEnd][]" value="0">
            구매금액 및 구매건수에 상관없이
            <?= gd_currency_symbol() ?> <input type="text" name="charge[price][]" value="" class="form-control js-number"> <?= gd_currency_string() ?>
        </td>
    </tr>
</script>
<script type="text/html" id="templateMethodFree">
    <tr>
        <th>배송비 설정</th>
        <td colspan="3">
            <input type="hidden" name="charge[sno][]" value=""/>
            <input type="hidden" name="charge[unitStart][]" value="0"/>
            <input type="hidden" name="charge[unitEnd][]" value="0"/>
            <input type="hidden" name="charge[price][]" value="0"/>
            <div class="">
                <label>
                    <input type="checkbox" name="basic[freeFl]" value="y" <?php echo gd_isset($checked['freeFl']['y']); ?>/> 이 배송비 조건이 적용된 상품이 포함된 주문건의 배송비를 함께 무료로 합니다.
                </label>
            </div>
            <p class="notice-info">
                공급사 구분이 같은 배송비 조건이 적용된 상품만 가능합니다.<br/>
                배송비를 함께 무료로 해도 지역별 추가배송비 설정은 각 상품에 적용된 배송비 조건의 지역별 추가배송비를 따릅니다.
            </p>
        </td>
    </tr>
</script>
<script type="text/html" id="templateMethodEtc">
    <tr>
        <th>배송비 설정(<%=strTitle%>별)</th>
        <td colspan="3">
            <table id="basicDeliveryConfigFeeForm" class="table table-cols mgb0">
                <thead>
                <tr>
                    <th>구매<%=strTitle%> 범위</th>
                    <th class="width-lg">배송비</th>
                    <th class="width-sm"></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <div class="form-inline">
                            <input type="hidden" name="charge[sno][]" value=""/>
                            <%=strSymbol%> <input type="text" name="charge[unitStart][]" value="<%=strUnitStart%>" readonly="readonly" class="form-control js-number"/> <%=strUnit%> 이상 ~
                            <%=strSymbol%> <input type="text" name="charge[unitEnd][]" value="" class="form-control js-number js-unitprice"/> <%=strUnit%> 미만일 때
                        </div>
                    </td>
                    <td class="center">
                        <div class="form-inline">
                            <?= gd_currency_symbol() ?> <input type="text" name="charge[price][]" value="" class="form-control"/> <?= gd_currency_string() ?>
                        </div>
                    </td>
                    <td class="text-center"><input type="button" value="추가" class="btn btn-sm btn-white btn-icon-plus js-table-row-add"/></td>
                </tr>
                <tr id="basicDeliveryConfigFeeTrEnd">
                    <td>
                        <div class="form-inline">
                            <%=strSymbol%> <input type="text" name="charge[unitStart][]" value="" readonly="readonly" class="form-control js-number"/> <%=strUnit%> 이상
                        </div>
                    </td>
                    <td class="center" nowrap="nowrap">
                        <div class="form-inline">
                            <?= gd_currency_symbol() ?> <input type="text" name="charge[price][]" value="" class="form-control"/> <?= gd_currency_string() ?>
                        </div>
                    </td>
                    <td class="center"></td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr id="basicDeliveryConfigFeePrice">
        <th class="display-none">금액별 배송비 기준</th>
        <td colspan="3" class="display-none">
            <h5>판매가</h5>
            <div class="form-inline">
                <strong class="btn">+</strong> (
                <label class="checkbox-inline">
                    <input type="checkbox" name="basic[pricePlusStandard][option]" value="option" <?= $checked['pricePlusStandard']['option'] ?> /> 옵션가
                </label>
                <label class="checkbox-inline">
                    <input type="checkbox" name="basic[pricePlusStandard][add]" value="add" <?= $checked['pricePlusStandard']['add'] ?> /> 추가상품가
                </label>
                <label class="checkbox-inline">
                    <input type="checkbox" name="basic[pricePlusStandard][text]" value="text" <?= $checked['pricePlusStandard']['text'] ?> /> 텍스트옵션가
                </label>
                )
            </div>
            <div class="form-inline">
                <strong class="btn">-</strong> (
                <label class="checkbox-inline">
                    <input type="checkbox" name="basic[priceMinusStandard][goods]" value="goods" <?= $checked['priceMinusStandard']['goods'] ?> /> 상품할인가
                </label>
                <label class="checkbox-inline">
                    <input type="checkbox" name="basic[priceMinusStandard][coupon]" value="coupon" <?= $checked['priceMinusStandard']['coupon'] ?> /> 상품쿠폰할인가
                </label>
                )
            </div>
            <?php if($useNaverPay == 'y') {?>
            <div class="notice-danger">네이버페이를 통하여 판매하는 경우에는, 판매가에 "옵션가/추가상품가/텍스트옵션가" 항목이 포함된 배송비 기준으로 <b>자동 적용</b>되므로 설정에 유의하시기 바랍니다.</div>
            <?php }?>
        </td>
    </tr>
</script>

<script type="text/html" id="templateAddPrice">
    <tr>
        <td>
            <div class="form-inline">
                <input type="hidden" name="charge[sno][]" value="<%=sno%>"/>
                <%=strSymbol%> <input type="text" name="charge[unitStart][]" value="<%=unitStart%>" readonly="readonly" class="form-control"/> <%=strUnit%> 이상 ~
                <%=strSymbol%> <input type="text" name="charge[unitEnd][]" value="<%=unitEnd%>" class="form-control js-number js-unitprice"/> <%=strUnit%> 미만일 때
            </div>
        </td>
        <td class="center" nowrap="nowrap">
            <div class="form-inline">
                <?= gd_currency_symbol() ?> <input type="text" name="charge[price][]" value="<%=price%>" class="form-control js-number"/> <?= gd_currency_string() ?>
            </div>
        </td>
        <td class="center">
            <input type="button" value="삭제" class="btn btn-sm btn-white btn-icon-minus js-table-row-delete"/>
        </td>
    </tr>
</script>
<script type="text/javascript">
    <!--
    var data = <?=json_encode($data)?>;
    var fixFl = '<?=$data['basic']['fixFl']?>';
    var dom = {
        fieldId: $('#basicDeliveryConfigFee'),
        tableId: $('#basicDeliveryConfigFeeForm'),
        endRowId: $('#basicDeliveryConfigFeeTrEnd'),
    };
    var fieldID = 'basicDeliveryConfig';


    function getUnitString() {
        var strUnit = '<?=gd_currency_string()?>';
        switch (fixFl) {
            case 'count':
                strUnit = '개';
                break;
            case 'weight':
                strUnit = '<?=gd_weight_string()?>';
                break;
        }
        return strUnit;
    }

    function getUnitSymbol() {
        var strSymbol = ''
        switch (fixFl) {
            case 'price':
                strSymbol = '<?=gd_currency_symbol()?>';
                break;
        }
        return strSymbol;
    }

    function getUnitTitle() {
        var strTitle = '금액';
        switch (fixFl) {
            case 'count':
                strTitle = '수량';
                break;
            case 'weight':
                strTitle = '무게';
                break;
        }
        return strTitle;
    }

    /**
     * 배송비설정 초기화 및 변경
     *
     * @param string fixMethod 배송비 책정 방법
     */
    function form_delivery_charge(fixMethod) {
        // 배송비 유형에 따른 설정 HTML 동적 로드
        var templateId = fixMethod.charAt(0).toUpperCase() + fixMethod.slice(1);
        var params = {
            fixMethod: fixMethod,
            sno: 1,
            idx: 1
        };
        switch (fixMethod) {
            case 'free':
            case 'fixed':
                break;
            default:
                templateId = 'Etc';
                if (fixMethod == 'weight') {
                    params.strUnitStart = '0.00';
                } else {
                    params.strUnitStart = '0';
                }
                params.strUnit = getUnitString();
                params.strSymbol = getUnitSymbol();
                params.strTitle = getUnitTitle();
                break;
        }
        var complied = _.template($('#templateMethod' + templateId).html());
        $(dom.fieldId.selector).html(complied(params));

        //--- 기본 배송 정책 설정
        if (_.isEmpty(data.basic) === false) {
            var basic = data.basic;
            var charge = data.charge;
            var add = data.add;

            if (fixMethod == 'price') {
                $('#basicDeliveryConfigFeePrice').find('th, td').removeClass('display-none');
            } else if (fixMethod == 'weight') {
                $('#basicDeliveryConfigFeeWeight').removeClass('display-none');
            } else {
                $('#basicDeliveryConfigFeePrice').find('th, td').addClass('display-none');
                $('#basicDeliveryConfigFeeWeight').addClass('display-none');
            }

            if (_.isArray(charge)) {
                var cntKey = 0;
                var length = charge.length;
                var unitStart = 0;
                var unitEnd = 0;
                $.each(charge, function (key, val) {
                    // 수량별 배송비인 경우 unit 값의 소수점 제거하기
                    switch (fixFl) {
                        case 'weight':
                            unitStart = numeral(parseFloat(val.unitStart)).format('0.00');
                            unitEnd = numeral(parseFloat(val.unitEnd)).format('0.00');
                            break;
                        default:
                            unitStart = parseInt(val.unitStart);
                            unitEnd = parseInt(val.unitEnd);
                            break;
                    }

                    if (cntKey > 0 && cntKey < length - 1) {
                        charge_field_add(val);
                    } else {
                        $('input[name="charge[sno][]"]').eq(cntKey).val(val.sno);
                        $('input[name="charge[unitStart][]"]').eq(cntKey).val(unitStart);
                        $('input[name="charge[unitEnd][]"]').eq(cntKey).val(unitEnd);
                        $('input[name="charge[price][]"]').eq(cntKey).val(val.price);
                    }
                    cntKey++;
                });
            }

            //--- 지역별 배송비 설정
            if (_.isEmpty(add) === false) {
                var chkKey = basicStnKey = 0;
                var basicChkKey = -1;
                $.each(add, function (key, val) {
                    if (basic.sno != chkKey) {
                        //addAreaInput(fieldID + 'addArea' + basic.sno, basic.sno);
                    } else {
                        chkKey = add.basicKey + 1;
                    }
                    if (basicChkKey == basic.sno) {
                        basicStnKey++;
                    } else {
                        basicStnKey = 0;
                    }

                    $('input[name="add[sno][' + basicStnKey + ']"]').val(add.sno);
                    $('input[name="add[addPrice][' + basicStnKey + ']"]').val(add.addPrice);
                    $('textarea[name="add[addArea][' + basicStnKey + ']"]').val(add.addArea);

                    basicChkKey = basic.sno;
                });
            }
        }
    }

    /**
     * 배송비 설정의 배송 구간 설정 폼
     *
     */
    function charge_field_add(val) {
        // 기존 삭제 버튼은 보이지 않게 처리
        $(dom.tableId.selector).find('tr .js-table-row-delete').removeClass('js-temp').addClass('display-none');

        var data = {};
        if (!_.isUndefined(val)) {
            data = val;
        } else {
            data = {
                sno: '',
                unitStart: $(dom.endRowId.selector).prev('tr').find('input[name="charge[unitEnd][]"]').val(),
                unitEnd: '',
                price: ''
            };
        }

        // 배송비 유형에 따른 unit 값의 소수점 추가/제거
        switch (fixFl) {
            case 'weight':
                if (!_.isEmpty(data.unitStart)) {
                    data.unitStart = numeral(parseFloat(data.unitStart)).format('0.00');
                } else {
                    data.unitStart = '';
                }
                if (!_.isEmpty(data.unitEnd)) {
                    data.unitEnd = numeral(parseFloat(data.unitEnd)).format('0.00');
                } else {
                    data.unitEnd = '';
                }
                break;
            default:
                if (!_.isEmpty(data.unitStart)) {
                    data.unitStart = parseInt(data.unitStart);
                } else {
                    data.unitStart = '';
                }
                if (!_.isEmpty(data.unitEnd)) {
                    data.unitEnd = parseInt(data.unitEnd);
                } else {
                    data.unitEnd = '';
                }
                break;
        }
        data.fixMethod = fixFl;
        data.strUnit = getUnitString();
        data.strSymbol = getUnitSymbol();
        console.log(data);

        var complied = _.template($('#templateAddPrice').html());
        $(dom.endRowId.selector).before(complied(data));
    }

    /**
     * @property integer scmNo 공급사번호
     */
    var globalScmNo = <?=DEFAULT_CODE_SCMNO?>;

    /**
     * 공급사 선택 후 실행되는 콜백함수
     * scmNo를 설정한다.
     *
     * @param data
     */
    function setScmNo(data) {
        displayTemplate(data);
        globalScmNo = data.info[0].scmNo;
        getAreaGroupList(data.info[0].scmNo);
    }

    /**
     * 공급사에 맞는 지역별배송비 그룹 셀렉트 박스 호출해서 넣기
     *
     * @param scmNo
     */
    function getAreaGroupList(scmNo) {
        var params = {
            mode: 'area_select_box',
            scmNo: scmNo
        };
        $.ajax({
            method: "POST",
            cache: false,
            url: "../policy/delivery_ps.php",
            data: params,
            success: function (data) {
                // error 메시지 예외 처리용
                if (!_.isUndefined(data.error) && data.error == 1) {
                    alert(data.message);
                    return false;
                }
                $('#areaGroupSelect').empty().append(data.data);

            },
            error: function (data) {
                alert(data.message);
            }
        });
    }

    $(document).ready(function () {
        <?php if($useNaverPay == 'y') {?>
        //네이버페이 사용중일경우에만
        $("select[name='basic[fixFl]']").bind('change', function () {
            val = $(this).val();
            $('#naverpay-info').remove();
            var msg = null;
            if (val == 'weight') {
                msg = '네이버페이 서비스를 사용중인 경우, 무게별 배송비 조건을 적용한 상품은 네이버페이로 구매할 수 없습니다.';
            }
            else if (val == 'count') {
                msg = '네이버페이 서비스를 사용중인 경우, 배송비 설정이 4구간 이상인 수량별 배송비 조건을 적용한 상품은 네이버페이로 구매할 수 없습니다.';
            }
            else if (val == 'price') {
                msg = '금액별 배송비는 배송비 설정이 2구간이면서, 기준 금액 이상일 때 무료배송인 경우에만 네이버페이를 통하여 판매할 수 있습니다.';
            }

            if (msg != null) {
                var msgTag = "<div id='naverpay-info' class='notice-danger'>" + msg + "</div>";
                $(this).after(msgTag);
            }
        });
        $("select[name='basic[fixFl]']:selected").trigger('change');

        <?php }?>

        // 배송비설정 초기화
        form_delivery_charge(fixFl);

        // 금액 입력부 초기화
        $('input[name*=\'[unitStart]\']').number_only();
        $('input[name*=\'[unitEnd]\']').number_only();
        $('input[name*=\'[price]\']').number_only();

        // 폼검증
        $("#frmDeliveryRegist").validate({
            dialog: false,
            submitHandler: function (form) {
                if ($('.js-default-fl').prop('checked') === true) {
                    BootstrapDialog.confirm({
                        title: '저장확인',
                        message: '기본설정을 이 배송비조건으로 변경하시겠습니까?',
                        type: BootstrapDialog.TYPE_WARNING,
                        closable: false,
                        callback: function (result) {
                            if (result) {
                                $('.js-default-fl').prop('checked', true);
                            } else {
                                $('.js-default-fl').prop('checked', false);
                            }
                            setTimeout(function () {
                                form.target = 'ifrmProcess';
                                form.submit();
                            }, 100);
                        }
                    });
                } else {
                    form.target = 'ifrmProcess';
                    form.submit();
                }
            },
            // onclick: false, // <-- add this option
            rules: {
                'basic[method]': 'required',
                'charge[price][]': {
                    required: function () {
                        return $.inArray($('#basic_fixFl option:selected').val(), ['price', 'count', 'weight']) != -1;
                    },
                    number: true,
                },
                'charge[unitStart][]': {
                    required: function () {
                        return $.inArray($('#basic_fixFl option:selected').val(), ['price', 'count', 'weight']) != -1;
                    },
                    number: true,
                },
                'charge[unitEnd][]': {
                    required: false,
                    number: true,
                    min: function() {
                        if ($('#basic_fixFl option:selected').val() == 'count') {
                            return 2;
                        } else {
                            return 0;
                        }
                    }
                }
            },
            messages: {
                'basic[method]': {
                    required: "배송비조건명을 입력하세요.",
                },
                'charge[price][]': {
                    required: "배송비를 입력하세요.",
                    number: "숫자만 입력하실 수 있습니다.",
                },
                'charge[unitStart][]': {
                    required: "구매수량 범위를 입력하세요.",
                    number: "숫자만 입력하실 수 있습니다.",
                },
                'charge[unitEnd][]': {
                    required: "구매수량 범위를 입력하세요.",
                    number: "숫자만 입력하실 수 있습니다.",
                    min: "최소 {0}이상의 숫자를 기입해주세요"
                }
            }
        });

        // 배송비 유형 선택 이벤트
        $('#basic_fixFl').change(function () {
            fixFl = this.value;
            form_delivery_charge(this.value);
        });

        // 공급사 구분 선택 이벤트
        $('.js-scm').click(function (e) {
            if ($(this).val() != '1') {
                getAreaGroupList(1);
            } else {
                getAreaGroupList(globalScmNo);
            }
        });

        // 삭제 버튼 클릭시 이벤트
        $(document).on('click', '.js-table-row-delete', function (e) {
            var inputVal = [];
            var $tr = $(this).closest('tr');
            var unitEnd = 0;
            $tr.find('input').each(function (key, val) {
                if (!$(val).is('.js-table-row-delete')) {
                    inputVal[$(val).attr('name').replace('charge[', '').replace('][]', '')] = $(val).val();
                    if (key == 2) unitEnd = $(val).val();
                }
            });

            $tr.prev('tr').find('.js-table-row-delete').removeClass('display-none');
            $tr.next('tr').find('input[name="charge[unitStart][]"]').val($tr.prev('tr').find('input[name="charge[unitEnd][]"]').val());
            $tr.remove();
        });

        // 추가 버튼 클릭시 이벤트
        $(document).on('click', '.js-table-row-add', function (e) {
            charge_field_add();
        });

        // 구매수량 범위 입력시 값의 크기 체크
        $(document).on('blur', '.js-unitprice', function (e) {
            var $tr = $(this).closest('tr');
            if ($(this).val() <= parseFloat($tr.find('input[name="charge[unitStart][]"]').val())) {
                switch (fixFl) {
                    case 'count':
                        alert('시작수량 보다 큰 수량을 입력하셔야 합니다.');
                        break;
                    case 'weight':
                        alert('시작무게 보다 큰 무게을 입력하셔야 합니다.');
                        break;
                    default:
                        alert('시작금액 보다 큰 금액을 입력하셔야 합니다.');
                        break;
                }
                $(this).val('');
                $tr.next().find('input[name="charge[unitStart][]"]').val('');
            }
        });

        // 구매수량 값 입력시 다음 셀에 값 복사
        $(document).on('keyup, blur', '.js-unitprice', function (e) {
            var $tr = $(this).closest('tr');
            $tr.next('tr').find('input[name="charge[unitStart][]"]').val($(this).val());
        });

        // 지역별추가배송비 선택여부에 따른 토글
        $('.js-areaFl').change(function (e) {
            $('#area-info').remove();
            if ($(this).val() == 'y') {
                $('#areaGroupSelect').show();
                <?php if($useNaverPay == 'y') {?>
                var msg = "네이버페이로 결제 시 <a href='naver_pay_config.php' target='_blank'>[기본설정>결제정책>네이버페이설정]</a>의 지역별 배송비 설정에 따라 지역별 배송비가 책정됩니다.";
                $('#areaGroupSelect').after("<div id='area-info' class='notice-danger'>"+msg+"</div>");
                <?php }?>
            }
            else {
                $('#areaGroupSelect').hide();
            }
        });

        // 출고지/반품/교환지 주소 토글
        $('.js-unstoringFl').change(function (e) {
            $('#unstoringFl_new').toggleClass('display-none');
        });
        $('.js-returnFl').change(function (e) {
            if ($(this).val() == 'new') {
                $('#returnFl_new').removeClass('display-none');
            } else {
                $('#returnFl_new').addClass('display-none');
            }
        });

        // 지역 및 배송비 추가 iframe 다이얼로그 호출
        $(document).on('click', '.js-popup-register', function (e) {
            win = popup({
                url: './delivery_area_regist.php?popupMode=true',
                width: 760,
                height: 610,
                scrollbars: 'yes',
                resizable: 'no'
            });
            win.focus();
        });
    });
    //-->
</script>
