<?php

namespace Ucscode\UssElement\Parser\Collection;

use Ucscode\UssElement\Parser\Dto\AttributeDto;
use Ucscode\UssElement\Parser\Exception\InvalidParserComponentException;
use Ucscode\UssElement\Support\AbstractCollection;

/**
 * A collection of AttributoDto
 *
 * @author Uchenna Ajah <uche23mail@gmail.com>
 */
class AttributeDtoCollection extends AbstractCollection
{
    protected function validateItemType(mixed $item)
    {
        if (!$item instanceof AttributeDto) {
            throw new InvalidParserComponentException(
                sprintf(InvalidParserComponentException::INVALID_ATTRIBUTE_DTO_EXCEPTION, AttributeDto::class, gettype($item))
            );
        }
    }

    public function get(string $name): ?AttributeDto
    {
        return $this->items[$name] ?? null;
    }

    public function has(string $name): bool
    {
        return array_key_exists($name, $this->items);
    }

    public function keys(): array
    {
        return array_keys($this->items);
    }

    public function values(): array
    {
        return array_map(fn (AttributeDto $attributeDto) => $attributeDto->getValue(), $this->items);
    }
}
