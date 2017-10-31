<?php

namespace Extractor\lib\Output;

class XmlFormat
{
    public function render($arData)
    {
        //echo "XML string";
        $xmlDoc = new \DOMDocument('1.0', 'UTF-8');

        //create the root element
        $root = $xmlDoc->appendChild($xmlDoc->createElement("Articles"));

        $tagArticle = $root->appendChild(
            $xmlDoc->createElement("Article"));

        // html_entity_decode($value)
        foreach ($arData as $key => $value) {
            $tagArticle->appendChild($xmlDoc->createElement($key, $value));
        }

        $xmlDoc->formatOutput = true;

        return $xmlDoc->saveXML();
    }
}