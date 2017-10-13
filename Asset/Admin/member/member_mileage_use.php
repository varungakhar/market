<form id="frmMileageUse" name="frmMileageUse" action="member_ps.php" method="post">    <input type="hidden" name="mode" value="mileage_use"/>    <div class="page-header js-affix">        <h3><?php echo end($naviMenu->location); ?>            <small>마일리지 사용에 대한 설정을 하실 수 있습니다.</small>        </h3>        <div class="btn-group">            <input type="submit" value="저장" class="btn btn-red"/>        </div>    </div>    <div class="table-title gd-help-manual">        마일리지 사용설정    </div>    <table class="table table-cols">        <colgroup>            <col class="width-md"/>            <col/>        </colgroup>        <tbody class="js-disabled-use">        <tr>            <th>최소 보유마일리지 제한</th>            <td>                <div class="form-inline">                    <input type="text" name="minimumHold" value="<?php echo $data['minimumHold']; ?>" class="form-control js-number width-xs">                    <?php echo Globals::get('gSite.member.mileageBasic.unit');?> 이상 적립된 경우 결제시 사용 가능                </div>            </td>        </tr>        <tr>            <th>최소 상품구매금액 제한</th>            <td>                <div class="form-inline">                    구매금액 합계가                    <input type="text" name="orderAbleLimit" value="<?php echo $data['orderAbleLimit']; ?>" class="form-control js-number width-xs">                    <?php echo gd_currency_default();?> 이상인 경우 결제시 사용 가능                </div>                <div class="form-inline pdt10">                    <label class="radio-inline">                        <input type="radio" name="standardPrice" value="goodsPrice" <?php echo $checked['standardPrice']['goodsPrice']; ?> />                        할인금액 미포함 가격기준                    </label>                    <label class="radio-inline">                        <input type="radio" name="standardPrice" value="salesPrice" <?php echo $checked['standardPrice']['salesPrice']; ?> />                        할인금액 포함 가격기준                    </label>                </div>            </td>        </tr>        <tr>            <th>최소 사용마일리지 제한</th>            <td>                <div class="form-inline">                    1회 결제 시 최소                    <input type="text" name="minimumLimit" value="<?php echo $data['minimumLimit']; ?>" class="form-control js-number width-xs">                    <?php echo Globals::get('gSite.member.mileageBasic.unit');?> 이상 사용 가능                </div>            </td>        </tr>        <tr>            <th>최대 사용금액 제한</th>            <td>                <div class="form-inline">                    1회 결제 시 최대                    <input type="text" name="maximumLimit" value="<?php echo $data['maximumLimit']; ?>" class="form-control js-number width-xs">                    <select name="maximumLimitUnit" class="form-control">                        <option value="percent" <?php echo $selected['maximumLimitUnit']['percent'] ?>>%</option>                        <option value="mileage" <?php echo $selected['maximumLimitUnit']['mileage'] ?>><?php echo Globals::get('gSite.member.mileageBasic.unit');?></option>                    </select>                    까지 사용 가능 (%는 구매금액 기준)                </div>                <div class="form-inline pdt10">                    배송비포함 여부 :                    <label class="radio-inline">                        <input type="radio" name="maximumLimitDeliveryFl" value="y" <?php echo $checked['maximumLimitDeliveryFl']['y']; ?> />                        포함                    </label>                    <label class="radio-inline">                        <input type="radio" name="maximumLimitDeliveryFl" value="n" <?php echo $checked['maximumLimitDeliveryFl']['n']; ?> />                        미포함                    </label>                </div>                <div class="notice-info">                    기본설정>회원정책>마일리지 기본설정에서 설정한 구매금액 기준에 따름 :                    <?=$basicData['goodsPrice'] == 1 ? '판매가' : ''?>                    <?=$basicData['optionPrice'] == 1 ? ' + 옵션가' : ''?>                    <?=$basicData['addGoodsPrice'] == 1 ? ' + 추가상품가' : ''?>                    <?=$basicData['textOptionPrice'] == 1 ? ' + 텍스트옵션가' : ''?>                    <?=$basicData['goodsDcPrice'] == 1 ? ' + 상품할인가' : ''?>                    <?=$basicData['memberDcPrice'] == 1 ? ' + 회원할인가' : ''?>                    <?=$basicData['couponDcPrice'] == 1 ? ' + 쿠폰할인가' : ''?>                </div>                <div class="notice-danger">                    "배송비포함 여부"를 미포함으로 설정 할 경우, 고객은 배송비를 제외한 상품금액에 대해서만 마일리지 사용이 가능합니다.                </div>            </td>        </tr>        </tbody>    </table></form><script type="text/javascript">    <!--    $(document).ready(function () {        // 마일리지 사용 설정 저장        $("#frmMileageUse").validate({            submitHandler: function (form) {                // 최대 최소 금액 체크                if ($('select[name=\'maximumLimitUnit\'] option:selected').val() == 'mileage') {                    if (parseInt($('input[name=\'maximumLimit\']').val()) < parseInt($('input[name=\'minimumLimit\']').val())) {                        BootstrapDialog.show({                            title: '마일리지 체크',                            type: BootstrapDialog.TYPE_WARNING,                            message: '"최대 사용 마일리지 금액" 보다 "최소 사용 마일리지 제한 금액" 이 더 크게 설정이 되었습니다.',                        });                        return false;                    }                }                if ($('select[name=\'maximumLimitUnit\'] option:selected').val() == 'percent') {                    if (parseInt($('input[name=\'maximumLimit\']').val()) > 100) {                        BootstrapDialog.show({                            title: '마일리지 체크',                            type: BootstrapDialog.TYPE_WARNING,                            message: '"최대 사용 마일리지 금액"이 너무 크게 설정이 되었습니다.',                        });                        return false;                    }                }                form.target = 'ifrmProcess';                form.submit();            },            rules: {                maximumLimit: {                    max: function() {                        return $('select[name=maximumLimitUnit]').val() == 'percent' ? 100 : 999999999;                    }                }            },            messages: {                maximumLimit: {                    max: '%로 설정시 100% 이상을 입력하실 수 없습니다.'                }            }        });        // 최대 100까지 입력 가능하도록 실시간 제한        $('input[name=maximumLimit]').keyup(function(e){            if ($('select[name=maximumLimitUnit]').val() == 'percent' && $(this).val() > 100) {                $(this).val(100);            }        });        // %->원 전환시 처리        $('select[name=maximumLimitUnit]').change(function(e){            if ($(this).val() == 'percent' && $('input[name=maximumLimit]').val() >= 100) {                $('input[name=maximumLimit]').val('100');            }        });		// 결제시 사용 가능 여부가 불가인 경우 인풋박스 비활성화 처리		$('[name="payUsableFl"]').click(function(e){			$isEnable = ($(this).val() != 'y');			console.log($isEnable );			$('.js-disabled-use input, .js-disabled-use select').each(function(obj) {				$(this).prop('disabled', $isEnable);			});		});		$('[name="payUsableFl"]:checked').trigger('click');    });    //--></script>