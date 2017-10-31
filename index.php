<?php

namespace Extractor;

try {
    // autoload
    require_once "Autoloader.php";
    Autoloader::register();

    $oArticle = new ArticleExtractor('http://www.vesti.ru/doc.html?id=2942560');
    $oArticle->factory();
    $oArticle->build();

    var_dump($oArticle->output('xml'));
}
catch (\Exception $ex) {
    echo $ex->getMessage();
}
