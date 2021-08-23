<?php $this->setLayoutVar('title', '月別カレンダー画面') ?>

<form action="<?php echo $base_url; ?>/calendar" method="post">
<div>
	<span class="link"><a href="<?php echo $base_url; ?>/calendar/new">予定作成</a></span>
</div>

<h2><?php echo $dt["year"] . '年' . $m . '月'; ?></h2>


<table class="calendar" border="1">
	<tr>
		<td class="header"><a href="<?php echo $base_url; ?>/calendar?ym=<?php echo $lastym; ?>">前月</a></td>
		<td class="header center" colspan="5"><a href="<?php echo $base_url; ?>/calendar">今月</a></td>
		<td class="header"><a href="<?php echo $base_url; ?>/calendar?ym=<?php echo $nextym; ?>">翌月</a></td>
	</tr>

	<tr>
		<th class="sunday calendar">日</th>
		<?php foreach($wd as $value): ?>
			<th class="calendar"><?php echo $value; ?></th>
		<?php endforeach; ?>
		<th class="saturday calendar">土</th>
	</tr>

	<tr>
		<?php for ($i=0; $i<$fw; $i++): ?>
			<?php if($i == '0'): ?>
				<td class="sunday calendar"></td>
			<?php else: ?>
				<td class="calendar"></td>
			<?php endif; ?>
		<?php endfor; ?>

		<?php for ($j=1; $j<=$ld; $j++): ?>
			<?php if ($today["mday"] == $j && $today["mon"] == $dt["mon"] && $today["year"] == $dt["year"]): ?>
				<td class="today calendar">
					<a href="<?php echo $base_url; ?>/day/schedule?event_date=<?php echo $ym . '-' . $j; ?>"><?php echo $j; ?></a>
			<?php else: ?>
				<?php if (($j+$i-1)%7 == 0): ?>
					<td class="sunday calendar">
						<a href="<?php echo $base_url; ?>/day/schedule?event_date=<?php echo $ym . '-' . $j; ?>"><?php echo $j; ?></a>
				<?php elseif (($j+$i)%7 == 0): ?>
					<td class="saturday calendar">
						<a href="<?php echo $base_url; ?>/day/schedule?event_date=<?php echo $ym . '-' . $j; ?>"><?php echo $j; ?></a>
				<?php else: ?>
					<td class="calendar">
						<a href="<?php echo $base_url; ?>/day/schedule?event_date=<?php echo $ym . '-' . $j; ?>"><?php echo $j; ?></a>
				<?php endif; ?>
				<?php endif; ?>
				
				<?php $j = sprintf('%02d', $j); ?>
				<?php if (isset($plans[$ym . '-' . $j])): echo '<br/>' . $plans[$ym . '-' . $j] . '件の予定'; ?>
				<?php endif; ?></td>


			<?php if (($j+$i)%7 == 0): ?>
				</tr><tr>
			<?php endif; ?>
		<?php endfor; ?>

		<?php for($i=6; $lw<$i; $i--): ?>
			<?php if($i == ($lw+1)): ?>
				<td class="saturday calendar"></td>
			<?php else: ?>
				<td class="calendar"></td>
			<?php endif; ?>
		<?php endfor; ?>
	</tr>
</table>
