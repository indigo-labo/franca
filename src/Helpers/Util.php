<?php
use Illuminate\Support\Carbon;

if (!function_exists('toObject')) {
    /**
     * 配列 -> Objectに。
     *
     * @param array $value
     * @return object
     */
    function toObject(array $value = []) {
        return json_decode(
            json_encode($value,
                JSON_UNESCAPED_SLASHES |
                JSON_UNESCAPED_UNICODE |
                JSON_PARTIAL_OUTPUT_ON_ERROR |
                JSON_PRESERVE_ZERO_FRACTION |
                JSON_FORCE_OBJECT
            )
        );
    }
}

if (!function_exists('toArray')) {
    /**
     * Object -> 配列に。
     *
     * @param object $value
     * @return object
     */
    function toArray(object $value) {
        if ($value === null) $value = new stdClass();
        return json_decode(json_encode($value, true));
    }
}

if (!function_exists('carbon')) {
    /**
     * @param $value
     *
     * @return Carbon
     */
    function carbon($value = null): Carbon
    {
        try {
            return new Carbon($value);
        } catch (Exception $e) {
            return now();
        }
    }
}
