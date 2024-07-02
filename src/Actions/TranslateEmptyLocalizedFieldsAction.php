<?php

namespace Ntriga\PimcoreGPTTranslations\Actions;

use Exception;
use OpenAI\Client;
use Pimcore\Model\DataObject\AbstractObject;

class TranslateEmptyLocalizedFieldsAction
{
    public function __construct(
        private readonly string $sourceLang,
        private Client $openAIClient,
    ){}

    public function __invoke(AbstractObject $object): void
    {
        $sourceLang = $this->sourceLang;
        $localizedFields = $object->getLocalizedfields();

        // Only continue if there are localized fields
        if ( !$localizedFields ) {
            return;
        }


        $localizedFieldsItems = $localizedFields->getItems();
        $textToTranslate = [];
        foreach( $localizedFieldsItems as $localizedFieldsLang => $localizedFieldsItem ){
            if( $localizedFieldsLang === $sourceLang ){
                continue;
            }


            foreach( $localizedFieldsItem as $fieldName => $fieldValue ){
                $defaultLangValue = $localizedFieldsItems[$sourceLang][$fieldName];

                if( !$defaultLangValue ){
                    continue;
                }

                if( $fieldValue ){
                    continue;
                }

                $textToTranslate[] = $defaultLangValue;
            }
        }

        $assistantId = 'asst_uPzTaiJasZeGgFZOEeAuYm2s';

        $response = $this->openAIClient->threads()->create([]);
        $threadId = $response->id;

        $this->openAIClient->threads()->messages()->create($threadId, [
            'role' => 'user',
            'content' => json_encode($textToTranslate),
        ]);

        $stream = $this->openAIClient->threads()->runs()->createStreamed(
            threadId: $threadId,
            parameters: [
                'assistant_id' => $assistantId,
            ],
        );

        do{
            foreach($stream as $response){
                switch($response->event){
                    case 'thread.run.created':
                    case 'thread.run.queued':
                    case 'thread.run.completed':
                    case 'thread.run.cancelling':
                        $run = $response->response;
                        break;
                    case 'thread.run.expired':
                    case 'thread.run.cancelled':
                    case 'thread.run.failed':
                        throw new Exception('Run failed');
                }
            }
        } while ($run->status != "completed");

        $response = $this->openAIClient->threads()->messages()->list($threadId, [
            'limit' => 1,
        ]);
        dump( $response->data[0]->content[0]->text->value );
        $translations = json_decode($response->data[0]->content[0]->text->value, true );

        foreach( $localizedFieldsItems as $localizedFieldsLang => $localizedFieldsItem ){
            if( $localizedFieldsLang === $sourceLang ){
                continue;
            }


            foreach( $localizedFieldsItem as $fieldName => $fieldValue ){
                $defaultLangValue = $localizedFieldsItems[$sourceLang][$fieldName];

                if( !$defaultLangValue ){
                    continue;
                }

                if( $fieldValue ){
                    continue;
                }

                if( !isset($translations[$defaultLangValue]) ){
                    continue;
                }

                foreach( $translations[$defaultLangValue] as $language => $translation ){
                    $object->set($fieldName, $translation, $language);
                }
            }
        }
    }
}

