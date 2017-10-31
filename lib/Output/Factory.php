<?php

namespace Extractor\lib\Output;

class Factory
{
    static public function create($format = null)
    {
        switch ($format) {
            case 'xml':
                return new XmlFormat();
                break;

            case 'html':
                return new HtmlFormat();
                break;

            case 'json':
                return new JsonFormat();
        }
    }
}