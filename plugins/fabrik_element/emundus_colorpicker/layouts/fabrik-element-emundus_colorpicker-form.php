<?php
defined('JPATH_BASE') or die;

use Joomla\CMS\Language\Text;

$d = $displayData;
?>

<div id="<?php echo $displayData->attributes['name']; ?>___map_container" class="fabrikSubElementContainer fabrikEmundusColorpicker">
</div>

<input type="text"
    <?php foreach ($displayData->attributes as $key => $value) :
        echo $key . '="' . $value . '" ';
    endforeach;
    ?> />