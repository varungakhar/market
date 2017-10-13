<form id="frm" name="frm" method="post" target="ifrmProcess" action="sns_share_ps.php" enctype="multipart/form-data">
    <input type="hidden" name="mode" value="config">
    <input type="hidden" name="snsRepresentImageTmp" value="<?php echo $data['snsRepresentImage']; ?>"/>

    <div class="page-header js-affix">
        <h3><?= end($naviMenu->location); ?></h3>
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-gray js-code-view">치환코드 보기</button>
            <input type="submit" value="저장" class="btn btn-red"/>
        </div>
    </div>

    <h4 class="table-title gd-help-manual">기본설정</h4>
    <table class="table table-cols mgb10">
        <colgroup>
            <col class="width-md"/>
            <col/>
        </colgroup>
        <tr>
            <th>대표이미지</th>
            <td class="form-inline">
                <input type="file" name="snsRepresentImage" class="form-control width70p"/>
                <span class="small-image">
<?php
if (empty($data['snsRepresentImage']) === false) {
    echo gd_html_image(UserFilePath::data('common', $data['snsRepresentImage'])->www(), '대표이미지');
    echo '<label class="checkbox-inline" style="padding-left:10px"><input type="checkbox" name="snsRepresentImageDel" value="y" />삭제</label>';
}
?>
				</span>
                <ul class="notice-info">
                    <li>대표 이미지 사이즈는 최소 600pixel(픽셀) 이상, 파일형식은 jpg, gif, png만 등록해 주세요.</li>
                    <li>페이스북에서 권장하는 미리보기 이미지 사이즈는 1200x627px이며 최소 권장 사이즈는 PC에서 400x209px, 모바일에서 560x292px 입니다.</li>
                </ul>
            </td>
        </tr>
        <tr>
            <th>대표제목</th>
            <td>
                <h4><?=$snsShareDefaultTitle?></h4>
                <p class="notice-info">
                    오픈그래프/트위터의 메타태그 제목은 <a href="../policy/base_info.php" class="btn-link" target="_blank">"기본설정>상단타이틀"</a>에 등록된 정보가 사용됩니다.
                </p>
            </td>
        </tr>
        <tr>
            <th>대표설명</th>
            <td>
                <textarea name="snsRepresentDescription" class="form-control"><?=$data['snsRepresentDescription']?></textarea>
                <p class="notice-info">
                    오픈그래프/트위터의 메타태그 설명으로 사용되며, 기본설정의 메타태그 설명과는 별개로 동작합니다.
                </p>
            </td>
        </tr>
    </table>
    <div class="notice-info">
        쇼핑몰 URL을 SNS로 전송시 대표이미지와 쇼핑몰 소개 내용을 설정할 수 있습니다.<br>
        쇼핑몰 상품상세페이지에서 상품정보 SNS공유 시에는 적용되지 않습니다.<br>
        대표이미지와 대표설명을 설정하지 않는 경우 소셜 정책에 따라 임의의 정보가 노출됩니다.
    </div>
    <div class="notice-info mgb20">
        썸네일 이미지 or 텍스트가 변경되지 않으신다면 기존이미지가 캐시로 적용되어 있을 수 있으니<br>
        <a href="https://developers.facebook.com/tools/debug/" class="btn-link" target="_blank">오픈그래프 개체 디버거</a>에 가셔서 "새로운 스크랩 정보 가져오기" 버튼을 눌러 해당 URL을 새롭게 갱신해주세요!
    </div>

    <h4 class="table-title gd-help-manual">사용설정</h4>
    <table class="table table-cols">
        <colgroup>
            <col class="width-md"/>
            <col/>
        </colgroup>
        <tr>
            <th>SNS 공유하기<br>사용설정</th>
            <td>
                <label class="radio-inline">
                    <input type="radio" name="useSnsShare" value="y" <?= ($data['useSnsShare'] == 'y') ? 'checked' : ''; ?>> 사용함
                </label>
                <label class="radio-inline">
                    <input type="radio" name="useSnsShare" value="n" <?= ($data['useSnsShare'] == 'n') ? 'checked' : ''; ?>> 사용안함
                </label>
            </td>
        </tr>
    </table>

    <h4 class="table-title gd-help-manual">카카오링크 사용 설정</h4>
    <table class="table table-cols">
        <colgroup>
            <col class="width-md"/>
            <col/>
        </colgroup>
        <tr>
            <th>앱키 (Javascript 키)</th>
            <td class="form-inline">
                <input type="text" class="form-control" name="kakaoAppKey" value="<?= $data['kakaoAppKey'] ?>" maxlength="32" minlength="32"/>&nbsp;&nbsp;
                <span class="notice-info"><a href="http://developers.kakao.com" target="_blank" class="btn-link">developers.kakao.com</a> 사이트에서 앱 키를 확인 후 입력하세요.</span>
            </td>
        </tr>
        <tr>
            <th>
                카카오톡 링크<br>공유하기 사용설정
            </th>
            <td class="form-inline">
                <label class="radio-inline">
                    <input type="radio" name="useKakaoLink" value="y" <?= ($data['useKakaoLink'] == 'y') ? 'checked' : ''; ?>> 사용함
                </label>
                <label class="radio-inline">
                    <input type="radio" name="useKakaoLink" value="n" <?= ($data['useKakaoLink'] == 'n') ? 'checked' : ''; ?>> 사용안함
                </label>
                <p class="notice-info mgt5">카카오톡 공유하기 기능은 모바일 쇼핑몰에서만 지원됩니다.
                    <a href="../mobile/mobile_config.php" target="_blank" class="btn-link">모바일샵 설정</a></p>
            </td>
        </tr>
        <tr>
            <th>
                카카오톡 링크<br>공유문구 설정<br>
            </th>
            <td>
                <table class="table table-cols mgb0">
                    <colgroup>
                        <col class="width-md"/>
                        <col/>
                    </colgroup>
                    <tbody>
                    <tr>
                        <th>라벨텍스트</th>
                        <td>
                            <textarea type="text" class="form-control" name="kakaoLinkTitle" placeholder="{<?=$replaceKey['goodsNm']?>}"><?= $data['kakaoLinkTitle'] ?></textarea>
                            <p class="notice-info">
                                치환코드로 노출되는 쇼핑몰명, 상품명 등의 데이터를 포함하여 최대 1,000까지 등록할 수 있습니다.<br>
                                1,000자 이상 입력되는 경우 상품명 치환코드 사용시 상품명을 줄여 등록하거니 내용 뒷부분이 삭제됩니다.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th>버튼텍스트</th>
                        <td>
                            <input type="text" class="form-control" name="kakaoLinkButtonTitle" placeholder="{<?=$replaceKey['mallNm']?>} 바로가기" value="<?= $data['kakaoLinkButtonTitle'] ?>"/>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding:5px;height:10px;" colspan="5"></td>
        </tr>
        <tr>
            <th>
                카카오스토리<br>공유하기 사용설정
            </th>
            <td class="form-inline">
                <label class="radio-inline">
                    <input type="radio" name="useKakaoStory" value="y" <?= ($data['useKakaoStory'] == 'y') ? 'checked' : ''; ?>> 사용함
                </label>
                <label class="radio-inline">
                    <input type="radio" name="useKakaoStory" value="n" <?= ($data['useKakaoStory'] == 'n') ? 'checked' : ''; ?>> 사용안함
                </label>
            </td>
        </tr>
        <tr>
            <th>
                카카오스토리<br>공유문구 설정<br>
                <button type="button"  class="btn btn-sm btn-gray js-code-view mgt5">치환코드 보기</button>
            </th>
            <td>
                <table class="table table-cols mgb0">
                    <colgroup>
                        <col class="width-md"/>
                        <col/>
                    </colgroup>
                    <tbody>
                    <tr>
                        <th>기본전송메시지</th>
                        <td>
                            <textarea name="kakaoStoryText" class="form-control"><?=$data['kakaoStoryText']?></textarea>
                            <p class="notice-info">
                                치환코드로 노출되는 쇼핑몰명, 상품명 등의 데이터를 포함하여 최대 1,000까지 등록할 수 있습니다.<br>
                                1,000자 이상 입력되는 경우 상품명 치환코드 사용시 상품명을 줄여 등록하거니 내용 뒷부분이 삭제됩니다.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th>링크타이틀</th>
                        <td>
                            <input type="text" name="kakaoStoryTitle" class="form-control" placeholder="{<?=$replaceKey['goodsNm']?>}" value="<?= $data['kakaoStoryTitle'] ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <th>링크요약</th>
                        <td>
                            <input type="text" name="kakaoStoryDesc"class="form-control" placeholder="{<?=$replaceKey['mallNm']?>}에서 자세한 정보를 확인하세요." value="<?= $data['kakaoStoryDesc'] ?>"/>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    <h4 class="table-title gd-help-manual">페이스북 사용 설정</h4>
    <table class="table table-cols">
        <colgroup>
            <col class="width-md"/>
            <col/>
        </colgroup>
        <tr>
            <th>
                페이스북 공유하기<br>사용설정
            </th>
            <td class="form-inline">
                <label class="radio-inline">
                    <input type="radio" name="useFacebook" value="y" <?= ($data['useFacebook'] == 'y') ? 'checked' : ''; ?>> 사용함
                </label>
                <label class="radio-inline">
                    <input type="radio" name="useFacebook" value="n" <?= ($data['useFacebook'] == 'n') ? 'checked' : ''; ?>> 사용안함
                </label>
            </td>
        </tr>
        <tr>
            <th>
                공유문구 설정<br>
            </th>
            <td>
                <table class="table table-cols">
                    <colgroup>
                        <col class="width-md"/>
                        <col/>
                    </colgroup>
                    <tbody>
                    <tr>
                        <th>링크타이틀</th>
                        <td>
                            <input type="text" name="facebookTitle" class="form-control" placeholder="{<?=$replaceKey['goodsNm']?>}" value="<?= $data['facebookTitle'] ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <th>링크요약</th>
                        <td>
                            <input type="text" name="facebookDesc" class="form-control" placeholder="{<?=$replaceKey['mallNm']?>}에서 자세한 정보를 확인하세요." value="<?= $data['facebookDesc'] ?>"/>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    <h4 class="table-title gd-help-manual">트위터 사용 설정</h4>
    <table class="table table-cols">
        <colgroup>
            <col class="width-md"/>
            <col/>
        </colgroup>
        <tr>
            <th>
                트위터 공유하기<br>사용설정
            </th>
            <td class="form-inline">
                <label class="radio-inline">
                    <input type="radio" name="useTwitter" value="y" <?php echo ($data['useTwitter'] == 'y') ? 'checked' : ''; ?>> 사용함
                </label>
                <label class="radio-inline">
                    <input type="radio" name="useTwitter" value="n" <?php echo ($data['useTwitter'] == 'n') ? 'checked' : ''; ?>> 사용안함
                </label>
            </td>
        </tr>
        <tr>
            <th>
                공유문구 설정<br>
            </th>
            <td>
                <input type="text" name="twitterTitle" class="form-control" placeholder="{<?=$replaceKey['goodsNm']?>}" value="<?= $data['twitterTitle'] ?>"/>
                <p class="notice-info">
                    치환코드로 노출되는 쇼핑몰명, 상품명 등의 데이터를 포함하여 최대 140자까지 등록할 수 있습니다.<br> 140자 이상 입력되는 경우 상품명 치환코드 사용시 상품명을 줄여 등록하거니 내용 뒷부분이 삭제됩니다.
                </p>
            </td>
        </tr>
    </table>

    <h4 class="table-title gd-help-manual">핀터레스트 사용 설정</h4>
    <table class="table table-cols">
        <colgroup>
            <col class="width-md"/>
            <col/>
        </colgroup>
        <tr>
            <th>
                핀터레스트 공유하기<br>사용설정
            </th>
            <td class="form-inline">
                <label class="radio-inline">
                    <input type="radio" name="usePinterest" value="y" <?php echo ($data['usePinterest'] == 'y') ? 'checked' : ''; ?>> 사용함
                </label>
                <label class="radio-inline">
                    <input type="radio" name="usePinterest" value="n" <?php echo ($data['usePinterest'] == 'n') ? 'checked' : ''; ?>> 사용안함
                </label>
            </td>
        </tr>
        <tr>
            <th>
                공유문구 설정<br>
            </th>
            <td>
                <input type="text" name="pinterestTitle" class="form-control" placeholder="[{<?=$replaceKey['mallNm']?>}] {<?=$replaceKey['goodsNm']?>}" value="<?= $data['pinterestTitle'] ?>"/>
            </td>
        </tr>
    </table>

    <h4 class="table-title gd-help-manual">상품URL 복사 사용 설정</h4>
    <table class="table table-cols">
        <colgroup>
            <col class="width-md"/>
            <col/>
        </colgroup>
        <tr>
            <th>
                상품URL 복사<br>사용설정
            </th>
            <td class="form-inline">
                <label class="radio-inline">
                    <input type="radio" name="useCopy" value="y" <?php echo ($data['useCopy'] == 'y') ? 'checked' : ''; ?>> 사용함
                </label>
                <label class="radio-inline">
                    <input type="radio" name="useCopy" value="n" <?php echo ($data['useCopy'] == 'n') ? 'checked' : ''; ?>> 사용안함
                </label>
            </td>
        </tr>
    </table>
</form>
<script type="text/template" id="codeLayer">
    <table class="table table-rows">
        <thead>
        <tr>
            <th>번호</th>
            <th>치환코드</th>
            <th>설명</th>
        </tr>
        </thead>
        <tbody>
        <tr class="text-center">
            <td>3</td>
            <td>{<%=rc_mallNm%>}</td>
            <td>쇼핑몰명</td>
        </tr>
        <tr class="text-center">
            <td>2</td>
            <td>{<%=rc_goodsNm%>}</td>
            <td>상품명</td>
        </tr>
        <tr class="text-center">
            <td>1</td>
            <td>{<%=rc_brandNm%>}</td>
            <td>브랜드명</td>
        </tr>
        </tbody>
    </table>
    <div class="table-btn">
        <button type="button" class="btn btn-lg btn-white js-layer-close">닫기</button>
    </div>
</script>
<script type="text/javascript">
    <!--
    $(document).ready(function () {
        // 저장 폼 체크
        $('#frm').validate({
            submitHandler: function (form) {
//                var params = $(form).serializeArray();
                form.target = 'ifrmProcess';
                form.submit();
            },
            rules: {
                useSnsShare: {
                    required: true
                },
                useKakaoAppKey: {
                    required: function() {
                        return true;
                    },
                    length: 32
                },
                useKakaoLink: {
                    required: true
                },
                useKakaoStory: {
                    required: true
                },
                useFacebook: {
                    required: true
                },
                useTwitter: {
                    required: true
                },
                usePinterest: {
                    required: true
                },
                useCopy: {
                    required: true
                },
            },
            message: {
                useSnsShare: {
                    required: 'SNS 공유하기 사용설정은 필수 입력값입니다.'
                },
                useKakaoAppKey: {
                    required: '카카오 앱키를 반드시 입력해주세요.',
                    length: 32
                },
                useKakaoLink: {
                    required: '카카오톡 링크 공유하기 사용설정을 해주세요.'
                },
                useKakaoStory: {
                    required: '카카오스토리 공유하기 사용설정을 해주세요.'
                },
                useFacebook: {
                    required: '페이스북 공유하기 사용설정을 해주세요.'
                },
                useTwitter: {
                    required: '트위터 공유하기 사용설정을 해주세요.'
                },
                usePinterest: {
                    required: '핀터레스트 공유하기 사용설정을 해주세요.'
                },
                useCopy: {
                    required: '상품 URL 복사 사용설정을 해주세요.'
                },
            }
        });

        // 치환코드 보기
        var compiledTempate = _.template($('#codeLayer').html())
        var replaceText = {
            rc_mallNm: 'rc_mallNm',
            rc_goodsNm: 'rc_goodsNm',
            rc_brandNm: 'rc_brandNm',
        };
        $('.js-code-view').click(function(e){
            BootstrapDialog.show({
                nl2br: false,
                type: BootstrapDialog.TYPE_PRIMARY,
                title: '치환코드 보기',
                message: compiledTempate(replaceText)
            });
        });
    });
    // -->
</script>
