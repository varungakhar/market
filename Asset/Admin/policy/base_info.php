<form id="frmBase" action="base_ps.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="mode" value="base_info"/>
    <input type="hidden" name="mallFaviconTmp" value="<?php echo $data['mallFavicon']; ?>"/>
    <input type="hidden" name="stampImageTmp" value="<?php echo $data['stampImage'];?>" />
    <input type="hidden" name="mallSno" value="<?php echo $mallSno;?>" />

    <div class="page-header js-affix">
        <h3><?php echo end($naviMenu->location); ?>
            <small>쇼핑몰의 기본적인 정보를 변경하실 수 있습니다.</small>
        </h3>
        <input type="submit" value="저장" class="btn btn-red"/>
    </div>

    <?php if ($mallCnt > 1) { ?>
        <ul class="multi-skin-nav nav nav-tabs" style="margin-bottom:20px;">
            <?php foreach ($mallList as $key => $mall) { ?>
                <li role="presentation" class="js-popover <?php echo $mallSno == $mall['sno'] ? 'active' : 'passive'; ?>" data-html="true" data-content="<?php echo $mall['mallName']; ?>" data-placement="top">
                    <a href="./base_info.php?mallSno=<?php echo $mall['sno']; ?>">
                        <span class="flag flag-16 flag-<?php echo $mall['domainFl']?>"></span>
                        <span class="mall-name"><?php echo $mall['mallName']; ?></span>
                    </a>
                </li>
            <?php } ?>
        </ul>
    <?php } ?>

    <div class="table-title gd-help-manual">
        쇼핑몰 기본 정보
    </div>
    <table class="table table-cols">
        <colgroup>
            <col class="width-md"/>
            <col class="width-xl"/>
            <col class="width-md"/>
            <col/>
        </colgroup>
        <tr>
            <th>쇼핑몰명</th>
            <td class="form-inline" <?php if ($mallSno > 1) { ?>colspan="3"<?php } ?>>
                <input type="text" name="mallNm" value="<?php echo $data['mallNm']; ?>" class="form-control width-lg"/>
            </td>
            <th class="<?php if ($mallSno > 1) { ?>display-none<?php } ?>">쇼핑몰영문명</th>
            <td class="form-inline <?php if ($mallSno > 1) { ?>display-none<?php } ?>">
                <input type="text" name="mallNmEng" value="<?php echo $data['mallNmEng']; ?>" <?php echo $readonly . $disabled; ?> class="form-control width-lg"/>
            </td>
        </tr>
        <tr>
            <th>상단타이틀</th>
            <td class="form-inline">
                <input type="text" name="mallTitle" value="<?php echo $data['mallTitle']; ?>" class="form-control width-lg"/>
            </td>
            <th>파비콘</th>
            <td class="form-inline">
                <input type="file" name="mallFavicon" <?php echo $disabled; ?> class="form-control width70p"/>
				<span>
<?php
if (empty($data['mallFavicon']) === false) {
    echo gd_html_image(UserFilePath::data('common', $data['mallFavicon'])->www(), '파비콘');
    echo '<label class="checkbox-inline" style="padding-left:10px"><input type="checkbox" name="mallFaviconDel" ' . $disabled . ' value="y" />삭제</label>';
}
?>
				</span>
                <div class="notice-info">이미지사이즈 16x16 pixel, 파일형식 ico로 등록해야 합니다</div>
            </td>
        </tr>
        <tr>
            <th class="require">쇼핑몰 도메인</th>
            <td colspan="3" class="form-inline">
                <span class="font-eng">http://</span>
                <input type="text" name="mallDomain" value="<?php echo $data['mallDomain']; ?>" <?php echo $readonly . $disabled; ?> class="form-control width-2xl"/>
                <div class="notice-info">
                    입력 시 쇼핑몰 및 관리자 화면에 도메인 정보가 노출됩니다. <br />
                    실제 쇼핑몰 접속 도메인의 추가 및 변경은 마이고도에서 가능합니다. <a class="btn-link" href="javascript:gotoGodomall('domain');">바로가기></a>
                </div>
            </td>
        </tr>
        <tr>
            <th>대표카테고리</th>
            <td colspan="3" class="form-inline">
                <input type="hidden" name="mallCategory" value="<?php echo $data['mallCategory']; ?>" <?php echo $readonly . $disabled; ?>/>
                <span id="mallCategory"><?php echo $data['mallCategory']; ?></span>
                <input type="button" onclick="mall_categoty();" value="대표카테고리 선택" <?php echo $disabled; ?> class="btn btn-gray btn-sm"/>
            </td>
        </tr>
    </table>

    <div class="table-title gd-help-manual">
        회사 정보
    </div>
    <table class="table table-cols">
        <colgroup>
            <col class="width-md"/>
            <col class="width-xl"/>
            <col class="width-md"/>
            <col/>
        </colgroup>
        <tr>
            <th>상호(회사명)</th>
            <td class="form-inline">
                <input type="text" name="companyNm" value="<?php echo $data['companyNm']; ?>" class="form-control width-md"/>
            </td>
            <th>사업자등록번호</th>
            <td class="form-inline">
                <div>
                    <input type="text" name="businessNo[]" value="<?php echo $data['businessNo'][0]; ?>" <?php echo $readonly . $disabled; ?> maxlength="3" class="form-control js-number-only width-3xs"/> -
                    <input type="text" name="businessNo[]" value="<?php echo $data['businessNo'][1]; ?>" <?php echo $readonly . $disabled; ?> maxlength="2" class="form-control js-number-only width-3xs"/> -
                    <input type="text" name="businessNo[]" value="<?php echo $data['businessNo'][2]; ?>" <?php echo $readonly . $disabled; ?> maxlength="5" class="form-control js-number-only width-3xs"/>
                </div>
                <div class="notice-info">
                    인터넷 쇼핑몰 운영자는 전자상거래법에 의해 사업자정보 공개페이지를 쇼핑몰 홈페이지 초기 화면에 연결해야 합니다.
                    <a href="http://www.godo.co.kr/news/notice_view.php?board_idx=1101&page=1" target="_blank" class="btn-link">자세히보기 ></a>
                </div>
                <div class="notice-info">
                    사업자번호를 입력하면 쇼핑몰 하단 푸터에 자동으로 사업자정보 공개페이지가 연결됩니다.
                    <a href="http://ftc.go.kr/info/bizinfo/communicationList.jsp" target="_black" class="btn-link">통신판매사업자 정보 공개페이지 ></a>
                </div>
            </td>
        </tr>
        <tr>
            <th>대표자명</th>
            <td colspan="3" class="form-inline">
                <input type="text" name="ceoNm" value="<?php echo $data['ceoNm']; ?>" class="form-control width-lg"/>
            </td>
        </tr>
        <tr>
            <th>업태</th>
            <td class="form-inline">
                <input type="text" name="service" value="<?php echo $data['service']; ?>" <?php echo $readonly . $disabled; ?> class="form-control width-lg"/>
            </td>
            <th>종목</th>
            <td class="form-inline">
                <input type="text" name="item" value="<?php echo $data['item']; ?>" <?php echo $readonly . $disabled; ?> class="form-control width-lg"/>
            </td>
        </tr>
        <tr>
            <th class="require">대표 이메일</th>
            <td colspan="3" class="form-inline">
                <input type="text" name="email[]" value="<?php echo $data['email'][0]; ?>" <?php echo $readonly . $disabled; ?> class="form-control width-lg"/> @
                <input type="text" id="email" name="email[]" value="<?php echo $data['email'][1]; ?>" <?php echo $readonly . $disabled; ?> class="form-control width-md js-email-domain"/>
                <?php echo gd_select_box('email_domain', null, $emailDomain, null, $data['email'][1],null,$disabled,'email_domain'); ?>
                <div class="notice-info">
                    대표 이메일은 쇼핑몰에서 메일 발송 시 기본 발송자이메일 정보로 사용됩니다. <br />
                    발송자 이메일 정보가 없으면 자동메일 발송이 되지 않으니, 대표 이메일 정보를 반드시 입력해주세요.</a>
                </div>
            </td>
        </tr>
        <tr>
            <th>사업장 주소</th>
            <td colspan="3">
                <div class="form-inline mgb5">
                    <input type="text" name="zonecode" value="<?php echo $data['zonecode']; ?>" maxlength="5" class="form-control width-2xs"/>
                    <input type="hidden" name="zipcode" value="<?php echo $data['zipcode']; ?>"/>
                    <span id="zipcodeText" class="number <?php if (strlen($data['zipcode']) != 7) { echo 'display-none'; } ?>">(<?php echo $data['zipcode']; ?>)</span>
                    <input type="button" onclick="postcode_search('zonecode', 'address', 'zipcode');" value="우편번호찾기" class="btn btn-gray btn-sm"/>
                </div>
                <div class="form-inline">
                    <input type="text" name="address" value="<?php echo $data['address']; ?>" class="form-control width-2xl"/>
                    <input type="text" name="addressSub" value="<?php echo $data['addressSub']; ?>" class="form-control width-2xl"/>
                </div>
            </td>
        </tr>
        <tr>
            <th>출고지 주소</th>
            <td colspan="3">
                <div class="form-inline">
                    <label class="radio-inline">
                        <input type="radio" name="unstoringFl" value="same" onclick="display_toggle('unstoringFl','same');" <?php echo gd_isset($checked['unstoringFl']['same']); ?> />사업장 주소와 동일
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="unstoringFl" value="new" onclick="display_toggle('unstoringFl','new');" <?php echo gd_isset($checked['unstoringFl']['new']); ?> />주소 등록
                    </label>
                </div>
                <div id="unstoringFl_new" class="display-none">
                    <div class="form-inline mgt10 mgb5">
                        <input type="text" name="unstoringZonecode" value="<?php echo $data['unstoringZonecode']; ?>" maxlength="5" class="form-control width-2xs"/>
                        <input type="hidden" name="unstoringZipcode" value="<?php echo $data['unstoringZipcode']; ?>"/>
                        <span id="unstoringZipcodeText" class="number <?php if (strlen($data['unstoringZipcode']) != 7) { echo 'display-none'; } ?>">(<?php echo $data['unstoringZipcode']; ?>)</span>
                        <input type="button" onclick="postcode_search('unstoringZonecode', 'unstoringAddress', 'unstoringZipcode');" value="우편번호찾기" class="btn btn-gray btn-sm"/>
                    </div>
                    <div class="form-inline">
                        <input type="text" name="unstoringAddress" value="<?php echo $data['unstoringAddress']; ?>" class="form-control width-2xl"/>
                        <input type="text" name="unstoringAddressSub" value="<?php echo $data['unstoringAddressSub']; ?>" class="form-control width-2xl"/>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <th>반품/교환지 주소</th>
            <td colspan="3" class="form-inline">
                <div class="form-inline">
                    <label class="radio-inline">
                        <input type="radio" name="returnFl" value="same" onclick="display_toggle('returnFl','same');" <?php echo gd_isset($checked['returnFl']['same']); ?> />사업장 주소와 동일
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="returnFl" value="unstoring" onclick="display_toggle('returnFl','same');" <?php echo gd_isset($checked['returnFl']['unstoring']); ?> />출고지 주소와 동일
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="returnFl" value="new" onclick="display_toggle('returnFl','new');" <?php echo gd_isset($checked['returnFl']['new']); ?> />주소 등록
                    </label>
                </div>
                <div id="returnFl_new" class="display-none">
                    <div class="form-inline mgt10 mgb5">
                        <input type="text" name="returnZonecode" value="<?php echo $data['returnZonecode']; ?>" maxlength="5" class="form-control width-2xs"/>
                        <input type="hidden" name="returnZipcode" value="<?php echo $data['returnZipcode']; ?>"/>
                        <span id="returnZipcodeText" class="number <?php if (strlen($data['returnZipcode']) != 7) { echo 'display-none'; } ?>">(<?php echo $data['returnZipcode']; ?>)</span>
                        <input type="button" onclick="postcode_search('returnZonecode', 'returnAddress', 'returnZipcode');" value="우편번호찾기" class="btn btn-gray btn-sm"/>
                    </div>
                    <div class="form-inline">
                        <input type="text" name="returnAddress" value="<?php echo $data['returnAddress']; ?>" class="form-control width-2xl"/>
                        <input type="text" name="returnAddressSub" value="<?php echo $data['returnAddressSub']; ?>" class="form-control width-2xl"/>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <th>대표전화</th>
            <td class="form-inline">
                <input type="text" name="phone" value="<?php echo $data['phone']; ?>" maxlength="12" class="form-control js-number-only width-md"/>
            </td>
            <th>팩스번호</th>
            <td class="form-inline">
                <input type="text" name="fax" value="<?php echo $data['fax']; ?>" maxlength="12" class="form-control js-number-only width-md"/>
            </td>
        </tr>
        <tr>
            <th>통신판매신고번호</th>
            <td colspan="3" class="form-inline">
                <input type="text" name="onlineOrderSerial" value="<?php echo $data['onlineOrderSerial']; ?>" <?php echo $readonly . $disabled; ?> class="form-control width-sm"/>
            </td>
        </tr>
        <tr>
            <th>인감 이미지 등록</th>
            <td colspan="3" class="form-inline">
                <input type="file" name="stampImage" <?php echo $disabled; ?> class="form-control"/>
                <span>
                    <?php
                    if (empty($data['stampImage']) === false) {
                        echo gd_html_image(UserFilePath::data('etc', $data['stampImage'])->www(), '인감 이미지');
                        echo ' <label><input type="checkbox" name="stampImageDel" value="y" style="display:none"/><button ' . $disabled . ' class="btn btn-info btn-xs btnStampDelete">삭제</button></label>';
                    }
                    ?>
			    </span>
                <div class="notice-info">* 가로x세로 74픽셀, jpg/png/gif만 가능<br/>등록된 인감 이미지는 "일반 세금계산서, 간이영수증, 거래명세서" 등에 사용됩니다.</div>
            </td>
        </tr>
    </table>

    <div class="table-title gd-help-manual">
        고객센터
    </div>
    <table class="table table-cols">
        <colgroup>
            <col class="width-md"/>
            <col class="width-2xl"/>
            <col class="width-md"/>
            <col/>
        </colgroup>
        <tr>
            <th>전화번호</th>
            <td class="form-inline">
                <div class="mgb5">
                    <?php
                        if ($mallCnt > 1 && $mallSno > 1) {
                            echo gd_select_box('centerPhoneHead', 'centerPhoneHead', $countryAddress, null, $data['centerPhoneHead'], '==선택==', null, 'form-control');
                        }
                    ?>
                    <input type="text" name="centerPhone" value="<?php echo $data['centerPhone']; ?>" maxlength="12" class="form-control js-number-only width-md"/>
                </div>
                <div>
                    <?php
                        if ($mallCnt > 1 && $mallSno > 1) {
                            echo gd_select_box('centerSubPhoneHead', 'centerSubPhoneHead', $countryAddress, null, $data['centerSubPhoneHead'], '==선택==', null, 'form-control');
                        }
                    ?>
                    <input type="text" name="centerSubPhone" value="<?php echo $data['centerSubPhone']; ?>" maxlength="15" class="form-control js-number-only width-md"/>
                </div>
            </td>
            <th>팩스번호</th>
            <td class="form-inline">
                <?php
                    if ($mallCnt > 1 && $mallSno > 1) {
                        echo gd_select_box('centerFaxHead', 'centerFaxHead', $countryAddress, null, $data['centerFaxHead'], '==선택==', null, 'form-control');
                    }
                ?>
                <input type="text" name="centerFax" value="<?php echo $data['centerFax']; ?>" maxlength="15" class="form-control js-number-only width-md"/>
            </td>
        </tr>
        <tr>
            <th>이메일</th>
            <td colspan="3" class="form-inline">
                <input type="text" name="centerEmail[]" value="<?php echo $data['centerEmail'][0]; ?>" class="form-control width-lg"/> @
                <input type="text" id="centerEmail" name="centerEmail[]" value="<?php echo $data['centerEmail'][1]; ?>" class="form-control width-md js-email-domain"/>
                <?php echo gd_select_box('center_email_domain', null, $emailDomain, null, $data['centerEmail'][1],null,null,'email_domain'); ?>
            </td>
        </tr>
        <tr>
            <th>운영시간</th>
            <td colspan="3" class="form-inline">
                <textarea name="centerHours" rows="4" class="form-control width-2xl"><?php echo $data['centerHours']; ?></textarea>
            </td>
        </tr>
    </table>

    <div class="table-title gd-help-manual">
        회사소개 내용 수정
    </div>
    <table class="table table-cols">
        <colgroup>
            <col class="width-md"/>
            <col/>
        </colgroup>
        <tr>
            <th>회사소개 내용</th>
            <td class="form-inline">
                <textarea name="company" id="editor" class="form-control width100p height400"><?php echo $data['company']; ?></textarea>
            </td>
        </tr>
    </table>

    <div class="table-title gd-help-manual">
        정보 검색 최적화 설정
    </div>
    <table class="table table-cols">
        <colgroup>
            <col class="width-md"/>
            <col/>
        </colgroup>
        <?php if ($mallInputDisp === false) { ?>
        <tr>
            <th>검색로봇 정보수집<br/>허용설정</th>
            <td colspan="3" class="form-inline">
                <label class="radio-inline">
                    <input type="radio" name="robotsFl" value="y" onclick="display_toggle('robotsTxt', 'y');"  <?php echo gd_isset($checked['robotsFl']['y']); ?> />로봇배제 설정
                </label>
                <label class="radio-inline">
                    <input type="radio" name="robotsFl" value="n" onclick="display_toggle('robotsTxt', 'n');"  <?php echo gd_isset($checked['robotsFl']['n']); ?> />검색로봇 정보수집 허용안함
                </label>

                <div id="robotsTxt_y" class="display-none">
                    <ul class="nav nav-tabs mgt20">
                        <li role="presentation" class="active">
                            <a href="#front" aria-controls="robot-content-front" role="tab" data-toggle="tab">PC 쇼핑몰</a>
                        </li>
                        <li role="presentation" class="">
                            <a href="#mobile" aria-controls="robot-content-mobile" role="tab" data-toggle="tab">모바일 쇼핑몰</a>
                        </li>
                    </ul>
                    <table class="table table-cols" style="border-top-color: #e6e6e6; margin-top: -10px;">
                        <colgroup>
                            <col class="width-md"/>
                            <col/>
                        </colgroup>
                        <tr>
                            <th>검색로봇 접근제어<br/>상세설정<br/>(robots.txt)</th>
                            <td colspan="3" class="form-inline">

                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane fade in active" id="front">
                                        <textarea name="robotsTxt[front]" rows="13" class="form-control width-3xl"><?php echo gd_isset($data['robotsTxt']['front']); ?></textarea>
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="mobile">
                                        <textarea name="robotsTxt[mobile]" rows="13" class="form-control width-3xl"><?php echo gd_isset($data['robotsTxt']['mobile']); ?></textarea>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <?php } ?>
        <tr>
            <th>메타태그 설명<br/>(Description)</th>
            <td colspan="3" class="form-inline">
                <input type="text" name="mallDescription" value="<?php echo $data['mallDescription']; ?>" class="form-control width100p"/>
                <p class="notice-info">
                    오픈그래프/트위터의 메타태그 설명은 <a href="../promotion/sns_share_config.php" class="btn-link" target="_blank">"프로모션>SNS서비스 관리>SNS공유하기 설정"</a>의 대표설명 항목에 등록된 정보가 적용됩니다.
                </p>
            </td>
        </tr>
        <tr>
            <th>메타태그 키워드<br/>(Keyword)</th>
            <td colspan="3" class="form-inline">
                <input type="text" name="mallKeyword" value="<?php echo $data['mallKeyword']; ?>" class="form-control width100p"/>
            </td>
        </tr>
        <?php if ($mallInputDisp === false) { ?>
        <tr>
            <th>사이트맵 등록<br/>(sitemap.xml)</th>
            <td colspan="3" class="form-inline">
                <input type="file" name="sitemap[front]" class="form-control width70p"/>
                <span>
<?php
if (empty($data['sitemap']['front']) === false) {
    echo '<span>sitemap.xml</span>';
    echo '<label class="checkbox-inline" style="padding-left:10px"><input type="checkbox" name="sitemapDel[front]" value="y" />삭제</label>';
}
?>
				</span>
                <div class="notice-info">확장자가 .xml 인 파일만 등록 가능하며, 업로드 가능한 파일 크기는 최대 <?php echo ini_get('upload_max_filesize');?>입니다.</div>
                <div class="notice-info">등록한 파일 경로는 <?php if (empty($data['mallDomain']) === false) { echo 'http://' . $data['mallDomain']; } else { echo '(쇼핑몰주소)';}?>/sitemap.xml 입니다.</div>
            </td>
        </tr>
        <?php } ?>
    </table>
</form>

<script type="text/javascript" src="<?= PATH_ADMIN_GD_SHARE ?>script/smart/js/HuskyEZCreator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?= PATH_ADMIN_GD_SHARE ?>script/smart/js/editorLoad.js" charset="utf-8"></script>
<script type="text/javascript">
    <!--
    $(document).ready(function () {
        // 쇼핑몰 기본 정보 저장
        $("#frmBase").validate({
            submitHandler: function (form) {
                oEditors.getById["editor"].exec("UPDATE_CONTENTS_FIELD", []);	// 에디터의 내용이 textarea에 적용됩니다.
                form.target = 'ifrmProcess';
                form.submit();
            },
            rules: {
                'mallDomain': "required",
                'email[]': "required",
            },
            messages: {
                'mallDomain': {
                    required: '쇼핑몰 도메인을 입력해 주세요.'
                },
                'email[]': {
                    required: '대표 이메일을 입력해 주세요.'
                }
            }
        });

        display_toggle('unstoringFl','<?php echo gd_isset($data['unstoringFl']);?>');
        display_toggle('returnFl','<?php echo gd_isset($data['returnFl']);?>');
        display_toggle('robotsTxt','<?php echo gd_isset($data['robotsFl']);?>');

        $('.email_domain').change(function () {
            var val =  $(this).val() == 'self' ? '' :  $(this).val();
            $(this).closest('td').find('.js-email-domain').val(val);
        });

        $(".btnStampDelete").click(function() {
            if (confirm('등록한 인감이미지를 삭제하시겠습니까?') == false){
                $("input:checkbox[name='stampImageDel']").prop("checked",false);
                return false;
            } else {
                $("input:checkbox[name='stampImageDel']").prop("checked",true);
                $("frmBase").submit();
            }
        })
    });

    /**
     * 대표 카테고리
     */
    function mall_categoty() {
        var loadChk = $('#layerCategotyForm').length;
        $.post('./layer_mall_categoty.php', '', function (data) {
            if (loadChk == 0) {
                data = '<div id="layerCategotyForm">' + data + '</div>';
            }
            var layerForm = data;
            layer_popup(layerForm, '대표카테고리 등록');
        });
    }

    /**
     * 출력 여부
     *
     * @param string thisIdKey 해당 ID Key
     */
    function display_toggle(thisId, thisIdKey) {
        $('div[id*=\''+thisId+'_\']').attr('class','display-none');
        $('#'+thisId+'_'+thisIdKey).attr('class','display-block');
    }
    //-->
</script>
