<?php // scripts/make-jwks.php
$kid = getenv('OIDC_KID') ?: 'kid-2025-01';
$pub = file_get_contents(__DIR__ . '/../storage/app/oidc/oidc-public.pem');

$clean = trim(preg_replace('/-----(BEGIN|END) PUBLIC KEY-----/', '', $pub));
$der = base64_decode($clean);

// Extraire n & e d’une clé RSA en DER
$seq = @unpack('Ca/a*', $der); // lecture simple (selon env)
if (!$der) { fwrite(STDERR, "Erreur lecture PEM\n"); exit(1); }

// Utilise openssl pour récupérer les détails
$k = openssl_pkey_get_public($pub);
$d = openssl_pkey_get_details($k);
$n = rtrim(strtr(base64_encode($d['rsa']['n']), '+/', '-_'), '=');
$e = rtrim(strtr(base64_encode($d['rsa']['e']), '+/', '-_'), '=');

$jwks = ['keys' => [[
  'kty'=>'RSA','use'=>'sig','alg'=>'RS256','kid'=>$kid,'n'=>$n,'e'=>$e
]]];

file_put_contents(__DIR__ . '/../storage/app/oidc/jwks.json', json_encode($jwks, JSON_UNESCAPED_SLASHES));
echo "OK jwks.json généré.\n";
