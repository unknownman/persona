<?php

namespace Persona\Facades;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Facade;
use Persona\Managers\ContactManager;
use Persona\Managers\DocumentManager;
use Persona\Managers\PersonaManager;
use Persona\Managers\RelationshipManager;

/**
 * @method static ContactManager contacts()
 * @method static DocumentManager documents()
 * @method static RelationshipManager relationships()
 * @method static void forgetAll(Model $personable)
 *
 * @see \Persona\Managers\PersonaManager
 * @see \Persona\Persona
 */
class Persona extends Facade
{
    /**
     * Create a PersonaManager instance scoped to the given model.
     */
    public static function for(Model $model): PersonaManager
    {
        return \Persona\Persona::for($model);
    }

    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return PersonaManager::class;
    }
}
