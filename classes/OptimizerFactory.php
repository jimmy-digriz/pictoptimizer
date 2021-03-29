<?php
namespace Saa\Pictoptimizer;

use Saa\Pictoptimizer\Optimizers\OptipngOptimizer;
use Saa\Pictoptimizer\Optimizers\ConvertOptimizer;
use Saa\Pictoptimizer\Optimizers\PngquantOptimizer;

class OptimizerFactory
{
    public function getOptimizer($mimeType)
    {
        switch($mimeType){
            case "image/png":
                if(PngquantOptimizer::isAvailable()){
                    return new PngquantOptimizer();
                } else {
                    return new OptipngOptimizer();
                }
                break;
            case "image/jpeg":
                return new ConvertOptimizer();
                break;
            default:
                return null;
        }
    }
}