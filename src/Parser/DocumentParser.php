<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace BaseCodeOy\Xit\Parser;

use BaseCodeOy\Xit\Data\Document;
use BaseCodeOy\Xit\Data\DocumentGroup;
use BaseCodeOy\Xit\Data\DocumentItem;
use BaseCodeOy\Xit\Enum\ItemStatus;
use BaseCodeOy\Xit\Enum\ItemType;
use BaseCodeOy\Xit\RegularExpression;

final readonly class DocumentParser
{
    private ModifierParser $modifierParser;

    public function __construct()
    {
        $this->modifierParser = new ModifierParser();
    }

    public function parse(string $content): Document
    {
        $groups = [];

        $prevItemType = null;

        $currentGroup = new DocumentGroup();
        $groups[] = $currentGroup;

        foreach (\explode("\n", $content) as $lineNumber => $line) {
            if (\preg_match(RegularExpression::TITLE, $line)) {
                $this->addDocumentItem($currentGroup, ItemType::GroupTitle, null, $line);

                $prevItemType = ItemType::GroupTitle;

                continue;
            }

            if (\preg_match(RegularExpression::OPEN_ITEM, $line)) {
                $this->addDocumentItem($currentGroup, ItemType::ItemStart, ItemStatus::Open, $line);

                $prevItemType = ItemType::ItemStart;

                continue;
            }

            if (\preg_match(RegularExpression::CHECKED_ITEM, $line)) {
                $this->addDocumentItem($currentGroup, ItemType::ItemStart, ItemStatus::Checked, $line);

                $prevItemType = ItemType::ItemStart;

                continue;
            }

            if (\preg_match(RegularExpression::ONGOING_ITEM, $line)) {
                $this->addDocumentItem($currentGroup, ItemType::ItemStart, ItemStatus::Ongoing, $line);

                $prevItemType = ItemType::ItemStart;

                continue;
            }

            if (\preg_match(RegularExpression::OBSOLETE_ITEM, $line)) {
                $this->addDocumentItem($currentGroup, ItemType::ItemStart, ItemStatus::Obsolete, $line);

                $prevItemType = ItemType::ItemStart;

                continue;
            }

            if (\preg_match(RegularExpression::IN_QUESTION_ITEM, $line)) {
                $this->addDocumentItem($currentGroup, ItemType::ItemStart, ItemStatus::InQuestion, $line);

                $prevItemType = ItemType::ItemStart;

                continue;
            }

            if (($prevItemType === ItemType::ItemStart || $prevItemType === ItemType::ItemContinuation) && \preg_match(RegularExpression::ITEM_DETAILS, $line)) {
                $this->addDocumentItem($currentGroup, ItemType::ItemContinuation, null, $line);

                $prevItemType = ItemType::ItemContinuation;

                continue;
            }

            if (\preg_match("/^[\n\r]*/m", $line)) {
                if ($prevItemType !== null) {
                    $currentGroup = new DocumentGroup();
                    $groups[] = $currentGroup;
                }

                $prevItemType = null;

                continue;
            }

            throw new \Exception(\sprintf('One or more lines of provided are invalid starting at L%d: %s', $lineNumber, $line));
        }

        return new Document($content, $groups);
    }

    private function addDocumentItem(DocumentGroup $documentGroup, ItemType $itemType, ?ItemStatus $itemStatus, string $content): void
    {
        $readableContent = $content;

        if ($itemType === ItemType::ItemStart) {
            switch ($itemStatus) {
                case ItemStatus::Open:
                    $readableContent = \preg_replace(RegularExpression::OPEN_ITEM, '', $readableContent);

                    break;

                case ItemStatus::Checked:
                    $readableContent = \preg_replace(RegularExpression::CHECKED_ITEM, '', $readableContent);

                    break;

                case ItemStatus::Ongoing:
                    $readableContent = \preg_replace(RegularExpression::ONGOING_ITEM, '', $readableContent);

                    break;

                case ItemStatus::Obsolete:
                    $readableContent = \preg_replace(RegularExpression::OBSOLETE_ITEM, '', $readableContent);

                    break;

                case ItemStatus::InQuestion:
                    $readableContent = \preg_replace(RegularExpression::IN_QUESTION_ITEM, '', $readableContent);

                    break;
            }
        }

        if ($itemType === ItemType::ItemStart || $itemType === ItemType::ItemContinuation) {
            $readableContent = \preg_replace(RegularExpression::PRIORITY, '', (string) $readableContent);
            $readableContent = \preg_replace(RegularExpression::DUE_DATE, '', (string) $readableContent);
            $readableContent = \preg_replace(RegularExpression::TAG, '', (string) $readableContent);
        }

        $documentItem = new DocumentItem($itemType, $itemStatus);

        if ($itemType === ItemType::NewLine) {
            $documentItem->setContent("\n");
        } else {
            $documentItem->setContent(\trim((string) \preg_replace("/[\n\r]*$/", '', (string) $readableContent)));
        }

        $trimmedRawContent = \preg_replace("/[\n\r]*$/", '', $content);

        if ($itemType === ItemType::NewLine) {
            $documentItem->setRawContent("\n");
        } else {
            $documentItem->setRawContent($trimmedRawContent);
        }

        if ($itemType === ItemType::GroupTitle || $itemType === ItemType::NewLine) {
            $documentItem->setModifiers(null);
        } else {
            $documentItem->setModifiers($this->modifierParser->parse($trimmedRawContent));
        }

        $documentGroup->addItem($documentItem);
    }
}
