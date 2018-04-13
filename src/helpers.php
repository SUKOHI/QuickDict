<?php

if (! function_exists('dict')) {

    function dict($key)
    {
        $pluck = function($key) {

            $keys = explode('.', $key);
            $table = array_get($keys, 0);
            $key = array_get($keys, 1);
            $collection = \DB::table($table)->pluck('value', 'key');

            if(!empty($key)) {

                return $collection->get($key);

            }

            return $collection;

        };

        if(is_array($key)) {

            $data = [];
            $dict_keys = $key;

            foreach ($dict_keys as $dict_key) {

                $data[$dict_key] = $pluck($dict_key);

            }

            return collect($data);

        }

        return $pluck($key);
    }

}