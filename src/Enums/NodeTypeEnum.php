<?php

namespace Ucscode\PHPDocument\Enums;

enum NodeTypeEnum: int
{
    case NODE_ELEMENT = 1;
    case NODE_ATTRIBUTE = 2;
    case NODE_TEXT = 3;
    case NODE_CDATA_SECTION = 4;
    case NODE_PROCESSING_INSTRUCTION = 7;
    case NODE_COMMENT = 8;
    case NODE_DOCUMENT = 9;
    case NODE_DOCUMENT_TYPE = 10;
    case NODE_DOCUMENT_FRAGMENT = 11;
}