<?php

if (!function_exists('res_data')) {
    /**
     * Return a response with the given data, message and status.
     *
     * @param mixed $data The data to be returned in the response.
     * @param string $message The message to be returned in the response.
     * @param int $status The HTTP status code for the response.
     * @return \Illuminate\Http\Response
     */
    function res_data($data, $message = null, $status = 200)
    {
        return response([
            'message' => $message ?? __('main.success'),
            'data' => !empty($data) ? $data : null,
            'status_code' => $status,
            'status' => in_array($status, [200, 201, 202, 203]),
        ], $status);
    }
}
