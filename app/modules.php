<?php

return array(
    'pbx' => array(
        // Should this module be autostarted?
        'autostart' => true,

        // Enable default routing to controllers
        'map' => true,

        'autoload' => array(
           'paths' => array(
              Forge\Module::path('pbx').'models/',
              Forge\Module::path('pbx').'ad/',
           ),

           'class' => array()
       ),
    )
);
