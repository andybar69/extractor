<?php

namespace Extractor;

use Extractor\lib\JsonLd;
use Extractor\lib\Microdata;
use Extractor\lib\PlainArticle;
use Extractor\lib\Article;
use Extractor\lib\Output\Factory;

class ArticleExtractor
{
    protected $doc;
    protected $xpath;
    protected $obj;

    public function __construct($url)
    {
        $strContent = file_get_contents($url);

        $this->doc = new \DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);

        $source = mb_convert_encoding($strContent, 'HTML-ENTITIES', 'utf-8');
        $this->doc->loadHTML($source);

        $this->initXPath();
    }

    public function factory()
    {
        $obj = $this->xpath->query('//script[@type="application/ld+json"]' );
        if ($obj->length > 0) {
            $this->obj = new JsonLd($obj, $this->xpath);
            return;
        }
        $obj = null;
        $obj = $this->xpath->query("//*[@itemprop]");
        if ($obj->length > 0) {
            $this->obj = new Microdata($obj, $this->xpath);
            return;
        }
        $this->obj = new PlainArticle($obj, $this->xpath);
    }

    public function enableDebug()
    {
        if (is_object($this->obj))
            $this->obj->setDebugMode();
        else
            throw new \Exception('Extractor object not defined');
    }

    protected function initXPath()
    {
        $this->xpath = new \DOMXPath($this->doc);
    }

    public function build()
    {
        $this->obj->build();

        $this->completeData();
    }

    protected function completeData()
    {
        $arr = $this->obj->getList();
        foreach ($arr as $key => &$value) {
            if (empty($value)) {
                if ($key == Article::KEY_BODY) {
                    $value = $this->obj->getArticleBody();
                }
                else if ($key == Article::KEY_HEADLINE) {
                    $value = $this->obj->getArticleTitle();
                }
                else if ($key == Article::KEY_DATE_PUBLISHED) {
                    $value = $this->obj->getArticleDate();
                }
                else {
                    $value = $this->obj->getValueFromMeta($key);
                }
            }
        }
        $this->obj->setList($arr);
    }

    public function output($format)
    {
        $strategy = Factory::create($format);
        $this->obj->setStrategy($strategy);
        return $this->obj->render();
    }
}