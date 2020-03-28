<?php 
function DateThai($strDate)
	{
		$strYear = date("Y",strtotime($strDate))+543;
		$strMonth= date("n",strtotime($strDate));
		$strDay= date("j",strtotime($strDate));
		$strHour= date("H",strtotime($strDate));
		$strMinute= date("i",strtotime($strDate));
		$strSeconds= date("s",strtotime($strDate));
		$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
		$strMonthThai=$strMonthCut[$strMonth];
		return "$strDay $strMonthThai $strYear, $strHour:$strMinute";
	}

?>
<div style="font-weight: bold;font-size: 24px;color: #000">หมายเลขบิล : <?php echo $customer->code; ?> </div>
<div style="font-weight: bold;font-size: 24px;color: #000">คุณ :  <?php echo $customer->name; ?> </div>
<div style="font-weight: bold;font-size: 24px;color: #000">หวย : <?php echo $customer->type; ?></div>

<div style="font-weight: bold;font-size: 24px;color: #000"><?php echo DateThai($customer->create_date); ?></div>
<hr style="border: 2px solid #000;margin-top: 10px" />
<table style="margin-top: -15px" border="0" width="100%">
    <tr>
        <th style="text-align: center;font-weight: bold;font-size: 24px;color: #000"><b>เลข</b></th>
        <th style="text-align: center;font-weight: bold;font-size: 24px;color: #000"><b>บน</b></th>
        <th style="text-align: center;font-weight: bold;font-size: 24px;color: #000"><b>ล่าง</b></th>
        <th style="text-align: center;font-weight: bold;font-size: 24px;color: #000"><b>โต็ด</b></th>
       
    </tr>
    <?php foreach ($arr as $key => $value): ?>
    <tr>
    	<td style="text-align: center;font-weight: bold;color: #000"><?php echo $value['number']; ?></td>
    	<td style="text-align: center;font-weight: bold;color: #000"><?php echo $value['top_amount']; ?></td>
    	<td style="text-align: center;font-weight: bold;color: #000"><?php echo $value['below_amount']; ?></td>
    	<td style="text-align: center;font-weight: bold;color: #000"><?php echo $value['otd_amount']; ?></td>
    </tr>
<?php endforeach; ?>
</table>
<hr style="border: 2px solid #000" />
<div style="font-weight: bold;color: #000">ราคารวม :  <b><?php echo $customer->amount_total; ?></b></div>
<div style="font-weight: bold;color: #000">ส่วนลด :  <b><?php echo $customer->discount; ?></b></div>
<div style="font-weight: bold;color: #000">เหลือ : <b><?php echo $customer->amount_total_remain; ?></b></div>
