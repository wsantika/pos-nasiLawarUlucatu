<?php

return [
    'enabled' => env('THERMAL_PRINTER_ENABLED', true),
    'name' => env('THERMAL_PRINTER_NAME', 'POS-58'),
    'line_width' => (int) env('THERMAL_PRINTER_LINE_WIDTH', 32),
    'cut' => env('THERMAL_PRINTER_CUT', false),
];