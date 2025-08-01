<?php

namespace AcfBlocksCli\ValueObject;

class BlockDefinition
{
    public function __construct(
        public string $name,
        public string $slug,
        public string $category,
        public string $mode,
        public bool   $generateScss,
        public bool   $generateJson
    )
    {
    }

}