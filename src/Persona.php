<?php

namespace Persona;

use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Persona\Managers\ContactManager;
use Persona\Managers\DocumentManager;
use Persona\Managers\PersonaManager;
use Persona\Managers\RelationshipManager;
use Persona\Models\Contact;
use Persona\Models\Document;
use Persona\Models\Relationship;

class Persona
{
    /**
     * Create a PersonaManager instance scoped to the given model.
     */
    public static function for(Model $model): PersonaManager
    {
        $manager = static::manager();

        return new class($model, $manager) extends PersonaManager {
            protected ContactManager $scopedContacts;
            protected DocumentManager $scopedDocuments;
            protected RelationshipManager $scopedRelationships;

            public function __construct(
                protected Model $personable,
                protected PersonaManager $rootManager,
            ) {
                parent::__construct(
                    $rootManager->contacts(),
                    $rootManager->documents(),
                    $rootManager->relationships(),
                );

                $this->scopedContacts = new class($this->personable, $rootManager->contacts()) extends ContactManager {
                    public function __construct(
                        protected Model $scopedModel,
                        protected ContactManager $underlying,
                    ) {
                        parent::__construct(Container::getInstance());
                    }

                    public function add(mixed ...$args): Contact
                    {
                        if (isset($args[0]) && $args[0] instanceof Model) {
                            return $this->underlying->add(...$args);
                        }

                        if (isset($args['personable']) && $args['personable'] instanceof Model) {
                            return $this->underlying->add(...$args);
                        }

                        $type = $args[0] ?? $args['type'] ?? '';
                        $value = $args[1] ?? $args['value'] ?? '';
                        $isPrimary = $args[2] ?? $args['isPrimary'] ?? false;
                        $isEmergency = $args[3] ?? $args['isEmergency'] ?? false;

                        return $this->underlying->add(
                            $this->scopedModel,
                            (string) $type,
                            (string) $value,
                            (bool) $isPrimary,
                            (bool) $isEmergency,
                        );
                    }

                    public function __call(string $method, array $arguments): mixed
                    {
                        return $this->underlying->{$method}(...$arguments);
                    }
                };

                $this->scopedDocuments = new class($this->personable, $rootManager->documents()) extends DocumentManager {
                    public function __construct(
                        protected Model $scopedModel,
                        protected DocumentManager $underlying,
                    ) {}

                    public function add(mixed ...$args): Document
                    {
                        if (isset($args[0]) && $args[0] instanceof Model) {
                            return $this->underlying->add(...$args);
                        }

                        if (isset($args['personable']) && $args['personable'] instanceof Model) {
                            return $this->underlying->add(...$args);
                        }

                        $type = $args[0] ?? $args['type'] ?? '';
                        $number = $args[1] ?? $args['number'] ?? '';
                        $metadata = $args[2] ?? $args['metadata'] ?? [];

                        return $this->underlying->add(
                            $this->scopedModel,
                            (string) $type,
                            (string) $number,
                            (array) $metadata,
                        );
                    }

                    public function __call(string $method, array $arguments): mixed
                    {
                        return $this->underlying->{$method}(...$arguments);
                    }
                };

                $this->scopedRelationships = new class($this->personable, $rootManager->relationships()) extends RelationshipManager {
                    public function __construct(
                        protected Model $scopedModel,
                        protected RelationshipManager $underlying,
                    ) {}

                    public function link(mixed ...$args): Relationship
                    {
                        if (isset($args[0]) && $args[0] instanceof Model && isset($args[1]) && $args[1] instanceof Model) {
                            return $this->underlying->link(...$args);
                        }

                        if (isset($args['source']) && $args['source'] instanceof Model && isset($args['target']) && $args['target'] instanceof Model) {
                            return $this->underlying->link(...$args);
                        }

                        $target = $args[0] ?? $args['target'] ?? null;
                        $type = $args[1] ?? $args['type'] ?? '';

                        return $this->underlying->link(
                            $this->scopedModel,
                            $target,
                            (string) $type,
                        );
                    }

                    public function __call(string $method, array $arguments): mixed
                    {
                        return $this->underlying->{$method}(...$arguments);
                    }
                };
            }

            public function getModel(): Model
            {
                return $this->personable;
            }

            public function contacts(): ContactManager
            {
                return $this->scopedContacts;
            }

            public function documents(): DocumentManager
            {
                return $this->scopedDocuments;
            }

            public function relationships(): RelationshipManager
            {
                return $this->scopedRelationships;
            }

            public function forgetAll(?Model $personable = null): void
            {
                $target = $personable ?? $this->personable;

                $this->rootManager->forgetAll($target);
            }
        };
    }

    /**
     * Wipe the complete Persona footprint for a personable model.
     */
    public static function forgetAll(Model $model): void
    {
        static::manager()->forgetAll($model);
    }

    /**
     * Resolve the root PersonaManager from the container.
     */
    public static function manager(): PersonaManager
    {
        return Container::getInstance()->make(PersonaManager::class);
    }

    /**
     * Dynamically forward static calls to the root PersonaManager.
     */
    public static function __callStatic(string $method, array $arguments): mixed
    {
        return static::manager()->{$method}(...$arguments);
    }
}
