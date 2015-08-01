<?php
namespace Motibu\Transformers;


/**
 * Class Transformer
 * @package Motibu\Transformers
 */
abstract class Transformer{

    /**
     * Transform the DB structure to make it obfuscated and consistant
     * @param $items
     * @return array
     */
    public function transformCollection(array $items){
        return array_map([$this,'transform'], $items->toArray());
    }

    public abstract function transform($item);
}
