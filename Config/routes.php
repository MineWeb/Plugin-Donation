<?php
Router::connect('/admin/donation/config', array('controller' => 'donation', 'action' => 'config', 'plugin' => 'donation', 'admin' => true));
Router::connect('/admin/donation/history', array('controller' => 'donation', 'action' => 'history', 'plugin' => 'donation', 'admin' => true));

Router::connect('/donation', ['controller' => 'donation', 'action' => 'index', 'plugin' => 'donation']);
Router::connect('/donation/canceled', ['controller' => 'donation', 'action' => 'canceled', 'plugin' => 'donation']);
Router::connect('/donation/return', ['controller' => 'donation', 'action' => 'return', 'plugin' => 'donation']);
