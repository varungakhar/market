<form id="frmDeliveryRegist" name="frmDeliveryRegist" action="delivery_ps.php" method="post">
    <input type="hidden" name="mode" value="regist"/>
    <input type="hidden" name="basic[sno]" value="<?=$data['basic']['sno']?>">
    <input type="hidden" name="basic[managerNo]" value="<?=$data['basic']['managerNo']?>">
    <input type="hidden" name="basic[printFl]" value="<?=$data['basic']['printFl']?>">
    <input type="hidden" name="basic[printPrice]" value="<?=$data['basic']['printPrice']?>">
    <input type="hidden" name="basic[printPriceFl]" value="<?=$data['basic']['printPriceFl']?>">
    <input type="hidden" name="scmNo" value="<?=$data['manage']['scmNo']?>"/>

    <div class="page-header js-affix">
        <h3><?php echo end($naviMenu->location); ?></h3>
        <input type="submit" value="저장" class="btn btn-red">
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
        <tr>
            <th>배송비조건명</th>
            <td colspan="3">
                <input type="text" name="basic[method]" value="<?=$data['basic']['method']?>" class="form-control width-lg js-maxlength" maxlength="20">
            </td>
        </tr>
        <tr>
            <th>배송비조건 설명</th>
            <td colspan="3">
                <input type="text" name="basic[description]" value="<?=$data['basic']['description']?>" class="form-control width-3xl js-maxlength" maxlength="100">
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
                <div id="areaGroupSelect" class="form-inline mgt10 <?=$data['basic']['areaFl']=='y'?'':'display-none'?>">
                    <?php echo gd_select_box('areaGroupNo', 'basic[areaGroupNo]', $mode['areaGroupList'], null, $data['basic']['areaGroupNo'] ?: $mode['areaGroupSelected']); ?>
                    <button type="button" class="btn btn-xs btn-gray js-popup-register" data-type="delivery_post" data-layer="layerDeliveryPostForm" data-size="wide-sm" data-sno="<?=$data['basic']['sno']?>">지역 및 배송비 추가</button>
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
                <div id="unstoringFl_new" class="mgt5 <?=$data['basic']['unstoringFl']=='new'?:'display-none'?>">
                    <div class="form-inline mgb5">
                        <label title="&quot;우편번호찾기&quot;를 통해서 &quot;우편번호&quot;를 입력해 주세요!">
                            <input type="text" name="basic[unstoringZonecode]" value="<?php echo $data['basic']['unstoringZonecode']; ?>" maxlength="5" class="form-control width-2xs"/>
                            <input type="hidden" name="basic[unstoringZipcode]" value="<?php echo $data['basic']['unstoringZipcode']; ?>"/>
                            <span id="unstoringZipcodeText" class="number <?php if (strlen($data['basic']['unstoringZipcode']) != 7) {
                                echo 'display-none';
                            } ?>">(<?php echo $data['basic']['unstoringZipcode']; ?>)</span>
                            <button type="button" onclick="postcode_search('basic[unstoringZonecode]', 'basic[unstoringAddress]', 'basic[unstoringZipcode]');" class="btn btn-gray btn-sm">우편번호찾기</button>
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
                <label title="반품/교환지 주소가 사업장 주소와 다른 경우 &quot;주소 등록&quot;을 선택하세요!" class="radio-inline">
                    <input type="radio" name="basic[returnFl]" value="new" class="js-returnFl" <?php echo gd_isset($checked['returnFl']['new']); ?> />주소 등록
                </label>
                <div id="returnFl_new" class="mgt5 <?=$data['basic']['returnFl']=='new'?:'display-none'?>">
                    <div class="form-inline mgb5">
                        <label title="&quot;우편번호찾기&quot;를 통해서 &quot;우편번호&quot;를 입력해 주세요!">
                            <input type="text" name="basic[returnZonecode]" value="<?php echo $data['basic']['returnZonecode']; ?>" maxlength="5"
                                   class="form-control width-2xs"/>
                            <input type="hidden" name="basic[returnZipcode]" value="<?php echo $data['basic']['returnZipcode']; ?>"/>
                            <span id="returnZipcodeText" class="number <?php if (strlen($data['basic']['returnZipcode']) != 7) {
                                echo 'display-none';
                            } ?>">(<?php echo $data['basic']['returnZipcode']; ?>)</span>
                            <button type="button" onclick="postcode_search('basic[returnZonecode]', 'basic[returnAddress]', 'basic[returnZipcode]');" class="btn btn-gray btn-sm">우편번호찾기</button>
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
                <input type="checkbox" name="basic[defaultFl]" value="y" class="js-default-fl" />
            <?php } else { ?>
                <input type="checkbox" name="basic[defaultFl]" checked="checked" disabled="disabled" />
                <input type="hidden" name="basic[defaultFl]" value="y" />
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
            <?=gd_currency_symbol()?> <input type="text" name="charge[price][]" value="" class="form-control js-number"> <?=gd_currency_string()?>
        </td>
    </tr>
</script>
<script type="text/html" id="templateMethodFree">
    <tr>
        <th>배송비 설정</th>
        <td colspan="3">
            <input type="hidden" name="charge[sno][]" value="" />
            <input type="hidden" name="charge[unitStart][]" value="0" />
            <input type="hidden" name="charge[unitEnd][]" value="0" />
            <input type="hidden" name="charge[price][]" value="0" />
            <div class="checkbox">
                <label class="checkbox-inline">
                    <input type="checkbox" name="basic[freeFl]" value="y" <?php echo gd_isset($checked['freeFl']['y']); ?>/> 이 배송비 조건이 적용된 상품이 포함된 주문건의 배송비를 함께 무료로 합니다.
                </label>
            </div>
            <p class="notice-info">
                공급사 구분이 같은 배송비 조건이 적용된 상품만 가능합니다.<br />
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
                            <input type="hidden" name="charge[sno][]" value="" />
                            <%=strSymbol%> <input type="text" name="charge[unitStart][]" value="0" readonly="readonly" class="form-control js-number" /> <%=strUnit%> 이상 ~
                            <%=strSymbol%> <input type="text" name="charge[unitEnd][]" value="" class="form-control js-number js-unitprice" /> <%=strUnit%> 미만일 때
                        </div>
                    </td>
                    <td class="center">
                        <div class="form-inline">
                            <?=gd_currency_symbol()?> <input type="text" name="charge[price][]" value="" class="form-control" /> <?=gd_currency_string()?>
                        </div>
                    </td>
                    <td class="text-center"><input type="button" value="추가" class="btn btn-sm btn-white btn-icon-plus js-table-row-add" /></td>
                </tr>
                <tr id="basicDeliveryConfigFeeTrEnd">
                    <td>
                        <div class="form-inline">
                            <%=strSymbol%> <input type="text" name="charge[unitStart][]" value="" readonly="readonly" class="form-control js-number" /> <%=strUnit%> 이상
                        </div>
                    </td>
                    <td class="center" nowrap="nowrap">
                        <div class="form-inline">
                            <?=gd_currency_symbol()?> <input type="text" name="charge[price][]" value="" class="form-control" /> <?=gd_currency_string()?>
                        </div>
                    </td>
                    <td class="center"></td>
                </tr>
                </tbody>
            </table>
            <%=strInfo%>
        </td>
    </tr>
    <tr id="basicDeliveryConfigFeePrice">
        <th class="display-none">금액별 배송비 기준</th>
        <td colspan="3" class="display-none">
            <h5>판매가</h5>
            <div class="form-inline">
                <strong class="btn">+</strong> (
                <label class="checkbox-inline">
                    <input type="checkbox" name="basic[pricePlusStandard][option]" value="option" <?=$checked['pricePlusStandard']['option']?> /> 옵션가
                </label>
                <label class="checkbox-inline">
                    <input type="checkbox" name="basic[pricePlusStandard][add]" value="add"  <?=$checked['pricePlusStandard']['add']?> /> 추가상품가
                </label>
                <label class="checkbox-inline">
                    <input type="checkbox" name="basic[pricePlusStandard][text]" value="text"  <?=$checked['pricePlusStandard']['text']?> /> 텍스트옵션가
                </label>
                )
            </div>
            <div class="form-inline">
                <strong class="btn">-</strong> (
                <label class="checkbox-inline">
                    <input type="checkbox" name="basic[priceMinusStandard][goods]" value="goods"  <?=$checked['priceMinusStandard']['goods']?> /> 상품할인가
                </label>
                <label class="checkbox-inline">
                    <input type="checkbox" name="basic[priceMinusStandard][coupon]" value="coupon"  <?=$checked['priceMinusStandard']['coupon']?> /> 상품쿠폰할인가
                </label>
                )
            </div>
        </td>
    </tr>
</script>

<script type="text/html" id="templateAddPrice">
    <tr>
        <td>
            <div class="form-inline">
                <input type="hidden" name="charge[sno][]" value="<%=sno%>" />
                <%=strSymbol%> <input type="text" name="charge[unitStart][]" value="<%=unitStart%>" readonly="readonly" class="form-control" /> <%=strUnit%> 이상 ~
                <%=strSymbol%> <input type="text" name="charge[unitEnd][]" value="<%=unitEnd%>" class="form-control js-number js-unitprice" /> <%=strUnit%> 미만일 때
            </div>
        </td>
        <td class="center">
            <div class="form-inline">
                <?=gd_currency_symbol()?> <input type="text" name="charge[price][]" value="<%=price%>" class="form-control js-number" /> <?=gd_currency_string()?>
            </div>
        </td>
        <td class="center">
            <input type="button" value="삭제" class="btn btn-sm btn-white btn-icon-minus js-table-row-delete" />
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
        switch(fixFl) {
            case 'count':
                strUnit = '개'
                break;
            case 'weight':
                strUnit = '<?=gd_weight_string()?>'
                break;
        }
        return strUnit;
    }

    function getUnitSymbol() {
        var strSymbol = ''
        switch(fixFl) {
            case 'price':
                strSymbol = '<?=gd_currency_symbol()?>';
                break;
        }
        return strSymbol;
    }

    function getUnitTitle() {
        var strTitle = '금액';
        switch(fixFl) {
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
        params.strInfo ='';
        switch(fixMethod) {
            case 'free':
            case 'fixed':
                break;
        <?php if($useNaverPay == 'y') {?>
            case 'price' :
                templateId = 'Etc';
                params.strUnit = getUnitString();
                params.strSymbol = getUnitSymbol();
                params.strTitle = getUnitTitle();

                //네이버인경우
                msg = '네이버페이 서비스를 사용중인 경우, 배송비 설정이 2구간 이상인 금액별 배송비 조건을 적용한 상품은 네이버페이로 구매할 수 없습니다.';
                msgTag = "<div id='naverpay-info' class='notice-info'>" + msg + "</div>";
                params.strInfo =  msgTag;
                break;
            case 'count' :
                templateId = 'Etc';
                params.strUnit = getUnitString();
                params.strSymbol = getUnitSymbol();
                params.strTitle = getUnitTitle();

                //네이버인경우
                msg = '배송비 설정이 3구간 이상 설정된 수량별 배송비가 설정된 상품은 네이버페이로 구매할 수 없습니다.';
                msgTag = "<div id='naverpay-info' class='notice-info'>" + msg + "</div>";
                params.strInfo =  msgTag;
                break;
        <?php }?>
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
            } else {
                $('#basicDeliveryConfigFeePrice').find('th, td').addClass('display-none');
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
            console.log(val);
            data = val;
        } else {
            data = {
                sno: '',
                unitStart: $(dom.endRowId.selector).prev('tr').find('input[name="charge[unitEnd][]"]').val(),
                unitEnd: '',
                price: ''
            };
        }

        if (_.isEmpty(data.unitStart)) {
            data.unitStart = '';
        }
        if (_.isEmpty(data.unitEnd)) {
            data.unitEnd = '';
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

        var complied = _.template($('#templateAddPrice').html());
        $(dom.endRowId.selector).before(complied(data));
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
                msg = '네이버페이 서비스를 사용중인 경우, 배송비 설정이 3구간 이상인 수량별 배송비 조건을 적용한 상품은 네이버페이로 구매할 수 없습니다.';
            }
            else if (val == 'price') {
                msg = '네이버페이 서비스를 사용중인 경우, 배송비 설정이 2구간 이상인 금액별 배송비 조건을 적용한 상품은 네이버페이로 구매할 수 없습니다.';
            }

            if (msg != null) {
                var msgTag = "<div id='naverpay-info' class='notice-danger'>" + msg + "</div>";
                $(this).after(msgTag);
            }
        });
        <?php }?>

        // 배송비설정 초기화
        form_delivery_charge(fixFl);

        // 금액 입력 초기화
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
                        callback: function(result){
                            if (result) {
                                $('.js-default-fl').prop('checked', true);
                            } else {
                                $('.js-default-fl').prop('checked', false);
                            }
                            setTimeout(function(){
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
                    required: function() {
                        return $.inArray($('#basic_fixFl option:selected').val(), ['price', 'count', 'weight']) != -1;
                    },
                    number: true,
                },
                'charge[unitStart][]': {
                    required: function() {
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

        $('#basic_fixFl').change(function () {
            console.log($('#basic_fixFl option:selected').val());
            fixFl = this.value;
            form_delivery_charge(this.value);
        });

        // 삭제 버튼 클릭시 이벤트
        $(document).on('click', '.js-table-row-delete', function(e){
            var inputVal = [];
            var $tr = $(this).closest('tr');
            var unitEnd = 0;
            $tr.find('input').each(function(key, val){
                if (!$(val).is('.js-table-row-delete')) {
                    inputVal[$(val).attr('name').replace('charge[','').replace('][]','')] = $(val).val();
                    if (key == 2) unitEnd = $(val).val();
                }
            });

            $tr.prev('tr').find('.js-table-row-delete').removeClass('display-none');
            $tr.next('tr').find('input[name="charge[unitStart][]"]').val($tr.prev('tr').find('input[name="charge[unitEnd][]"]').val());
            $tr.remove();
        });

        // 추가 버튼 클릭시 이벤트
        $(document).on('click', '.js-table-row-add', function(e){
            charge_field_add();
        });

        // 구매수량범위 입력시 값의 크기 체크
        $(document).on('blur', '.js-unitprice', function(e){
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
        $(document).on('keyup', '.js-unitprice', function(e){
            var $tr = $(this).closest('tr');
            $tr.next('tr').find('input[name="charge[unitStart][]"]').val($(this).val());
        });

        // 지역별추가배송비 선택여부에 따른 토글
        $('.js-areaFl').change(function(e){
            $('#area-info').remove();
            if ($(this).val() == 'y') {
                $('#areaGroupSelect').show();
                <?php if($useNaverPay == 'y') {?>
                var msg = "네이버페이로 결제 시 <a href='base_info.php' target='_blank'>[기본설정>기본정책>기본정보설정]</a>의 네이버페이 지역별 배송비 설정에 따라 지역별 배송비가 책정됩니다.";
                $('#areaGroupSelect').after("<div id='area-info' class='notice-danger'>"+msg+"</div>");
                <?php }?>
            }
            else {
                $('#areaGroupSelect').hide();
            }
        });

        // 출고지/반품/교환지 주소 토글
        $('.js-unstoringFl').change(function(e){
            $('#unstoringFl_new').toggleClass('display-none');
        });
        $('.js-returnFl').change(function(e){
            $('#returnFl_new').toggleClass('display-none');
        });

        // 지역 및 배송비 추가 iframe 다이얼로그 호출
        $('.js-popup-register').click(function(e){
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
