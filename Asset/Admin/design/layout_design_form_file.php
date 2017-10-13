<div class="table-title gd-help-manual">
    디자인 페이지 설정
</div>
<div>
    <table class="table table-cols">
        <colgroup>
            <col class="width-md" />
            <col class="width40p"/>
            <col class="width-md" />
            <col />
        </colgroup>
        <tr>
            <th>파일설명</th>
            <td <?php if ($skinType !== 'front') { echo 'colspan="3"'; } ?>>
                <div class="form-inline">
                    <input type="text" name="text" value="<?php echo $designInfo['file']['text'];?>" maxlength="30" class="form-control js-maxlength width90p" />
                </div>
            </td>
            <?php if ($skinType === 'front') { ?>
            <th>측면디자인 영역 위치</th>
            <td>
                <div class="form-inline">
                    <?php foreach ($designInfo['sidefloat'] as $opt){ ?>
                    <label class="radio-inline"><input type="radio" name="outline_sidefloat" id="outline_sidefloat" onclick="DCMAPM.file_float(this)" value="<?php echo gd_isset($opt['value']);?>" <?php echo gd_isset($opt['checked']);?> float="<?php echo gd_isset($opt['float']);?>" /> <?php echo gd_isset($opt['text']);?></label>
                    <?php } ?>
                </div>
            </td>
            <?php } ?>
        </tr>
        <tr>
            <th>현재위치</th>
            <td colspan="3">
                <div class="form-inline">
                    <label class="radio-inline"><input type="radio" name="current_page" value="y" <?php if (gd_isset($designInfo['file']['current_page'], 'n') === 'y') { echo 'checked="checked"'; }?> /> 출력</label>
                    <label class="radio-inline"><input type="radio" name="current_page" value="n" <?php if ($designInfo['file']['current_page'] === 'n') { echo 'checked="checked"'; }?> /> 미출력</label>
                </div>
            </td>
        </tr>
        <tr>
            <th>전체색상</th>
            <td>
                <div class="form-inline">
                    <label for="colorSelector1">전체 배경색상 &nbsp; &nbsp; </label>
                    <input type="text" name="outbg_color" id="colorSelector1" value="<?php echo gd_isset($designInfo['file']['outbg_color']);?>" readonly maxlength="7" class="form-control width-xs center color-selector" />
                </div>
                <div class="form-inline mgt10">
                    <label for="outbg_img_up" >전체 배경이미지</label>
                    <input type="file" name="outbg_img_up" id="outbg_img_up" class="form-control width-xl" />
                    <input type="hidden" name="outbg_img" value="<?php echo gd_isset($designInfo['file']['outbg_img']);?>" />
                    <?php if (empty($designInfo['file']['outbg_img']) === false) {?>
                    <input type="button" onclick="image_viewer('<?php echo UserFilePath::frontSkin(Globals::get('gSkin.frontSkinWork'), 'img', 'codi', $designInfo['file']['outbg_img'])->www();?>');" value="VIEW" class="btn btn-success btn-xs" />
                    <label><input type="checkbox" name="outbg_img_del" value="Y"><span class="text-red">삭제</span></label>
                    <?php }?>
                </div>
            </td>
            <th>본문색상</th>
            <td>
                <div class="form-inline">
                    <label for="colorSelector2">배경색상 &nbsp; &nbsp; </label>
                    <input type="text" name="inbg_color" id="colorSelector2" value="<?php echo gd_isset($designInfo['file']['inbg_color']);?>" readonly maxlength="7" class="form-control width-xs center color-selector" />
                </div>
                <div class="form-inline mgt10">
                    <label for="inbg_img_up" >배경이미지</label>
                    <input type="file" name="inbg_img_up" id="inbg_img_up" class="form-control width-xl" />
                    <input type="hidden" name="inbg_img" value="<?php echo gd_isset($designInfo['file']['inbg_img']);?>" />
                    <?php if (empty($designInfo['file']['inbg_img']) === false) {?>
                    <input type="button" onclick="image_viewer('<?php echo UserFilePath::frontSkin(Globals::get('gSkin.frontSkinWork'), 'img', 'codi', $designInfo['file']['inbg_img'])->www();?>');" value="VIEW" class="btn btn-success btn-xs" />
                    <label><input type="checkbox" name="inbg_img_del" value="Y"><span class="text-red">삭제</span></label>
                    <?php }?>
                </div>
            </td>
        </tr>
    </table>
</div>
