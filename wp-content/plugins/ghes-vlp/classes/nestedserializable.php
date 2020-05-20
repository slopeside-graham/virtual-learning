<?php

namespace GHES\VLP {
    class NestedSerializable  implements \JsonSerializable
    {
        private $elements;

        function __construct() { 
            $this->elements = array();
         }

        public function jsonSerialize()
        {
            return $this->elements;
        }

        public function add_item($value)
        {
            $this->elements[] = $value;
            return $this->elements;
        }
        
    }
}
