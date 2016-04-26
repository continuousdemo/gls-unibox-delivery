<?php
/**
 * This file is part of the gls-unixbox-delivery.
 * (c) 2016 Pierre Tomasina
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Plab\GlsUniboxDelivery\Generator\Pdf\Html2Pdf;

/**
 * Class Html2Pdf
 * @package Plab\GlsUniboxDelivery\Generator\Pdf\Html2Pdf
 */
class Html2Pdf
{
    const GRID_TOP_LEFT = 'TL';
    const GRID_TOP_RIGHT = 'TR';
    const GRID_BOTTOM_LEFT = 'BL';
    const GRID_BOTTOM_RIGHT = 'BR';

    protected $gridTopLeftUsed = false;
    protected $gridTopRightUsed = false;
    protected $gridBottomLeftUsed = false;
    protected $gridBottomRightUsed = false;
    
    protected $mapGridPlacement = [
        'TL' => 'gridTopLeftUsed',
        'TR' => 'gridTopRightUsed',
        'BL' => 'gridBottomLeftUsed',
        'BR' => 'gridBottomRightUsed',
    ];
    
    protected $html;
    protected $parcelsInfo = [];
    
    public function __construct() 
    {
        $this->html = file_get_contents(__DIR__ . '/templates/layout.phtml');
    }
    
    public function insertPage()
    {
        $page = '<page backtop="0mm" backbottom="0mm" backleft="0mm" backright="0mm">' . "\n";

        if ($this->gridTopLeftUsed) {
            $page .= '<div class="parcel '. self::GRID_TOP_LEFT .'">'. $this->gridTopLeftUsed .'</div>'. "\n";
        }

        if ($this->gridTopRightUsed) {
            $page .= '<div class="parcel '. self::GRID_TOP_RIGHT .'">'. $this->gridTopRightUsed .'</div>'. "\n";
        }

        if ($this->gridBottomLeftUsed) {
            $page .= '<div class="parcel '. self::GRID_BOTTOM_LEFT .'">'. $this->gridBottomLeftUsed .'</div>'. "\n";
        }

        if ($this->gridBottomRightUsed) {
            $page .= '<div class="parcel '. self::GRID_BOTTOM_RIGHT .'">'. $this->gridBottomRightUsed .'</div>'. "\n";
        }

        $page .= '</page>' . "\n";
        
        $this->html .= $page;
        
        $this->gridTopLeftUsed     = false;
        $this->gridTopRightUsed    = false;
        $this->gridBottomLeftUsed  = false;
        $this->gridBottomRightUsed = false;
    }

    /**
     * @param Parcel $parcel
     * @param $gridPlacement [GRID_TOP_LEFT, GRID_TOP_RIGHT, GRID_BOTTOM_LEFT, GRID_BOTTOM_RIGHT]
     */
    public function addParcel(Parcel $parcel, $gridPlacement = null)
    {
        if ($this->gridTopLeftUsed && $this->gridTopRightUsed && $this->gridBottomLeftUsed && $this->gridBottomRightUsed) {
            $this->insertPage();
        }
        
        if (null !== $gridPlacement && false !== $this->{$this->mapGridPlacement[$gridPlacement]}) {
            throw new \Exception('gridPlacement already used');
        }

        if (null === $gridPlacement && false === $this->gridTopLeftUsed) {
            $gridPlacement = self::GRID_TOP_LEFT;
            $this->gridTopLeftUsed = true;
        }

        if (null === $gridPlacement && false === $this->gridTopRightUsed) {
            $gridPlacement = self::GRID_TOP_RIGHT;
            $this->gridTopRightUsed = true;
        }

        if (null === $gridPlacement && false === $this->gridBottomLeftUsed) {
            $gridPlacement = self::GRID_BOTTOM_LEFT;
            $this->gridBottomLeftUsed = true;
        }

        if (null === $gridPlacement && false === $this->gridBottomRightUsed) {
            $gridPlacement = self::GRID_BOTTOM_RIGHT;
            $this->gridBottomRightUsed = true;
        }
        
        if (!in_array($gridPlacement, [
            self::GRID_TOP_LEFT, 
            self::GRID_TOP_RIGHT, 
            self::GRID_BOTTOM_LEFT, 
            self::GRID_BOTTOM_RIGHT
        ])) {
            throw new \Exception('gridPlacement are invalid');
        }

        $gridProperty = $this->mapGridPlacement[$gridPlacement];
        $this->$gridProperty = $parcel->parseTemplate();

        $this->parcelsInfo[] = $parcel->extract();
    }
    
    public function render()
    {
        $this->insertPage();
        
        try
        {
            $html2pdf = new \Spipu\Html2Pdf\Html2Pdf('P', 'A4', 'fr', true, 'UTF-8', [0, 0, 0, 0]);
            $html2pdf->pdf->SetDisplayMode('fullpage');

            $html2pdf->addFont('swiss721', '', __DIR__ . '/res/swiss721.php');
            $html2pdf->setDefaultFont('swiss721');

            $html2pdf->writeHTML($this->html);
            
            $binaryPdf = $html2pdf->Output('', 'S');
            
        } catch(Html2PdfException $e) {
            throw new \Exception('Error during pdf build' . "\n" .  $e->getMessage());
            //TODO should throw exception of generator namespace and not global
        }
        
        return (object)[
            'file'   => base64_encode($binaryPdf),
            'parcels' => $this->parcelsInfo,
        ];
    }
}