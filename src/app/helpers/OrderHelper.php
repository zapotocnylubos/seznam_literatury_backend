<?php

namespace App\Helpers;


final class OrderHelper
{
    public static function ChangeOrderUp($ids, $id)
    {
        $itemIndex = array_search($id, $ids);

        if ($itemIndex !== count($ids) - 1) {
            $swappingGroup = $ids[$itemIndex + 1];
            $ids[$itemIndex + 1] = $id;
            $ids[$itemIndex] = $swappingGroup;
        }

        return $ids;
    }

    public static function ChangeOrderDown($ids, $id)
    {
        $itemIndex = array_search($id, $ids);

        if ($itemIndex !== 0) {
            $swappingGroup = $ids[$itemIndex - 1];
            $ids[$itemIndex - 1] = $id;
            $ids[$itemIndex] = $swappingGroup;
        }

        return $ids;
    }
}