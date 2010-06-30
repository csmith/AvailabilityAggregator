<?PHP

 header('Content-type: text/calendar');

 $calendars = array(
  'http://www.google.com/calendar/ical/chris87%40gmail.com/public/basic.ics',
  'http://www.google.com/calendar/ical/jgagrlt3cf3a0h279ipfs6dj38%40group.calendar.google.com/public/basic.ics',
  'http://www.google.com/calendar/ical/ftcvfmft9utr4n2588lppmh3mo%40group.calendar.google.com/public/basic.ics'
 );

 $cacheTime = 60 * 60 * 24;

 function getCalendars() {
  global $calendars, $cacheTime;
  $res = array();

  foreach ($calendars as $url) {
   $cache = dirname(__FILE__) . '/.cache-' . md5($url);

   if (file_exists($cache) && filemtime($cache) > time() - $cacheTime) {
    $res[] = file_get_contents($cache);
   } else {
    file_put_contents($cache, $res[] = file_get_contents($url));
   }
  }

  return $res;
 }

 echo "BEGIN:VCALENDAR\r\n";
 echo "PRODID:-//Google Inc//Google Calendar 70.9054//EN\r\n";
 echo "VERSION:2.0\r\n";
 echo "CALSCALE:GREGORIAN\r\n";
 echo "METHOD:PUBLISH\r\n";
 echo "X-WR-CALNAME:Availability\r\n";
 echo "X-WR-TIMEZONE:Europe/London\r\n";
 echo "X-WR-CALDESC:Schedule information for Chris\r\n";

 foreach (getCalendars() as $calendar) {
  echo str_replace('VFREEBUSY', 'VEVENT',
         substr($calendar, $start = strpos($calendar, 'BEGIN:VFREEBUSY'),
         strrpos($calendar, 'END:VFREEBUSY') + strlen("END:VFREEBUSY\r\n") - $start));
 }

 echo "END:VCALENDAR\r\n";

?>
