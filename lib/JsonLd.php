<?php

namespace Extractor\lib;

class JsonLd extends Article
{
    protected $data;

    public function __construct($obj, $xpath)
    {
        parent::__construct($xpath);
        foreach( $obj as $node) {
            $this->data = json_decode($node->nodeValue, true);
            if (json_last_error()) {
                throw new \Exception( json_last_error_msg() );
            }
        }
    }

    protected function parseTitle()
    {
        $this->list[self::KEY_HEADLINE] = (isset($this->data['headline'])) ? html_entity_decode($this->data['headline']) : null;
        $this->printLine(self::KEY_HEADLINE, $this->list[self::KEY_HEADLINE]);
    }

    protected function parseAuthor()
    {
        if (isset($this->data['author'])) {
            if (is_array($this->data['author'])) {
                if (array_key_exists('name', $this->data['author'])) {
                    $this->list[self::KEY_AUTHOR] = html_entity_decode($this->data['author']['name']);
                }
                else {
                    $this->list[self::KEY_AUTHOR] = html_entity_decode($this->data['author'][0]);
                }
            }
            else if (is_string($this->data['author'])) {
                $this->list[self::KEY_AUTHOR] = html_entity_decode($this->data['author']);
            }
        }
        else {
            $this->list[self::KEY_AUTHOR] = null;
        }

        $this->printLine(self::KEY_AUTHOR, $this->list[self::KEY_AUTHOR]);
    }

    protected function parseDate()
    {
        $this->list[self::KEY_DATE_PUBLISHED] = (isset($this->data['datePublished'])) ? $this->data['datePublished'] : null;
        $this->printLine(self::KEY_DATE_PUBLISHED, $this->list[self::KEY_DATE_PUBLISHED]);
    }

    protected function parseUrl()
    {
        $this->list[self::KEY_URL] = (isset($this->data['url'])) ? $this->data['url'] : null;
        $this->printLine(self::KEY_URL, $this->list[self::KEY_URL]);
    }

    protected function parseImage()
    {
        $this->list[self::KEY_IMAGE] = (isset($this->data['image']['url'])) ? $this->data['image']['url'] : null;
        $this->printLine(self::KEY_IMAGE, $this->list[self::KEY_IMAGE]);
    }

    protected function parseDescription()
    {
        $this->list[self::KEY_DESCRIPTION] = (isset($this->data['description'])) ? $this->data['description'] : null;
        $this->printLine(self::KEY_DESCRIPTION, $this->list[self::KEY_DESCRIPTION]);
    }

    protected function parseArticle()
    {
        $this->list[self::KEY_BODY] = (isset($this->data['articleBody'])) ? $this->data['articleBody'] : null;
        $this->printLine(self::KEY_BODY, $this->list[self::KEY_BODY]);
    }
}