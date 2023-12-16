<?php

use App\Http\Common\Services\RouteService;

$module = 'authentication';
$lstRoutes = array_keys(RouteService::GetApiSiteRoute($module));
for($i=1;$i<count($lstRoutes);$i++){
    RouteService::GetApiSiteRoute($module,$lstRoutes[$i]);
}
