<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *
 * Convert HTML to PDF in CodeIgniter applications.
 *
 * @package            CodeIgniter
 * @subpackage        Libraries
 * @category        Libraries
 * @author            Hostmystory
 * @link            https://www.hostmystory.com
 */

// Dompdf namespace
use Dompdf\Dompdf;

class dompdfs
{
    public function __construct(){   
        // require_once autoloader 
        require_once dirname(__FILE__).'/dompdf/autoload.inc.php';
        $pdf = new DOMPDF();
        $CI =& get_instance();
        $CI->dompdf = $pdf;

    }
}
?>