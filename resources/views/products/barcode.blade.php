@include('prints/print')
<table class="table table-bordered">
	@for($i=0;$i<count($list);$i=$i+4)
		<tr>
			@for($k=0;$k<4;$k++)
				<?php 
					if(!isset($list[$k+$i])) break;
					$item = $list[$k+$i];
				?>
				<td>
					<?php /*
					<img style="float: left;"  src="data:image/png;base64, {!! base64_encode(QrCode::encoding('UTF-8')->format('png')->size(100)->generate($item->code)) !!} "/>
					*/ 
					//echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG($item->code, "C39+") . '" alt="barcode"   />';
					echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG($item->code, "C39+",2,33,array(1,1,1)) . '" alt="'.$item->code.'"   />';
					?>
					<span>{{ $item->title }}</span><br/>
				</td>
			@endfor
		</tr>
	@endfor
</table>