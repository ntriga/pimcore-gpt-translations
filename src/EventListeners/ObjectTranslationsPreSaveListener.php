<?php

namespace Ntriga\PimcoreGPTTranslations\EventListeners;

use Ntriga\PimcoreGPTTranslations\Actions\TranslateEmptyLocalizedFieldsAction;
use Pimcore\Event\Model\DataObjectEvent;

class ObjectTranslationsPreSaveListener
{
    public function __construct(
        private readonly TranslateEmptyLocalizedFieldsAction $translateEmptyLocalizedFieldsAction,
    ){}

    public function translateEmptyLocalizedFields(DataObjectEvent $event): void
    {
        $object = $event->getObject();

        // Check if the getLocalizedFields method exists
        if (!method_exists($object, 'getLocalizedFields') || $this->isAutoSave($event)) {
            return;
        }

        ($this->translateEmptyLocalizedFieldsAction)($object);
    }

    private function isAutoSave(DataObjectEvent $event): bool
    {
        // Check for autosave indicators, such as request parameters or user actions
        // This is an example check, you might need to adjust it based on your autosave logic
        $arguments = $event->getArguments();
        if (isset($arguments['isAutoSave']) && $arguments['isAutoSave'] === true) {
            return true;
        }

        return false;
    }

}
