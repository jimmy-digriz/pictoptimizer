<?php
namespace Saa\Pictoptimizer;

class ResizerFactory
{
    public function getResizer()
    {
        return new \Saa\Pictoptimizer\Resizers\MogrifyResizer();
    }
}