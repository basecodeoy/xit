<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace BaseCodeOy\Xit\Renderer;

use BaseCodeOy\Xit\Element\ElementInterface;
use BaseCodeOy\Xit\Element\Tailwind\Checked;
use BaseCodeOy\Xit\Element\Tailwind\Due;
use BaseCodeOy\Xit\Element\Tailwind\InQuestion;
use BaseCodeOy\Xit\Element\Tailwind\Obsolete;
use BaseCodeOy\Xit\Element\Tailwind\Ongoing;
use BaseCodeOy\Xit\Element\Tailwind\Open;
use BaseCodeOy\Xit\Element\Tailwind\Priority;
use BaseCodeOy\Xit\Element\Tailwind\Tag;
use BaseCodeOy\Xit\Element\Tailwind\Title;

final readonly class TailwindRenderer extends AbstractHtmlRenderer
{
    protected function createCheckedElement(string $text): ElementInterface
    {
        return Checked::fromString($text);
    }

    protected function createDueElement(string $text): ElementInterface
    {
        return Due::fromString($text);
    }

    protected function createInQuestionElement(string $text): ElementInterface
    {
        return InQuestion::fromString($text);
    }

    protected function createObsoleteElement(string $text): ElementInterface
    {
        return Obsolete::fromString($text);
    }

    protected function createOngoingElement(string $text): ElementInterface
    {
        return Ongoing::fromString($text);
    }

    protected function createOpenElement(string $text): ElementInterface
    {
        return Open::fromString($text);
    }

    protected function createPriorityElement(string $text): ElementInterface
    {
        return Priority::fromString($text);
    }

    protected function createTagElement(string $text): ElementInterface
    {
        return Tag::fromString($text);
    }

    protected function createTitleElement(string $text): ElementInterface
    {
        return Title::fromString($text);
    }
}
