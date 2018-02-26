<?php

namespace Slince\Tuzki\Common\Argument;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArgumentsStorage extends ArrayCollection
{
    /**
     * @var OptionsResolver
     */
    protected $resolver;

    public function __construct()
    {
        $this->resolver = new OptionsResolver();
    }
}