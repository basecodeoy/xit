<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace BaseCodeOy\Xit\Data;

use Illuminate\Contracts\Support\Arrayable;

final class Document implements \JsonSerializable, Arrayable
{
    /**
     * @param array<DocumentGroup> $groups
     */
    public function __construct(
        private string $content,
        private readonly array $groups,
    ) {}

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return array<DocumentGroup>
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    #[\Override()]
    public function toArray(): array
    {
        return [
            'content' => $this->content,
            'groups' => $this->groups,
        ];
    }

    #[\Override()]
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
