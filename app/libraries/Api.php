<?php


class Api
{
    public function __construct()
    {
        $this->CI = &get_instance();
    }


    private function _set_header($header)
    {
        switch ($header) {
            case 'json': {
                    return header('Content-Type: application/json');
                }
        }
    }
    private function _json_encode($data, $header = 'json')
    {
        $this->_set_header($header);
        return json_encode($data);
    }


    public function error_response_std($message, $response = [], $array = NULL)
    {
        $data = [
            'error' => true,
            'message' => $this->strip_tags_array($message),
            'response' => $response
        ];

        $data = ($array) ? array_merge($data, $array) : $data;
        return $this->_json_encode($data);
    }
    public function success_response_std($message = 'SUCCESS', $response = [], $array = NULL)
    {
        $data = [
            'error' => false,
            'message' => $this->strip_tags_array($message),
            'response' => $response
        ];

        $data = ($array) ? array_merge($data, $array) : $data;
        return $this->_json_encode($data);
    }

    public function error_response($message, $array = NULL)
    {
        $data = [
            'error' => true,
            'response' => [
                'message' => $this->strip_tags_array($message)
            ],
        ];
        $data = ($array) ? array_merge($data, $array) : $data;
        return $this->_json_encode($data);
    }

    public function success_response($message, $array = NULL)
    {

        $data = [
            'error' => false,
            'response' => [
                'message' => $this->strip_tags_array($message)
            ],
        ];
        $data = ($array) ? array_merge($data, $array) : $data;
        return $this->_json_encode($data);
    }

    private function strip_tags_array($array)
    {
        if ($array == null)
            return $this->_json_encode($array);

        $result = array();
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    $result[$key] = $this->strip_tags_array($value);
                } else if (is_string($value)) {
                    $result[$key] = strip_tags($value);
                } else {
                    $result[$key] = $value;
                }
            }
        } else {
            return strip_tags($array);
        }
        return $result;
    }
}
