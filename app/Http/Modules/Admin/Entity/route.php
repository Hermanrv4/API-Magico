<?php

use App\Http\Common\Services\RouteService;

$module = 'entity';
$lstRoutes = array_keys(RouteService::GetApiAdminRoute($module));
for($i=1;$i<count($lstRoutes);$i++){
    RouteService::GetApiAdminRoute($module,$lstRoutes[$i]);
}
