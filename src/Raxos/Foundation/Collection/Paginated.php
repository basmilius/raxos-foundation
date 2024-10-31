<?php
declare(strict_types=1);

namespace Raxos\Foundation\Collection;

use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;
use Raxos\Foundation\Contract\ArrayListInterface;

/**
 * Class Paginated
 *
 * @template TValue
 *
 * @author Bas Milius <bas@mili.us>
 * @package Raxos\Foundation\Collection
 * @since 1.3.1
 */
readonly class Paginated implements JsonSerializable
{

    /**
     * Paginated constructor.
     *
     * @param ArrayListInterface<int, TValue> $items
     * @param int $page
     * @param int $pageSize
     * @param int $pages
     * @param int $totalItems
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.3.1
     */
    public function __construct(
        public ArrayListInterface $items,
        public int $page,
        public int $pageSize,
        public int $pages,
        public int $totalItems
    ) {}

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.3.1
     */
    #[ArrayShape([
        'items' => ArrayListInterface::class,
        'page' => 'int',
        'page_size' => 'int',
        'pages' => 'int',
        'total_items' => 'int'
    ])]
    public function jsonSerialize(): array
    {
        return [
            'items' => $this->items,
            'page' => $this->page,
            'page_size' => $this->pageSize,
            'pages' => $this->pages,
            'total_items' => $this->totalItems
        ];
    }

}
