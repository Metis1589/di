<?php
return [
    'adminEmail'     => 'admin@example.com',
    'displayTitleOn' => [
        'restaurant' => ['view'],
        'order'      => ['tracker']
    ],
    'etaDiff'        => 5,
    'postcodeRegexp' => '/^((EC([124][AMNPRVY]|3[AMNPV]|50|88))|(WC[12][ABEHNR]\s)|(WC1[VX])|(^(N|E|NW|SE|SW)[1-9][0-9]?)|(BR[1-8])|(BR98)|(CR[0-9])|(CR44\s|CR90)|(DA[1-9][0-9]?)|(^EN[0-9]|EN1[01]|EN77)|(HA[0-9])|(IG[1-9]|IG1[01])|(KT[1-2]?[0-9])|(RM[1-9]|RM[125]0)|(SM[1-7])|(TW[1-2]?[0-9])|(UB([1-9]|1[108])|)|(WD([1-7]|1[789]|2[345]|99))|(W1[PKMNRSTUWXYJHA-G])|(W[2-9])|(W1[0-4]))\s{0,1}[0-9]{0,1}[A-Z]{2}$/i',
];
