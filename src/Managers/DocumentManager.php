<?php

namespace Persona\Managers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Persona\Events\DocumentAdded;
use Persona\Models\Document;

class DocumentManager
{
    /**
     * Add a document (passport, national ID, ...) for a personable model.
     *
     * The raw $number is passed to the model; the encrypted and lookup-hash
     * casts take care of storage and unique lookups respectively.
     *
     * @param  array<string, mixed>  $metadata  Extra columns, e.g.
     *                                          ['country_code' => 'US',
     *                                           'issued_at' => '2024-01-01'].
     */
    public function add(
        Model $personable,
        string $type,
        string $number,
        array $metadata = [],
    ): Document {
        return DB::transaction(function () use ($personable, $type, $number, $metadata) {
            $document = new Document(array_merge($metadata, [
                'type' => $type,
                'number' => $number,
                'number_hash' => $number,
            ]));

            $document->personable_type = $personable->getMorphClass();
            $document->personable_id = $personable->getKey();
            $document->save();

            DocumentAdded::dispatch($document);

            return $document;
        });
    }
}