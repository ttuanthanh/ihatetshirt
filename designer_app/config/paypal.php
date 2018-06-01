<?php

return array(
    /** set your paypal credential **/
    'client_id' =>'AVimrRqjUb4G5kPPjTSgOLox1QdnU8MbC9rcJKLksKApRa_P1t5BevQkPV6IOBmXZZwRp3O4HzNXb_Ym',
    'secret' => 'EABId4Z49eAxDe5a9e6kALgw1F7vE3hDqOXNp06C3JQ5VRp7ucHe0BwE8dzoAS8fGCtwx4XczL6yT9H4',
    /**
    * SDK configuration 
    */
    'settings' => array(
        /**
        * Available option 'sandbox' or 'live'
        */
        'mode' => 'sandbox',
        /**
        * Specify the max request time in seconds
        */
        'http.ConnectionTimeOut' => 1000,
        /**
        * Whether want to log to a file
        */
        'log.LogEnabled' => true,
        /**
        * Specify the file that want to write on
        */
        'log.FileName' => storage_path() . '/logs/paypal.log',
        /**
        * Available option 'FINE', 'INFO', 'WARN' or 'ERROR'
        *
        * Logging is most verbose in the 'FINE' level and decreases as you
        * proceed towards ERROR
        */
        'log.LogLevel' => 'FINE'
    ),
);