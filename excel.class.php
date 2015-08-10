<?php
/**
* Simple excel generating from PHP5
*
* @package Utilities
* @license http://www.opensource.org/licenses/mit-license.php
* @author Oliver Schwarz <oliver.schwarz@gmail.com>
* @version 1.0
*/

/**
* Generating excel documents on-the-fly from PHP5
* 
* Uses the excel XML-specification to generate a native
* XML document, readable/processable by excel.
* 
* @package Utilities
* @subpackage Excel
* @author Oliver Schwarz <oliver.schwarz@vaicon.de>
* @version 1.1
* 
* @todo Issue #4: Internet Explorer 7 does not work well with the given header
* @todo Add option to give out first line as header (bold text)
* @todo Add option to give out last line as footer (bold text)
* @todo Add option to write to file
*/
ob_clean();
ob_start();
class Excel_XML
{

    /**
    * Header (of document)
    * @var string
    */
    private $header = "<?xml version=\"1.0\" encoding=\"%s\"?\>\n<Workbook xmlns=\"urn:schemas-microsoft-com:office:spreadsheet\" xmlns:x=\"urn:schemas-microsoft-com:office:excel\" xmlns:ss=\"urn:schemas-microsoft-com:office:spreadsheet\" xmlns:html=\"http://www.w3.org/TR/REC-html40\">";
    
    /**
    * Footer (of document)
    * @var string
    */
    private $footer = "</Workbook>";
    
    /**
    * Lines to output in the excel document
    * @var array
    */
    private $lines = array();
    
    /**
    * Used encoding
    * @var string
    */
    private $sEncoding;
    
    /**
    * Convert variable types
    * @var boolean
    */
    private $bConvertTypes;
    
    /**
    * Worksheet title
    * @var string
    */
    private $sWorksheetTitle;
    
    /**
    * Constructor
    * 
    * The constructor allows the setting of some additional
    * parameters so that the library may be configured to
    * one's needs.
    * 
    * On converting types:
    * When set to true, the library tries to identify the type of
    * the variable value and set the field specification for Excel
    * accordingly. Be careful with article numbers or postcodes
    * starting with a '0' (zero)!
    * 
    * @param string $sEncoding Encoding to be used (defaults to UTF-8)
    * @param boolean $bConvertTypes Convert variables to field specification
    * @param string $sWorksheetTitle Title for the worksheet
    */
    public function __construct($sEncoding = 'UTF-8', $bConvertTypes = false, $sWorksheetTitle = 'Table1')
    {
        $this->bConvertTypes = $bConvertTypes;
        $this->setEncoding($sEncoding);
        $this->setWorksheetTitle($sWorksheetTitle);
    }
    
    /**
    * Set encoding
    * @param string Encoding type to set
    */
    public function setEncoding($sEncoding)
    {
    	$this->sEncoding = $sEncoding;
    }
    
    /**
    * Set worksheet title
    * 
    * Strips out not allowed characters and trims the
    * title to a maximum length of 31.
    * 
    * @param string $title Title for worksheet
    */
    public function setWorksheetTitle ($title)
    {
        $title = preg_replace ("/[\\\|:|\/|\?|\*|\[|\]]/", "", $title);
        $title = substr ($title, 0, 31);
        $this->sWorksheetTitle = $title;
    }

    /**
    * Add row
    * 
    * Adds a single row to the document. If set to true, self::bConvertTypes
    * checks the type of variable and returns the specific field settings
    * for the cell.
    * 
    * @param array $array One-dimensional array with row content
    */
    private function addRow ($array)
    {
        $cells = "";
        foreach ($array as $k => $v):
        $type = 'String';
        if ($this->bConvertTypes === true && is_numeric($v)):
        $type = 'Number';
        endif;
        $v = htmlentities($v, ENT_COMPAT, $this->sEncoding);
        $cells .= "<Cell><Data ss:Type=\"$type\">" . $v . "</Data></Cell>\n"; 
        endforeach;
        $this->lines[] = "<Row>\n" . $cells . "</Row>\n";
    }
    
    /**
    * Add an array to the document
    * @param array 2-dimensional array
    */
    public function addArray ($array)
    {
        foreach ($array as $k => $v)
        $this->addRow ($v);
    }
    
    /**
    * Generate the excel file
    * @param string $filename Name of excel file to generate (...xls)
    */
    public function generateXML ($filename = 'excel-export')
    {
        // correct/validate filename
        $filename = preg_replace('/[^aA-zZ0-9\_\-]/', '', $filename);
        
        // deliver header (as recommended in php manual)
        header("Content-Type: application/vnd.ms-excel; charset=" . $this->sEncoding);
        header("Content-Disposition: inline; filename=\"" . $filename . ".xls\"");
        
        // print out document to the browser
        // need to use stripslashes for the damn ">"
        echo stripslashes (sprintf($this->header, $this->sEncoding));
        echo "\n<Worksheet ss:Name=\"" . $this->sWorksheetTitle . "\">\n<Table>\n";
        foreach ($this->lines as $line)
        echo $line;
        
        echo "</Table>\n</Worksheet>\n";
        echo $this->footer;
    }

}
ob_end_clean();
?>

// <?php
//      require 'excel.class.php';//引用谷歌的phpexcel 类
        
//     $xls = new Excel_XML();       
//     $mysql = new SaeMysql();
//     // create a simple 2-dimensional array      
//     $table = array(1=>array("微信名","领奖编码","刮刮卡奖品","参与时间","助力数","是否参与刮刮卡"));    
//     $sql = "select user_name,id,lottery,add_time,zlnum,islottery from tree_user order by zlnum desc,add_time asc";
//     $data =$mysql->getData($sql);   
//     if(!empty($data))
//     {
//         //中奖id 0:未中奖 1:apple watch 2:小白公仔 3:多肉植物盆栽 4:空气净化器
//         $prize = array("未中奖","apple watch","小白公仔","多肉植物盆栽","空气净化器");
//         $islottery = array("否","是");
//         foreach($data as $row)
//         {               
//             array_push($table,array($row["user_name"],$row["id"], $prize[$row["lottery"]], $row["add_time"],$row["zlnum"],$prize[$row["islottery"]]));
//         }               
//     }
//     $xls = new Excel_XML('UTF-8', false, '奖品列表');
//     $xls->addArray($table);
//     $xls->generateXML("prize");
// ?>