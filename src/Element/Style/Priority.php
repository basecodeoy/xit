<?php

declare(strict_types=1);

namespace BaseCodeOy\Xit\Element\Style;

use BaseCodeOy\Xit\Element\AbstractElement;
use BaseCodeOy\Xit\Enum\Color;

final class Priority extends AbstractElement
{
    public function getHtml(): string
    {
        return '<span style="color: '.Color::Priority->value.';">%s</span>';
    }
}
