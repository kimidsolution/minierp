<?php

namespace App\Helpers;

class ArrayHelper
{
    /**
     * get single message error, from result validate form request laravel
     *
     * @param  array $arrError     list error message
     * @return string     if list error message is valid, return single message error
     */
    public function getErrorLaravelFirstKey($arrError)
    {
        if (is_array($arrError)) {
            foreach ($arrError as $key => $value) {
                return $value[0];
            }
        }
        return '';
    }

    public function buildTree(array $elements, $parentId = 0, $key_parent, $key_child) {
        $branch = [];

        foreach ($elements as $element) {
            if ($element[$key_parent] == $parentId) {
                $children = $this->buildTree($elements, $element['id'], $key_parent, $key_child);
                if ($children) {
                    $element[$key_child] = $children;
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }

    /**
     * get single message error, from result validate form request laravel
     *
     * @param  array $comparison
     * @return array value bool
     */
    public function setArrayValueBoolWithComparison($from_compare = [], $with_compare = [])
    {
        $result = [];
        if (!empty($from_compare) && !empty($with_compare)) {
            $result =  array_map(function ($count) {
                return $count != 1;
            }, array_count_values(array_merge($from_compare, $with_compare)));
        }

        return $result;
    }

    /**
     * get single message error, from result validate form request laravel
     *
     * @param  array $comparison
     * @return array value bool
     */
    public function setArrayFilterWithComparisonKey($from_compare = [], $with_compare = [])
    {
        $result = [];
        if (!empty($from_compare) && !empty($with_compare)) {
            $result =  array_filter($from_compare, function($param) use ($with_compare) {
                return in_array($param, $with_compare);
            }, ARRAY_FILTER_USE_KEY);
        }

        return $result;
    }
}
