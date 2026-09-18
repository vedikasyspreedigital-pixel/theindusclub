<?php

$leadId = $_GET['lead'] ?? '';

$zohoEndpoint = 'https://www.zohoapis.com/crm/v7/functions/test_cta_click/actions/execute?auth_type=apikey&zapikey=1003.8561ff2bd4ce1bc6fc4ac35be9c9cd75.328d1ffe47abb25bf63a7c2909390a74';

if ($leadId !== '' && ctype_digit($leadId)) {

    $separator = (strpos($zohoEndpoint, '?') !== false) ? '&' : '?';

    $url = $zohoEndpoint . $separator . 'leadId=' . urlencode($leadId);

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'The Indus Club CTA');

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);

    curl_close($ch);

    // TEMPORARY DEBUG
    echo "<!-- ZOHO HTTP: " . htmlspecialchars($httpCode) . " -->";
    echo "<!-- ZOHO RESPONSE: " . htmlspecialchars($response ?? '') . " -->";
    echo "<!-- CURL ERROR: " . htmlspecialchars($curlError) . " -->";
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Thank You | The Indus Club</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=EB+Garamond:ital@0;1&display=swap" rel="stylesheet">

<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }

  body {
    font-family: 'EB Garamond', Georgia, serif;
    background: #ffffff;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 24px;
  }

  .wrap {
    width: 100%;
    max-width: 620px;
    text-align: center;
  }

  h1 {
    font-family: 'Cormorant Garamond', serif;
    font-weight: 400;
    font-size: 26px;
    line-height: 1.7;
    color: #c39b5c;
    margin-bottom: 4px;
  }

  h1 .greeting_name {
    font-style: italic;
  }

  p.line {
    font-family: 'Cormorant Garamond', serif;
    font-size: 26px;
    line-height: 1.7;
    color: #c39b5c;
    margin-bottom: 4px;
  }

  .spacer {
    height: 18px;
  }

  .divider {
    width: 78%;
    height: 1px;
    background: #c39b5c;
    margin: 28px auto 30px;
  }

  .image-holder img {
    width: 280px;
    height: auto;
  }

  .logo {
    max-width: 220px;
    height: auto;
    margin-bottom: 36px;
  }

  .image-holder {
    width: 100%;
    max-width: 480px;
    margin: 0 auto;
  }

  @media (max-width: 480px) {
    h1, p.line {
      font-size: 21px;
    }
  }
</style>

<!-- Google Tag Manager -->
<script>
(function(w,d,s,l,i){
w[l]=w[l]||[];
w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});
var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),
dl=l!='dataLayer'?'&l='+l:'';
j.async=true;
j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;
f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-MFBFBSMD');
</script>
<!-- End Google Tag Manager -->

</head>

<body>

<div class="wrap">

  <h1>
    Thank you<span class="greeting_name"></span> for your interest in The Indus Club.
  </h1>

  <p class="line">We will contact you shortly.</p>

  <div class="spacer"></div>

  <p class="line">Best Regards,</p>
  <p class="line">Membership Team</p>

  <div class="divider"></div>

  <div class="image-holder">
    <img src="/assets/img/logo.jpg" alt="">
  </div>

</div>

<script>
(function() {
  const params = new URLSearchParams(window.location.search);

  let fname = params.get('fname') || params.get('First_name') || '';

  if (
    fname &&
    fname.indexOf('$[') === -1 &&
    fname.toLowerCase() !== 'guest'
  ) {
    const span = document.querySelector('.greeting_name');
    span.textContent = ', ' + fname + ',';
  }
})();
</script>

</body>
</html>