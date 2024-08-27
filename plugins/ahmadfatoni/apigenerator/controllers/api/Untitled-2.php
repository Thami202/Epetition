$response = Http::post('https://www.googleapis.com/oauth2/v4/token', [
'code' => $code,
'grant_type' => 'authorization_code',
'client_id' => Config::get('ahmadfatoni.apigenerator::clientId'),
'client_secret' => Config::get('ahmadfatoni.apigenerator::clientSecret'),
'redirect_uri' => Config::get('ahmadfatoni.apigenerator::redirectURL')
])

$_url = http_build_query([
'code' => $code,
'grant_type' => 'authorization_code',
'client_id' => Config::get('ahmadfatoni.apigenerator::clientId'),
'client_secret' => Config::get('ahmadfatoni.apigenerator::clientSecret'),
'redirect_uri' => Config::get('ahmadfatoni.apigenerator::redirectURL')
])
$url = 'https://www.googleapis.com/oauth2/v4/token?' . $_url;

Http:post($url);

use Google\Client;
$client = new Client();

$client->setClientId('75432176991-js6qvkeb4nlkagcg8j02iaf0gpvdte6u.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost:8081/epetitionoctober/signin');
$client->addScope("email");
$client->addScope("openid");
$client->setAccessType("offline");

$code = '4/0AVHEtk7bDHsV7mbO6t2G_0isgEVdLZaeFIsE6wG2-NAqgmuPBIMSowuFgHbrpYDRXee_1g';
$client->authenticate($code);