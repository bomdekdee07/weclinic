<?

require ('vendor/autoload.php');


//$mpdf = new \Mpdf\Mpdf(['languageToFont' => new CustomLanguageToFontImplementation()]);


/*
// Require composer autoload
require_once __DIR__ . '/vendor/autoload.php';
*/
// Create an instance of the class:
$mpdf = new \Mpdf\Mpdf();

// Write some HTML code:


$html = '<p style="font-family: Garuda">Text in Frutiger ภาณุ ศรีวชิรโรจน์</p>';


$mpdf->WriteHTML($html);

// Output a PDF file directly to the browser
$mpdf->Output();

?>
