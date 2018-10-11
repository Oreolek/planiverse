<?php

namespace App\Helpers;

use Illuminate\Http\Request;

/**
 * Parse and store pagination query parameters.
 */
class PaginationParameters
{
    public $max_id;
    public $since_id;

    /**
     * Parse a Request and populate the pagination parameters from it.
     *
     * @param Request $request The request received with pagination query params.
     */
    function __construct(Request $request)
    {
        if ($request->has('max_id') && $request->has('since_id'))
        {
            # This scenario makes no sense.
            # Someone's trying to dicker with the URL.
            abort(400);
        }
        elseif ($request->has('max_id'))
        {
            $this->max_id = $request->max_id;
        }
        elseif ($request->has('since_id'))
        {
            $this->since_id = $request->since_id;
        }
    }

    /**
     * Figure out which property is set and return it in an array.
     *
     * @return string[] The max_id or the since_id in an associative array.
     */
    public function to_array()
    {
        if (isset($this->max_id))
        {
            return ['max_id' => $this->max_id];
        }
        else
        {
            return ['since_id' => $this->since_id];
        }
    }
}