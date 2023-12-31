<?php
defined('JPATH_BASE') or die;

$d = $displayData;
$tip = $d->tipText;

if ($d->tipTitle !== '')
{
    switch ($d->tipOpts->position)
    {
        default;
        case 'top-left':
            $placement = 'left';
            break;
        case 'top-right':
        case 'top-left':
        case 'top':
            $placement = 'top';
            break;
        case 'right':
        case 'bottom-right':
            $placement = 'right';
            break;
        case 'bottom':
        case 'bottom-left':
            $placement = 'bottom';
            break;

    }
    $heading = isset($d->tipOpts->heading) ? $d->tipOpts->heading : '';
    $tip = ' data-bs-toggle="popover" data-bs-trigger="' . $d->tipOpts->trigger . '"  data-bs-placement="' . $placement . '" data-title="' . $heading . '" data-bs-content="' . $d->tipTitle . '"';
    $tip = '<span class="fabrikTip" ' . $tip . '>' . $d->tipText . '</span>';

}

echo $tip;
