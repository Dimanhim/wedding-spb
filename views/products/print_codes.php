<?php
	use yii\helpers\Html;
	use barcode\barcode\BarcodeGenerator;
	use app\models\Product;
	use app\models\Size;

	$this->title = 'Штрих-коды';
?>

<style>
	.barcode_table{
	    width: 4.5cm;
	    height: 7cm;
	    float: left;
	    margin-right: 30px;
	    margin-bottom: 90px;
	}
	.barcode_table td{
	    padding: 5px;
	}
</style>

<?php foreach ($codes as $barcode): ?>
	<?php

		$barcode_arr = explode('(', $barcode);
		$barcode_arr[1] = isset($barcode_arr[1]) ? rtrim($barcode_arr[1], ',') : 1;
		$model = Product::findByBarcode($barcode_arr[0]);
		$start = strlen(ltrim($barcode_arr[0], '0')) - 2;

		$size_id = ltrim(substr($barcode_arr[0], $start, 2), '0');
		$size = Size::findOne($size_id);
		$price = $model->price;
		if ($size) {
			$price = ((int) $size->name <= 48) ? $model->price_small : $model->price_big;
		}
	?>
	<?php
		for ($i = 0; $i < $barcode_arr[1]; $i++):
	?>
		<?= BarcodeGenerator::widget([
		    'elementId'=> 'barcode_'.$barcode_arr[0].'_'.$i,
		    'value'=> $barcode_arr[0],
		    'type'=>'ean13',
		]); ?>

		<table border="1" class="barcode_table" style="font-size: 10px;">
			<tr>
				<td colspan="2" style="text-align: center;">Дом свадебной моды Prazdnik</td>
			</tr>
			<tr>
				<td colspan="2" style="font-size: 14px; font-weight: bold; text-align: center;"><?= $model->model->name ?></td>
			</tr>
			<?php if ($size or $model->color): ?>
				<tr>
					<td colspan="2" style="font-size: 14px; font-weight: bold;">
						<?= $size ? $size->name.' р., ' : '' ?>
						<?= $model->color ? $model->color->name : '' ?>
					</td>
				</tr>
			<?php endif ?>
			<tr>
				<td colspan="2"><div id="barcode_<?= $barcode_arr[0] ?>_<?= $i ?>" style="margin: 0 auto;"></div></td>
			</tr>
			<tr>
				<td><?= $price ?> рублей</td>
				<td style="padding-left: 55px;">шт.</td>
			</tr>
			<tr>
				<td style="font-size: 8px;"><?= date('d.m.Y', time()) ?></td>
				<td><span style="display: block; border-bottom: 1px solid #000; padding-top: 13px;"></span></td>
			</tr>
		</table>
	<?php endfor; ?>
<?php endforeach ?>
