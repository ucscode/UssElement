<?php

namespace Ucscode\PHPDocument\Test\Traits;

use Ucscode\PHPDocument\Collection\NodeList;
use Ucscode\PHPDocument\Contracts\NodeInterface;
use Ucscode\PHPDocument\Enums\NodeEnum;
use Ucscode\PHPDocument\Node\ElementNode;
use Ucscode\PHPDocument\Node\TextNode;

trait NodeHelperTrait
{
    /**
     * @return ElementNode
     */
    protected NodeList $nodeList;

    /**
     * @return ElementNode
     */
    protected function setUp(): void
    {
        $this->nodeList = new NodeList([
            new ElementNode(NodeEnum::NODE_BODY, [
                'class' => 'body',
                'id' => 'body',
            ]),
            new ElementNode(NodeEnum::NODE_DIV, [
                'class' => 'position-relative',
                'data-theme' =>  'dark',
            ]),
            new ElementNode(NodeEnum::NODE_H1),
            new ElementNode(NodeEnum::NODE_FORM, [
                'action' => '',
                'name' => 'form'
            ]),
            new ElementNode(NodeEnum::NODE_INPUT, [
                'name' => 'username',
                'value' => '224',
                'type' => 'text',
            ]),
            new ElementNode(NodeEnum::NODE_A, [
                'href' => 'https://example.com',
                'error' => 3,
            ]),
            new ElementNode(NodeEnum::NODE_BR),
            new ElementNode(NodeEnum::NODE_BUTTON, [
                'class' => 'btn btn-primary',
                'type' => 'submit',
                'data-value' => '["data1", "data2"]',
            ]),
            new ElementNode(NodeEnum::NODE_IMG, [
                'src' => 'https://dummyimage.com/300x500/fff',
                'class' => 'img-fluid',
                'id' => 'factor',
            ]),
            new TextNode('This is a text'),
        ]);

        $this->randomizeNodesHierarchy();
    }

    /**
     * @return ElementNode
     */
    protected function getNodeBody(): NodeInterface
    {
        return $this->nodeList->get(0);
    }

    /**
     * @return ElementNode
     */
    protected function getNodeDiv(): NodeInterface
    {
        return $this->nodeList->get(1);
    }

    /**
     * @return ElementNode
     */
    protected function getNodeH1(): NodeInterface
    {
        return $this->nodeList->get(2);
    }

    /**
     * @return ElementNode
     */
    protected function getNodeForm(): NodeInterface
    {
        return $this->nodeList->get(3);
    }

    /**
     * @return ElementNode
     */
    protected function getNodeInput(): NodeInterface
    {
        return $this->nodeList->get(4);
    }

    /**
     * @return ElementNode
     */
    protected function getNodeA(): NodeInterface
    {
        return $this->nodeList->get(5);
    }

    /**
     * @return ElementNode
     */
    protected function getNodeBr(): NodeInterface
    {
        return $this->nodeList->get(6);
    }

    /**
     * @return ElementNode
     */
    protected function getNodeButton(): NodeInterface
    {
        return $this->nodeList->get(7);
    }

    /**
     * @return ElementNode
     */
    protected function getNodeImg(): NodeInterface
    {
        return $this->nodeList->get(8);
    }

    /**
     * @return ElementNode
     */
    protected function getNodeText(): NodeInterface
    {
        return $this->nodeList->get(9);
    }

    protected function randomizeNodesHierarchy(): void
    {
        // body > div
        $this->getNodeBody()
            ->appendChild($this->getNodeDiv())
        ;

        // body >
        $this->getNodeDiv()
            // div > h1
            ->appendChild($this->getNodeH1())
            // div > a
            ->appendChild($this->getNodeA())
            // div > form
            ->appendChild($this->getNodeForm())
        ;

        // body > div >
        $this->getNodeA()
            // a > img
            ->appendChild($this->getNodeImg())
        ;

        // body > div >
        $this->getNodeForm()
            // form > input
            ->appendChild($this->getNodeInput())
            // form > br
            ->appendChild($this->getNodeBr())
            // form > button
            ->appendChild($this->getNodeButton())
        ;

        // body > div > form >
        $this->getNodeButton()
            // button > text
            ->appendChild($this->getNodeText())
        ;

        // Visualization
        /*
            <body>
                <div>
                    <h1></h1>
                    <a>
                        <img>
                    </a>
                    <form>
                        <input/>
                        <br/>
                        <button>
                            #text
                        </button>
                    </form>
                </div>
            </body>
        */
    }
}