<?php

/**
 * Class EasyPieChart
 *
 * @author chervand <chervand@gmail.com>
 *
 * @link https://github.com/rendro/easy-pie-chart
 * @link http://rendro.github.io/easy-pie-chart/
 */
class EasyPieChart extends CWidget
{
	const PACKAGE_ID = 'jquery.easy-pie-chart';

	public $htmlOptions = [];
	public $label = '';
	public $options = [];
	public $valueSign = '%';

	public function init()
	{
		parent::init();

		if (!isset($this->htmlOptions['id']))
			$this->htmlOptions['id'] = $this->id;
		if (!isset($this->htmlOptions['data-percent']))
			$this->htmlOptions['data-percent'] = 0;

		if (!isset($this->options['size']))
			$this->options['size'] = 110;

		if (!isset($this->options['onStep']))
			$this->options['onStep'] = 'js:function(from,to,value){jQuery(this.el).parent().find("span").text(value.toFixed(1));}';
		if (!isset($this->options['onStep']))
			$this->options['onStop'] = 'js:function(from,to,value){jQuery(this.el).parent().find("span").text(to.toFixed(1));}';

		$this->registerEasyPieChart();

	}

	public function run()
	{
		echo CHtml::openTag('div', CMap::mergeArray(['class' => 'easy-pie-chart'], $this->htmlOptions));
		echo CHtml::tag('div', ['class' => 'value',
			'style' => 'line-height: ' . $this->options['size'] . 'px;' . 'font-size: ' . $this->options['size'] / 64 . 'em;'
		], '<span></span>' . $this->valueSign);
		echo CHtml::tag('div', ['class' => 'canvas', 'data-percent' => $this->htmlOptions['data-percent']], '');
		echo CHtml::tag('div', ['class' => 'label'], $this->label);
		echo CHtml::closeTag('div');
	}

	protected function registerEasyPieChart()
	{
		/** @var CClientScript $clientScript */
		$clientScript = Yii::app()->getClientScript();
		$options = CJavaScript::encode($this->options);
		$assetsPath = Yii::getPathOfAlias('vendor.bower-asset') . '/' . self::PACKAGE_ID;
		$assetsUrl = Yii::app()->getAssetManager()->publish($assetsPath);

		$clientScript
			->addPackage(
				self::PACKAGE_ID,
				['baseUrl' => $assetsUrl, 'js' => ['dist/jquery.easypiechart.min.js'], 'depends' => ['jquery']]
			)
			->registerPackage(
				self::PACKAGE_ID
			)
			->registerCss(
				self::PACKAGE_ID,
				'.easy-pie-chart{display:inline-block;text-align:center;vertical-align:middle;position:relative;}.easy-pie-chart>.value{position:absolute;width:100%;height:100%;margin:auto;}')
			->registerScript(
				$this->id,
				'jQuery("#' . $this->id . '").find(".canvas").easyPieChart(' . $options . ');',
				CClientScript::POS_READY
			);
	}
}
