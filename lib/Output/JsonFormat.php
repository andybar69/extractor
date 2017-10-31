<?php

namespace Extractor\lib\Output;

class JsonFormat
{
    public function render($arData)
    {
        echo "JSON string";
        $output = json_encode($arData, JSON_UNESCAPED_UNICODE);
        if (json_last_error()) {
            throw new \Exception( json_last_error_msg() );
        }
        return $output;
    }
}