<?php
require_once __DIR__ . '/' . 'src/logging.php';
\logging\Logger::getInstance()->setMinLevel(\logging\Logger::DEBUG);

require_once __DIR__ . '/' . 'src/objects.php';
require_once __DIR__ . '/' . 'src/sql.php';
require_once __DIR__ . '/' . 'src/session.php';
require_once __DIR__ . '/' . 'src/template.php';
require_once __DIR__ . '/' . 'src/forms.php';
require_once __DIR__ . '/' . 'src/validation.php';
