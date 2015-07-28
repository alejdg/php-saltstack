<?php

class SaltStackService
{
    /**
     * Send a post to saltStack api
     *
     * @return array
     */
    public function curlSaltApi($host, $function, $args) {
        $ch = curl_init('http://salt:8000/run');
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json')
        );

        $request_body = '{
            "client": "local",
            "tgt": "'.$host.'",
            "fun": "'.$function.'",
            "arg": '.$args.',
            "username": "saltdev",
            "password": "123",
            "eauth": "pam"
        }';

        curl_setopt($ch, CURLOPT_POSTFIELDS, $request_body);

        $result = curl_exec($ch);

        $result = json_decode($result, true);
        return $result['return'][0][$host];

    }

    /**
     * Verify if a file exists in determined server
     *
     * @return bool
     */
    public function fileExists ($host, $file_path)
    {

        $function = "file.file_exists";
        $args = '"'.$file_path.'"';

        return $this->curlSaltApi($host, $function, $args);

    }

     /**
     * Copy a file in a remote server
     *
     * @return bool
     */
    public function fileCopy ($host, $file_path, $copy_file_path)
    {
        $function = "file.copy";
        $args = '[
            "'.$file_path.'",
            "'.$copy_file_path.'"
        ]';


        return $this->curlSaltApi($host, $function, $args);

    }

     /**
     *  Move a file in a remote server
     *
     * @return bool
     */
    public function fileMove ($host, $file_path, $copy_file_path)
    {
        $function = "file.move";
        $args = '[
            "'.$file_path.'",
            "'.$copy_file_path.'"
        ]';


        return $this->curlSaltApi($host, $function, $args);

    }

    /**
     * Write to a file in a remote server, if it does not exist, create it
     *
     * @return bool
     */
    public function fileWrite ($host, $file_path, $content)
    {

        $function = "file.write";
        $args = '[
            "'.$file_path.'",
            '.json_encode($content).'
        ]';

        return $this->curlSaltApi($host, $function, $args);

    }

    /**
     * Reload nginx Configs
     *
     * @return bool
     */
    public function nginxReload ($host)
    {

        $function = "nginx.signal";
        $args = '"reload"';

        return $this->curlSaltApi($host, $function, $args);

    }

    /**
     * Run arbitrary shell command
     *
     * @return bool
     */
    public function runCmd ($host, $args)
    {

        $function = "cmd.run";

        return $this->curlSaltApi($host, $function, $args);

    }

}
