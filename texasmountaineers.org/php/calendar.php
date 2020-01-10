<?php
$your_google_calendar="https://calendar.google.com/calendar/embed?showTitle=0&amp;showDate=0&amp;showTabs=0&amp;showCalendars=0&amp;mode=AGENDA&amp;height=400&amp;wkst=1&amp;hl=en&amp;bgcolor=%23ffffff&amp;src=texasmountaineers%40gmail.com&amp;color=%23125A12&amp;ctz=America%2FChicago";

$url= parse_url($your_google_calendar);
$google_domain = $url['scheme'].'://'.$url['host'];

// Load and parse Google's raw calendar
$dom = new DOMDocument;
$dom->loadHTMLfile($your_google_calendar);

// Create a link to a new CSS file called schedule.min.css
$element = $dom->createElement('link');
$element->setAttribute('type', 'text/css');
$element->setAttribute('rel', 'stylesheet');
$element->setAttribute('href', '/css/schedule.min.css');

// Change Google's JS file to use absolute URLs
$scripts = $dom->getElementsByTagName('script');

foreach ($scripts as $script) {
  $js_src = $script->getAttribute('src');

  if ($js_src) {
    $parsed_js = parse_url($js_src, PHP_URL_HOST);
    if (!$parsed_js) {
      $script->setAttribute('src', $google_domain . $js_src);
    }
  }
}

 // Append this link at the end of the element
$head = $dom->getElementsByTagName('head')->item(0);
$head->appendChild($element);

// Remove old stylesheet
$oldcss = $dom->documentElement;
$link = $oldcss->getElementsByTagName('link')->item(0);
$head->removeChild($link);

// Export the HTML
echo $dom->saveHTML();
?>
