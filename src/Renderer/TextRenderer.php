<?php

declare(strict_types=1);

namespace BaseCodeOy\Xit\Renderer;

use BaseCodeOy\Xit\Data\Document;
use BaseCodeOy\Xit\Enum\ItemStatus;
use BaseCodeOy\Xit\Enum\ItemStatusCharacter;
use BaseCodeOy\Xit\Enum\ItemType;

final readonly class TextRenderer implements RendererInterface
{
    public function render(Document $document): string
    {
        $rendered = '';

        foreach ($document->getGroups() as $group) {
            foreach ($group->getItems() as $line) {
                if ($line->getType() === ItemType::GroupTitle) {
                    $rendered .= $line->getContent()."\n";

                    continue;
                }

                if ($line->getType() === ItemType::ItemStart || ItemType::ItemContinuation) {
                    switch ($line->getStatus()) {
                        case ItemStatus::Open:
                            $rendered .= \sprintf('[%s] ', ItemStatusCharacter::Open->value);

                            break;

                        case ItemStatus::Checked:
                            $rendered .= \sprintf('[%s] ', ItemStatusCharacter::Checked->value);

                            break;

                        case ItemStatus::Ongoing:
                            $rendered .= \sprintf('[%s] ', ItemStatusCharacter::Ongoing->value);

                            break;

                        case ItemStatus::Obsolete:
                            $rendered .= \sprintf('[%s] ', ItemStatusCharacter::Obsolete->value);

                            break;

                        case ItemStatus::InQuestion:
                            $rendered .= \sprintf('[%s] ', ItemStatusCharacter::InQuestion->value);

                            break;
                    }

                    if ($line->getModifiers()->getHasPriority()) {
                        for ($priorityPadding = 1; $priorityPadding <= $line->getModifiers()->getPriorityPadding(); $priorityPadding++) {
                            $rendered .= '.';
                        }

                        for ($priorityLevel = 1; $priorityLevel <= $line->getModifiers()->getPriorityLevel(); $priorityLevel++) {
                            $rendered .= '!';
                        }

                        $rendered .= ' ';
                    }

                    $rendered .= $line->getContent().' ';

                    if (\count($line->getModifiers()->getTags())) {
                        $rendered .= \implode(' ', $line->getModifiers()->getTags()).' ';
                    }

                    if ($line->getModifiers()->getDue() !== null) {
                        $rendered .= '-> '.$line->getModifiers()->getDue();
                    }

                    $rendered .= '\n';
                }
            }

            $rendered .= '\n';
        }

        return \trim($rendered).'\n';
    }
}
