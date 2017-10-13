<table class="table table-cols">
    <tbody>
    <tr>
        <th class="width-sm">배송비 유형</th>
        <td><?=$fixText?></td>
    </tr>
    <?php if (!$isProvider) { ?>
    <tr>
        <th class="width-sm">부가세율</th>
        <td><?=$taxFreeStr?></td>
    </tr>
    <?php } ?>
    </tbody>
</table>

<table class="table table-rows">
    <thead>
    <tr>
        <th>번호</th>
        <th>조건</th>
        <th>배송비</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $sortNo = 1;
    foreach($data as $key => $val) {
        ?>
        <tr class="text-center">
            <td><?=$sortNo++?></td>
        <td><?=$val['condition']?></td>
        <td><?=gd_money_format($val['price'])?>원</td>
    </tr>
    <?php } ?>
    </tbody>
</table>
