<?php

namespace App\Framework\Renderer\Interfaces;

use App\Framework\Renderer\Interfaces\RenderingEngineInterface;

interface RendererInterface
{
    /**
     * Return the active renderer.
     *
     * @return \App\Framework\Renderer\Interfaces\RenderingEngineInterface
     */
    public function getRenderingEngine(): RenderingEngineInterface;
}
