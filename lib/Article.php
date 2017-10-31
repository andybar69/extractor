<?php

namespace Extractor\lib;

abstract class Article
{
    const KEY_HEADLINE = 'headline';
    const KEY_AUTHOR = 'author';
    const KEY_DATE_PUBLISHED = 'datePublished';
    const KEY_URL = 'url';
    const KEY_IMAGE = 'image';
    const KEY_VIDEO = 'video';
    const KEY_DESCRIPTION = 'description';
    const KEY_BODY = 'body';

    protected $list;
    protected $xpath;
    protected $is_debug;
    protected $strategy;

    public function __construct($xpath)
    {
        $this->xpath = $xpath;
        $this->list = array();
    }

    public function build()
    {
        $this->setDebugMode();
        $this->parseTitle();
        $this->parseAuthor();
        $this->parseDate();
        $this->parseUrl();
        $this->parseImage();
        $this->parseDescription();
        $this->parseArticle();
    }

    public function setDebugMode()
    {
        $this->is_debug = true;
    }

    public function setList($data)
    {
        $this->list = $data;
    }

    public function getList()
    {
        return $this->list;
    }

    public function getArticleBody()
    {
        $body = null;
        $obj = $this->xpath->query("//div[contains(@class, 'js-mediator-article')]/*");
        if ($obj->length > 0) {
            foreach ($obj as $item) {
                $body .= trim(html_entity_decode($item->nodeValue, ENT_HTML5)) . PHP_EOL;
            }
        }
        if (empty($body)) {
            $obj = $this->xpath->query("//article");
            if ($obj->length > 0) {
                $body = trim($obj->item(0)->nodeValue);
            }
        }
        if (empty($body)) {
            $obj = $this->xpath->query("//*[@itemprop='articleBody']/*");
            if ($obj->length > 0) {
                foreach ($obj as $item){
                   $body .= trim($item->nodeValue).PHP_EOL;
                }
                $this->body = $body;
                $this->list['body']= $body;
            }
        }
        if (empty($body)) {
            $obj = $this->xpath->query("//div[contains(@class, 'content')]/*");
            if ($obj->length > 0) {
                foreach ($obj as $item){
                    $body .= trim($item->nodeValue).PHP_EOL;
                }
                $this->body = $body;
                $this->list['body']= $body;
            }
        }
        return $body;
    }

    public function getArticleTitle()
    {
        $obj = $this->xpath->query("//h1");
        return ($obj->length > 0) ? trim($obj->item(0)->nodeValue) : null;
    }

    public function getArticleDate()
    {
        $date = null;
        $obj = $this->xpath->query("//meta[contains(@name, 'publish')]/@content");
        $date = ($obj->length > 0) ? trim($obj->item(0)->nodeValue) : null;

        if (empty($date)) {
            $obj = $this->xpath->query("//meta[@property='article:published_time']/@content");
            $date = ($obj->length > 0) ? trim($obj->item(0)->nodeValue) : null;
        }
        if (empty($date)) {
            $date = $this->parseTimeTag();
        }

        return $date;
    }

    protected function parseTimeTag()
    {
        $date = null;
        $obj = $this->xpath->query("//time");
        //var_dump($obj);
        if ($obj->length == 1) {
            var_dump($obj->item(0));
            foreach ($obj->item(0)->attributes as $attribute) {
                if ($attribute->name == 'datetime' || $attribute->name == 'content') {
                    $date = trim($attribute->nodeValue);
                    break;
                }
                else {
                    $date = $obj->item(0)->nodeValue;
                }
            }
        }

        return $date;
    }

    public function getValueFromMeta($var)
    {
        $str = $this->getValueFromStandardMeta($var);
        if (empty($str)) {
            $str = $this->getValueFromOpenGraphMeta($var);
        }
        if (empty($str)) {
            $str = $this->getValueFromTwitterMeta($var);
        }

        return $str;
    }

    protected function getValueFromStandardMeta($property)
    {
        $obj = $this->xpath->query("//meta[contains(@name, '".$property."')]/@content");
        return ($obj->length > 0) ? trim($obj->item(0)->nodeValue) : null;
    }

    protected function getValueFromOpenGraphMeta($property)
    {
        $obj = $this->xpath->query("//meta[@property='og:".$property."']/@content");
        return ($obj->length > 0) ? trim($obj->item(0)->nodeValue) : null;
    }

    protected function getValueFromTwitterMeta($property)
    {
        $obj = $this->xpath->query("//meta[@property='twitter:".$property."']/@content");
        return ($obj->length > 0) ? trim($obj->item(0)->nodeValue) : null;
    }

    protected function printLine($key, $val)
    {
        if ($this->is_debug) {
            echo "$key: " . $val . "<br>";
        }
    }

    abstract protected function parseTitle();
    abstract protected function parseAuthor();
    abstract protected function parseDate();
    abstract protected function parseUrl();
    abstract protected function parseImage();
    abstract protected function parseDescription();
    abstract protected function parseArticle();

    public function setStrategy($obj)
    {
        $this->strategy = $obj;
    }

    public function render()
    {
        return $this->strategy->render($this->list);
    }
}