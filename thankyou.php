<!DOCTYPE html>
<html lang="en">

	<?php
$lead_submission_success = false;
$lead_event_id = '';
$lead_email = '';
$lead_source = 'Website Lead';
$lead_attribution = [];

function indus_clean_post_field($key) {
  $value = $_POST[$key] ?? '';
  return is_string($value) ? trim(str_replace(["
", "
"], ' ', $value)) : '';
}

function indus_valid_email($email) {
  return $email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $to = 'membership@theindusclub.com, mm1@theindusclub.com, it@theindusclub.com, mm2@theindusclub.com, mm3@theindusclub.com';

  $first_name = indus_clean_post_field('uname');
  $mail1 = indus_clean_post_field('uemail');
  $phone = indus_clean_post_field('ucompany');
  $mess = indus_clean_post_field('umobile');
  $city = indus_clean_post_field('ucity');
  $age = indus_clean_post_field('uage');
  $degination = indus_clean_post_field('sel1');
  $social = indus_clean_post_field('sel2');
  $submission_id = indus_clean_post_field('submission_id');
  $subject = 'THE INDUS CLUB | Website query';

  $required_fields_present = $first_name !== '' && indus_valid_email($mail1) && $phone !== '' && $mess !== '' && $city !== '' && $age !== '' && $degination !== '';

  if ($required_fields_present) {
    $attribution_keys = ['gclid','gbraid','wbraid','utm_source','utm_medium','utm_campaign','utm_term','utm_content','utm_id','landing_page','last_landing_page','referrer','first_visit_time'];
    $attribution = [];
    foreach ($attribution_keys as $key) {
      $attribution[$key] = indus_clean_post_field($key);
    }

    require __DIR__ . '/zoho-dispatch.php';

    dispatch_to_zoho([
      'fullName'    => $first_name,
      'companyName' => $phone,
      'city'        => $city,
      'age'         => $age,
      'designation' => $degination,
      'referral'    => $social,
      'mobile'      => $mess,
      'email'       => $mail1,
      'source'      => $lead_source,
    ] + $attribution);

    $message = "Name - " . $first_name . "
 Company Name - " . $phone . "
 Email Id- " . $mail1 . "
 Mobile - " . $mess . "
 City - " . $city . "
 age - " . $age . "
 Designation- " . $degination . "
 social - " . $social;
    foreach ($attribution as $key => $value) {
      if ($value !== '') $message .= "
" . strtoupper($key) . " - " . $value;
    }

    $headers = "From: The Indus Club Website <no-reply@theindusclub.com>
";
    $headers .= "Reply-To: " . $mail1 . "
";

    $mail = mail($to, $subject, $message, $headers);
    if (!$mail) {
      error_log('thankyou.php: mail() failed for submission from ' . $mail1);
    }

    $lead_submission_success = true;
    $lead_email = $mail1;
    $lead_event_id = $submission_id !== '' ? $submission_id : hash('sha256', strtolower($mail1) . '|' . $mess . '|' . $phone);
    $lead_attribution = $attribution;
  } else {
    error_log('thankyou.php: ignored invalid or incomplete submission');
  }
}
?>


<head>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-MTGZLDG');</script>
<!-- End Google Tag Manager -->

<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '454165783126291');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=454165783126291&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->



    <title>The most admired &amp; aspired to club in Mumbai - By invitation only.</title>
    <meta charset="utf-8">
    <meta name="title" content="The Indus Club">
    <meta name="description"
        content="The Indus Club is a private members business & lifestyle club for newsmakers, leaders & legends.">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" sizes="16x16 24x24 32x32 48x48 64x64" type="assets/img/x-icon"
        href="assets/img/favicon.ico">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="assets/css/style_index.css">
    <link rel="stylesheet" href="assets/css/index.css">
    <link href="//cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/assets/owl.carousel.min.css" rel="stylesheet" />

    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=GTM-MTGZLDG"></script>

	 <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());

        gtag('config', 'G-HFZ5FC9EFV');
		 gtag('config', 'GTM-MTGZLDG');

    </script>

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-MFBFBSMD');</script>
<!-- Indus lead conversion event -->
<script>
(function(){
  window.dataLayer = window.dataLayer || [];
  var isLead = <?php echo $lead_submission_success ? 'true' : 'false'; ?>;
  if (!isLead) return;
  var eventId = <?php echo json_encode($lead_event_id); ?>;
  var storageKey = 'indus_generate_lead_' + eventId;
  try {
    if (window.sessionStorage && sessionStorage.getItem(storageKey)) return;
    if (window.sessionStorage) sessionStorage.setItem(storageKey, '1');
  } catch (e) {}
  var leadPayload = {
    event_id: eventId,
    lead_source: <?php echo json_encode($lead_source); ?>,
    email_domain: <?php echo json_encode(substr(strrchr($lead_email, '@') ?: '', 1)); ?>,
    gclid: <?php echo json_encode($lead_attribution['gclid'] ?? ''); ?>,
    gbraid: <?php echo json_encode($lead_attribution['gbraid'] ?? ''); ?>,
    wbraid: <?php echo json_encode($lead_attribution['wbraid'] ?? ''); ?>,
    utm_source: <?php echo json_encode($lead_attribution['utm_source'] ?? ''); ?>,
    utm_medium: <?php echo json_encode($lead_attribution['utm_medium'] ?? ''); ?>,
    utm_campaign: <?php echo json_encode($lead_attribution['utm_campaign'] ?? ''); ?>,
    utm_term: <?php echo json_encode($lead_attribution['utm_term'] ?? ''); ?>,
    utm_content: <?php echo json_encode($lead_attribution['utm_content'] ?? ''); ?>
  };
  window.dataLayer.push(Object.assign({ event: 'generate_lead' }, leadPayload));
  window.dataLayer.push(Object.assign({ event: 'ads_conversion_Form_1' }, leadPayload));
  if (typeof gtag === 'function') {
    gtag('event', 'ads_conversion_Form_1', leadPayload);
  }
})();
</script>
<!-- End Indus lead conversion event -->
<!-- End Google Tag Manager -->
</head>



<style type="text/css">
    .vertical .carousel-inner {
        height: 100%;
    }

    .carousel.vertical .item {
        -webkit-transition: 1s ease-in-out top;
        -moz-transition: 1s ease-in-out top;
        -ms-transition: 1s ease-in-out top;
        -o-transition: 1s ease-in-out top;
        transition: 1s ease-in-out top;
    }

    .carousel.vertical .active {
        top: 0;
    }

    .carousel.vertical .next {
        top: 400px;
    }

    .carousel.vertical .prev {
        top: 400px;
    }

    .carousel.vertical .next.left,
    .carousel.vertical .prev.right {
        top: 0;
    }

    .carousel.vertical .active.left {
        top: -400px;
    }

    .carousel.vertical .active.right {
        top: 400px;
    }

    .carousel.vertical .item {
        left: 0;
    }

    .thank-you {
        padding: 50px;
        background: #fff;
        float: left;
        position: relative;
        margin-top: 62px;
        line-height: 24px;
        font-size: 15px;
        margin-bottom: 20px;
        width: 100%;
    }
    .thank-you .thank-you-inner .thank-you-content p{
        font-family: 'Walburn-Regular-2', serif;
        font-size: 26px !important;
        color: #242d4a;
        line-height: 30px;
        text-transform: uppercase;
    }
    .thank-you .thank-you-inner .thank-you-logo {
        text-align: center;
        margin-bottom: 50px;
    }
    .thank-you .thank-you-inner .thank-you-logo img {
        width: 150px;
    }
    .thank-you .thank-you-inner .thank-you-content .thank-you-para1{
        margin-bottom: 40px;
    }
    @media only screen and (max-width: 991px)  {
        .thank-you .thank-you-inner .thank-you-content p {
            font-size: 28px !important;
        }
        .thank-you .thank-you-inner .thank-you-content .thank-you-para1 {
            margin-bottom: 30px;
        }
    }
    @media only screen and (max-width: 767px)  {
        .thank-you .thank-you-inner .thank-you-content p {
            font-size: 20px !important;
            line-height: 26px;
        }
        .thank-you .thank-you-inner .thank-you-logo {
            margin-bottom: 60px;
        }
    }
    @media only screen and (max-width: 767px)  {
        .thank-you {
            padding: 50px 40px;
        }
    }
    @media only screen and (max-width: 375px)  {
        .thank-you .thank-you-inner .thank-you-content p {
            font-size: 16px !important;
        }
        .thank-you .thank-you-inner .thank-you-content .thank-you-para1 {
            margin-bottom: 20px;
        }
    }
</style>
<script type="text/javascript">
    window.onload = function () {
        $("#reg_button").animate({ right: '0px', width: '200px', opacity: '1' }, "slow");
    }
</script>

<body ng-app="myApp" class="body">
	<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MTGZLDG"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
    <wb-headerindex></wb-headerindex>
    <section>
        <div class="container">
            <div class="thank-you">
                <div class="row">
                    <div class="col-12">
                        <div class="thank-you-inner">
                            <div class="thank-you-logo">
                                <img src="assets/img/logo-small.png" alt="The Indus Club logo">
                            </div>
                            <div class="thank-you-content text-center">
                                <div class="thank-you-content1">
                                    <p class="thank-you-para1">Thank you for your interest! <br> We will get in touch with you shortly.</p>
                                </div>
                                <div class="thank-you-content2">
                                    <p class="thank-you-para2">Warm Regards,<br>the indus club team</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--<div class="top-reg-buttton" id="reg_button" data-toggle="modal" data-target="#myModal"><img src="assets/img/forms-icon.png" width="20"> <span class="int-text"> Register Your Interest </span> </div>-->
    <wb-footerindex></wb-footerindex>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
    <script src="assets/js/common.js"></script>
    <script src="assets/js/anim.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/owl.carousel.min.js"></script>
    <script src="assets/js/modernizr-custom.js"></script>
    <script>
        $('.owl-carousel').owlCarousel({
            loop: true,
            margin: 10,
            nav: false,
            autoplay: true,
            autoplayTimeout: 15000,
            // autoplay:15000,

            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 1
                },
                1000: {
                    items: 1
                }
            }
        })    </script>
</body>

</html>
