<?php

namespace Ucscode\PHPDocument\Support;

/**
 * @author Uchenna Ajah <uche23mail@gmail.com>
 */
abstract class AbstractCharacterData extends AbstractNode
{
    protected string $data = '';

    public function getLength(): int
    {
        return strlen($this->data);
    }
}