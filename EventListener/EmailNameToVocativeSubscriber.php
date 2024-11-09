<?php

declare(strict_types=1);

namespace MauticPlugin\GranamCzechVocativeBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Mautic\EmailBundle\Event\EmailSendEvent;
use MauticPlugin\GranamCzechVocativeBundle\Service\NameToVocativeConverter;

class EmailNameToVocativeSubscriber implements EventSubscriberInterface
{
    public const SERVICE_ID = 'plugin.vocative.emailNameToVocative.subscriber';

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
            'mautic.email_on_send' => ['onEmailGenerate', -999],
            'mautic.email_on_display' => ['onEmailGenerate', -999],
        ];
    }

    public function onEmailGenerate(EmailSendEvent $event): void
    {
        $content = $event->getSubject()
            . $event->getContent(true) // With tokens replaced
            . $event->getPlainText();

        $tokenList = $this->nameToVocativeConverter->findAndReplace($content);

        if (!empty($tokenList)) {
            $event->addTokens($tokenList);
        }
    }
}
