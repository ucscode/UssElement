<?php

namespace Ucscode\PHPDocument\Node;

use Ucscode\PHPDocument\Collection\Attributes;
use Ucscode\PHPDocument\Enums\NodeNameEnum;
use Ucscode\PHPDocument\Collection\AttributesMutable;
use Ucscode\PHPDocument\Collection\ClassList;
use Ucscode\PHPDocument\Collection\HtmlCollection;
use Ucscode\PHPDocument\Contracts\ElementInterface;
use Ucscode\PHPDocument\Contracts\NodeInterface;
use Ucscode\PHPDocument\Enums\NodeTypeEnum;
use Ucscode\PHPDocument\Parser\Engine\Matcher;
use Ucscode\PHPDocument\Parser\Engine\Tokenizer;
use Ucscode\PHPDocument\Parser\Engine\Transformer;
use Ucscode\PHPDocument\Parser\NodeSelector;
use Ucscode\PHPDocument\Support\AbstractNode;

class ElementNode extends AbstractNode implements ElementInterface
{
    protected ClassList $classList;
    protected AttributesMutable $attributes;
    protected bool $void;
    protected string $tagName;

    public function __construct(string|NodeNameEnum $nodeName, array $attributes = [])
    {
        parent::__construct($nodeName);

        $this->nodePresets($attributes);
    }

    public function getTagName(): string
    {
        return $this->nodeName;
    }

    public function getNodeType(): int
    {
        return NodeTypeEnum::NODE_ELEMENT->value;
    }

    public function render(): string
    {
        return sprintf('%s%s%s', $this->getOpenTag(), $this->getInnerHtml(), $this->getCloseTag());
    }

    public function setInnerHtml(string $html): static
    {
        return $this;
    }

    public function getInnerHtml(): string
    {
        $renderedNodes = array_map(
            fn (NodeInterface $node) => $node->render(),
            $this->childNodes->toArray()
        );

        return implode($renderedNodes);
    }

    public function setVoid(bool $void): static
    {
        $this->void = $void;

        return $this;
    }

    public function isVoid(): bool
    {
        return $this->void;
    }

    public function getOpenTag(): string
    {
        return sprintf("<%s %s%s>", strtolower($this->nodeName), $this->attributes->render(), $this->isVoid() ? '/' : '');
    }

    public function getCloseTag(): ?string
    {
        return $this->isVoid() ? null : sprintf('</%s>', strtolower($this->nodeName));
    }

    public function getChildren(): HtmlCollection
    {
        $filter = array_filter(
            $this->childNodes->toArray(),
            fn (NodeInterface $node) => $node->getNodeType() === NodeTypeEnum::NODE_ELEMENT->value
        );

        return new HtmlCollection($filter);
    }

    public function getAttribute(string $name, \Stringable|string|null $default = null): ?string
    {
        return $this->attributes->get($name, $default);
    }

    public function getAttributes(): Attributes
    {
        $attributes = array_map(fn ($value) => (string)$value, $this->attributes->toArray());

        return new Attributes($attributes);
    }

    public function hasAttribute(string $name): bool
    {
        return $this->attributes->has($name);
    }

    public function getClassList(): ClassList
    {
        return $this->classList;
    }

    public function getAttributeNames(): array
    {
        return $this->attributes->getNames();
    }

    public function setAttribute(string $name, \Stringable|string|null $value): static
    {
        if (strtolower(trim($name)) === 'class') {
            if ($value !== $this->classList) {
                $this->classList->clear();

                $value = ($value !== null) ? $this->classList->add($value) : $this->classList;
            }
        }

        $this->attributes->set($name, $value);

        return $this;
    }

    public function hasAttributes(): bool
    {
        return !$this->attributes->isEmpty();
    }

    public function removeAttribute(string $name): static
    {
        $this->attributes->remove($name);

        return $this;
    }

    public function querySelectorAll(string $selector): HtmlCollection
    {
        return (new NodeSelector($this, $selector))->getResult();
    }

    public function querySelector(string $selector): ?ElementInterface
    {
        return $this->querySelectorAll($selector)->first();
    }

    public function matches(string $selector): bool
    {
        $transformer = new Transformer();
        $encodeSelector = $transformer->encodeAttributes($transformer->encodeQuotedStrings($selector));
        $matcher = new Matcher($this, new Tokenizer($encodeSelector));

        return $matcher->matchesNode();
    }

    public function getElementsByClassName(string $names): HtmlCollection
    {
        $classes = implode('.', array_map('trim', explode(' ', $names)));

        return $this->querySelectorAll(".{$classes}");
    }

    public function getElementsByTagName(string $name): HtmlCollection
    {
        return $this->querySelectorAll($name);
    }

    private function nodePresets(array $attributes): void
    {
        $this->tagName = $this->nodeName;
        $this->attributes = new AttributesMutable();
        $this->classList = new ClassList();

        foreach ($attributes as $name => $value) {
            $this->setAttribute($name, $value);
        }

        $this->void = in_array(
            $this->nodeName,
            array_map(fn (NodeNameEnum $enum) => $enum->value, NodeNameEnum::voidCases())
        );
    }
}
