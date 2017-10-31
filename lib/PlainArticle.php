<?php

namespace Extractor\lib;

class PlainArticle extends Article
{
    protected $doc;
    protected $xpath;

    public function __construct($doc, $xpath)
    {
        parent::__construct($xpath);
        $this->doc = $doc;
    }

    protected function parseTitle()
    {
        $obj = $this->xpath->query("//h1");
        $this->list[self::KEY_HEADLINE] = ($obj->length > 0) ? trim($obj->item(0)->nodeValue) : null;
        $this->printLine(self::KEY_HEADLINE, $this->list[self::KEY_HEADLINE]);
    }

    protected function parseAuthor()
    {
        $this->list[self::KEY_AUTHOR] = null;
    }

    protected function parseDate()
    {
        $this->list[self::KEY_DATE_PUBLISHED] = $this->parseTimeTag();
        $this->printLine(self::KEY_DATE_PUBLISHED, $this->list[self::KEY_DATE_PUBLISHED]);
    }

    protected function parseUrl()
    {
        $this->list[self::KEY_URL] = null;
    }

    protected function parseImage()
    {
        $this->list[self::KEY_IMAGE] = null;
    }

    protected function parseDescription()
    {
        $this->list[self::KEY_DESCRIPTION] = null;
    }

    protected function parseArticle()
    {
        $this->list[self::KEY_BODY] = null;
    }
}
