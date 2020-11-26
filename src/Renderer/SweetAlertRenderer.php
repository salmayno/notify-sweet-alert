<?php

namespace Notify\SweetAlert\Renderer;

use Notify\Config\ConfigInterface;
use Notify\Envelope\Envelope;
use Notify\Renderer\HasGlobalOptionsInterface;
use Notify\Renderer\HasScriptsInterface;
use Notify\Renderer\HasStylesInterface;
use Notify\Renderer\RendererInterface;

class SweetAlertRenderer implements RendererInterface, HasScriptsInterface, HasStylesInterface, HasGlobalOptionsInterface
{
    /**
     * @var \Notify\Config\ConfigInterface
     */
    private $config;

    /**
     * @var array
     */
    private $scripts;

    /**
     * @var array
     */
    private $styles;

    /**
     * @var array
     */
    private $options;

    public function __construct(ConfigInterface $config)
    {
        $this->config  = $config;
        $this->scripts = $config->get('adapters.sweet_alert.scripts', array());
        $this->styles  = $config->get('adapters.sweet_alert.styles', array());
        $this->options = $config->get('adapters.sweet_alert.options', array());
    }

    /**
     * @inheritDoc
     */
    public function render(Envelope $envelope)
    {
        $context = $envelope->getContext();
        $options = isset($context['options']) ? $context['options'] : array();

        $options['title'] = $envelope->getTitle();
        $options['text'] = $envelope->getMessage();
        $options['icon'] = $envelope->getType();

        if (!empty($options['imageUrl'])) {
            unset($options['icon']);
        }

        return sprintf("SwalToast.fire(%s);", json_encode($options));
    }

    /**
     * @inheritDoc
     */
    public function getScripts()
    {
        return $this->scripts;
    }

    /**
     * @inheritDoc
     */
    public function getStyles()
    {
        return $this->styles;
    }

    public function renderOptions()
    {
        return sprintf('var SwalToast = Swal.mixin(%s);', json_encode($this->options));
    }
}
