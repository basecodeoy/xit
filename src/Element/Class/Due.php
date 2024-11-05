<?php

declare(strict_types=1);

namespace BaseCodeOy\Xit\Element\Class;

use BaseCodeOy\Xit\Element\AbstractElement;

final class Due extends AbstractElement
{
    public function getHtml(): string
    {
        return '<span class="due">-> %s</span>';
    }
}
