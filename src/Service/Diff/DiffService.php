<?php

namespace App\Service\Diff;

use App\Service\Environment\Env;
use App\Service\Serializer\SerializerService;
use App\Service\Validation\Error\UnknownError;
use RuntimeException;

readonly class DiffService
{
    public function __construct(private SerializerService $serializerService)
    {
    }

    public function diff(mixed $a, mixed $b): array|string
    {
        $is_object_a = is_object($a);
        $is_object_b = is_object($b);

        if ($is_object_a && $is_object_b) {
            $classA = get_class($a);
            $classB = get_class($b);
            if ($classA !== $classB) {
                throw new RuntimeException(Env::isDev() ? "Object class $classA and $classB are not the same" : UnknownError::DEFAULT_MESSAGE);
            }
        }

        if ($is_object_a) {
            $a = $this->serializerService->serialize($a, '');
        }
        if ($is_object_b) {
            $b = $this->serializerService->serialize($b, '');
        }

        $this->recursive_unset($a, '_format');
        $this->recursive_unset($b, '_format');

        return $this->makeDiff($a, $b);
    }

    private function makeDiff(?array $a, ?array $b): array
    {
        if (
            (is_array($a) && !is_array($b)) ||
            (!is_array($a) && is_array($b))
        ) {
            return [
                'prev' => $a,
                'next' => $b
            ];
        }

        $changes = [];
        foreach ($a as $keyA => $valueA) {
            if (is_array($valueA)) {
                if ($keyA === 'collection') {
                    $diff = $this->makeCollectionDiff($valueA, $b[$keyA]);
                    if (count($diff) > 0) {
                        $changes[] = $diff;
                    }
                } else if (array_key_exists($keyA, $b)) {
                    $diff = $this->makeDiff($valueA, $b[$keyA]);
                    if (count($diff) > 0) {
                        $changes[$keyA] = $diff;
                    }
                }
            } else if (array_key_exists($keyA, $b) && $valueA !== $b[$keyA]) {
                $changes[$keyA] = [
                    'prev' => $valueA,
                    'next' => $b[$keyA]
                ];
            }
        }

        return $changes;
    }

    private function makeCollectionDiff(array $a, array $b): array
    {
        $changes = [];
        $common = [];

        foreach ($a as $childA) {
            if ($childA === null) {
                continue;
            }
            if (array_key_exists('id', $childA)) {
                $search = in_array($childA['id'], array_column($b, 'id'), true);
            }
            else {
                $search = true;
            }

            if ($search === false) {
                $changes[] = [
                    'prev' => $childA,
                    'next' => null,
                    'type' => 'deleted'
                ];
            } else {
                $common['fromA'][] = $childA;
            }
        }

        foreach ($b as $childB) {
            if ($childB === null) {
                continue;
            }
            if (array_key_exists('id', $childB)) {
                $search = in_array($childB['id'], array_column($a, 'id'), true);
            }
            else {
                $search = true;
            }

            if ($search === false) {
                $changes[] = [
                    'prev' => null,
                    'next' => $childB,
                    'type' => 'added'
                ];
            } else {
                $common['fromB'][] = $childB;
            }
        }

        if (count($common) > 0) {
            $fromA = $common['fromA'];
            $fromB = $common['fromB'];

            $commonChanges = [];
            foreach ($fromA as $key => $elementA) {
                $diff = $this->makeDiff($elementA, $fromB[$key]);

                if (count($diff) > 0) {
                    $commonChange = ['prev' => $elementA];
                    foreach ($diff as $keyChange => $change) {
                        $commonChange['next'][$keyChange] = $change;
                    }
                    $commonChanges[] = $commonChange;
                }
            }

            if (count($commonChanges) > 0) {
                $changes = array_merge($changes, $commonChanges);
            }
        }

        return $changes;
    }

    public function recursive_unset(&$array, $unwanted_key): void
    {
        unset($array[$unwanted_key]);
        foreach ($array as &$value) {
            if (is_array($value)) {
                $this->recursive_unset($value, $unwanted_key);
            }
        }
    }
}