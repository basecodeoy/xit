<?php

declare(strict_types=1);

namespace BombenProdukt\Xit\Element\Tailwind;

final class Priority extends AbstractElement
{
    public function getHtml(): string
    {
        return '<span class="text-xit-priority">%s</span>';
    }
}