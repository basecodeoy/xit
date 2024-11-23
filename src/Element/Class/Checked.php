<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace BaseCodeOy\Xit\Element\Class;

use BaseCodeOy\Xit\Element\AbstractElement;
use BaseCodeOy\Xit\Enum\ItemStatusCharacter;

final class Checked extends AbstractElement
{
    public function getHtml(): string
    {
        return '<div class="item checked"><span class="checkbox">['.ItemStatusCharacter::Checked->value.']</span><p>%s</p></div>';
    }
}
