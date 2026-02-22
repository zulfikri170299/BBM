<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait PaginatesTables
{
    /**
     * Get the number of items per page from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $default
     * @return int
     */
    protected function getPerPage(Request $request, $default = 15)
    {
        $perPage = $request->get('per_page', $default);
        
        // Allow only specific values to prevent abuse
        $allowed = [10, 25, 50, 100];
        
        return in_array($perPage, $allowed) ? (int) $perPage : (int) $default;
    }
}
