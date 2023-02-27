@php
    $items = [];

    if($app->ban_by_tid){
        $items[] = 'Ban by TID';
    }
    else {
        if($app->ban_if_countries_not_matched){
            $items[] = 'SIM not matched with IP';
        }
        if($app->ban_if_no_country){
            $items[] = 'No country';
        }
        if($app->allowed_countries_filter){
            $items[] = 'Allowed countries';
        }
        if($app->banned_devices_filter){
            $items[] = 'Device filter';
        }
        if($app->banned_partners_filter){
            $items[] = 'Partners filter';
        }
        if($app->banned_time_filter){
            $items[] = 'Time filter';
        }
    }
@endphp

{{join(', ', $items)}}
