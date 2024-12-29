<?php

declare(strict_types=1);

namespace MauticPlugin\GranamCzechVocativeBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Mautic\DynamicContentBundle\Event\TokenReplacementEvent;
use MauticPlugin\GranamCzechVocativeBundle\Service\NameToVocativeConverter;

class VocativeDynamicContentSubscriber implements EventSubscriberInterface
{
    public const SERVICE_ID = 'plugin.vocative.dynamic.content.subscriber';

    private NameToVocativeConverter $nameToVocativeConverter;

    public function __construct(NameToVocativeConverter $nameToVocativeConverter)
    {
        $this->nameToVocativeConverter = $nameToVocativeConverter;
    }

    /**
     * @return array<string, array>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'mautic.dynamic_content_on_token_replacement' => ['onTokenReplacement', -10],
        ];
    }

    public function onTokenReplacement(TokenReplacementEvent $event): void
    {
        $content = $event->getContent();
        $tokenList = $this->nameToVocativeConverter->findAndReplace($content);

        if (!empty($tokenList)) {
            $content = str_replace(array_keys($tokenList), array_values($tokenList), $content);
        }

        $event->setContent($content);
    }
}
