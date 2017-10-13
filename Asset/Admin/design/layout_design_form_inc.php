<?php
// 현재 화일의 확장자 체크 후 출력 여부 체크
$ext = explode('.', trim($designInfo['file']['name']));
$tmp = array_pop($ext);
?>
<?php if (in_array($tmp, ['js', 'css']) === false) {?>
<div class="table-title gd-help-manual">
    파일 설명
</div>
<div>
    <table class="table table-cols">
        <colgroup>
            <col class="width-md"/>
            <col/>
        </colgroup>
        <tr>
            <th class="require">파일설명</th>
            <td>
                <div class="form-inline">
                    <input type="text" name="text" value="<?php echo $designInfo['file']['text'];?>" maxlength="30" class="form-control js-maxlength width-2xl" />
                </div>
            </td>
        </tr>
    </table>
</div>
<?php }?>
