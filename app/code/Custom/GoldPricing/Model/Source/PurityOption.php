<?php
namespace Custom\GoldPricing\Model\Source;
 
class PurityOption extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource 
{
    public function getAllOptions() {
        if ($this->_options === null) {
            $this->_options = [
                ['label' => __('--Select Purity--'), 'value' => ''],                
                ['label' => __('14 Caret'), 'value' => '14_carat_gold_rate'],
                ['label' => __('18 Caret'), 'value' => '18_carat_gold_rate'],
                ['label' => __('20 Caret'), 'value' => '20_carat_gold_rate'],
                ['label' => __('22 Caret'), 'value' => '22_carat_gold_rate'],
                ['label' => __('24 Caret'), 'value' => '24_carat_gold_rate'],
                ['label' => __('Ounce'), 'value' => 'ounce_gold_rate']
            ];
        }
        return $this->_options;
    }
}