<?php

namespace App\src\Framework\Renderer\Interfaces;

use App\src\Framework\Renderer\Interfaces\RenderingEngineInterface;

interface RendererInterface
{
    /**
     * Return the active renderer.
     *
     * @return \App\src\Framework\Renderer\Interfaces\RenderingEngineInterface
     */
    public function getRenderingEngine(): RenderingEngineInterface;
}
