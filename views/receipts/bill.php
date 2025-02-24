<?php
	$items = [];
	foreach ($model->items as $receipt_item) {
		$name = '';
		$total_price = $receipt_item->total_price;
		if ($receipt_item->product->marka) $name .= ', '.$receipt_item->product->marka->name;
		if ($receipt_item->product->model) $name .= ', '.$receipt_item->product->model->name;
		if ($receipt_item->product->color) $name .= ', '.$receipt_item->product->color->name;
		if ($receipt_item->size) $name .= ', '.$receipt_item->size->name;
		if ($receipt_item->gift) {
			$name .= ' (подарок)';
			$total_price = $receipt_item->amount * $receipt_item->price;
		}
		
		$items[] = [
			'LeftString' 	=> '',
            'CenterString'	=> '',
            'RightString' 	=> '',
            'Name'  		=> $name,
            'Quantity' 		=> $receipt_item->amount,
            'Price' 		=> $receipt_item->sale ? ($receipt_item->total_price + $receipt_item->sale) : $total_price,
            'Amount'		=> ($receipt_item->gift) ? 0 : $total_price,
            'Department' 	=> 0,
            'Tax' 			=> 0,  
            'EndPage' 		=> false
		];
	}

	$this->registerJs('
		$(function() {
			var Check = {
			    IsFiscalCheck: true,                    // true - фискальный чек, false - не фискальный
			    TypeCheck: 0,                           // Тип чека: 0 – продажа; 1 – покупка; 2 – возврат продажи (Не все ККМ); 3 – возврат покупки (Не все ККМ); 10 - Этикетка (только для принтера)
			    CheckStrings: '.json_encode($items).',
			        //{
			        //    // BarCode: { 
			        //    //     BarcodeType: "EAN13",       // Тип штрихкода: "EAN13", "CODE39", "CODE128", "PDF417", "CODEQR"  (Не обязательно)
			        //    //     Barcode: "1254789547853",   // Значение штрихкода  (Не обязательно)
			        //    // },
			        //    LeftString: "",                 // Строка печатаемая слева чека  (Не обязательно)
			        //    CenterString: "",               // Строка печатаемая по центру чека (Не обязательно)
			        //    RightString: "",                // Строка печатаемая слева справа чека  (Не обязательно)
			        //    Name: "Товар №2",               // Наименование товара
			        //    Quantity: 1,                    // Количество
			        //    Price: 100.50,                  // Цена
			        //    Amount: 100.50,                 // Скидка абсолютная
			        //    Department: 0,                  // Номер отдела
			        //    Tax: 0,                     	// Ставка НДА (поддерживается не во всех ККМ, а там где поддерживается практически не используется)
			        //    EndPage: false                  // При печати этикетки - конец этикетки - для чеков игнорируется
			        //}
			    Cash: '.$model->cash_total.',       	// Сумма оплаты наличными до сдачи
			    PayByCard: '.$model->nocash_total.',     // Сумма оплаты по банковской карте
			    PayByCredit: 0,                         // Сумма оплаты по кредиту
			    PayByCertificate: 0,                    // Сумма оплаты по сертификату
			    // Для принтеров чеков можно задавать реквизиты на лету:
			    // INN: "1253658741",
			    // NumberKkm: "54781-54",
			    // NumberEklz: "526-257-358",
			    NameOrganization: "ООО Wedding",
			};
			$.support.cors = true; 
			$.ajax({
			    type: "POST",
			    async: false,
			    url: "http://localhost:5893/Check/sync/1",
			    crossDomain: true, 
			    dataType: "json",
			    contentType: "application/json; charset=UTF-8",
			    processData: false,
			    data: $.toJSON(Check),
			    headers: { "Authorization": "Basic " + btoa("User:123456") },
			    success: function (data, textStatus, jqXHR){
			        location.href = "/receipts/view?id='.$model->id.'&first_load=1";
			    },
			    error: function() {
			    	location.href = "/receipts/view?id='.$model->id.'&first_load=1";
			    }
			});
		});
	', 3, 'print-bill');
?>