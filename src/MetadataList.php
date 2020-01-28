<?php

declare(strict_types=1);

namespace PhpCfdi\CfdiSatScraper;

class MetadataList implements \Countable, \IteratorAggregate
{
    /** @var Metadata[] */
    private $list = [];

    /** @param Metadata[] $list */
    public function __construct(array $list)
    {
        $this->list = [];
        foreach ($list as $metadata) {
            if (! $metadata instanceof Metadata) {
                continue;
            }
            $this->list[$metadata->uuid()] = $metadata;
        }
    }

    public function merge(self $list): self
    {
        $new = new self([]);
        $new->list = array_merge($this->list, $list->list);
        return $new;
    }

    public function has(string $uuid): bool
    {
        return isset($this->list[$uuid]);
    }

    public function find(string $uuid): ?Metadata
    {
        return $this->list[$uuid] ?? null;
    }

    public function get(string $uuid): Metadata
    {
        $values = $this->find($uuid);
        if (null === $values) {
            throw new \RuntimeException(sprintf('UUID %s not found', $uuid));
        }
        return $values;
    }

    /**
     * @return \Traversable|Metadata[]
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->list);
    }

    public function count(): int
    {
        return count($this->list);
    }
}
