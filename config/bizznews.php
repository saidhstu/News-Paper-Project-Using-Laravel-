
<?php 

$env = env('APP_ENV');
if ($env == 'production' ) {
    return ['bizzmail_url' => 'https://api.mybizzmail.com'];
}else{
    return  ['bizzmail_url' => 'https://dev.api.mybizzmail.com'];
}
