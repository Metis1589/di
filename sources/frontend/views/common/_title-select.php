<?php
/**
 * @author jarik <jarik1112@gmail.com>
 * @date 5/29/15
 * @time 8:27 PM
 */
use frontend\components\language\T;
$ngModel = empty($ngModel) ? 'title' : $ngModel;
$name = empty($name) ? 'title' : $name;
$class = empty($class) ? '' : $class;
$disabled = empty($disabled) ? '' : $disabled;
?>
<dinein-select
    class="registration_select checkout_info_hide slset <?=$class?>"
    ng-model="<?=$ngModel?>"
    placeholder="'<?= T::l('TITLE') ?>*'"
    items="{
            '<?=T::l('Miss.');?>':'<?=T::l('Miss.');?>',
            '<?=T::l('Ms.');?>':'<?=T::l('Ms.');?>',
            '<?=T::l('Mrs.');?>':'<?=T::l('Mrs.');?>',
            '<?=T::l('Mr.');?>':'<?=T::l('Mr.');?>'
        }"
    name="<?=$name?>"
    disabled="<?=$disabled?>"
    required
    err-required="<?= T::l('Title is missing')?>"></dinein-select>