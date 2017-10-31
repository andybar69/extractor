<?php

namespace Extractor\lib;

class Microdata extends Article
{
    protected $doc;


    public function __construct($doc, $xpath)
    {
        parent::__construct($xpath);
        $this->doc = $doc;
    }

    protected function parseTitle()
    {
        $this->list[self::KEY_HEADLINE] = null;
        $items1 = $this->xpath->query("//*[@itemprop='headline']/*");
        $items2 = $this->xpath->query("//*[@itemprop='headline']");
        $items = ($items1->length > 0) ? $items1 : $items2;
        if ($items->length > 0 || $items->item(0)) {
            foreach ($items as $item) {
                if ($item->tagName == 'h1') {
                    $this->list[self::KEY_HEADLINE]= trim($item->nodeValue);
                    break;
                }
            }
        }
        $this->printLine(self::KEY_HEADLINE, $this->list[self::KEY_HEADLINE]);
        unset($items);
    }

    protected function parseAuthor()
    {
        $obj = $this->xpath->query("//*[@itemprop='author']//*[@itemprop='name']");
        if ($obj->length > 0 || $obj->item(0)) {
            $this->list[self::KEY_AUTHOR] = trim($obj->item(0)->nodeValue);
        }
        if (empty($this->list[self::KEY_AUTHOR])) {
            $obj = $this->xpath->query("//*[@itemprop='author']");
            $this->list[self::KEY_AUTHOR] = ($obj->length > 0) ? trim($obj->item(0)->nodeValue) : null;
        }
        $this->printLine(self::KEY_AUTHOR, $this->list[self::KEY_AUTHOR]);
        unset($obj);
    }

    protected function parseDate()
    {
        $obj = $this->list[self::KEY_DATE_PUBLISHED] = null;
        $o1 = $this->xpath->query("//*[@itemprop='datePublished']");
        $o2 = $this->xpath->query("//*[@itemprop='dateCreated']");
        $obj = ($o1->length > 0) ? $o1 : $o2;
        if ($obj->length > 0 || $obj->item(0)) {
            foreach ($obj->item(0)->attributes as $attribute) {
                if ($attribute->name == 'datetime' || $attribute->name == 'content') {
                    $this->list[self::KEY_DATE_PUBLISHED] = trim($attribute->nodeValue);
                    break;
                }
            }
        }
        $this->printLine(self::KEY_DATE_PUBLISHED, $this->list[self::KEY_DATE_PUBLISHED]);
        unset($obj);
    }

    protected function parseUrl()
    {
        $this->list[self::KEY_URL] = null;
        $obj = $this->xpath->query("//*[@itemprop='url']");
        var_dump($obj);
        if ($obj->length > 0 || $obj->item(0)) {
            foreach ($obj->item(0)->attributes as $attribute) {
                if ($attribute->name == 'href') {
                    $this->list[self::KEY_URL] = trim($attribute->nodeValue);
                    break;
                }
            }
        }
        $this->printLine(self::KEY_URL, $this->list[self::KEY_URL]);
        unset($obj);
    }

    protected function parseImage()
    {
        $obj = null;
        $this->list[self::KEY_IMAGE] = null;
        $o1 = $this->xpath->query("//*[@itemprop='image']");
        $o2 = $this->xpath->query("//*[@itemprop='associatedMedia']");
        $obj = ($o1->length > 0) ? $o1 : $o2;
        if ($obj->length > 0) {
            foreach($obj as $item) {
                foreach ($item->attributes as $attribute) {
                    if ($attribute->name == 'href' || $attribute->name == "src") {
                        $this->list[self::KEY_IMAGE] = trim($attribute->nodeValue);
                        break;
                    }
                }
            }
        }
        if (empty($this->list[self::KEY_IMAGE])) {
            $obj = $this->xpath->query("//*[@itemprop='image']/img");
            if ($obj->length > 0) {
                foreach($obj as $item) {
                    foreach ($item->attributes as $attribute) {
                        if ($attribute->name == "src") {
                            $this->list[self::KEY_IMAGE] = trim($attribute->nodeValue);
                            break;
                        }
                    }
                }
            }
        }
        $this->printLine(self::KEY_IMAGE, $this->list[self::KEY_IMAGE]);
        unset($obj);
    }

    protected function parseDescription()
    {
        $this->list[self::KEY_DESCRIPTION] = null;
        $items = $this->xpath->query("//*[@itemprop='description']");
        if ($items->length > 0 || $items->item(0)) {
            $this->list[self::KEY_DESCRIPTION] = trim($items->item(0)->nodeValue);
        }
        $this->printLine(self::KEY_DESCRIPTION, $this->list[self::KEY_DESCRIPTION]);
        unset($items);
    }

    protected function parseArticle()
    {
        $this->list[self::KEY_BODY] = null;
        $obj = $this->xpath->query("//*[@itemprop='articleBody']");
        if ($obj->length > 0) {
            $this->list[self::KEY_BODY] = trim($obj->item(0)->nodeValue);
        }
        $this->printLine(self::KEY_BODY, $this->list[self::KEY_BODY]);
        unset($obj);
    }
}
