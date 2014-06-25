<?php

namespace Knp\FriendlyExtension\Utils;

use Doctrine\Common\Inflector\Inflector;
use Knp\FriendlyContexts\Utils\TextFormater;

class NameProposer
{
    private $formater;

    public function __construct(TextFormater $formater)
    {
        $this->formater = $formater;
    }

    public function match($name, $other)
    {
        $proposals = $this->buildProposals($name);

        return in_array($other, $proposals);
    }

    public function buildProposals($name, $pluralize = false)
    {
        $proposals = [
            $this->formater->toCamelCase($name),
            $this->formater->toUnderscoreCase($name),
            $this->formater->toSpaceCase($name),
        ];

        if (true === $pluralize) {
            $proposals = array_merge(
                array_map(function ($e) { return Inflector::singularize($e); }, $proposals),
                array_map(function ($e) { return Inflector::pluralize($e); }, $proposals)
            );
        }

        $proposals = array_merge(
            array_map('strtoupper', $proposals),
            array_map('strtolower', $proposals)
        );

        return array_unique($proposals);
    }
}
