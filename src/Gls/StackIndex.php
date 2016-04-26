<?php
/**
 * This file is part of the gls-unixbox-delivery.
 * (c) 2016 Pierre Tomasina
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Plab\GlsUniboxDelivery\Gls;

class StackIndex extends \SplDoublyLinkedList
{
    protected $mapping = [];
    protected $counter = 0;
    
    protected function findMapIndex($index)
    {
        return array_key_exists($index, $this->mapping) ? $this->mapping[$index] : null;
    }
    
    public function add($index, $value)
    {
        $this->mapping[$index] = $this->counter;
        
        parent::add($this->counter, $value);
        
        ++$this->counter;
    }
    
    public function offsetExists($index)
    {
        return parent::offsetExists($index);
    }

    public function offsetGet($index)
    {
        $index = $this->findMapIndex($index);
        
        return parent::offsetGet($index);
    }
    
    public function offsetSet($index, $value)
    {
        $index = $this->findMapIndex($index);

        return parent::offsetSet($index, $value);
    }
    
    public function offsetUnset($index)
    {
        $index = $this->findMapIndex($index);

        return parent::offsetUnset($index);
    }
}